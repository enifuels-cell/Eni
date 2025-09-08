@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Page Header -->
    <div class="border-b border-eni-yellow/30 pb-4 mb-6">
        <h1 class="text-3xl font-bold leading-tight text-white">Admin Dashboard</h1>
        <p class="mt-2 text-sm text-gray-300">Real-time platform overview and management</p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-eni-yellow/20 border border-eni-yellow/40 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-eni-yellow text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-white">{{ number_format($totalUsers ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-eni-yellow/5 border-t border-eni-yellow/20 px-5 py-3">
                <div class="text-sm">
                    <span class="text-green-400 font-medium">+{{ $newSignupsToday ?? 0 }}</span>
                    <span class="text-gray-400">new today</span>
                </div>
            </div>
        </div>

        <!-- Total Funds -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500/20 border border-green-500/40 rounded-md flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Total Funds</dt>
                            <dd class="text-lg font-medium text-white">${{ number_format($totalFunds ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-eni-yellow/5 border-t border-eni-yellow/20 px-5 py-3">
                <div class="text-sm">
                    <span class="text-eni-yellow font-medium">${{ number_format($totalRevenue ?? 0, 2) }}</span>
                    <span class="text-gray-400">revenue</span>
                </div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500/20 border border-orange-500/40 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-orange-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Pending Reviews</dt>
                            <dd class="text-lg font-medium text-white">{{ ($pendingDeposits ?? 0) + ($pendingWithdrawals ?? 0) + ($pendingRequestFunds ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-eni-yellow/5 border-t border-eni-yellow/20 px-5 py-3">
                <div class="text-sm">
                    <span class="text-orange-400 font-medium">{{ $pendingDeposits ?? 0 }}</span>
                    <span class="text-gray-400">deposits</span>
                    <span class="mx-1">•</span>
                    <span class="text-red-400 font-medium">{{ $pendingWithdrawals ?? 0 }}</span>
                    <span class="text-gray-400">withdrawals</span>
                </div>
            </div>
        </div>

        <!-- Daily Interest -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500/20 border border-purple-500/40 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-400 text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-400 truncate">Interest Today</dt>
                            <dd class="text-lg font-medium text-white">${{ number_format($dailyInterestToday ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-eni-yellow/5 border-t border-eni-yellow/20 px-5 py-3">
                <div class="text-sm">
                    <span class="text-purple-400 font-medium">{{ $activeUsersToday ?? 0 }}</span>
                    <span class="text-gray-400">active users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-white mb-4">
                    <i class="fas fa-bolt text-eni-yellow mr-2"></i>Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.deposits.pending') }}" class="inline-flex items-center px-4 py-2 border border-orange-500/40 text-sm font-medium rounded-md text-orange-400 bg-orange-500/10 hover:bg-orange-500/20 transition-colors">
                        <i class="fas fa-clock mr-2"></i>Review Deposits
                        @if(($pendingDeposits ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/40">
                                {{ $pendingDeposits }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.withdrawals.pending') }}" class="inline-flex items-center px-4 py-2 border border-red-500/40 text-sm font-medium rounded-md text-red-400 bg-red-500/10 hover:bg-red-500/20 transition-colors">
                        <i class="fas fa-arrow-up mr-2"></i>Review Withdrawals
                        @if(($pendingWithdrawals ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/40">
                                {{ $pendingWithdrawals }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.users.manage') }}" class="inline-flex items-center px-4 py-2 border border-eni-yellow/40 text-sm font-medium rounded-md text-eni-yellow bg-eni-yellow/10 hover:bg-eni-yellow/20 transition-colors">
                        <i class="fas fa-users mr-2"></i>Manage Users
                    </a>
                    
                    <a href="{{ route('admin.interest.daily') }}" class="inline-flex items-center px-4 py-2 border border-purple-500/40 text-sm font-medium rounded-md text-purple-400 bg-purple-500/10 hover:bg-purple-500/20 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>Interest Log
                    </a>
                </div>
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-white mb-4">
                    <i class="fas fa-exclamation-triangle text-eni-yellow mr-2"></i>System Alerts
                </h3>
                @if(isset($usersWithMultiplePending) && $usersWithMultiplePending->count() > 0)
                    <div class="space-y-2">
                        @foreach($usersWithMultiplePending->take(5) as $user)
                            <div class="flex items-center justify-between p-2 bg-orange-500/10 border border-orange-500/20 rounded">
                                <span class="text-sm text-gray-300">{{ $user->name }} has {{ $user->pending_count }} pending requests</span>
                                <a href="{{ route('admin.users.manage') }}" class="text-sm text-eni-yellow hover:text-eni-yellow/80">View</a>
                            </div>
                        @endforeach
                        @if($usersWithMultiplePending->count() > 5)
                            <p class="text-xs text-gray-400">{{ $usersWithMultiplePending->count() - 5 }} more users...</p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400">No system alerts at this time.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-white mb-4">
                <i class="fas fa-exchange-alt text-eni-yellow mr-2"></i>Recent Transactions
            </h3>
            <div class="flow-root">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <ul class="-mb-8">
                        @foreach($recentTransactions as $transaction)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-700" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-eni-dark border
                                                @if($transaction->type === 'deposit') border-green-500/40 bg-green-500/20
                                                @elseif($transaction->type === 'withdrawal') border-red-500/40 bg-red-500/20  
                                                @else border-blue-500/40 bg-blue-500/20
                                                @endif">
                                                <i class="fas fa-{{ $transaction->type === 'deposit' ? 'arrow-down' : ($transaction->type === 'withdrawal' ? 'arrow-up' : 'exchange-alt') }} 
                                                    @if($transaction->type === 'deposit') text-green-400
                                                    @elseif($transaction->type === 'withdrawal') text-red-400
                                                    @else text-blue-400
                                                    @endif text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-300">
                                                    {{ $transaction->user->name }} - 
                                                    <span class="font-medium text-white">{{ ucfirst($transaction->type) }}</span>
                                                    of ${{ number_format($transaction->amount, 2) }}
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border
                                                        @if($transaction->status === 'pending') bg-yellow-500/10 text-yellow-400 border-yellow-500/40
                                                        @elseif($transaction->status === 'approved') bg-green-500/10 text-green-400 border-green-500/40
                                                        @else bg-red-500/10 text-red-400 border-red-500/40
                                                        @endif">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-400">
                                                {{ $transaction->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400 text-center py-4">No recent transactions.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-eni-dark border border-eni-yellow/20 overflow-hidden shadow-lg rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-white mb-4">
                <i class="fas fa-server text-eni-yellow mr-2"></i>System Status
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="border border-green-500/40 rounded-lg p-4 bg-green-500/10">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-database text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-400">Database</p>
                            <p class="text-xs text-gray-400">Connected</p>
                        </div>
                    </div>
                </div>
                
                <div class="border border-blue-500/40 rounded-lg p-4 bg-blue-500/10">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-400">Queue System</p>
                            <p class="text-xs text-gray-400">{{ $queueJobs ?? 0 }} jobs pending</p>
                        </div>
                    </div>
                </div>
                
                <div class="border border-eni-yellow/40 rounded-lg p-4 bg-eni-yellow/10">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-eni-yellow text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-eni-yellow">Security</p>
                            <p class="text-xs text-gray-400">All systems secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
