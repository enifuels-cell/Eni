<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\Transaction;
use App\Models\FranchiseApplication;
use App\Models\DailyInterestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Admin Dashboard Overview
     */
    public function index()
    {
        // User Statistics
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $newSignupsToday = User::where('role', '!=', 'admin')
            ->whereDate('created_at', today())
            ->count();
        $activeUsersToday = User::where('role', '!=', 'admin')
            ->whereNotNull('last_login_at')
            ->whereDate('last_login_at', today())
            ->count();

        // Financial Overview
        $totalFunds = DB::table('transactions')
            ->where('status', 'approved')
            ->where('type', 'deposit')
            ->sum('amount');

        $pendingDeposits = Transaction::where('type', 'deposit')
            ->where('status', 'pending')
            ->count();

        $pendingWithdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'pending')
            ->count();

        $pendingRequestFunds = Transaction::where('type', 'request_fund')
            ->where('status', 'pending')
            ->count();

        // Daily Interest Total Today
        $dailyInterestToday = DailyInterestLog::whereDate('created_at', today())
            ->sum('interest_amount');

        // Revenue from fees (assuming 2% deposit fee)
        $totalRevenue = $totalFunds * 0.02;

        // User signup trends (last 7 days)
        $signupTrends = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('date(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // System Alerts - Users with multiple pending requests
        $usersWithMultiplePending = User::whereHas('transactions', function($query) {
            $query->where('status', 'pending');
        })
        ->get()
        ->filter(function($user) {
            return $user->transactions()->where('status', 'pending')->count() > 2;
        })
        ->map(function($user) {
            $user->pending_count = $user->transactions()->where('status', 'pending')->count();
            return $user;
        });

        // Recent activity
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'newSignupsToday', 'activeUsersToday',
            'totalFunds', 'pendingDeposits', 'pendingWithdrawals', 'pendingRequestFunds',
            'dailyInterestToday', 'totalRevenue', 'signupTrends',
            'usersWithMultiplePending', 'recentTransactions'
        ));
    }

    /**
     * Pending Deposits Management
     */
    public function pendingDeposits()
    {
        $deposits = Transaction::with(['user'])
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.deposits.pending', compact('deposits'));
    }

    /**
     * Approve Deposit
     */
    public function approveDeposit(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'deposit' || $transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Invalid transaction for approval.']);
        }

        try {
            DB::transaction(function () use ($transaction) {
                // Update transaction status
                $transaction->update([
                    'status' => 'approved',
                    'processed_at' => now()
                ]);

                // Get the amount as a decimal number
                $amountValue = (float)$transaction->amount;

                // Check if this is a package investment deposit
                $isInvestmentDeposit = str_contains($transaction->description, 'Investment in') &&
                                      str_contains($transaction->description, 'package');

                // Only add to account balance if this is NOT an investment deposit
                // Investment deposits go directly to locked investment
                if (!$isInvestmentDeposit) {
                    // Regular deposit - add to withdrawable account balance
                    $transaction->user->increment('account_balance', $amountValue);
                }

                // If this is an investment deposit, activate the investment directly
                if ($isInvestmentDeposit) {
                    // Find and activate investments created around the same time as this transaction
                    $transactionAmountValue = (float)$transaction->amount;

                    $investments = $transaction->user->investments()
                        ->where('active', false)
                        ->whereBetween('created_at', [
                            $transaction->created_at->copy()->subMinutes(5),
                            $transaction->created_at->copy()->addMinutes(5)
                        ])
                        ->get()
                        ->filter(function($investment) use ($transactionAmountValue) {
                            $investmentAmount = (float)$investment->amount;
                            return abs($investmentAmount - $transactionAmountValue) < 0.01;
                        });

                    if ($investments->isEmpty()) {
                        \Log::warning('No matching investment found for deposit approval', [
                            'transaction_id' => $transaction->id,
                            'user_id' => $transaction->user_id,
                            'amount' => $transactionAmountValue,
                            'description' => $transaction->description,
                            'created_at' => $transaction->created_at,
                            'all_inactive_investments' => $transaction->user->investments()->where('active', false)->get()->map(function($inv) {
                                return [
                                    'id' => $inv->id,
                                    'amount' => (float)$inv->amount,
                                    'created_at' => $inv->created_at
                                ];
                            })
                        ]);
                    }

                    foreach ($investments as $investment) {
                        \Log::info('Activating investment from deposit approval', [
                            'investment_id' => $investment->id,
                            'transaction_id' => $transaction->id,
                            'amount' => $transactionAmountValue,
                            'user_id' => $transaction->user_id
                        ]);

                        // Activate the investment (funds are now locked)
                        $investment->update([
                            'active' => true,
                            'started_at' => now()
                        ]);

                        // Deduct available slots from the package if applicable
                        $package = $investment->investmentPackage;
                        if ($package) {
                            // Use direct database decrement to ensure it's persisted
                            \App\Models\InvestmentPackage::where('id', $package->id)
                                ->where('available_slots', '>', 0)
                                ->decrement('available_slots');

                            // Reload the package to get updated value
                            $package->refresh();
                        }

                        // Process referral bonus if user was referred
                        $referral = $transaction->user->referralReceived;
                        if ($referral && $package) {
                            // Calculate bonus amount
                            $investmentAmountValue = (float)$investment->amount;
                            $bonusRate = $package->referral_bonus_rate / 100;
                            $bonusAmount = $investmentAmountValue * $bonusRate;

                            // Create referral bonus record
                            $referralBonus = \App\Models\ReferralBonus::create([
                                'referral_id' => $referral->id,
                                'investment_id' => $investment->id,
                                'bonus_amount' => $bonusAmount,
                                'paid' => true,
                                'paid_at' => now()
                            ]);

                            // Add bonus to referrer's account balance
                            $referrer = $referral->referrer;
                            if ($referrer) {
                                $referrer->increment('account_balance', $bonusAmount);

                                // Create transaction record for the referral bonus
                                $referrer->transactions()->create([
                                    'type' => 'referral_bonus',
                                    'amount' => $bonusAmount,
                                    'status' => 'completed',
                                    'description' => 'Referral bonus from ' . $transaction->user->name . ' - ' . $package->name . ' investment',
                                    'reference' => 'REF' . time() . rand(1000, 9999),
                                    'processed_at' => now()
                                ]);
                            }
                        }

                        // Note: We don't add to account_balance and then deduct
                        // The approved deposit goes DIRECTLY into the locked investment
                        // This way account_balance only contains withdrawable funds
                    }
                }

                // Log admin action
                $this->logAdminAction('approve_deposit', $transaction);
            });

            return back()->with('success', 'Deposit approved successfully.');
        } catch (\Exception $e) {
            \Log::error('Error approving deposit: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'exception' => $e
            ]);

            return back()->withErrors(['error' => 'Failed to approve deposit: ' . $e->getMessage()]);
        }
    }

    /**
     * Deny Deposit
     */
    public function denyDeposit(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'denied',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
            'admin_notes' => $request->reason
        ]);

        $this->logAdminAction('deny_deposit', $transaction, $request->reason);

        return back()->with('success', 'Deposit denied.');
    }

    /**
     * Delete Deposit
     */
    public function deleteDeposit(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_password' => 'required'
        ]);

        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Invalid admin password.']);
        }

        $this->logAdminAction('delete_deposit', $transaction);
        $transaction->delete();

        return back()->with('success', 'Deposit deleted successfully.');
    }

    /**
     * Approved Deposits
     */
    public function approvedDeposits()
    {
        $deposits = Transaction::with(['user'])
            ->where('type', 'deposit')
            ->where('status', 'approved')
            ->latest('processed_at')
            ->paginate(20);

        return view('admin.deposits.approved', compact('deposits'));
    }

    /**
     * Daily Interest Log
     */
    public function dailyInterestLog(Request $request)
    {
        $query = DailyInterestLog::with(['user', 'investment']);

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->latest()->paginate(50);

        $totalToday = DailyInterestLog::whereDate('created_at', today())
            ->sum('interest_amount');

        return view('admin.interest.daily-log', compact('logs', 'totalToday'));
    }

    /**
     * Pending Withdrawals
     */
    public function pendingWithdrawals()
    {
        $withdrawals = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.withdrawals.pending', compact('withdrawals'));
    }

    /**
     * Approve Withdrawal
     */
    public function approveWithdrawal(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'withdrawal' || $transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Invalid transaction for approval.']);
        }

        // Verify user has sufficient balance
        if ($transaction->user->account_balance < $transaction->amount) {
            return back()->withErrors(['error' => 'User has insufficient balance.']);
        }

        DB::transaction(function () use ($transaction) {
            // Update transaction
            $transaction->update([
                'status' => 'approved',
                'processed_at' => now(),
                'processed_by' => Auth::id()
            ]);

            // Deduct from user balance
            $transaction->user->decrement('account_balance', $transaction->amount);

            $this->logAdminAction('approve_withdrawal', $transaction);
        });

        return back()->with('success', 'Withdrawal approved successfully.');
    }

    /**
     * Deny Withdrawal
     */
    public function denyWithdrawal(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'denied',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
            'admin_notes' => $request->reason
        ]);

        $this->logAdminAction('deny_withdrawal', $transaction, $request->reason);

        return back()->with('success', 'Withdrawal denied.');
    }

    /**
     * Approved Withdrawals
     */
    public function approvedWithdrawals()
    {
        $withdrawals = Transaction::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'approved')
            ->latest('processed_at')
            ->paginate(20);

        return view('admin.withdrawals.approved', compact('withdrawals'));
    }

    /**
     * User Management
     */
    public function manageUsers()
    {
        $users = User::where('role', '!=', 'admin')
            ->with(['referralReceived.referrer'])
            ->withSum('transactions as total_deposits', 'amount')
            ->withCount(['referrals as referrals_count'])
            ->latest()
            ->paginate(20);

        // Calculate referral statistics
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $referredUsers = User::where('role', '!=', 'admin')
            ->whereHas('referralReceived')
            ->count();
        $activeReferrers = User::where('role', '!=', 'admin')
            ->whereHas('referrals')
            ->count();
        $referralRate = $totalUsers > 0 ? ($referredUsers / $totalUsers) * 100 : 0;

        return view('admin.users.manage', compact('users', 'totalUsers', 'referredUsers', 'activeReferrers', 'referralRate'));
    }

    /**
     * Delete User
     */
    public function deleteUser(Request $request, User $user)
    {
        $request->validate([
            'admin_password' => 'required'
        ]);

        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Invalid admin password.']);
        }

        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Cannot delete admin accounts.']);
        }

        DB::transaction(function () use ($user) {
            // Delete all related records
            $user->transactions()->delete();
            $user->investments()->delete();
            $user->referrals()->delete();
            $user->dailyInterestLogs()->delete();

            $this->logAdminAction('delete_user', null, "Deleted user: {$user->email}");

            $user->delete();
        });

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Request Fund Management
     */
    public function pendingRequestFunds()
    {
        $requests = Transaction::with('user')
            ->where('type', 'request_fund')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.request-funds.pending', compact('requests'));
    }

    /**
     * Approve Request Fund
     */
    public function approveRequestFund(Request $request, Transaction $transaction)
    {
        if ($transaction->type !== 'request_fund' || $transaction->status !== 'pending') {
            return back()->withErrors(['error' => 'Invalid request for approval.']);
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'approved',
                'processed_at' => now(),
                'processed_by' => Auth::id()
            ]);

            // Add to user balance
            $transaction->user->increment('account_balance', $transaction->amount);

            $this->logAdminAction('approve_request_fund', $transaction);
        });

        return back()->with('success', 'Request fund approved successfully.');
    }

    /**
     * Franchise Applications
     */
    public function franchiseApplications()
    {
        $applications = FranchiseApplication::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.franchise.applications', compact('applications'));
    }

    /**
     * Package Slot Management
     */
    public function packageSlots()
    {
        $packages = InvestmentPackage::all();
        return view('admin.packages.slots', compact('packages'));
    }

    /**
     * Update Package Slots
     */
    public function updatePackageSlots(Request $request, InvestmentPackage $package)
    {
        $request->validate([
            'available_slots' => 'required|integer|min:0'
        ]);

        $package->update([
            'available_slots' => $request->available_slots
        ]);

        $this->logAdminAction('update_package_slots', null, "Updated {$package->name} slots to {$request->available_slots}");

        return back()->with('success', 'Package slots updated successfully.');
    }

    /**
     * Deny Request Fund
     */
    public function denyRequestFund(Request $request, Transaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $transaction->update([
            'status' => 'denied',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
            'admin_notes' => $request->reason
        ]);

        $this->logAdminAction('deny_request_fund', $transaction, $request->reason);

        return back()->with('success', 'Request fund denied.');
    }

    /**
     * Approved Request Funds
     */
    public function approvedRequestFunds()
    {
        $requests = Transaction::with('user')
            ->where('type', 'request_fund')
            ->where('status', 'approved')
            ->latest('processed_at')
            ->paginate(20);

        return view('admin.request-funds.approved', compact('requests'));
    }

    /**
     * Transfer Funds Monitoring
     */
    public function transferFunds()
    {
        $transfers = Transaction::with(['user'])
            ->where('type', 'transfer')
            ->latest()
            ->paginate(20);

        return view('admin.transfer-funds.index', compact('transfers'));
    }

    /**
     * Activation Fund Management
     */
    public function activationFund()
    {
        $activationFunds = Transaction::with('user')
            ->where('type', 'activation_fund')
            ->latest()
            ->paginate(20);

        return view('admin.activation-fund.index', compact('activationFunds'));
    }

    /**
     * Approve Franchise Application
     */
    public function approveFranchise(Request $request, FranchiseApplication $application)
    {
        $application->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => Auth::id()
        ]);

        $this->logAdminAction('approve_franchise', null, "Approved franchise application for {$application->user->email}");

        return back()->with('success', 'Franchise application approved.');
    }

    /**
     * Reject Franchise Application
     */
    public function rejectFranchise(Request $request, FranchiseApplication $application)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $application->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => Auth::id(),
            'admin_notes' => $request->reason
        ]);

        $this->logAdminAction('reject_franchise', null, "Rejected franchise application for {$application->user->email}: {$request->reason}");

        return back()->with('success', 'Franchise application rejected.');
    }

    /**
     * Verify a user's email
     */
    public function verifyUser(User $user)
    {
        if ($user->email_verified_at) {
            return back()->with('error', 'User is already verified.');
        }

        // Force update with refresh
        $user->email_verified_at = now();
        $saved = $user->save();

        // Log the verification attempt
        \Log::info('Admin user verification', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'verification_time' => $user->email_verified_at,
            'save_result' => $saved,
            'admin_id' => Auth::id()
        ]);

        // Refresh the user model to make sure we have latest data
        $user->refresh();

        $this->logAdminAction('verify_user', null, "Manually verified user: {$user->email}");

        return back()->with('success', 'User has been verified successfully.');
    }

    /**
     * Suspend or unsuspend a user
     */
    public function suspendUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot suspend admin users.');
        }

        // Toggle suspension status using the new User model methods
        if ($user->isSuspended()) {
            // Unsuspend user
            $user->unsuspend();
            $action = 'unsuspend_user';
            $message = 'User has been unsuspended successfully.';
            $logMessage = "Unsuspended user: {$user->email}";
        } else {
            // Suspend user
            $user->suspend();
            $action = 'suspend_user';
            $message = 'User has been suspended successfully.';
            $logMessage = "Suspended user: {$user->email}";
        }

        $this->logAdminAction($action, null, $logMessage);

        return back()->with('success', $message);
    }

    private function logAdminAction($action, $transaction = null, $notes = null)
    {
        \Log::info('Admin Action', [
            'admin_id' => Auth::id(),
            'admin_email' => Auth::user()->email,
            'action' => $action,
            'transaction_id' => $transaction?->id,
            'target_user_id' => $transaction?->user_id,
            'ip_address' => request()->ip(),
            'notes' => $notes,
            'timestamp' => now()
        ]);
    }
}
