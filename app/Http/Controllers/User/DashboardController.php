<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\InvestmentPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\QrCodeService;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Get user statistics
        $total_invested = $user->totalInvestedAmount();
        $total_interest = $user->totalInterestEarned();
        $total_referral_bonus = $user->totalReferralBonuses();
        $account_balance = $user->accountBalance(); // Use calculated balance that excludes locked investments

        // Count active investments
        $active_investments = $user->investments()->active()->count();

        // Get recent transactions
        $recent_transactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        // Get recent notifications for dropdown
        $recent_notifications = $user->userNotifications()
            ->active()
            ->latest()
            ->take(4)
            ->get();

        // Get unread notifications count
        $unread_notifications_count = $user->userNotifications()
            ->unread()
            ->active()
            ->count();

        return view('dashboard', compact(
            'total_invested', 
            'total_interest', 
            'total_referral_bonus', 
            'account_balance',
            'active_investments',
            'recent_transactions',
            'recent_notifications',
            'unread_notifications_count'
        ));
    }

    public function investments()
    {
        \Log::info('UserDashboardController investments() called');
        
        $user = Auth::user();
        $investments = $user->investments()
            ->with(['investmentPackage', 'dailyInterestLogs'])
            ->latest()
            ->paginate(10);

        // Get available investment packages
        $investmentPackages = InvestmentPackage::active()->get();
        \Log::info('Investment packages loaded for user.investments view: ' . $investmentPackages->count());

        return view('user.investments', compact('investments', 'investmentPackages'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user->transactions()
            ->latest()
            ->paginate(15);

        // Calculate summary statistics
        $totalDeposits = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->sum('amount');

        $totalWithdrawals = $user->transactions()
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->sum('amount');

        $totalInterest = $user->transactions()
            ->where('type', 'interest')
            ->where('status', 'completed')
            ->sum('amount');

        $totalReferralBonuses = $user->transactions()
            ->where('type', 'referral_bonus')
            ->where('status', 'completed')
            ->sum('amount');

        return view('user.transactions', compact(
            'transactions', 
            'totalDeposits', 
            'totalWithdrawals', 
            'totalInterest', 
            'totalReferralBonuses'
        ));
    }

    public function referrals()
    {
        $user = Auth::user();
        $referrals = $user->referralsGiven()
            ->with(['referee', 'referralBonuses'])
            ->latest()
            ->get();

        // Get investment packages for commission rate display
        $packages = InvestmentPackage::active()
            ->orderBy('min_amount')
            ->get();

        // Generate referral QR code with Eni logo
        $referralLink = route('register', ['ref' => $user->id]);
        $qrCode = QrCodeService::generateWithLogo($referralLink, 200);

        return view('user.referrals', compact('referrals', 'qrCode', 'referralLink', 'packages'));
    }

    public function packages()
    {
        $packages = InvestmentPackage::active()->get();
        $accountBalance = Auth::user()->accountBalance(); // Use calculated balance that excludes locked investments
        return view('user.packages', compact('packages', 'accountBalance'));
    }

    public function deposit()
    {
        return view('user.deposit');
    }

    public function processDeposit(Request $request)
    {
        try {
            \Log::info('Processing deposit', ['request_data' => $request->all()]);
            
            $request->validate([
                'amount' => 'required|numeric|min:10',
                'payment_method' => 'required|string',
                'package_id' => 'nullable|exists:investment_packages,id',
                'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'selected_bank' => 'nullable|string|in:landbank,bpi,rcbc'
            ]);

            $user = Auth::user();
            
            // If package_id is provided, validate investment amount against package limits
            if ($request->package_id) {
                $package = InvestmentPackage::findOrFail($request->package_id);
                
                if ($request->amount < $package->min_amount || $request->amount > $package->max_amount) {
                    $errorMessage = "Investment amount must be between $" . number_format($package->min_amount) . " and $" . number_format($package->max_amount) . " for this package.";
                    
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage
                        ], 422);
                    }
                    
                    return back()->withErrors([
                        'amount' => $errorMessage
                    ]);
                }
            }
            
            // Handle receipt upload if provided
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $receiptPath = $request->file('receipt')->store('receipts', 'public');
            }

            // Build reference string with bank info if bank transfer
            $reference = 'Deposit via ' . $request->payment_method;
            if ($request->payment_method === 'bank_transfer' && $request->selected_bank) {
                $bankNames = [
                    'landbank' => 'LandBank',
                    'bpi' => 'BPI',
                    'rcbc' => 'RCBC'
                ];
                $reference .= ' (' . $bankNames[$request->selected_bank] . ')';
            }

            $description = $request->package_id 
                ? 'Investment in ' . InvestmentPackage::find($request->package_id)->name . ' package'
                : 'Deposit request - awaiting approval';

            // Create pending transaction
            $transaction = $user->transactions()->create([
                'type' => 'deposit',
                'amount' => $request->amount,
                'reference' => $reference,
                'status' => 'pending',
                'description' => $description,
                'receipt_path' => $receiptPath,
            ]);

            \Log::info('Transaction created', ['transaction_id' => $transaction->id]);

            // If this is a package investment, create the investment record
            if ($request->package_id) {
                $package = InvestmentPackage::find($request->package_id);
                
                $investment = $user->investments()->create([
                    'investment_package_id' => $request->package_id,
                    'amount' => $request->amount,
                    'daily_shares_rate' => $package->daily_shares_rate,
                    'remaining_days' => $package->effective_days,
                    'total_interest_earned' => 0,
                    'active' => false, // Will be activated when deposit is approved
                    'started_at' => now(),
                    'ended_at' => null,
                ]);
                
                \Log::info('Investment created', ['investment_id' => $investment->id]);
                
                // Check if this is an AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Investment submitted successfully! Your transaction receipt is ready.',
                        'redirect' => route('user.investment.receipt', $transaction->id)
                    ]);
                }
                
                // Redirect to investment receipt for regular requests
                \Log::info('Redirecting to investment receipt', ['transaction_id' => $transaction->id]);
                
                return redirect()->route('user.investment.receipt', $transaction->id)
                    ->with('success', 'Investment submitted successfully! Your transaction receipt is ready.');
            }

            // For regular deposits (non-investment)
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Deposit request submitted successfully! Transaction ID: ' . $transaction->id,
                    'redirect' => route('user.dashboard')
                ]);
            }
            
            return redirect()->route('user.dashboard')
                ->with('success', 'Deposit request submitted successfully! Transaction ID: ' . $transaction->id);
                
        } catch (\Exception $e) {
            \Log::error('Deposit processing error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error submitting investment. Please try again. Error: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors([
                'general' => 'Error submitting investment. Please try again. Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function investmentReceipt($transactionId)
    {
        $user = Auth::user();
        
        // Get the transaction and ensure it belongs to the authenticated user
        $transaction = $user->transactions()->findOrFail($transactionId);
        
        // Get the associated investment if this was an investment transaction
        $investment = null;
        if ($transaction->description && str_contains($transaction->description, 'Investment in')) {
            $investment = $user->investments()
                ->where('amount', $transaction->amount)
                ->where('created_at', '>=', $transaction->created_at->subMinutes(5))
                ->where('created_at', '<=', $transaction->created_at->addMinutes(5))
                ->with('package')
                ->first();
        }
        
        return view('user.investment-receipt', compact('transaction', 'investment'));
    }

    public function withdraw()
    {
        $user = Auth::user();
        $availableBalance = $user->accountBalance();
        
        return view('user.withdraw', compact('availableBalance'));
    }

    public function processWithdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'bank_details' => 'required|string'
        ]);

        $user = Auth::user();
        $availableBalance = $user->accountBalance();

        if ($request->amount > $availableBalance) {
            return back()->withErrors(['amount' => 'Insufficient balance.']);
        }

        // Create pending withdrawal transaction
        $user->transactions()->create([
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'reference' => 'Withdrawal request',
            'status' => 'pending',
            'description' => 'Withdrawal to: ' . $request->bank_details,
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Withdrawal request submitted successfully!');
    }

    public function franchise()
    {
        $user = Auth::user();
        $existingApplication = $user->franchiseApplications()->latest()->first();
        
        return view('user.franchise', compact('existingApplication'));
    }

    public function processFranchise(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        // Check if user already has a pending application
        $existingApplication = $user->franchiseApplications()
            ->where('status', 'pending')
            ->exists();

        if ($existingApplication) {
            return back()->withErrors(['application' => 'You already have a pending franchise application.']);
        }

        $user->franchiseApplications()->create($request->all());

        return redirect()->route('user.franchise')
            ->with('success', 'Franchise application submitted successfully!');
    }

    public function transfer()
    {
        $user = Auth::user();
        $accountBalance = $user->accountBalance(); // Use calculated balance that excludes locked investments
        
        return view('user.transfer', compact('accountBalance'));
    }

    public function processTransfer(Request $request)
    {
        \Log::info('Transfer attempt started', [
            'user' => Auth::user()->email,
            'recipient_email' => $request->recipient_email,
            'amount' => $request->amount,
            'package_id' => $request->package_id
        ]);

        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
            'package_id' => 'nullable|exists:investment_packages,id',
            'description' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        // Check if user has sufficient available balance (excluding locked investments)
        $availableBalance = $user->accountBalance();
        if ($availableBalance < $amount) {
            return back()->withErrors([
                'amount' => 'Insufficient available balance. Your current available balance is $' . number_format($availableBalance, 2)
            ])->withInput();
        }

        // Get recipient user
        $recipient = User::where('email', $request->recipient_email)->first();
        
        if (!$recipient) {
            return back()->withErrors([
                'recipient_email' => 'Recipient not found.'
            ])->withInput();
        }

        // Prevent self-transfer
        if ($recipient->id === $user->id) {
            return back()->withErrors([
                'recipient_email' => 'You cannot transfer funds to yourself.'
            ])->withInput();
        }

        try {
            \DB::transaction(function () use ($user, $recipient, $amount, $request) {
                // Deduct from sender
                $user->decrement('account_balance', $amount);
                
                // Add to recipient
                $recipient->increment('account_balance', $amount);

                // Record sender transaction
                $user->transactions()->create([
                    'type' => 'transfer',
                    'amount' => -$amount, // Negative for outgoing transfer
                    'status' => 'completed',
                    'description' => 'Transfer to ' . $recipient->email . ($request->description ? ': ' . $request->description : ''),
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // Record recipient transaction
                $recipient->transactions()->create([
                    'type' => 'transfer',
                    'amount' => $amount, // Positive for incoming transfer
                    'status' => 'completed',
                    'description' => 'Transfer from ' . $user->email . ($request->description ? ': ' . $request->description : ''),
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // If package_id is provided, create investment automatically
                if ($request->package_id) {
                    $package = InvestmentPackage::findOrFail($request->package_id);
                    
                    // Validate investment amount against package limits
                    if ($amount >= $package->min_amount && $amount <= $package->max_amount) {
                        // Create investment for recipient (automatically active since paid from transfer)
                        $investment = $recipient->investments()->create([
                            'investment_package_id' => $package->id,
                            'amount' => $amount,
                            'daily_shares_rate' => $package->daily_shares_rate,
                            'remaining_days' => $package->effective_days,
                            'total_interest_earned' => 0,
                            'active' => true, // Auto-activate when paid via transfer
                            'started_at' => now(),
                            'ended_at' => null,
                        ]);

                        // Create investment transaction record for recipient
                        $recipient->transactions()->create([
                            'type' => 'other',
                            'amount' => -$amount, // Negative because it's deducted from their balance
                            'status' => 'completed',
                            'description' => 'Investment in ' . $package->name . ' (funded by transfer from ' . $user->email . ')',
                            'reference' => 'INV' . time() . rand(1000, 9999)
                        ]);

                        // Deduct investment amount from recipient's balance
                        $recipient->decrement('account_balance', $amount);
                        
                        // Update package slots if applicable
                        if ($package->available_slots !== null) {
                            $package->decrement('available_slots');
                        }
                    }
                }
            });

            return redirect()->route('dashboard.transfer')
                ->with('success', 'Transfer completed successfully!' . 
                    ($request->package_id ? ' Investment has been automatically activated.' : ''));

        } catch (\Exception $e) {
            \Log::error('Transfer failed', [
                'user' => Auth::user()->email,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'transfer' => 'Transfer failed. Please try again. Error: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function notifications(Request $request)
    {
        $user = Auth::user();
        
        // Define available categories
        $categories = [
            'all' => 'All Notifications',
            'security' => 'Security',
            'investment' => 'Investment',
            'account' => 'Account',
            'system' => 'System',
            'welcome' => 'Welcome',
            'referral' => 'Referral',
            'transaction' => 'Transaction',
            'announcement' => 'Announcement',
            'maintenance' => 'Maintenance'
        ];
        
        // Get filter from request, default to 'all'
        $filter = $request->get('filter', 'all');
        
        // Build notifications query
        $notificationsQuery = $user->userNotifications()->active()->latest();
        
        // Apply filter if not 'all'
        if ($filter !== 'all' && array_key_exists($filter, $categories)) {
            $notificationsQuery->where('category', $filter);
        }
        
        $notifications = $notificationsQuery->paginate(15);

        return view('user.notifications', compact('notifications', 'categories', 'filter'));
    }

    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->userNotifications()->findOrFail($id);
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->userNotifications()->unread()->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
}
