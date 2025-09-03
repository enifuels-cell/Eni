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
            <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-white text-sm">← Back to Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-white/70 hover:text-white text-sm">Logout</button>
            </form>
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
                <div class="mb-8">
                    <label class="block text-white/80 font-semibold mb-3">Bank Account Details</label>
                    <textarea name="bank_details" rows="4" 
                              class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white focus:outline-none focus:border-eni-yellow" 
                              placeholder="Enter your complete bank details:&#10;Bank Name:&#10;Account Number:&#10;Account Holder Name:&#10;Routing Number/SWIFT Code:" required>{{ old('bank_details') }}</textarea>
                    <p class="text-white/60 text-sm mt-2">Please provide complete and accurate bank details for processing</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-eni-yellow text-eni-dark font-bold py-4 rounded-lg hover:bg-yellow-400 transition-colors text-lg">
                    Submit Withdrawal Request
                </button>
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
    </div>
</body>
</html>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Available to Withdraw</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format(auth()->user()->balance - auth()->user()->investments()->where('status', 'active')->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 dark:bg-orange-900/20 p-4 rounded-lg border border-orange-200 dark:border-orange-700">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-orange-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Locked in Investments</p>
                                <p class="text-2xl font-bold text-orange-600">${{ number_format(auth()->user()->investments()->where('status', 'active')->sum('amount'), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Form -->
                <form action="{{ route('user.withdraw.process') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Withdrawal Amount ($)
                            </label>
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="10" 
                                   max="{{ auth()->user()->balance - auth()->user()->investments()->where('status', 'active')->sum('amount') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Enter amount to withdraw"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Minimum withdrawal: $10.00</p>
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Method
                            </label>
                            <select name="payment_method" 
                                    id="payment_method" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Select payment method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="paypal">PayPal</option>
                                <option value="bitcoin">Bitcoin</option>
                                <option value="ethereum">Ethereum</option>
                                <option value="usdt">USDT (Tether)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div id="payment_details" class="hidden">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border">
                            <h4 class="text-lg font-medium mb-4">Payment Details</h4>
                            
                            <!-- Bank Transfer Details -->
                            <div id="bank_details" class="hidden space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Bank Name
                                        </label>
                                        <input type="text" 
                                               name="bank_name" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                               placeholder="Enter bank name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Account Number
                                        </label>
                                        <input type="text" 
                                               name="account_number" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                               placeholder="Enter account number">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Account Holder Name
                                        </label>
                                        <input type="text" 
                                               name="account_holder" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                               placeholder="Enter account holder name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Swift Code (Optional)
                                        </label>
                                        <input type="text" 
                                               name="swift_code" 
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                               placeholder="Enter swift code">
                                    </div>
                                </div>
                            </div>

                            <!-- Wallet Address Details -->
                            <div id="wallet_details" class="hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Wallet Address
                                    </label>
                                    <input type="text" 
                                           name="wallet_address" 
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                           placeholder="Enter wallet address">
                                    <p class="mt-1 text-sm text-gray-500">Please ensure the wallet address is correct. Transactions cannot be reversed.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Verification -->
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-700">
                        <h4 class="text-lg font-medium mb-2 text-yellow-800 dark:text-yellow-200">Security Verification</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Transaction PIN
                                </label>
                                <input type="password" 
                                       name="transaction_pin" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="Enter your 4-digit PIN"
                                       maxlength="4"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    2FA Code (if enabled)
                                </label>
                                <input type="text" 
                                       name="two_factor_code" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                                       placeholder="Enter 6-digit code">
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Submit -->
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   name="terms" 
                                   id="terms" 
                                   class="mt-1 mr-2 rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500"
                                   required>
                            <label for="terms" class="text-sm text-gray-600 dark:text-gray-400">
                                I understand that withdrawal requests are processed within 24-48 hours and may be subject to verification procedures. I confirm that all information provided is accurate.
                            </label>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" 
                                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                                Submit Withdrawal Request
                            </button>
                            <a href="{{ route('dashboard') }}" 
                               class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md text-center hover:bg-gray-400 transition duration-200">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Recent Withdrawals -->
                <div class="mt-12">
                    <h3 class="text-xl font-bold mb-4">Recent Withdrawals</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse(auth()->user()->transactions()->where('type', 'withdrawal')->orderBy('created_at', 'desc')->take(5)->get() as $withdrawal)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        -${{ number_format($withdrawal->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($withdrawal->status === 'completed') bg-green-100 text-green-800
                                            @elseif($withdrawal->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($withdrawal->status === 'processing') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $withdrawal->transaction_id }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
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

    paymentMethodSelect.addEventListener('change', function() {
        const method = this.value;
        
        if (method) {
            paymentDetails.classList.remove('hidden');
            
            // Hide all detail sections first
            bankDetails.classList.add('hidden');
            walletDetails.classList.add('hidden');
            
            // Show relevant section
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
