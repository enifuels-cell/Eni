<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FranchiseApplication;
use App\Models\InvestmentPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $account_balance = $user->accountBalance();

        // Count active investments
        $active_investments = $user->investments()->active()->count();

        // Get recent transactions
        $recent_transactions = $user->transactions()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'total_invested', 
            'total_interest', 
            'total_referral_bonus', 
            'account_balance',
            'active_investments',
            'recent_transactions'
        ));
    }

    public function investments()
    {
        $user = Auth::user();
        $investments = $user->investments()
            ->with(['investmentPackage', 'dailyInterestLogs'])
            ->latest()
            ->paginate(10);

        // Get available investment packages
        $investmentPackages = InvestmentPackage::active()->get();

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

        // Generate referral QR code
        $referralLink = route('register', ['ref' => $user->id]);
        $qrCode = QrCode::size(200)->generate($referralLink);

        return view('user.referrals', compact('referrals', 'qrCode', 'referralLink', 'packages'));
    }

    public function packages()
    {
        $packages = InvestmentPackage::active()->get();
        $accountBalance = Auth::user()->account_balance ?? 0;
        return view('user.packages', compact('packages', 'accountBalance'));
    }

    public function deposit()
    {
        return view('user.deposit');
    }

    public function processDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string',
            'package_id' => 'nullable|exists:investment_packages,id',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $user = Auth::user();
        
        // If package_id is provided, validate investment amount against package limits
        if ($request->package_id) {
            $package = InvestmentPackage::findOrFail($request->package_id);
            
            if ($request->amount < $package->min_amount || $request->amount > $package->max_amount) {
                return back()->withErrors([
                    'amount' => "Investment amount must be between $" . number_format($package->min_amount) . " and $" . number_format($package->max_amount) . " for this package."
                ]);
            }
        }
        
        // Handle receipt upload if provided
        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $description = $request->package_id 
            ? 'Investment in ' . InvestmentPackage::find($request->package_id)->name . ' package'
            : 'Deposit request - awaiting approval';

        // Create pending transaction
        $transaction = $user->transactions()->create([
            'type' => 'deposit',
            'amount' => $request->amount,
            'reference' => 'Deposit via ' . $request->payment_method,
            'status' => 'pending',
            'description' => $description,
        ]);

        // If this is a package investment, create the investment record
        if ($request->package_id) {
            $user->investments()->create([
                'investment_package_id' => $request->package_id,
                'amount' => $request->amount,
                'status' => 'pending', // Will be activated when deposit is approved
                'start_date' => now(),
                'end_date' => now()->addDays(InvestmentPackage::find($request->package_id)->duration_days),
            ]);
            
            return redirect()->route('user.dashboard')
                ->with('success', 'Investment submitted successfully! Your investment will be activated once payment is confirmed.');
        }

        return redirect()->route('user.dashboard')
            ->with('success', 'Deposit request submitted successfully! It will be processed shortly.');
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
}
