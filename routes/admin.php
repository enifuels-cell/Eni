<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin', 'admin.session'])->group(function () {

    // Dashboard Overview
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Deposit Management
    Route::get('/pending-deposits', [AdminDashboardController::class, 'pendingDeposits'])->name('deposits.pending');
    Route::patch('/deposits/{transaction}/approve', [AdminDashboardController::class, 'approveDeposit'])->name('deposits.approve');
    Route::patch('/deposits/{transaction}/deny', [AdminDashboardController::class, 'denyDeposit'])->name('deposits.deny');
    Route::delete('/deposits/{transaction}', [AdminDashboardController::class, 'deleteDeposit'])->name('deposits.delete');
    Route::get('/approved-deposits', [AdminDashboardController::class, 'approvedDeposits'])->name('deposits.approved');

    // Daily Interest Log
    Route::get('/daily-interest-log', [AdminDashboardController::class, 'dailyInterestLog'])->name('interest.daily');

    // Withdrawal Management
    Route::get('/pending-withdrawals', [AdminDashboardController::class, 'pendingWithdrawals'])->name('withdrawals.pending');
    Route::patch('/withdrawals/{transaction}/approve', [AdminDashboardController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::patch('/withdrawals/{transaction}/deny', [AdminDashboardController::class, 'denyWithdrawal'])->name('withdrawals.deny');
    Route::get('/approved-withdrawals', [AdminDashboardController::class, 'approvedWithdrawals'])->name('withdrawals.approved');

    // Request Fund Management
    Route::get('/pending-request-funds', [AdminDashboardController::class, 'pendingRequestFunds'])->name('request-funds.pending');
    Route::patch('/request-funds/{transaction}/approve', [AdminDashboardController::class, 'approveRequestFund'])->name('request-funds.approve');
    Route::patch('/request-funds/{transaction}/deny', [AdminDashboardController::class, 'denyRequestFund'])->name('request-funds.deny');
    Route::get('/approved-request-funds', [AdminDashboardController::class, 'approvedRequestFunds'])->name('request-funds.approved');

    // User Management
    Route::get('/manage-users', [AdminDashboardController::class, 'manageUsers'])->name('users.manage');
    Route::patch('/users/{user}/verify', [AdminDashboardController::class, 'verifyUser'])->name('users.verify');
    Route::patch('/users/{user}/suspend', [AdminDashboardController::class, 'suspendUser'])->name('users.suspend');
    Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');

    // Transfer Fund Monitoring (placeholder)
    Route::get('/transfer-funds', [AdminDashboardController::class, 'transferFunds'])->name('transfer-funds.index');

    // Package Slot Management
    Route::get('/package-slots', [AdminDashboardController::class, 'packageSlots'])->name('packages.slots');
    Route::patch('/packages/{package}/slots', [AdminDashboardController::class, 'updatePackageSlots'])->name('packages.update-slots');

    // Franchise Applications
    Route::get('/franchise-applications', [AdminDashboardController::class, 'franchiseApplications'])->name('franchise.applications');
    Route::patch('/franchise/{application}/approve', [AdminDashboardController::class, 'approveFranchise'])->name('franchise.approve');
    Route::patch('/franchise/{application}/reject', [AdminDashboardController::class, 'rejectFranchise'])->name('franchise.reject');

    // Activation Fund Management (placeholder)
    Route::get('/activation-fund', [AdminDashboardController::class, 'activationFund'])->name('activation-fund.index');
    Route::post('/activation-fund/send', [AdminDashboardController::class, 'sendActivationFund'])->name('activation-fund.send');

    // Raffle Management
    Route::resource('raffles', \App\Http\Controllers\Admin\RaffleController::class);
    Route::post('/raffles/{raffle}/conduct-draw', [\App\Http\Controllers\Admin\RaffleController::class, 'conductDraw'])->name('raffles.conduct-draw');

});

// Admin redirect route
Route::get('/admin-dashboard', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['admin', 'admin.session']);
