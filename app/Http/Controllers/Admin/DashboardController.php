<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyInterestLog;
use App\Models\FranchiseApplication;
use App\Models\Investment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Log admin actions for auditing
     */
    private function logAdminAction($action, $transaction = null, $notes = null)
    {
        Log::info('Admin Action', [
            'admin_id' => Auth::id(),
            'admin_email' => Auth::user()?->email,
            'action' => $action,
            'transaction_id' => $transaction?->id,
            'target_user_id' => $transaction?->user_id,
            'ip_address' => request()->ip(),
            'notes' => $notes,
            'timestamp' => now(),
        ]);
    }

    /**
     * Admin dashboard overview
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_investments' => Investment::sum('amount'),
            'active_investments' => Investment::active()->count(),
            'total_interest_paid' => DailyInterestLog::sum('interest_amount'),
            'pending_transactions' => Transaction::pending()->count(),
            'pending_franchises' => FranchiseApplication::pending()->count(),
        ];

        $recentInvestments = Investment::with(['user', 'investmentPackage'])
            ->latest()
            ->take(10)
            ->get();

        $pendingTransactions = Transaction::pending()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentInvestments', 'pendingTransactions'));
    }

    /**
     * List all investments
     */
    public function investments()
    {
        $investments = Investment::with(['user', 'investmentPackage'])
            ->latest()
            ->paginate(20);

        return view('admin.investments', compact('investments'));
    }

    /**
     * List all transactions
     */
    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Approve a transaction
     */
    public function approveTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        $this->logAdminAction('approve_transaction', $transaction);

        return back()->with('success', 'Transaction approved successfully!');
    }

    /**
     * Reject a transaction
     */
    public function rejectTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
            'processed_at' => now(),
        ]);

        $this->logAdminAction('reject_transaction', $transaction);

        return back()->with('success', 'Transaction rejected successfully!');
    }

    /**
     * Franchise applications list
     */
    public function franchiseApplications()
    {
        $applications = FranchiseApplication::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.franchise-applications', compact('applications'));
    }

    /**
     * Approve franchise application
     */
    public function approveFranchise(FranchiseApplication $application)
    {
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->logAdminAction('approve_franchise', null, 'Franchise ID: ' . $application->id);

        return back()->with('success', 'Franchise application approved!');
    }

    /**
     * Reject franchise application
     */
    public function rejectFranchise(FranchiseApplication $application)
    {
        $application->update(['status' => 'rejected']);

        $this->logAdminAction('reject_franchise', null, 'Franchise ID: ' . $application->id);

        return back()->with('success', 'Franchise application rejected!');
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        $monthlyStats = DB::table('investments')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as investment_count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $packageStats = DB::table('investments')
            ->join('investment_packages', 'investments.investment_package_id', '=', 'investment_packages.id')
            ->select(
                'investment_packages.name',
                DB::raw('COUNT(*) as investment_count'),
                DB::raw('SUM(investments.amount) as total_amount')
            )
            ->groupBy('investment_packages.id', 'investment_packages.name')
            ->get();

        return view('admin.analytics', compact('monthlyStats', 'packageStats'));
    }

    /**
     * Daily Interest Log page
     */
    public function dailyInterestLog(Request $request)
    {
        $query = DailyInterestLog::with(['user', 'investment.investmentPackage'])->latest();

        // Filter by date if provided
        if ($request->has('date') && $request->date) {
            $query->forDate($request->date);
        }

        $logs = $query->paginate(20);

        $totalToday = DailyInterestLog::forDate(now())->sum('interest_amount');

        return view('admin.daily-interest-log', compact('logs', 'totalToday'));
    }
}
