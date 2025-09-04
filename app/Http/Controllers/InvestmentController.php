<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvestmentController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $packages = InvestmentPackage::available()->get();
        $userInvestments = Auth::user()->investments()->with('investmentPackage')->latest()->get();
        
        return view('investments.index', compact('packages', 'userInvestments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'investment_package_id' => 'required|exists:investment_packages,id',
            'amount' => 'required|numeric|min:0.01',
            'referral_code' => 'nullable|string'
        ]);

        $package = InvestmentPackage::findOrFail($request->investment_package_id);

        // Validate amount range
        if ($request->amount < $package->min_amount || $request->amount > $package->max_amount) {
            return back()->withErrors(['amount' => "Amount must be between $" . number_format($package->min_amount, 2) . " and $" . number_format($package->max_amount, 2)]);
        }

        // Check available slots
        if ($package->available_slots !== null && $package->available_slots <= 0) {
            return back()->withErrors(['package' => 'This package is currently full.']);
        }

        // Check user balance
        $user = Auth::user();
        if ($user->account_balance < $request->amount) {
            return back()->withErrors(['amount' => 'Insufficient account balance.']);
        }

        DB::transaction(function () use ($request, $package, $user) {
            // Create investment
            $investment = Investment::create([
                'user_id' => $user->id,
                'investment_package_id' => $package->id,
                'amount' => $request->amount,
                'daily_shares_rate' => $package->daily_shares_rate,
                'remaining_days' => $package->effective_days,
                'total_interest_earned' => 0,
                'active' => true,
                'started_at' => now(),
                'ended_at' => null,
            ]);

            // Deduct from user balance
            $user->decrement('account_balance', $request->amount);
            
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'other',
                'amount' => -$request->amount,
                'reference' => "Investment #" . $investment->id,
                'status' => 'completed',
                'description' => "Investment in " . $package->name,
                'processed_at' => now(),
            ]);

            // Handle referral if provided
            if ($request->referral_code) {
                $referral = Referral::where('referral_code', $request->referral_code)->first();
                if ($referral && $referral->referee_id !== $user->id) {
                    $bonusAmount = $request->amount * ($package->referral_bonus_rate / 100);
                    
                    ReferralBonus::create([
                        'referral_id' => $referral->id,
                        'investment_id' => $investment->id,
                        'bonus_amount' => $bonusAmount,
                        'paid' => true,
                        'paid_at' => now(),
                    ]);

                    // Credit referrer
                    Transaction::create([
                        'user_id' => $referral->referrer_id,
                        'type' => 'referral_bonus',
                        'amount' => $bonusAmount,
                        'reference' => "Referral bonus for investment #" . $investment->id,
                        'status' => 'completed',
                        'description' => "Referral bonus from " . $user->name,
                        'processed_at' => now(),
                    ]);
                }
            }

            // Update package slots
            if ($package->available_slots !== null) {
                $package->decrement('available_slots');
            }
        });

        return redirect()->route('investments.index')->with('success', 'Investment created successfully!');
    }

    public function show(Investment $investment)
    {
        $this->authorize('view', $investment);
        
        $investment->load(['investmentPackage', 'dailyInterestLogs']);
        return view('investments.show', compact('investment'));
    }
}
