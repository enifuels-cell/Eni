<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Total Invested</div>
                        <div class="text-2xl font-bold text-green-600">${{ number_format($stats['total_invested'], 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Total Interest</div>
                        <div class="text-2xl font-bold text-blue-600">${{ number_format($stats['total_interest'], 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Referral Bonus</div>
                        <div class="text-2xl font-bold text-purple-600">${{ number_format($stats['total_referral_bonus'], 2) }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Account Balance</div>
                        <div class="text-2xl font-bold text-gray-800">${{ number_format($stats['account_balance'], 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('investments.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">New Investment</h3>
                    <p class="text-blue-100">Browse investment packages and start earning daily interest</p>
                </a>
                
                <a href="{{ route('user.deposit') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Deposit Funds</h3>
                    <p class="text-green-100">Add money to your account balance</p>
                </a>
                
                <a href="{{ route('user.referrals') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Refer & Earn</h3>
                    <p class="text-purple-100">Share your referral link and earn bonuses</p>
                </a>
            </div>

            <!-- Active Investments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Active Investments</h3>
                    
                    @if($activeInvestments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daily Rate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Left</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($activeInvestments as $investment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $investment->investmentPackage->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($investment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $investment->daily_shares_rate }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $investment->remaining_days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">${{ number_format($investment->total_interest_earned, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No active investments yet. <a href="{{ route('investments.index') }}" class="text-blue-600 hover:text-blue-800">Start investing now</a></p>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
                    
                    @if($recentTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTransactions as $transaction)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-gray-900">{{ ucfirst($transaction->type) }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y - H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($transaction->status) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.transactions') }}" class="text-blue-600 hover:text-blue-800 text-sm">View all transactions →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No transactions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
