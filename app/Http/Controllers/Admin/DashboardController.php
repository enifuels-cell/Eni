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

class DashboardController extends Controller
{
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

    public function investments()
    {
        $investments = Investment::with(['user', 'investmentPackage'])
            ->latest()
            ->paginate(20);

        return view('admin.investments', compact('investments'));
    }

    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    public function approveTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Transaction approved successfully!');
    }

    public function rejectTransaction(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'rejected',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Transaction rejected successfully!');
    }

    public function franchiseApplications()
    {
        $applications = FranchiseApplication::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.franchise-applications', compact('applications'));
    }

    public function approveFranchise(FranchiseApplication $application)
    {
        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Franchise application approved!');
    }

    public function rejectFranchise(FranchiseApplication $application)
    {
        $application->update(['status' => 'rejected']);
        return back()->with('success', 'Franchise application rejected!');
    }

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
}
