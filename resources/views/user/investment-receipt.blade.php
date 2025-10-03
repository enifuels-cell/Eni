<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Receipt - ENI Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --eni-dark: #0B2241;
            --eni-yellow: #FFCD00;
            --eni-charcoal: #2D2D2D;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
        }

        .bg-eni-dark { background-color: var(--eni-dark) !important; }
        .text-eni-dark { color: var(--eni-dark) !important; }
        .bg-eni-yellow { background-color: var(--eni-yellow) !important; }
        .text-eni-yellow { color: var(--eni-yellow) !important; }
        .bg-eni-charcoal { background-color: var(--eni-charcoal) !important; }
        .border-eni-yellow { border-color: var(--eni-yellow) !important; }
        .bg-eni-yellow\/10 { background-color: rgba(255, 205, 0, 0.1) !important; }
        .bg-eni-yellow\/90 { background-color: rgba(255, 205, 0, 0.9) !important; }
        .hover\:bg-eni-yellow\/90:hover { background-color: rgba(255, 205, 0, 0.9) !important; }

        .receipt-gradient {
            background: linear-gradient(135deg, var(--eni-dark) 0%, #1a3a5c 100%);
        }

        .receipt-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        @media print {
            .no-print { display: none !important; }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background: white !important;
            }
            .receipt-gradient {
                background: var(--eni-dark) !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">
    <div class="container mx-auto max-w-4xl">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8">
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg flex items-center shadow-sm">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Receipt Card -->
        <div class="receipt-card">
            <!-- Receipt Header with ENI Branding -->
            <div class="receipt-gradient px-8 py-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-14 w-auto" />
                        <div>
                            <h2 class="text-3xl font-bold text-eni-yellow">Investment Receipt</h2>
                            <p class="text-white/80 text-sm mt-1">ENI Investment Platform</p>
                        </div>
                    </div>
                    <div class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg shadow-lg">
                        <span class="text-xs font-semibold uppercase tracking-wide block">Receipt #</span>
                        <div class="text-xl font-bold mt-1">{{ $transaction->receipt_code ?? str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="px-8 py-8">
                <!-- Status Badge -->
                <div class="mb-8 text-center">
                    @if($transaction->status === 'pending')
                        <div class="inline-flex items-center px-8 py-4 bg-orange-50 border-2 border-orange-300 text-orange-800 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-bold text-lg">Pending Approval</span>
                        </div>
                    @elseif($transaction->status === 'approved')
                        <div class="inline-flex items-center px-8 py-4 bg-green-50 border-2 border-green-400 text-green-800 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-bold text-lg">Approved & Active</span>
                        </div>
                    @elseif($transaction->status === 'completed')
                        <div class="inline-flex items-center px-8 py-4 bg-blue-50 border-2 border-blue-400 text-blue-800 rounded-xl shadow-sm">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-bold text-lg">Completed</span>
                        </div>
                    @endif
                </div>

                <!-- Transaction Details -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="font-bold text-xl mb-4 text-eni-dark flex items-center">
                            <svg class="w-6 h-6 mr-2 text-eni-yellow" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Transaction Details
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600 font-medium">Date & Time:</span>
                                <span class="font-semibold text-right">{{ $transaction->created_at->format('M d, Y') }}<br><span class="text-sm text-gray-500">{{ $transaction->created_at->format('h:i A') }}</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Transaction ID:</span>
                                <span class="font-mono font-bold text-eni-dark">{{ $transaction->receipt_code ?? $transaction->id }}</span>
                            </div>
                            @if(isset($investment) && $investment->investment_code)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Investment Code:</span>
                                <span class="font-mono font-bold text-eni-yellow bg-eni-dark px-3 py-1 rounded">{{ $investment->investment_code }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Payment Method:</span>
                                <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $transaction->reference)) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Status:</span>
                                <span class="font-bold capitalize {{ $transaction->status === 'approved' ? 'text-green-600' : ($transaction->status === 'pending' ? 'text-orange-600' : 'text-blue-600') }}">{{ $transaction->status }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="font-bold text-xl mb-4 text-eni-dark flex items-center">
                            <svg class="w-6 h-6 mr-2 text-eni-yellow" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Customer Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Name:</span>
                                <span class="font-semibold">{{ $transaction->user->name }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="font-semibold text-right break-all">{{ $transaction->user->email }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">User ID:</span>
                                <span class="font-mono font-bold text-eni-dark">{{ str_pad($transaction->user->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($investment)
                <!-- Investment Package Details -->
                <div class="bg-gradient-to-br from-eni-yellow/20 via-eni-yellow/10 to-transparent rounded-xl p-8 mb-8 border-2 border-eni-yellow/30">
                    <h3 class="font-bold text-2xl mb-6 text-eni-dark flex items-center">
                        <svg class="w-7 h-7 mr-3 text-eni-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                        Investment Package Details
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="text-gray-600 text-sm font-medium block mb-1">Package Name</span>
                                <span class="font-bold text-lg text-eni-dark">{{ $investment->investmentPackage->name }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="text-gray-600 text-sm font-medium block mb-1">Investment Amount</span>
                                <span class="font-bold text-2xl text-eni-dark">${{ number_format($investment->amount, 2) }}</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="text-gray-600 text-sm font-medium block mb-1">Daily Interest Rate</span>
                                <span class="font-bold text-xl text-green-600">{{ $investment->daily_shares_rate }}%</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="text-gray-600 text-sm font-medium block mb-1">Duration</span>
                                <span class="font-bold text-lg text-eni-dark">{{ $investment->investmentPackage->effective_days }} days</span>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="text-gray-600 text-sm font-medium block mb-1">Expected Maturity Date</span>
                                <span class="font-bold text-lg text-eni-dark">{{ $investment->started_at->addDays($investment->investmentPackage->effective_days)->format('M d, Y') }}</span>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 shadow-sm border-2 border-green-200">
                                <span class="text-green-700 text-sm font-semibold block mb-1">Total Expected Return</span>
                                <span class="font-bold text-2xl text-green-700">${{ number_format($investment->amount * (1 + ($investment->daily_shares_rate / 100) * $investment->investmentPackage->effective_days), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary -->
                <div class="border-t-2 border-gray-200 pt-6 mb-8">
                    <div class="receipt-gradient rounded-xl p-6 shadow-lg">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-white">Total Amount:</span>
                            <span class="text-3xl font-bold text-eni-yellow">${{ number_format($transaction->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Receipt -->
                @if($transaction->receipt_path)
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-8 border border-gray-200">
                    <h3 class="text-2xl font-bold text-eni-dark mb-6 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-eni-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        Payment Receipt
                    </h3>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md border-2 border-eni-yellow/30">
                        @php
                            $extension = pathinfo($transaction->receipt_path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImage)
                            <img src="{{ route('transaction.receipt.file', $transaction->id) }}"
                                alt="Payment Receipt"
                                class="receipt-image w-full max-w-2xl mx-auto block cursor-pointer hover:opacity-90 transition-opacity"
                                data-receipt-src="{{ route('transaction.receipt.file', $transaction->id) }}">
                        @else
                            <div class="p-8 text-center">
                                <div class="mb-4">
                                    <svg class="w-20 h-20 mx-auto text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-bold text-lg mb-4">PDF Receipt Uploaded</p>
                                <a href="{{ route('transaction.receipt.file', $transaction->id) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-bold hover:bg-eni-yellow/90 transition-colors shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View PDF Receipt
                                </a>
                            </div>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 text-center mt-4 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Uploaded: {{ $transaction->created_at->format('M d, Y - H:i A') }}
                    </p>
                </div>
                @endif

                <!-- Important Notice -->
                <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg p-6 shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-blue-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900 mb-2">Important Information</p>
                            <p class="text-sm text-blue-800 leading-relaxed">
                                @if($transaction->status === 'pending')
                                    Your investment is currently <strong>pending approval</strong>. Our team is reviewing your payment receipt. You will receive an email confirmation once your payment has been verified and your investment is activated. This typically takes 24-48 hours.
                                @elseif($transaction->status === 'approved')
                                    Your investment is now <strong>active and earning daily interest</strong>. You can track your earnings in real-time from your dashboard. Daily interest will be credited to your account automatically.
                                @else
                                    Your investment has been successfully processed. Check your dashboard for the latest updates on your investment performance.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Footer -->
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 px-8 py-6 text-center border-t-2 border-gray-300">
                <p class="text-base font-semibold text-gray-700 mb-2">Thank you for choosing ENI Platform for your investment needs.</p>
                <p class="text-xs text-gray-600">This is an automatically generated receipt. Please keep it for your records.</p>
                <p class="text-xs text-gray-500 mt-2">ENI Investment Platform • Secure • Transparent • Profitable</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-10 flex flex-wrap gap-4 justify-center no-print">
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-eni-yellow text-eni-dark px-8 py-4 rounded-lg font-bold text-lg hover:bg-eni-yellow/90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Receipt
            </button>
            <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 bg-eni-dark text-eni-yellow border-2 border-eni-yellow px-8 py-4 rounded-lg font-bold text-lg hover:bg-eni-yellow hover:text-eni-dark transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('user.transactions') }}" class="inline-flex items-center gap-2 bg-white text-eni-dark border-2 border-gray-300 px-8 py-4 rounded-lg font-bold text-lg hover:bg-gray-50 hover:border-gray-400 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                View All Transactions
            </a>
        </div>
    </div>

    <!-- Receipt Image Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4 hidden" role="dialog" aria-modal="true" aria-label="Receipt image modal">
        <div class="relative max-w-4xl max-h-full">
            <button data-action="close-receipt" class="absolute -top-4 -right-4 bg-white rounded-full p-2 hover:bg-gray-100 transition-colors z-10" aria-label="Close modal">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalReceiptImage" src="" alt="Payment Receipt" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        </div>
    </div>

    <script>
        let lastFocusedElement = null;
        function openReceiptModal(imageSrc) {
            lastFocusedElement = document.activeElement;
            const modal = document.getElementById('receiptModal');
            document.getElementById('modalReceiptImage').src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            const focusable = modal.querySelectorAll('button, [href], [tabindex]:not([tabindex="-1"])');
            if (focusable.length) focusable[0].focus();
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            modal.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
                }
            });
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (lastFocusedElement) lastFocusedElement.focus();
        }

        // Close modal when clicking outside the image
        document.getElementById('receiptModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReceiptModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReceiptModal();
            }
        });

        // Delegated handlers: open receipt modal for images with .receipt-image and close via data-action
        document.addEventListener('click', function (e) {
            const openEl = e.target.closest && e.target.closest('.receipt-image');
            if (openEl) {
                const src = openEl.dataset.receiptSrc || openEl.src;
                openReceiptModal(src);
                return;
            }
            const closeBtn = e.target.closest && e.target.closest('[data-action="close-receipt"]');
            if (closeBtn) {
                closeReceiptModal();
                return;
            }
        });
    </script>
</body>
</html>
