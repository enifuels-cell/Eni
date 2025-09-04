@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Real-time platform overview and management</p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalUsers ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-green-600 font-medium">+{{ $newSignupsToday ?? 0 }}</span>
                    <span class="text-gray-500">new today</span>
                </div>
            </div>
        </div>

        <!-- Total Funds -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Funds</dt>
                            <dd class="text-lg font-medium text-gray-900">${{ number_format($totalFunds ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-blue-600 font-medium">${{ number_format($totalRevenue ?? 0, 2) }}</span>
                    <span class="text-gray-500">revenue</span>
                </div>
            </div>
        </div>

        <!-- Pending Reviews -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Reviews</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ ($pendingDeposits ?? 0) + ($pendingWithdrawals ?? 0) + ($pendingRequestFunds ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-orange-600 font-medium">{{ $pendingDeposits ?? 0 }}</span>
                    <span class="text-gray-500">deposits</span>
                    <span class="mx-1">•</span>
                    <span class="text-red-600 font-medium">{{ $pendingWithdrawals ?? 0 }}</span>
                    <span class="text-gray-500">withdrawals</span>
                </div>
            </div>
        </div>

        <!-- Daily Interest -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Interest Today</dt>
                            <dd class="text-lg font-medium text-gray-900">${{ number_format($dailyInterestToday ?? 0, 2) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="text-purple-600 font-medium">{{ $activeUsersToday ?? 0 }}</span>
                    <span class="text-gray-500">active users</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-bolt text-admin-accent mr-2"></i>Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.deposits.pending') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                        <i class="fas fa-clock mr-2"></i>Review Deposits
                        @if(($pendingDeposits ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $pendingDeposits }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.withdrawals.pending') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-arrow-up mr-2"></i>Review Withdrawals
                        @if(($pendingWithdrawals ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ $pendingWithdrawals }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.users.manage') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-users mr-2"></i>Manage Users
                    </a>
                    
                    <a href="{{ route('admin.interest.daily') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <i class="fas fa-chart-line mr-2"></i>Interest Log
                    </a>
                </div>
            </div>
        </div>

        <!-- System Alerts -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>System Alerts
                </h3>
                @if(isset($usersWithMultiplePending) && $usersWithMultiplePending->count() > 0)
                    <div class="space-y-2">
                        @foreach($usersWithMultiplePending->take(5) as $user)
                            <div class="flex items-center justify-between p-2 bg-yellow-50 rounded">
                                <span class="text-sm">{{ $user->name }} has {{ $user->pending_count }} pending requests</span>
                                <a href="{{ route('admin.users.manage') }}" class="text-sm text-blue-600 hover:text-blue-800">View</a>
                            </div>
                        @endforeach
                        @if($usersWithMultiplePending->count() > 5)
                            <p class="text-xs text-gray-500">{{ $usersWithMultiplePending->count() - 5 }} more users...</p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">No system alerts at this time.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-history text-gray-500 mr-2"></i>Recent Activity
            </h3>
            <div class="flow-root">
                @if(isset($recentTransactions) && $recentTransactions->count() > 0)
                    <ul class="-mb-8">
                        @foreach($recentTransactions as $transaction)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                @if($transaction->type === 'deposit') bg-green-500
                                                @elseif($transaction->type === 'withdrawal') bg-red-500  
                                                @else bg-blue-500
                                                @endif">
                                                <i class="fas fa-{{ $transaction->type === 'deposit' ? 'arrow-down' : ($transaction->type === 'withdrawal' ? 'arrow-up' : 'exchange-alt') }} text-white text-xs"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    {{ $transaction->user->name }} - 
                                                    <span class="font-medium text-gray-900">{{ ucfirst($transaction->type) }}</span>
                                                    of ${{ number_format($transaction->amount, 2) }}
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($transaction->status === 'approved') bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $transaction->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No recent activity.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
