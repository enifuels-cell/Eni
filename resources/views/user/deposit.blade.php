<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit - ENI Platform</title>
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
                <h1 class="font-extrabold text-xl tracking-tight">Make a Deposit</h1>
                <p class="text-sm text-white/70">Add funds to your account</p>
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

        <!-- Deposit Form -->
        <div class="bg-white/5 rounded-2xl p-8 backdrop-blur">
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Deposit Funds</h2>
            
            <form method="POST" action="{{ route('dashboard.deposit') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Amount Input -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Deposit Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-white/60">$</span>
                        <input type="number" name="amount" step="0.01" min="10" required
                               class="w-full bg-white/10 border border-white/20 rounded-lg pl-8 pr-4 py-4 text-white placeholder-white/40 focus:ring-2 focus:ring-eni-yellow focus:border-transparent"
                               placeholder="0.00" value="{{ old('amount') }}">
                    </div>
                    <p class="text-white/60 text-sm mt-2">Minimum deposit: $10.00</p>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Payment Method</label>
                    <select name="payment_method" required
                            class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white focus:ring-2 focus:ring-eni-yellow focus:border-transparent">
                        <option value="" class="bg-eni-dark">Select payment method</option>
                        <option value="bank_transfer" class="bg-eni-dark">Bank Transfer</option>
                        <option value="credit_card" class="bg-eni-dark">Credit Card</option>
                        <option value="paypal" class="bg-eni-dark">PayPal</option>
                        <option value="cryptocurrency" class="bg-eni-dark">Cryptocurrency</option>
                    </select>
                </div>

                <!-- Payment Instructions -->
                <div class="mb-6 p-4 bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg">
                    <h3 class="font-semibold text-eni-yellow mb-2">Payment Instructions</h3>
                    <div class="text-white/80 text-sm space-y-2">
                        <p><strong>Bank Transfer:</strong> Use account details provided after submission</p>
                        <p><strong>Credit Card:</strong> Secure payment link will be sent to your email</p>
                        <p><strong>PayPal:</strong> Payment request will be sent to your registered email</p>
                        <p><strong>Cryptocurrency:</strong> Wallet address will be provided</p>
                    </div>
                </div>

                <!-- Receipt Upload -->
                <div class="mb-8">
                    <label class="block text-white/80 font-semibold mb-3">Payment Receipt (Optional)</label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" 
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-eni-yellow file:text-eni-dark file:font-semibold hover:file:bg-yellow-400">
                    <p class="text-white/60 text-sm mt-2">Upload proof of payment (JPG, PNG, PDF - Max 2MB)</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-eni-yellow text-eni-dark font-bold py-4 rounded-lg hover:bg-yellow-400 transition-colors text-lg">
                    Submit Deposit Request
                </button>
            </form>

            <!-- Important Notice -->
            <div class="mt-8 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <h3 class="font-semibold text-blue-400 mb-2">Important Notice</h3>
                <ul class="text-white/70 text-sm space-y-1">
                    <li>• All deposits are reviewed and processed within 24 hours</li>
                    <li>• You will receive an email confirmation once processed</li>
                    <li>• Funds will be available for investment immediately after approval</li>
                    <li>• Contact support if you have any questions</li>
                </ul>
            </div>
        </div>

        <!-- Recent Deposits -->
        <div class="mt-8 bg-white/5 rounded-2xl p-8 backdrop-blur">
            <h3 class="text-xl font-bold text-eni-yellow mb-4">Recent Deposits</h3>
            
            @php
                $recentDeposits = Auth::user()->transactions()
                    ->where('type', 'deposit')
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp

            <div class="space-y-3">
                @forelse($recentDeposits as $deposit)
                    <div class="flex justify-between items-center p-4 bg-white/5 rounded-lg">
                        <div>
                            <div class="font-medium text-white">${{ number_format($deposit->amount, 2) }}</div>
                            <div class="text-sm text-white/60">{{ $deposit->created_at->format('M d, Y - H:i') }}</div>
                            <div class="text-sm text-white/60">{{ $deposit->reference }}</div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $deposit->status == 'completed' ? 'bg-green-500/20 text-green-400' : 
                                   ($deposit->status == 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-white/60">No deposit requests yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
