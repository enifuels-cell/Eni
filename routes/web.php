<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;

// Include debug routes
require __DIR__.'/debug_investment.php';

Route::get('/', function () {
    // Check if the user is authenticated
    if (auth()->check()) {
        // If authenticated, redirect immediately to the dashboard
        return redirect()->route('dashboard');
    }

    // If not authenticated, show the splash screen
    return view('splash-screen');
})->name('splash');

// Public FAQs JSON endpoint (read-only)
Route::get('/faqs.json', [FaqController::class, 'index'])->name('faqs.index');
// Test route for debugging
Route::get('/test', function () {
    return view('test');
});

// Temporary cache clear route (REMOVE AFTER USE)
Route::get('/clear-cache-temp', function () {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    
    return response()->json([
        'message' => 'All caches cleared successfully!',
        'commands_run' => [
            'config:clear',
            'cache:clear', 
            'route:clear',
            'view:clear'
        ]
    ]);
});

// Temporary debug route for investment issues (REMOVE AFTER USE)
Route::get('/debug-investment', function () {
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $packages = \App\Models\InvestmentPackage::all();
    $activePackages = \App\Models\InvestmentPackage::where('active', true)->get();
    
    return response()->json([
        'user_id' => $user->id,
        'user_balance' => $user->account_balance,
        'calculated_balance' => $user->accountBalance(),
        'total_packages' => $packages->count(),
        'active_packages' => $activePackages->count(),
        'packages' => $packages->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'active' => $p->active,
                'min_amount' => $p->min_amount,
                'max_amount' => $p->max_amount,
                'available_slots' => $p->available_slots
            ];
        }),
        'recent_logs' => \DB::table('laravel_log')->latest()->take(5)->get() ?? []
    ]);
});

// Public packages route for testing
Route::get('/public-packages', function () {
    $packages = App\Models\InvestmentPackage::available()->get();
    return view('investments.index', compact('packages'))->with('userInvestments', collect());
});

// Debug packages route
Route::get('/debug-packages', function () {
    $packages = App\Models\InvestmentPackage::all();
    $available = App\Models\InvestmentPackage::available()->get();
    
    return response()->json([
        'total_packages' => $packages->count(),
        'available_packages' => $available->count(),
        'all_packages' => $packages->map(function($p) {
            return [
                'name' => $p->name,
                'active' => $p->active,
                'available_slots' => $p->available_slots,
                'min_amount' => $p->min_amount,
                'max_amount' => $p->max_amount
            ];
        }),
        'available_only' => $available->map(function($p) {
            return [
                'name' => $p->name,
                'active' => $p->active,
                'available_slots' => $p->available_slots
            ];
        })
    ]);
});

// Production debug route (simpler, no auth required)
Route::get('/prod-debug', function () {
    try {
        $totalPackages = App\Models\InvestmentPackage::count();
        $activePackages = App\Models\InvestmentPackage::where('active', true)->count();
        $packagesWithSlots = App\Models\InvestmentPackage::where('active', true)
            ->where(function($q) {
                $q->whereNull('available_slots')->orWhere('available_slots', '>', 0);
            })->count();
            
        return response()->json([
            'status' => 'success',
            'total_packages' => $totalPackages,
            'active_packages' => $activePackages,
            'available_packages' => $packagesWithSlots,
            'environment' => app()->environment(),
            'database_connection' => config('database.default'),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'environment' => app()->environment(),
        ]);
    }
});

// Debug route to check auth status and PIN
Route::get('/debug-auth', function () {
    $user = auth()->user();
    if ($user) {
        return response()->json([
            'authenticated' => true,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'has_pin' => $user->pin_hash ? true : false,
            'pin_set_at' => $user->pin_set_at,
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'message' => 'User not logged in'
        ]);
    }
});

// Alternative logout route (GET)
Route::get('/logout-alt', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout.alt');

// Session and CSRF test route
Route::get('/session-test', function () {
    session(['test_key' => 'Session is working!']);
    $csrfToken = csrf_token();
    return response()->json([
        'session_works' => session('test_key'),
        'csrf_token' => $csrfToken,
        'session_id' => session()->getId(),
        'config_url' => config('app.url')
    ]);
});

// Demo route to force splash screen view
Route::get('/demo-splash', function () {
    return view('splash-screen');
})->name('demo.splash');

// Redirect /dashboard based on user role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified', 'check.suspended'])->name('dashboard');

// Dashboard action routes
Route::middleware(['auth', 'verified', 'check.suspended'])->group(function () {
    Route::get('/dashboard/investments', [UserDashboardController::class, 'investments'])->name('dashboard.investments');
    Route::get('/dashboard/transactions', [UserDashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::get('/dashboard/referrals', [UserDashboardController::class, 'referrals'])->name('dashboard.referrals');
    Route::get('/dashboard/packages', [UserDashboardController::class, 'packages'])->name('dashboard.packages');
    Route::get('/dashboard/deposit', [UserDashboardController::class, 'deposit'])->name('dashboard.deposit');
    Route::post('/dashboard/deposit', [UserDashboardController::class, 'processDeposit'])->name('dashboard.deposit.process');
    Route::get('/dashboard/withdraw', [UserDashboardController::class, 'withdraw'])->name('dashboard.withdraw');
    Route::post('/dashboard/withdraw', [UserDashboardController::class, 'processWithdraw'])->name('dashboard.withdraw.process');
    Route::get('/dashboard/transfer', [UserDashboardController::class, 'transfer'])->name('dashboard.transfer');
    Route::post('/dashboard/transfer', [UserDashboardController::class, 'processTransfer'])->name('dashboard.transfer.process');
    Route::get('/dashboard/franchise', [UserDashboardController::class, 'franchise'])->name('dashboard.franchise');
    Route::post('/dashboard/franchise', [UserDashboardController::class, 'processFranchise'])->name('dashboard.franchise.process');

    // Secure transaction receipt file streaming
    Route::get('/transaction/{transaction}/receipt-file', [ReceiptController::class, 'show'])->name('transaction.receipt.file');
});

Route::middleware(['auth', 'check.suspended'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // PIN Setup Route
    Route::get('/pin-setup', function () {
        return view('auth.pin-setup');
    })->name('pin.setup.form');
});

// User Routes (Protected)
Route::middleware(['auth', 'verified', 'check.suspended'])->group(function () {
    // User Dashboard
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/investments', [UserDashboardController::class, 'investments'])->name('investments');
        Route::get('/transactions', [UserDashboardController::class, 'transactions'])->name('transactions');
        Route::get('/referrals', [UserDashboardController::class, 'referrals'])->name('referrals');
        Route::get('/notifications', [UserDashboardController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-all-read', [UserDashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');
        Route::post('/notifications/{id}/mark-read', [UserDashboardController::class, 'markNotificationAsRead'])->name('notifications.mark-read');
        
        // Investment Packages
        Route::get('/packages', [UserDashboardController::class, 'packages'])->name('packages');
        
        // Deposit & Withdrawal
        Route::get('/deposit', [UserDashboardController::class, 'deposit'])->name('deposit');
    Route::post('/deposit', [UserDashboardController::class, 'processDeposit'])->middleware('throttle:deposits')->name('deposit.process');
        Route::get('/withdraw', [UserDashboardController::class, 'withdraw'])->name('withdraw');
    Route::post('/withdraw', [UserDashboardController::class, 'processWithdraw'])->middleware('throttle:withdrawals')->name('withdraw.process');
        
        // Investment Receipt
        Route::get('/investment/receipt/{transaction}', [UserDashboardController::class, 'investmentReceipt'])->name('investment.receipt');
        
        // Franchise Applications
        Route::get('/franchise', [UserDashboardController::class, 'franchise'])->name('franchise');
        Route::post('/franchise', [UserDashboardController::class, 'processFranchise'])->name('franchise.process');
    });

    // Investment Routes
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [InvestmentController::class, 'index'])->name('index');
    Route::post('/', [InvestmentController::class, 'store'])->middleware('throttle:investments')->name('store');
        Route::get('/{investment}', [InvestmentController::class, 'show'])->name('show');
    });
});

// Admin Routes (Protected with admin middleware) - Moved to admin.php
// Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//     Route::get('/investments', [AdminDashboardController::class, 'investments'])->name('investments');
//     Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions');
//     Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
//     
//     // Transaction Management
//     Route::patch('/transactions/{transaction}/approve', [AdminDashboardController::class, 'approveTransaction'])->name('transactions.approve');
//     Route::patch('/transactions/{transaction}/reject', [AdminDashboardController::class, 'rejectTransaction'])->name('transactions.reject');
//     
//     // Franchise Management
//     Route::get('/franchise-applications', [AdminDashboardController::class, 'franchiseApplications'])->name('franchise.index');
//     Route::patch('/franchise-applications/{application}/approve', [AdminDashboardController::class, 'approveFranchise'])->name('franchise.approve');
//     Route::patch('/franchise-applications/{application}/reject', [AdminDashboardController::class, 'rejectFranchise'])->name('franchise.reject');
// });

require __DIR__.'/auth.php';

// Admin routes
require __DIR__.'/admin.php';

// Debug route
Route::get('/debug/packages', function() {
    $packages = App\Models\InvestmentPackage::active()->get();
    return view('debug.packages', compact('packages'));
});
