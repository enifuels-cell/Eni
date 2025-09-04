<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Packages - ENI Platform</title>
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
                <h1 class="font-extrabold text-xl tracking-tight">Investment Packages</h1>
                <p class="text-sm text-white/70">Choose your investment plan</p>
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
        <!-- Available Investment Packages -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-6 text-eni-yellow">Available Investment Packages</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($investmentPackages ?? [] as $package)
                    <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10 hover:border-eni-yellow/50 transition-all">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-eni-yellow">{{ $package->name }}</h3>
                            <p class="text-white/70 text-sm">{{ $package->description }}</p>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-white/70">Minimum:</span>
                                <span class="font-semibold">${{ number_format($package->minimum_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/70">Maximum:</span>
                                <span class="font-semibold">${{ number_format($package->maximum_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/70">Daily Interest:</span>
                                <span class="font-semibold text-eni-yellow">{{ $package->interest_rate }}%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-white/70">Duration:</span>
                                <span class="font-semibold">{{ $package->duration_days }} days</span>
                            </div>
                        </div>
                        
                        <button onclick="openInvestModal('{{ $package->id }}', '{{ $package->name }}', {{ $package->minimum_amount }}, {{ $package->maximum_amount }})" 
                                class="w-full bg-eni-yellow text-eni-dark font-bold py-3 rounded-xl hover:bg-yellow-400 transition-colors">
                            Invest Now
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-white/70">No investment packages available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Your Active Investments -->
        <div>
            <h2 class="text-2xl font-bold mb-6 text-eni-yellow">Your Active Investments</h2>
            
            <div class="bg-eni-dark rounded-2xl overflow-hidden">
                @forelse($investments ?? [] as $investment)
                    <div class="border-b border-white/10 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-lg">{{ $investment->investmentPackage->name }}</h3>
                                <p class="text-white/70 text-sm">Started: {{ $investment->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($investment->status === 'active') bg-green-500/20 text-green-400
                                @elseif($investment->status === 'completed') bg-blue-500/20 text-blue-400
                                @else bg-yellow-500/20 text-yellow-400 @endif">
                                {{ ucfirst($investment->status) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-white/70">Investment</p>
                                <p class="font-semibold">${{ number_format($investment->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-white/70">Daily Interest</p>
                                <p class="font-semibold text-eni-yellow">{{ $investment->investmentPackage->interest_rate }}%</p>
                            </div>
                            <div>
                                <p class="text-white/70">Total Earned</p>
                                <p class="font-semibold text-green-400">${{ number_format($investment->totalInterestEarned(), 2) }}</p>
                            </div>
                            <div>
                                <p class="text-white/70">Days Left</p>
                                <p class="font-semibold">{{ $investment->daysRemaining() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-white/70">You don't have any active investments yet.</p>
                        <p class="text-white/50 text-sm mt-2">Choose a package above to get started!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Investment Modal -->
    <div id="investModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-eni-dark rounded-2xl p-8 m-4 max-w-md w-full border border-white/10">
            <h3 id="modalTitle" class="text-xl font-bold mb-6 text-eni-yellow">Invest in Package</h3>
            
            <form method="POST" action="{{ route('investments.store') }}">
                @csrf
                <input type="hidden" id="packageId" name="investment_package_id">
                
                <div class="mb-6">
                    <label class="block text-white/80 mb-2">Investment Amount ($)</label>
                    <input type="number" id="investAmount" name="amount" step="0.01" 
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-eni-yellow" 
                           placeholder="Enter amount" required>
                    <p id="amountRange" class="text-white/60 text-sm mt-1"></p>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeInvestModal()" 
                            class="flex-1 bg-white/10 text-white py-3 rounded-lg hover:bg-white/20 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-eni-yellow text-eni-dark font-bold py-3 rounded-lg hover:bg-yellow-400 transition-colors">
                        Confirm Investment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openInvestModal(packageId, packageName, minAmount, maxAmount) {
            const modal = document.getElementById('investModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            const title = document.getElementById('modalTitle');
            if (title) title.textContent = 'Invest in ' + packageName;
            const pkgId = document.getElementById('packageId');
            if (pkgId) pkgId.value = packageId;
            const investAmount = document.getElementById('investAmount');
            if (investAmount) {
                investAmount.min = minAmount;
                investAmount.max = maxAmount;
            }
            const amountRange = document.getElementById('amountRange');
            if (amountRange) {
                amountRange.textContent = `Min: $${Number(minAmount).toLocaleString()} - Max: $${Number(maxAmount).toLocaleString()}`;
            }
        }
        function closeInvestModal() {
            const modal = document.getElementById('investModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
