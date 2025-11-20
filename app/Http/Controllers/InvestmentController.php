<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Services\InvestmentService;
use App\Http\Requests\StoreInvestmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InvestmentController extends Controller
{
    use AuthorizesRequests;

    // Display packages and user investments
    public function index()
    {
        Log::info('InvestmentController@index called');

        // Fetch active packages
        $packages = InvestmentPackage::active()
            ->orderBy('min_amount', 'asc')
            ->get();

        Log::info('Active packages count: ' . $packages->count());

        $userInvestments = Auth::user()
            ->investments()
            ->with('investmentPackage')
            ->latest()
            ->get();

        Log::info('User investments count: ' . $userInvestments->count());

        return view('investments.index', compact('packages', 'userInvestments'));
    }

    // Store a new investment
    public function store(StoreInvestmentRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $package = InvestmentPackage::find($data['investment_package_id']);
        if (!$package) {
            return back()->withErrors(['investment_package_id' => 'Selected package not found']);
        }

        try {
            app(InvestmentService::class)->createInvestment(
                $user,
                $package,
                round((float) $data['amount'], 2),
                $data['referral_code'] ?? null
            );

            return redirect()->route('investments.index')
                ->with('success', 'Investment created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator->errors());
        } catch (\Throwable $e) {
            Log::channel('investment')->error('Investment creation failed', [
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 1000)
            ]);
            return back()->withErrors(['general' => 'Failed to create investment. Please try again.']);
        }
    }

    // Show single investment
    public function show(Investment $investment)
    {
        $this->authorize('view', $investment);

        $investment->load(['investmentPackage', 'dailyInterestLogs']);
        return view('investments.show', compact('investment'));
    }
}
