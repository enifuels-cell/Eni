@extends('layouts.app')

@section('title', 'Investment Packages - ENI Platform')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Debug Info -->
            @if(config('app.debug'))
                <div class="bg-eni-yellow/10 border border-eni-yellow/20 text-eni-yellow px-4 py-3 rounded mb-6">
                    <strong>Debug Info:</strong>
                    Packages count: {{ $packages->count() }} |
                    User investments: {{ $userInvestments->count() }}
                    @if($packages->count() == 0)
                        <br><strong>No packages available!</strong> Check if packages are active and have available slots.
                    @endif
                </div>
            @endif

            <!-- Investment Packages -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($packages as $package)
                <div class="bg-eni-dark/50 overflow-hidden shadow-lg rounded-lg border border-eni-yellow/20">
                    <div class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark p-6">
                        <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                        <p class="text-eni-dark/80 mt-2">Daily Rate: {{ $package->daily_shares_rate }}%</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-300">Min Amount:</span>
                                <span class="font-semibold text-white">${{ number_format($package->min_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Max Amount:</span>
                                <span class="font-semibold text-white">${{ number_format($package->max_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Duration:</span>
                                <span class="font-semibold text-white">{{ $package->effective_days }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Referral Bonus:</span>
                                <span class="font-semibold text-green-400">{{ $package->referral_bonus_rate }}%</span>
                            </div>
                            @if($package->available_slots)
                            <div class="flex justify-between">
                                <span class="text-gray-300">Available Slots:</span>
                                <span class="font-semibold text-orange-400">{{ $package->available_slots }}</span>
                            </div>
                            @endif
                        </div>

                        <form action="{{ route('investments.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="investment_package_id" value="{{ $package->id }}">

                            <div>
                                <label for="amount_{{ $package->id }}" class="block text-sm font-medium text-gray-300">Investment Amount</label>
                                <input type="number" step="0.01" min="{{ $package->min_amount }}" max="{{ $package->max_amount }}"
                                       name="amount" id="amount_{{ $package->id }}"
                                       class="mt-1 block w-full rounded-md border-eni-yellow/30 bg-eni-dark/50 text-white shadow-sm focus:border-eni-yellow focus:ring-eni-yellow"
                                       placeholder="${{ number_format($package->min_amount) }}" required>
                            </div>

                            <div>
                                <label for="referral_code_{{ $package->id }}" class="block text-sm font-medium text-gray-300">Referral Code (Optional)</label>
                                <input type="text" name="referral_code" id="referral_code_{{ $package->id }}"
                                       class="mt-1 block w-full rounded-md border-eni-yellow/30 bg-eni-dark/50 text-white shadow-sm focus:border-eni-yellow focus:ring-eni-yellow"
                                       placeholder="Enter referral code">
                            </div>

                            <button type="submit"
                                    class="w-full bg-eni-yellow text-eni-dark py-2 px-4 rounded-lg hover:bg-yellow-400 focus:ring-4 focus:ring-eni-yellow/50 transition-colors font-semibold">
                                Invest Now
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-eni-dark/30 rounded-lg p-8 border border-eni-yellow/20">
                        <h3 class="text-xl font-semibold text-white mb-4">No Investment Packages Available</h3>
                        <p class="text-gray-300 mb-4">There are currently no investment packages available for investment.</p>
                        <p class="text-sm text-gray-400">Please contact support if you believe this is an error.</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- My Investments -->
            <div class="bg-eni-dark/50 overflow-hidden shadow-lg rounded-lg border border-eni-yellow/20">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">My Investments</h3>

                    @if($userInvestments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-eni-yellow/20">
                                <thead class="bg-eni-yellow/10">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Days Left</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Total Earned</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Started</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($userInvestments as $investment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $investment->investmentPackage->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">${{ number_format($investment->amount->toFloat(), 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $investment->active ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30' }}">
                                                {{ $investment->active ? 'Active' : 'Completed' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $investment->remaining_days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400">${{ number_format($investment->total_interest_earned, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $investment->started_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('investments.show', $investment) }}" class="text-eni-yellow hover:text-yellow-400">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-300">You haven't made any investments yet. Choose a package above to get started!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
