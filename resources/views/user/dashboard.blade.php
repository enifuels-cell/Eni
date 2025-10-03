<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-800/20 border border-green-400 text-green-400 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-800/20 border border-red-400 text-red-400 px-4 py-3 rounded-lg" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="text-sm font-medium text-gray-400">Total Invested</div>
                        <div class="text-2xl font-bold text-eni-yellow">$@money($stats['total_invested'])</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="text-sm font-medium text-gray-400">Total Interest</div>
                        <div class="text-2xl font-bold text-green-400">$@money($stats['total_interest'])</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="text-sm font-medium text-gray-400">Referral Bonus</div>
                        <div class="text-2xl font-bold text-blue-400">$@money($stats['total_referral_bonus'])</div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-white">
                        <div class="text-sm font-medium text-gray-400">Account Balance</div>
                        <div class="text-2xl font-bold text-white">$@money($stats['account_balance'])</div>
                        <div class="text-xs text-gray-500 mt-1">Account ID: {{ Auth::user()->account_id }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('investments.index') }}" class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">New Investment</h3>
                    <p class="text-eni-dark/80">Browse investment packages and start earning daily interest</p>
                </a>

                <a href="{{ route('user.deposit') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Deposit Funds</h3>
                    <p class="text-green-100">Add money to your account balance</p>
                </a>

                @if(!Auth::user()->pin_hash)
                <a href="{{ route('pin.setup.form') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow border-2 border-purple-400">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-shield-alt text-purple-200 mr-2"></i>
                        <h3 class="text-lg font-semibold">Set Up PIN</h3>
                    </div>
                    <p class="text-purple-100">Enable 4-digit PIN for quick login</p>
                </a>
                @else
                <a href="{{ route('pin.setup.form') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-check-shield text-green-200 mr-2"></i>
                        <h3 class="text-lg font-semibold">PIN Enabled</h3>
                    </div>
                    <p class="text-green-100">Manage your PIN settings</p>
                </a>
                @endif

                <a href="{{ route('dashboard.franchise') }}" class="bg-gradient-to-r from-gray-800 to-eni-dark border border-eni-yellow/20 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-gas-pump text-eni-yellow mr-2"></i>
                        <h3 class="text-lg font-semibold">Fuel Station Franchise</h3>
                    </div>
                    <p class="text-gray-300">Open your own ENI fuel station business</p>
                </a>
            </div>

            <!-- Additional Quick Action -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('user.referrals') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">Refer & Earn</h3>
                    <p class="text-blue-100">Share your referral link and earn bonuses</p>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-user-cog text-gray-300 mr-2"></i>
                        <h3 class="text-lg font-semibold">Account Settings</h3>
                    </div>
                    <p class="text-gray-300">Manage your profile and security settings</p>
                </a>
            </div>

            <!-- Active Investments -->
            <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-eni-yellow mb-4">Active Investments</h3>

                    @if($activeInvestments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-eni-yellow/20">
                                <thead class="bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Daily Rate</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Days Left</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-800 divide-y divide-eni-yellow/20">
                                    @foreach($activeInvestments as $investment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $investment->investmentPackage->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">$@money($investment->amount)</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $investment->daily_shares_rate }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $investment->remaining_days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400">$@money($investment->total_interest_earned)</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-400">No active investments yet. <a href="{{ route('investments.index') }}" class="text-eni-yellow hover:text-yellow-300">Start investing now</a></p>
                    @endif
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-eni-yellow mb-4">Recent Transactions</h3>

                    @if($recentTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentTransactions as $transaction)
                            <div class="flex justify-between items-center p-3 bg-gray-700 rounded-lg">
                                <div>
                                    <div class="font-medium text-white">{{ ucfirst($transaction->type) }}</div>
                                    <div class="text-sm text-gray-400">{{ $transaction->created_at->format('M d, Y - H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium {{ $transaction->amount > 0 ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}$@money($transaction->amount)
                                    </div>
                                    <div class="text-sm text-gray-400">{{ ucfirst($transaction->status) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('user.transactions') }}" class="text-eni-yellow hover:text-yellow-300 text-sm">View all transactions →</a>
                        </div>
                    @else
                        <p class="text-gray-400">No transactions yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
