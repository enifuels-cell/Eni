<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\InvestmentPackage;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/debug/investment-test', function (Request $request) {
    try {
        // Test 1: Check if models can be loaded
        $packageCount = InvestmentPackage::count();
        $investmentCount = Investment::count();
        $userCount = User::count();
        
        // Test 2: Check if we can create a basic investment query
        $testInvestment = Investment::with('investmentPackage')->first();
        
        // Test 3: Check if current user is authenticated
        $user = Auth::user();
        
        $results = [
            'database_connection' => 'OK',
            'package_count' => $packageCount,
            'investment_count' => $investmentCount,
            'user_count' => $userCount,
            'auth_user' => $user ? $user->id : 'Not authenticated',
            'test_investment_relationship' => $testInvestment ? 'OK' : 'No investments found',
            'timestamp' => now()->toDateTimeString()
        ];
        
        if ($testInvestment) {
            $results['test_package_name'] = $testInvestment->investmentPackage->name ?? 'Relationship failed';
        }
        
        return response()->json([
            'status' => 'success',
            'debug_info' => $results
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

Route::post('/debug/test-deposit', function (Request $request) {
    try {
        \Log::info('Debug deposit test started', [
            'request_data' => $request->except(['receipt']),
            'has_receipt' => $request->hasFile('receipt'),
            'content_type' => $request->header('Content-Type'),
            'user_agent' => $request->header('User-Agent')
        ]);
        
        // Test authentication
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // Test package loading
        if ($request->package_id) {
            $package = InvestmentPackage::findOrFail($request->package_id);
            \Log::info('Package loaded successfully', ['package_name' => $package->name]);
        }
        
        // Test investment relationship
        $testInvestment = $user->investments()->with('investmentPackage')->first();
        if ($testInvestment) {
            $packageName = $testInvestment->investmentPackage->name;
            \Log::info('Investment relationship test passed', ['package_name' => $packageName]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Debug test completed successfully',
            'user_id' => $user->id,
            'package_test' => $request->package_id ? 'Package loaded: ' . $package->name : 'No package ID provided',
            'relationship_test' => $testInvestment ? 'Relationship works: ' . $packageName : 'No existing investments'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Debug deposit test error', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Debug test failed: ' . $e->getMessage(),
            'error_details' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
})->middleware('auth');
