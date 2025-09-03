<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_users']) }}</div>
                        <div class="text-sm font-medium text-gray-500">Total Users</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-green-600">${{ number_format($stats['total_investments'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-500">Total Investments</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['active_investments']) }}</div>
                        <div class="text-sm font-medium text-gray-500">Active Investments</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-yellow-600">${{ number_format($stats['total_interest_paid'], 2) }}</div>
                        <div class="text-sm font-medium text-gray-500">Interest Paid</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-red-600">{{ number_format($stats['pending_transactions']) }}</div>
                        <div class="text-sm font-medium text-gray-500">Pending Transactions</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ number_format($stats['pending_franchises']) }}</div>
                        <div class="text-sm font-medium text-gray-500">Pending Franchises</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('admin.transactions') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Manage Transactions</h3>
                    <p class="text-blue-100">Review and approve pending deposits & withdrawals</p>
                </a>
                
                <a href="{{ route('admin.investments') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">View Investments</h3>
                    <p class="text-green-100">Monitor all user investments and performance</p>
                </a>
                
                <a href="{{ route('admin.franchise.index') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Franchise Applications</h3>
                    <p class="text-purple-100">Review and process franchise requests</p>
                </a>
                
                <a href="{{ route('admin.analytics') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Analytics</h3>
                    <p class="text-orange-100">View detailed reports and statistics</p>
                </a>
            </div>

            <!-- Recent Investments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Investments</h3>
                        <a href="{{ route('admin.investments') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    @if($recentInvestments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentInvestments as $investment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $investment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $investment->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $investment->investmentPackage->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">${{ number_format($investment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $investment->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $investment->active ? 'Active' : 'Completed' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $investment->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No investments yet.</p>
                    @endif
                </div>
            </div>

            <!-- Pending Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Transactions</h3>
                        <a href="{{ route('admin.transactions') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
                    </div>
                    
                    @if($pendingTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($pendingTransactions as $transaction)
                            <div class="flex justify-between items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                                            <div class="text-sm text-gray-600 mt-1">{{ ucfirst($transaction->type) }} - ${{ number_format($transaction->amount, 2) }}</div>
                                            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y - H:i') }}</div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                    Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No pending transactions.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
