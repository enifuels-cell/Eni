<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\InvestmentService;
use App\Http\Requests\StoreInvestmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvestmentController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        \Log::info('InvestmentController index called');
        
        // Try using active() instead of available() for debugging
        $packages = InvestmentPackage::active()->get();
        \Log::info('Active packages count: ' . $packages->count());
        \Log::info('All packages count: ' . InvestmentPackage::count());
        
        // Also get available packages separately for comparison
        $availablePackages = InvestmentPackage::available()->get();
        \Log::info('Available packages count: ' . $availablePackages->count());
        
        $userInvestments = Auth::user()->investments()->with('investmentPackage')->latest()->get();
        \Log::info('User investments count: ' . $userInvestments->count());
        
        return view('investments.index', compact('packages', 'userInvestments'));
    }

    public function store(StoreInvestmentRequest $request)
    {
        $data = $request->validated();
        $package = InvestmentPackage::findOrFail($data['investment_package_id']);
        $user = Auth::user();

        try {
            app(InvestmentService::class)->createInvestment(
                $user,
                $package,
                round((float) $data['amount'], 2),
                $data['referral_code'] ?? null
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator->errors());
        } catch (\Throwable $e) {
            \Log::channel('investment')->error('Investment creation failed', [
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 1000)
            ]);
            return back()->withErrors(['general' => 'Failed to create investment. Please try again.']);
        }

        return redirect()->route('investments.index')->with('success', 'Investment created successfully!');
    }

    public function show(Investment $investment)
    {
        $this->authorize('view', $investment);
        
        $investment->load(['investmentPackage', 'dailyInterestLogs']);
        return view('investments.show', compact('investment'));
    }
}
