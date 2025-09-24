<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Investment Packages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Debug Info -->
            @if(config('app.debug'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
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
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                        <h3 class="text-xl font-bold">{{ $package->name }}</h3>
                        <p class="text-blue-100 mt-2">Daily Rate: {{ $package->daily_shares_rate }}%</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Min Amount:</span>
                                <span class="font-semibold">${{ number_format($package->min_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Max Amount:</span>
                                <span class="font-semibold">${{ number_format($package->max_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-semibold">{{ $package->effective_days }} days</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Referral Bonus:</span>
                                <span class="font-semibold text-green-600">{{ $package->referral_bonus_rate }}%</span>
                            </div>
                            @if($package->available_slots)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Available Slots:</span>
                                <span class="font-semibold text-orange-600">{{ $package->available_slots }}</span>
                            </div>
                            @endif
                        </div>

                        <form action="{{ route('investments.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="investment_package_id" value="{{ $package->id }}">

                            <div>
                                <label for="amount_{{ $package->id }}" class="block text-sm font-medium text-gray-700">Investment Amount</label>
                                <input type="number" step="0.01" min="{{ $package->min_amount }}" max="{{ $package->max_amount }}"
                                       name="amount" id="amount_{{ $package->id }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="${{ number_format($package->min_amount) }}" required>
                            </div>

                            <div>
                                <label for="referral_code_{{ $package->id }}" class="block text-sm font-medium text-gray-700">Referral Code (Optional)</label>
                                <input type="text" name="referral_code" id="referral_code_{{ $package->id }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Enter referral code">
                            </div>

                            <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-colors">
                                Invest Now
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="bg-gray-100 rounded-lg p-8">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">No Investment Packages Available</h3>
                        <p class="text-gray-600 mb-4">There are currently no investment packages available for investment.</p>
                        <p class="text-sm text-gray-500">Please contact support if you believe this is an error.</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- My Investments -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">My Investments</h3>

                    @if($userInvestments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Left</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Earned</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($userInvestments as $investment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $investment->investmentPackage->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($investment->amount->toFloat(), 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $investment->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $investment->active ? 'Active' : 'Completed' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $investment->remaining_days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">${{ number_format($investment->total_interest_earned, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $investment->started_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('investments.show', $investment) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">You haven't made any investments yet. Choose a package above to get started!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
