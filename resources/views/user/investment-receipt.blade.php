<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Receipt - ENI Platform</title>
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
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-eni-charcoal text-white min-h-screen">
    <!-- Header -->
    <header class="bg-eni-dark px-6 py-4 flex items-center justify-between shadow-md no-print">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
            <div>
                <h1 class="font-extrabold text-xl tracking-tight">Investment Receipt</h1>
                <p class="text-sm text-white/70">Transaction confirmation</p>
            </div>
        </div>
        <a href="{{ route('user.dashboard') }}" class="text-eni-yellow hover:text-eni-yellow/80 transition-colors">
            ← Back to Dashboard
        </a>
    </header>

    <div class="container mx-auto px-6 py-12">
        <!-- Receipt Card -->
        <div class="max-w-2xl mx-auto bg-white text-eni-dark rounded-2xl shadow-2xl overflow-hidden">
            <!-- Receipt Header -->
            <div class="bg-gradient-to-r from-eni-yellow to-eni-yellow/80 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-12 w-auto mb-2" />
                        <h2 class="text-2xl font-bold text-eni-dark">Investment Receipt</h2>
                    </div>
                    <div class="text-right">
                        <div class="bg-eni-dark text-eni-yellow px-4 py-2 rounded-lg">
                            <span class="text-sm font-semibold">Receipt #</span>
                            <div class="text-lg font-bold">{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="px-8 py-8">
                <!-- Status Badge -->
                <div class="mb-6 text-center">
                    @if($transaction->status === 'pending')
                        <div class="inline-flex items-center px-6 py-3 bg-orange-100 text-orange-800 rounded-full">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Pending Approval</span>
                        </div>
                    @elseif($transaction->status === 'approved')
                        <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-full">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Approved & Active</span>
                        </div>
                    @endif
                </div>

                <!-- Transaction Details -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="font-bold text-lg mb-4 text-eni-dark">Transaction Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date & Time:</span>
                                <span class="font-semibold">{{ $transaction->created_at->format('M d, Y - H:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID:</span>
                                <span class="font-mono font-semibold">{{ $transaction->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $transaction->reference)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-semibold capitalize">{{ $transaction->status }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-lg mb-4 text-eni-dark">Customer Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-semibold">{{ $transaction->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold">{{ $transaction->user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">User ID:</span>
                                <span class="font-mono font-semibold">{{ str_pad($transaction->user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($investment)
                <!-- Investment Package Details -->
                <div class="bg-eni-yellow/10 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg mb-4 text-eni-dark">Investment Package Details</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Package:</span>
                                <span class="font-semibold">{{ $investment->package->name }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Investment Amount:</span>
                                <span class="font-bold text-lg text-eni-dark">${{ number_format($investment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Daily Interest Rate:</span>
                                <span class="font-semibold text-green-600">{{ $investment->daily_shares_rate }}%</span>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-semibold">{{ $investment->package->effective_days }} days</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Expected Maturity:</span>
                                <span class="font-semibold">{{ $investment->started_at->addDays($investment->package->effective_days)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Total Expected Return:</span>
                                <span class="font-bold text-lg text-green-600">${{ number_format($investment->amount * (1 + ($investment->daily_shares_rate / 100) * $investment->package->effective_days), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary -->
                <div class="border-t pt-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-eni-dark">Total Amount:</span>
                            <span class="text-2xl font-bold text-eni-dark">${{ number_format($transaction->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Important:</strong> 
                                @if($transaction->status === 'pending')
                                    Your investment is pending approval. You will receive an email confirmation once your payment has been verified and your investment is activated.
                                @else
                                    Your investment is now active and earning daily interest. Check your dashboard for daily updates.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Footer -->
            <div class="bg-gray-100 px-8 py-4 text-center">
                <p class="text-sm text-gray-600">Thank you for choosing ENI Platform for your investment needs.</p>
                <p class="text-xs text-gray-500 mt-1">This is an automatically generated receipt. Please keep it for your records.</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="max-w-2xl mx-auto mt-8 flex gap-4 justify-center no-print">
            <button onclick="window.print()" class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-eni-yellow/90 transition-colors">
                Print Receipt
            </button>
            <a href="{{ route('user.dashboard') }}" class="bg-eni-dark text-eni-yellow border border-eni-yellow px-6 py-3 rounded-lg font-semibold hover:bg-eni-yellow hover:text-eni-dark transition-colors">
                Back to Dashboard
            </a>
            <a href="{{ route('user.transactions') }}" class="bg-white text-eni-dark border border-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                View All Transactions
            </a>
        </div>
    </div>
</body>
</html>
