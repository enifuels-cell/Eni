<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - ENI Platform</title>
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
                <h1 class="font-extrabold text-xl tracking-tight">Transaction History</h1>
                <p class="text-sm text-white/70">View all your transactions</p>
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

    <div class="container mx-auto px-6 py-8">
        <!-- Transaction Summary -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-400">Total Deposits</h3>
                    <p class="text-2xl font-bold">${{ number_format($totalDeposits ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-red-400">Total Withdrawals</h3>
                    <p class="text-2xl font-bold">${{ number_format($totalWithdrawals ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-eni-yellow rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-eni-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eni-yellow">Interest Earned</h3>
                    <p class="text-2xl font-bold">${{ number_format($totalInterest ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-400">Referral Bonuses</h3>
                    <p class="text-2xl font-bold">${{ number_format($totalReferralBonuses ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-3">
                <button onclick="filterTransactions('all')" 
                        class="filter-btn active bg-eni-yellow text-eni-dark px-4 py-2 rounded-lg font-semibold">
                    All Transactions
                </button>
                <button onclick="filterTransactions('deposit')" 
                        class="filter-btn bg-white/10 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white/20">
                    Deposits
                </button>
                <button onclick="filterTransactions('withdrawal')" 
                        class="filter-btn bg-white/10 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white/20">
                    Withdrawals
                </button>
                <button onclick="filterTransactions('interest')" 
                        class="filter-btn bg-white/10 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white/20">
                    Interest
                </button>
                <button onclick="filterTransactions('referral')" 
                        class="filter-btn bg-white/10 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white/20">
                    Referrals
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl border border-white/10 overflow-hidden">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-2xl font-bold text-eni-yellow">Recent Transactions</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="text-left p-4 font-semibold text-white/80">Date</th>
                            <th class="text-left p-4 font-semibold text-white/80">Type</th>
                            <th class="text-left p-4 font-semibold text-white/80">Description</th>
                            <th class="text-right p-4 font-semibold text-white/80">Amount</th>
                            <th class="text-center p-4 font-semibold text-white/80">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions ?? [] as $transaction)
                            <tr class="border-b border-white/10 transaction-row" data-type="{{ $transaction->type }}">
                                <td class="p-4 text-white/80">
                                    {{ $transaction->created_at->format('M d, Y') }}<br>
                                    <span class="text-xs text-white/60">{{ $transaction->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($transaction->type === 'deposit') bg-green-500/20 text-green-400
                                        @elseif($transaction->type === 'withdrawal') bg-red-500/20 text-red-400
                                        @elseif($transaction->type === 'interest') bg-eni-yellow/20 text-eni-yellow
                                        @else bg-blue-500/20 text-blue-400 @endif">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="p-4 text-white/80">
                                    {{ $transaction->description ?? $transaction->reference }}
                                </td>
                                <td class="p-4 text-right font-semibold
                                    @if($transaction->type === 'deposit' || $transaction->type === 'interest' || $transaction->type === 'referral') text-green-400
                                    @else text-red-400 @endif">
                                    @if($transaction->type === 'deposit' || $transaction->type === 'interest' || $transaction->type === 'referral')
                                        +${{ number_format($transaction->amount, 2) }}
                                    @else
                                        -${{ number_format($transaction->amount, 2) }}
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if($transaction->status === 'completed') bg-green-500/20 text-green-400
                                        @elseif($transaction->status === 'pending') bg-yellow-500/20 text-yellow-400
                                        @elseif($transaction->status === 'processing') bg-blue-500/20 text-blue-400
                                        @else bg-red-500/20 text-red-400 @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white/70 mb-2">No transactions yet</h3>
                                    <p class="text-white/50 mb-4">Your transaction history will appear here</p>
                                    <a href="{{ route('dashboard.deposit') }}" 
                                       class="inline-block bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                                        Make Your First Deposit
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($transactions) && $transactions->hasPages())
                <div class="p-6 border-t border-white/10">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterTransactions(type) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-eni-yellow', 'text-eni-dark');
                btn.classList.add('bg-white/10', 'text-white');
            });
            
            event.target.classList.add('active', 'bg-eni-yellow', 'text-eni-dark');
            event.target.classList.remove('bg-white/10', 'text-white');
            
            // Filter rows
            document.querySelectorAll('.transaction-row').forEach(row => {
                if (type === 'all' || row.dataset.type === type) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
