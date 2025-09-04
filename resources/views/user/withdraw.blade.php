<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw - ENI Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-yellow': '#FFCD00',
                        'eni-dark': '#0B2241',
                        'eni-charcoal': '#121417'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui; }
    </style>
</head>
<body class="bg-eni-charcoal text-white min-h-screen">
    <!-- Header -->
    <header class="bg-eni-dark px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
            <div>
                <h1 class="font-extrabold text-xl tracking-tight">Withdraw Funds</h1>
                <p class="text-sm text-white/70">Request withdrawal from your account</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-white transition-colors" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8 max-w-2xl">
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6">
                <p class="text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6">
                <ul class="text-red-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Available Balance -->
        <div class="bg-gradient-to-r from-eni-yellow to-yellow-400 text-eni-dark rounded-2xl p-6 mb-8">
            <div class="text-center">
                <h2 class="text-lg font-semibold mb-2">Available Balance</h2>
                <p class="text-3xl font-bold">${{ number_format($availableBalance ?? 0, 2) }}</p>
                <p class="text-sm opacity-80 mt-1">Ready for withdrawal</p>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-8 border border-white/10">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-eni-yellow rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-eni-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-eni-yellow mb-2">Request Withdrawal</h2>
                <p class="text-white/70">Withdraw your earnings to your bank account</p>
            </div>

            <form method="POST" action="{{ route('user.withdraw.process') }}">
                @csrf
                
                <!-- Amount -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Withdrawal Amount ($)</label>
                    <input type="number" name="amount" step="0.01" min="10" max="{{ $availableBalance ?? 0 }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white text-lg focus:outline-none focus:border-eni-yellow" 
                           placeholder="Enter amount to withdraw" value="{{ old('amount') }}" required>
                    <p class="text-white/60 text-sm mt-2">
                        Minimum: $10.00 | Maximum: ${{ number_format($availableBalance ?? 0, 2) }}
                    </p>
                </div>

                <!-- Bank Details -->
                @if(!auth()->user()->bank_name || !auth()->user()->account_number || !auth()->user()->account_holder_name)
                    <div class="mb-8 p-6 bg-red-500/10 border border-red-500/30 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="font-semibold text-red-400">Bank Details Required</h3>
                        </div>
                        <p class="text-white/80 mb-4">
                            You need to add your bank details to your profile before you can make withdrawals.
                        </p>
                        <a href="{{ route('profile.edit') }}#bank" 
                           class="inline-flex items-center bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Update Bank Details
                        </a>
                    </div>
                @else
                    <div class="mb-8">
                        <label class="block text-white/80 font-semibold mb-3">Withdrawal Destination</label>
                        <div class="bg-white/5 border border-white/20 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-semibold text-eni-yellow">Your Bank Account</h4>
                                <a href="{{ route('profile.edit') }}#bank" 
                                   class="text-sm text-eni-yellow hover:text-yellow-300 transition-colors">
                                    Edit Details
                                </a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-white/60">Bank Name:</span>
                                    <p class="text-white font-medium">{{ auth()->user()->bank_name }}</p>
                                </div>
                                <div>
                                    <span class="text-white/60">Account Holder:</span>
                                    <p class="text-white font-medium">{{ auth()->user()->account_holder_name }}</p>
                                </div>
                                <div>
                                    <span class="text-white/60">Account Number:</span>
                                    <p class="text-white font-medium">**** **** {{ substr(auth()->user()->account_number, -4) }}</p>
                                </div>
                                @if(auth()->user()->routing_number)
                                <div>
                                    <span class="text-white/60">Routing Number:</span>
                                    <p class="text-white font-medium">{{ auth()->user()->routing_number }}</p>
                                </div>
                                @endif
                                @if(auth()->user()->swift_code)
                                <div>
                                    <span class="text-white/60">SWIFT Code:</span>
                                    <p class="text-white font-medium">{{ auth()->user()->swift_code }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                @if(!auth()->user()->bank_name || !auth()->user()->account_number || !auth()->user()->account_holder_name)
                    <button type="button" disabled
                            class="w-full bg-gray-600 text-gray-400 font-bold py-4 rounded-lg cursor-not-allowed text-lg">
                        Complete Bank Details to Enable Withdrawals
                    </button>
                @else
                    <button type="submit" 
                            class="w-full bg-eni-yellow text-eni-dark font-bold py-4 rounded-lg hover:bg-yellow-400 transition-colors text-lg">
                        Submit Withdrawal Request
                    </button>
                @endif
            </form>

            <!-- Important Notice -->
            <div class="mt-8 p-4 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                <h3 class="font-semibold text-orange-400 mb-2">Processing Information</h3>
                <ul class="text-white/70 text-sm space-y-1">
                    <li>• Withdrawal requests are processed within 1-3 business days</li>
                    <li>• A processing fee of 2% may apply to withdrawals</li>
                    <li>• Ensure bank details are accurate to avoid delays</li>
                    <li>• You will receive email confirmation once processed</li>
                    <li>• Minimum withdrawal amount is $10.00</li>
                </ul>
            </div>
        </div>

        <!-- Recent Withdrawals -->
                <div class="mt-12">
                    <h3 class="text-xl font-bold mb-6 text-eni-yellow">Recent Withdrawals</h3>
                    <div class="bg-black/20 border border-white/10 rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead class="bg-black/40">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-eni-yellow uppercase tracking-wider">Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @forelse(auth()->user()->transactions()->where('type', 'withdrawal')->orderBy('created_at', 'desc')->take(5)->get() as $withdrawal)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-400">
                                        -${{ number_format($withdrawal->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                        {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($withdrawal->status === 'completed') bg-green-500/20 text-green-400
                                            @elseif($withdrawal->status === 'pending') bg-yellow-500/20 text-yellow-400
                                            @elseif($withdrawal->status === 'processing') bg-blue-500/20 text-blue-400
                                            @else bg-red-500/20 text-red-400
                                            @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white/60">
                                        {{ $withdrawal->transaction_id }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-white/60">
                                        No withdrawal history found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentDetails = document.getElementById('payment_details');
    const bankDetails = document.getElementById('bank_details');
    const walletDetails = document.getElementById('wallet_details');
    if (!paymentMethodSelect || !paymentDetails || !bankDetails || !walletDetails) return;
    paymentMethodSelect.addEventListener('change', function() {
        const method = this.value;
        if (method) {
            paymentDetails.classList.remove('hidden');
            bankDetails.classList.add('hidden');
            walletDetails.classList.add('hidden');
            if (method === 'bank_transfer') {
                bankDetails.classList.remove('hidden');
            } else if (['paypal', 'bitcoin', 'ethereum', 'usdt'].includes(method)) {
                walletDetails.classList.remove('hidden');
            }
        } else {
            paymentDetails.classList.add('hidden');
        }
    });
});
</script>
</body>
</html>
