<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\InvestmentPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\QrCodeService;
use Carbon\Carbon;

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

        // Attendance System Data
        $showAttendanceModal = $user->shouldShowAttendanceModal();
        $currentMonthTickets = $user->getMonthlyTicketCount();
        $currentMonthAttendance = $user->getMonthlyAttendance()->count();
        $currentMonthDays = now()->daysInMonth;

        // Get attendance dates for calendar
        $attendanceDates = $user->getMonthlyAttendance()->pluck('attendance_date')->map(function($date) {
            return $date->toDateString();
        })->toArray();

        // Get investment packages for rotating banner
        $investmentPackages = InvestmentPackage::active()
            ->orderBy('min_amount')
            ->get();

        return view('dashboard', compact(
            'total_invested',
            'total_interest',
            'total_referral_bonus',
            'account_balance',
            'active_investments',
            'recent_transactions',
            'recent_notifications',
            'unread_notifications_count',
            'showAttendanceModal',
            'currentMonthTickets',
            'currentMonthAttendance',
            'currentMonthDays',
            'attendanceDates',
            'investmentPackages'
        ));
    }

    public function mobileIndex()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get user statistics
        $total_invested = $user->totalInvestedAmount();
        $total_interest = $user->totalInterestEarned();
        $total_referral_bonus = $user->totalReferralBonuses();
        $account_balance = $user->accountBalance();

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

        // Attendance System Data
        $showAttendanceModal = $user->shouldShowAttendanceModal();
        $currentMonthTickets = $user->getMonthlyTicketCount();
        $currentMonthAttendance = $user->getMonthlyAttendance()->count();
        $currentMonthDays = now()->daysInMonth;

        // Get attendance dates for calendar
        $attendanceDates = $user->getMonthlyAttendance()->pluck('attendance_date')->map(function($date) {
            return $date->toDateString();
        })->toArray();

        return view('mobile-dashboard', compact(
            'total_invested',
            'total_interest',
            'total_referral_bonus',
            'account_balance',
            'active_investments',
            'recent_transactions',
            'recent_notifications',
            'unread_notifications_count',
            'showAttendanceModal',
            'currentMonthTickets',
            'currentMonthAttendance',
            'currentMonthDays',
            'attendanceDates'
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
        $investmentPackages = InvestmentPackage::active()
            ->orderBy('min_amount', 'asc') // Order by min_amount to ensure correct display order
            ->get();
        \Log::info('Investment packages loaded for user.investments view: ' . $investmentPackages->count());

        return view('user.investments', compact('investments', 'investmentPackages'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user->transactions()
            ->latest()
            ->paginate(15);

        // Calculate summary statistics - handle Money objects properly
        $depositTransactions = $user->transactions()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->get();
        $totalDeposits = 0.0;
        foreach ($depositTransactions as $transaction) {
            $totalDeposits += $transaction->amount instanceof \App\Support\Money ? $transaction->amount->toFloat() : (float)$transaction->amount;
        }

        $withdrawalTransactions = $user->transactions()
            ->where('type', 'withdrawal')
            ->where('status', 'completed')
            ->get();
        $totalWithdrawals = 0.0;
        foreach ($withdrawalTransactions as $transaction) {
            $totalWithdrawals += $transaction->amount instanceof \App\Support\Money ? $transaction->amount->toFloat() : (float)$transaction->amount;
        }

        $interestTransactions = $user->transactions()
            ->where('type', 'interest')
            ->where('status', 'completed')
            ->get();
        $totalInterest = 0.0;
        foreach ($interestTransactions as $transaction) {
            $totalInterest += $transaction->amount instanceof \App\Support\Money ? $transaction->amount->toFloat() : (float)$transaction->amount;
        }

        $referralBonusTransactions = $user->transactions()
            ->where('type', 'referral_bonus')
            ->where('status', 'completed')
            ->get();
        $totalReferralBonuses = 0.0;
        foreach ($referralBonusTransactions as $transaction) {
            $totalReferralBonuses += $transaction->amount instanceof \App\Support\Money ? $transaction->amount->toFloat() : (float)$transaction->amount;
        }

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
        $referralLink = $user->getReferralUrl();
        $qrCode = QrCodeService::generateWithLogo($referralLink, 200);

        return view('user.referrals', compact('referrals', 'qrCode', 'referralLink', 'packages'));
    }

    public function packages()
    {
        $investmentPackages = InvestmentPackage::active()
            ->orderBy('min_amount', 'asc') // Order by min_amount to ensure correct display order
            ->get();
        $accountBalance = Auth::user()->accountBalance(); // Use calculated balance that excludes locked investments

        // Get user's investments for the "Your Active Investments" section
        $investments = Auth::user()->investments()
            ->with(['investmentPackage', 'dailyInterestLogs'])
            ->latest()
            ->paginate(10);

        return view('user.packages', compact('investmentPackages', 'accountBalance', 'investments'));
    }

    public function deposit()
    {
        return view('user.deposit');
    }

    public function processDeposit(Request $request)
    {
        try {
            \Log::info('Processing deposit', [
                'request_data' => $request->except(['receipt']), // Exclude file from log
                'has_receipt' => $request->hasFile('receipt'),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'content_type' => $request->header('Content-Type')
            ]);

            $request->validate([
                'amount' => 'required|numeric|min:10',
                'payment_method' => 'required|string',
                'package_id' => 'nullable|exists:investment_packages,id',
                'receipt' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf|max:2048',
                'selected_bank' => 'nullable|string|in:landbank,bpi,rcbc'
            ]);

            $user = Auth::user();

            // If package_id is provided, validate investment amount against package limits
            if ($request->package_id) {
                $package = InvestmentPackage::findOrFail($request->package_id);
                \Log::info('Package found', ['package' => $package->toArray()]);

                // Check if package is active
                if (!$package->active) {
                    $errorMessage = "This investment package is currently not available.";

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage
                        ], 422);
                    }

                    return back()->withErrors([
                        'package_id' => $errorMessage
                    ]);
                }

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

            // Handle receipt upload if provided (hardened)
            $receiptPath = null;
            if ($request->hasFile('receipt')) {
                $file = $request->file('receipt');
                // Extra mime validation using PHP's Fileinfo
                $finfoMime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file->getRealPath());
                $allowed = ['image/jpeg','image/png','application/pdf'];
                if (!in_array($finfoMime, $allowed)) {
                    return back()->withErrors(['receipt' => 'Invalid file type. Only JPG, PNG or PDF allowed.']);
                }
                $randomName = 'rcpt_' . bin2hex(random_bytes(8)) . '.' . $file->getClientOriginalExtension();
                // Store in private storage (local disk) not publicly accessible
                $receiptPath = $file->storeAs('receipts', $randomName, 'local');

                // Detailed logging for diagnostics
                try {
                    $fullPath = storage_path('app/' . $receiptPath);
                    \Log::channel('investment')->info('Receipt stored', [
                        'transaction_stage' => 'pre-create',
                        'original_name' => $file->getClientOriginalName(),
                        'stored_name' => $randomName,
                        'relative_path' => $receiptPath,
                        'size_bytes' => $file->getSize(),
                        'mime_reported' => $file->getClientMimeType(),
                        'mime_finfo' => $finfoMime,
                        'hash_sha256' => (is_file($fullPath) ? hash_file('sha256', $fullPath) : null),
                        'exists' => is_file($fullPath),
                    ]);
                } catch (\Throwable $logErr) {
                    \Log::warning('Receipt logging failure', ['error' => $logErr->getMessage()]);
                }
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

            // Check if this is an account balance payment (auto-approved)
            $isAccountBalancePayment = $request->payment_method === 'account_balance';

            // Create transaction (approved if account balance, pending otherwise)
            $transaction = $user->transactions()->create([
                'type' => 'deposit',
                'amount' => $request->amount,
                'reference' => $reference,
                'status' => $isAccountBalancePayment ? 'approved' : 'pending',
                'description' => $description,
                'receipt_path' => $receiptPath, // stored in local disk (private)
                'processed_at' => $isAccountBalancePayment ? now() : null,
            ]);

            \Log::info('Transaction created', [
                'transaction_id' => $transaction->id,
                'has_receipt' => (bool)$receiptPath,
                'receipt_path' => $receiptPath,
            ]);

            // If this is a package investment, create the investment record
            if ($request->package_id) {
                $package = InvestmentPackage::find($request->package_id);

                // Check if payment method is account_balance - if so, auto-approve
                $isAccountBalancePayment = $request->payment_method === 'account_balance';
                $isAutoApproved = false;

                // For account balance payments, verify user has sufficient balance
                if ($isAccountBalancePayment) {
                    $availableBalance = $user->account_balance;
                    if ($availableBalance < $request->amount) {
                        if ($request->ajax() || $request->wantsJson()) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Insufficient account balance. Available: ₱' . number_format($availableBalance, 2)
                            ], 422);
                        }
                        return back()->withErrors(['amount' => 'Insufficient account balance']);
                    }

                    // Deduct from account balance immediately
                    $user->decrement('account_balance', $request->amount);
                    $isAutoApproved = true;
                }

                $investment = $user->investments()->create([
                    'investment_package_id' => $request->package_id,
                    'amount' => $request->amount,
                    'daily_shares_rate' => $package->daily_shares_rate,
                    'remaining_days' => $package->effective_days,
                    'total_interest_earned' => 0,
                    'active' => $isAutoApproved, // Auto-approve if account balance payment
                    'started_at' => $isAutoApproved ? now() : null,
                    'ended_at' => null,
                ]);

                \Log::info('Investment created', [
                    'investment_id' => $investment->id,
                    'payment_method' => $request->payment_method,
                    'auto_approved' => $isAutoApproved
                ]);

                // If auto-approved (account balance payment), handle slots and referral bonus immediately
                if ($isAutoApproved) {
                    // Decrement package slots
                    if ($package) {
                        \App\Models\InvestmentPackage::where('id', $package->id)
                            ->where('available_slots', '>', 0)
                            ->decrement('available_slots');
                        $package->refresh();
                    }

                    // Process referral bonus if user was referred
                    $referral = $user->referralReceived;
                    if ($referral && $package) {
                        // Calculate bonus amount
                        $investmentAmountValue = $investment->amount instanceof \App\Support\Money
                            ? $investment->amount->toFloat()
                            : (float) $investment->amount;

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
                                'description' => 'Referral bonus from ' . $user->name . ' - ' . $package->name . ' investment',
                                'reference' => 'REF' . time() . rand(1000, 9999),
                                'processed_at' => now()
                            ]);
                        }
                    }
                }

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
            \Log::error('Deposit processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // More specific error messages based on the exception type
            $errorMessage = 'Server error. Please contact support.';

            if (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage = 'Database error. Please try again or contact support.';
            } elseif (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'upload')) {
                $errorMessage = 'File upload error. Please check your receipt and try again.';
            } elseif (str_contains($e->getMessage(), 'package') || str_contains($e->getMessage(), 'Package')) {
                $errorMessage = 'Invalid investment package. Please refresh the page and try again.';
            } elseif (str_contains($e->getMessage(), 'balance')) {
                $errorMessage = 'Insufficient balance. Please check your account balance.';
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'debug_info' => config('app.debug') ? $e->getMessage() : null
                ], 422);
            }

            return back()->withErrors([
                'general' => $errorMessage
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
                ->with('investmentPackage')
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
            'recipient' => $request->recipient,
            'amount' => $request->amount,
            'package_id' => $request->package_id
        ]);

        $request->validate([
            'recipient' => 'required|string',
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

        // Get recipient user by email, username, or name
        $recipients = User::where('email', $request->recipient)
                         ->orWhere('username', $request->recipient)
                         ->orWhere('name', $request->recipient)
                         ->get();

        if ($recipients->isEmpty()) {
            return back()->withErrors([
                'recipient' => 'Recipient not found. Please check the email address, username, or full name.'
            ])->withInput();
        }

        // If multiple users found with the same name, require more specific identifier
        if ($recipients->count() > 1) {
            // Check if exact email or username match exists
            $exactMatch = $recipients->filter(function ($user) use ($request) {
                return $user->email === $request->recipient || $user->username === $request->recipient;
            })->first();

            if ($exactMatch) {
                $recipient = $exactMatch;
            } else {
                $usersList = $recipients->map(function ($user) {
                    return $user->name . ' (' . $user->username . ', ' . $user->email . ')';
                })->join(', ');

                return back()->withErrors([
                    'recipient' => 'Multiple users found with that name: ' . $usersList . '. Please use email or username for exact match.'
                ])->withInput();
            }
        } else {
            $recipient = $recipients->first();
        }

        // Prevent self-transfer
        if ($recipient->id === $user->id) {
            return back()->withErrors([
                'recipient' => 'You cannot transfer funds to yourself.'
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
                    'description' => 'Transfer to ' . $recipient->name . ' (' . $recipient->email . ')' . ($request->description ? ': ' . $request->description : ''),
                    'reference' => 'TXN' . time() . rand(1000, 9999)
                ]);

                // Record recipient transaction
                $recipient->transactions()->create([
                    'type' => 'transfer',
                    'amount' => $amount, // Positive for incoming transfer
                    'status' => 'completed',
                    'description' => 'Transfer from ' . $user->name . ' (' . $user->email . ')' . ($request->description ? ': ' . $request->description : ''),
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
                            'description' => 'Investment in ' . $package->name . ' (funded by transfer from ' . $user->name . ')',
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

            // Create receipt data
            $receiptData = [
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                'sender_name' => $user->name,
                'sender_account_id' => $user->account_id,
                'recipient_name' => $recipient->name,
                'recipient_account_id' => $recipient->account_id,
                'amount' => $amount,
                'description' => $request->description,
                'date' => now()->format('M d, Y'),
                'time' => now()->format('H:i:s'),
                'package_investment' => $request->package_id ? true : false,
                'package_name' => $request->package_id ? InvestmentPackage::find($request->package_id)->name : null
            ];

            return redirect()->route('dashboard.transfer')
                ->with('success', 'Transfer completed successfully!' .
                    ($request->package_id ? ' Investment has been automatically activated.' : ''))
                ->with('receipt', $receiptData);

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

        // Attendance System Data for Raffle Modal
        $currentMonthTickets = $user->getMonthlyTicketCount();
        $currentMonthAttendance = $user->getMonthlyAttendance()->count();
        $currentMonthDays = now()->daysInMonth;

        // Get attendance dates for calendar
        $attendanceDates = $user->getMonthlyAttendance()->pluck('attendance_date')->map(function($date) {
            return $date->toDateString();
        })->toArray();

        return view('user.notifications', compact(
            'notifications',
            'categories',
            'filter',
            'currentMonthTickets',
            'currentMonthAttendance',
            'currentMonthDays',
            'attendanceDates'
        ));
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

    /**
     * Mark attendance for a specific date manually
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today'
        ]);

        $user = Auth::user();
        $date = Carbon::parse($request->date);

        // Check if attendance already exists for this date
        $existingAttendance = \App\Models\DailyAttendance::where('user_id', $user->id)
            ->where('attendance_date', $date)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already marked for this date.'
            ]);
        }

        // Check if the date is in the future
        if ($date->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark attendance for future dates.'
            ]);
        }

        // Record the attendance
        \App\Models\DailyAttendance::create([
            'user_id' => $user->id,
            'attendance_date' => $date,
            'tickets_earned' => 1,
            'first_login_time' => Carbon::now()->format('H:i:s'),
            'logged_in_at' => Carbon::now(),
        ]);

        // Get updated ticket count
        $newTicketCount = $user->getMonthlyTicketCount();

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully!',
            'newTicketCount' => $newTicketCount
        ]);
    }

    public function claimSignupBonus(Request $request)
    {
        $user = Auth::user();

        // Check if bonus already claimed
        if ($user->signup_bonus_claimed) {
            return response()->json([
                'success' => false,
                'message' => 'Sign-up bonus has already been claimed.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $bonusAmount = 10.00; // $10 sign-up bonus

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $bonusAmount,
                'reference' => 'SIGNUP-BONUS-' . $user->id,
                'status' => 'completed',
                'description' => 'Welcome Sign-up Bonus',
                'processed_at' => now(),
            ]);

            // Update user record
            $user->update([
                'signup_bonus_claimed' => true,
                'signup_bonus_claimed_at' => now(),
            ]);

            // Mark the notification as read
            $user->unreadNotifications()
                ->whereJsonContains('data->type', 'signup_bonus')
                ->update(['read_at' => now()]);

            DB::commit();

            \Log::info('Sign-up bonus claimed', [
                'user_id' => $user->id,
                'amount' => $bonusAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => '$10 sign-up bonus has been added to your account!',
                'new_balance' => $user->fresh()->accountBalance()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to claim sign-up bonus', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to claim bonus. Please try again.'
            ], 500);
        }
    }
}
