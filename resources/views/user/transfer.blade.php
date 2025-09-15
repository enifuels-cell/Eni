<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Funds - ENI Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    
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
                <h1 class="font-extrabold text-xl tracking-tight">Transfer Funds</h1>
                <p class="text-sm text-white/70">Send funds to another user</p>
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
                <ul class="text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Account Balance Display -->
        <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <span class="text-white/80">Available Balance:</span>
                <span class="font-bold text-eni-yellow text-xl">${{ number_format($accountBalance, 2) }}</span>
            </div>
        </div>

        <!-- Transfer Form -->
        <div class="bg-white/5 rounded-2xl p-8 backdrop-blur">
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Transfer Funds</h2>
            
            <form method="POST" action="{{ route('dashboard.transfer.process') }}" id="transferForm">
                @csrf
                
                <!-- Recipient Email, Username, or Name -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Recipient Information</label>
                    <input type="text" name="recipient" required
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white placeholder-white/40 focus:ring-2 focus:ring-eni-yellow focus:border-transparent"
                           placeholder="Email, username, or full name" value="{{ old('recipient') }}">
                    <p class="text-white/60 text-sm mt-2">Enter the recipient's email address, username, or full name</p>
                </div>

                <!-- Amount Input -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Transfer Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-white/60">$</span>
                        <input type="number" name="amount" step="0.01" min="1" required
                               class="w-full bg-white/10 border border-white/20 rounded-lg pl-8 pr-4 py-4 text-white placeholder-white/40 focus:ring-2 focus:ring-eni-yellow focus:border-transparent"
                               placeholder="0.00" value="{{ old('amount') }}" max="{{ $accountBalance }}">
                    </div>
                    <p class="text-white/60 text-sm mt-2">Maximum: ${{ number_format($accountBalance, 2) }}</p>
                </div>

                <!-- Optional Investment Package -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">
                        Auto-Invest for Recipient (Optional)
                        <span class="text-white/50 text-sm font-normal ml-2">- Creates investment automatically for recipient</span>
                    </label>
                    <select name="package_id" id="packageSelect"
                            class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white focus:ring-2 focus:ring-eni-yellow focus:border-transparent">
                        <option value="" class="bg-eni-dark">No automatic investment</option>
                        @php
                            $packages = \App\Models\InvestmentPackage::active()->orderBy('min_amount')->get();
                        @endphp
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" class="bg-eni-dark" 
                                    data-min="{{ $package->min_amount }}" 
                                    data-max="{{ $package->max_amount }}">
                                {{ $package->name }} ({{ $package->duration_days }} days) - 
                                ${{ number_format($package->min_amount) }} to ${{ number_format($package->max_amount) }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-white/60 text-sm mt-2">If selected, the recipient's investment will be automatically activated without admin approval</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Description (Optional)</label>
                    <input type="text" name="description" maxlength="255"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white placeholder-white/40 focus:ring-2 focus:ring-eni-yellow focus:border-transparent"
                           placeholder="Enter transfer description" value="{{ old('description') }}">
                </div>

                <!-- Transfer Instructions -->
                <div class="mb-6 p-4 bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg">
                    <h3 class="font-semibold text-eni-yellow mb-2">Transfer Instructions</h3>
                    <div class="text-white/80 text-sm space-y-2">
                        <p>• Funds will be instantly transferred from your account balance</p>
                        <p>• The recipient will receive the funds in their account balance</p>
                        <p>• If an investment package is selected, it will be automatically activated for the recipient</p>
                        <p>• Transaction records will be created for both sender and recipient</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-eni-yellow text-eni-dark font-bold py-4 rounded-lg hover:bg-yellow-400 transition-colors text-lg">
                    Transfer Funds
                </button>
            </form>
        </div>
    </div>

    <script>
        const accountBalance = {{ $accountBalance }};
        
        // Package amount validation
        document.getElementById('packageSelect').addEventListener('change', function() {
            const amountInput = document.querySelector('input[name="amount"]');
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                const minAmount = parseFloat(selectedOption.dataset.min);
                const maxAmount = parseFloat(selectedOption.dataset.max);
                
                amountInput.min = minAmount;
                amountInput.max = Math.min(maxAmount, accountBalance);
                
                // Update placeholder text
                const placeholder1 = document.querySelector('p.text-white\\/60');
                if (placeholder1) {
                    placeholder1.textContent = `Amount must be between $${minAmount.toLocaleString()} and $${Math.min(maxAmount, accountBalance).toLocaleString()} for selected package`;
                }
            } else {
                amountInput.min = 1;
                amountInput.max = accountBalance;
                
                // Reset placeholder text
                const placeholder2 = document.querySelector('p.text-white\\/60');
                if (placeholder2) {
                    placeholder2.textContent = 'Maximum: $' + accountBalance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            }
        });

        // Form validation
        document.getElementById('transferForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.querySelector('input[name="amount"]').value);
            
            if (amount > accountBalance) {
                e.preventDefault();
                alert('Transfer amount cannot exceed your account balance.');
                return false;
            }
            
            const packageSelect = document.getElementById('packageSelect');
            const selectedOption = packageSelect.options[packageSelect.selectedIndex];
            
            if (selectedOption.value) {
                const minAmount = parseFloat(selectedOption.dataset.min);
                const maxAmount = parseFloat(selectedOption.dataset.max);
                
                if (amount < minAmount || amount > maxAmount) {
                    e.preventDefault();
                    alert(`Amount must be between $${minAmount.toLocaleString()} and $${maxAmount.toLocaleString()} for the selected investment package.`);
                    return false;
                }
            }
        });
    </script>

    <!-- Transfer Receipt Modal -->
    @if(session('receipt'))
    <div id="receiptModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full mx-auto shadow-2xl">
            <div class="p-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Transfer Successful</h2>
                    <p class="text-gray-600">Your funds have been transferred successfully</p>
                </div>

                <!-- Receipt Details -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-semibold text-gray-900">{{ session('receipt.transaction_id') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date & Time:</span>
                        <span class="font-semibold text-gray-900">{{ session('receipt.date') }} {{ session('receipt.time') }}</span>
                    </div>
                    
                    <hr class="border-gray-200">
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">From:</span>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ session('receipt.sender_name') }}</div>
                            <div class="text-sm text-gray-500">ID: {{ session('receipt.sender_account_id') }}</div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">To:</span>
                        <div class="text-right">
                            <div class="font-semibold text-gray-900">{{ session('receipt.recipient_name') }}</div>
                            <div class="text-sm text-gray-500">ID: {{ session('receipt.recipient_account_id') }}</div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-200">
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Amount:</span>
                        <span class="text-2xl font-bold text-green-600">${{ number_format(session('receipt.amount'), 2) }}</span>
                    </div>
                    
                    @if(session('receipt.description'))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Description:</span>
                        <span class="font-semibold text-gray-900">{{ session('receipt.description') }}</span>
                    </div>
                    @endif
                    
                    @if(session('receipt.package_investment'))
                    <div class="bg-blue-50 border border-blue-200 rounded p-3 mt-3">
                        <div class="flex items-center text-blue-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-sm font-medium">Investment Package: {{ session('receipt.package_name') }}</span>
                        </div>
                        <p class="text-xs text-blue-600 mt-1">Investment has been automatically activated for the recipient</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button onclick="printReceipt()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>
                    <button onclick="closeReceiptModal()" class="flex-1 bg-eni-yellow hover:bg-yellow-400 text-eni-dark px-4 py-2 rounded-lg transition-colors font-semibold">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-show receipt modal on page load
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('receiptModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        });

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function printReceipt() {
            // Get receipt data from DOM
            const modal = document.getElementById('receiptModal');
            const receiptSection = modal.querySelector('.bg-gray-50');
            
            // Create printable content
            const receiptContent = `
                <html>
                <head>
                    <title>Transfer Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
                        h2 { text-align: center; margin-bottom: 20px; }
                        .receipt-box { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
                        hr { margin: 10px 0; border: none; border-top: 1px solid #ccc; }
                        .footer { text-align: center; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <h2>ENI Platform - Transfer Receipt</h2>
                    <div class="receipt-box">
                        ${receiptSection.innerHTML.replace(/class="[^"]*"/g, '')}
                    </div>
                    <p class="footer">Thank you for using ENI Platform</p>
                </body>
                </html>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(receiptContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('receiptModal');
            if (modal && e.target === modal) {
                closeReceiptModal();
            }
        });
    </script>
    @endif
</body>
</html>
