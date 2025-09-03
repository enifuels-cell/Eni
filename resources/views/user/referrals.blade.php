<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referrals - ENI Platform</title>
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
                <h1 class="font-extrabold text-xl tracking-tight">Referral Program</h1>
                <p class="text-sm text-white/70">Earn rewards by inviting friends</p>
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

    <div class="container mx-auto px-6 py-8">
        <!-- Referral Overview -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-eni-yellow rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-eni-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-eni-yellow">Total Referrals</h3>
                    <p class="text-2xl font-bold">{{ count($referrals ?? []) }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-400">Total Earned</h3>
                    <p class="text-2xl font-bold">${{ number_format(Auth::user()->totalReferralBonuses() ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10">
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-400">Commission Rate</h3>
                    <p class="text-2xl font-bold">10%</p>
                </div>
            </div>
        </div>

        <!-- Share Your Link -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-8 border border-white/10 mb-8">
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Share Your Referral Link</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Referral Link -->
                <div>
                    <label class="block text-white/80 font-semibold mb-3">Your Referral Link</label>
                    <div class="flex">
                        <input type="text" id="referralLink" 
                               value="{{ $referralLink ?? route('register', ['ref' => Auth::id()]) }}" 
                               class="flex-1 bg-white/10 border border-white/20 rounded-l-lg px-4 py-3 text-white focus:outline-none focus:border-eni-yellow" 
                               readonly>
                        <button onclick="copyReferralLink()" 
                                class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-r-lg font-semibold hover:bg-yellow-400 transition-colors">
                            Copy
                        </button>
                    </div>
                    <p class="text-white/60 text-sm mt-2">Share this link to earn 10% commission on all investments made by your referrals</p>
                </div>

                <!-- QR Code -->
                <div class="text-center">
                    <label class="block text-white/80 font-semibold mb-3">QR Code</label>
                    <div class="bg-white p-4 rounded-lg inline-block">
                        {!! $qrCode ?? '<div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-500">QR Code</div>' !!}
                    </div>
                    <p class="text-white/60 text-sm mt-2">Share this QR code for quick registration</p>
                </div>
            </div>
        </div>

        <!-- Your Referrals -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-8 border border-white/10">
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Your Referrals</h2>
            
            @forelse($referrals ?? [] as $referral)
                <div class="border-b border-white/10 py-6 last:border-b-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $referral->referee->name ?? 'Unknown User' }}</h3>
                            <p class="text-white/70 text-sm">Joined: {{ $referral->created_at->format('M d, Y') ?? 'Unknown' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-eni-yellow text-lg">
                                ${{ number_format($referral->referralBonuses->sum('amount') ?? 0, 2) }}
                            </p>
                            <p class="text-white/70 text-sm">Total Earned</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white/70 mb-2">No referrals yet</h3>
                    <p class="text-white/50 mb-4">Start sharing your referral link to earn commissions!</p>
                    <button onclick="copyReferralLink()" 
                            class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Copy Referral Link
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function copyReferralLink() {
            const linkInput = document.getElementById('referralLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value);
            
            // Show feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-500');
            button.classList.remove('bg-eni-yellow');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-eni-yellow');
            }, 2000);
        }
    </script>
</body>
</html>
                                <div class="flex">
                                    <input type="text" value="{{ auth()->id() }}" id="referralCode" readonly
                                           class="flex-1 rounded-l-md border-gray-300 bg-gray-50 text-gray-600">
                                    <button onclick="copyCode()" 
                                            class="bg-green-600 text-white px-4 py-2 rounded-r-md hover:bg-green-700">
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Code</h3>
                        
                        <div class="text-center">
                            <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                                {!! $qrCode !!}
                            </div>
                            <p class="text-sm text-gray-500 mt-2">Share this QR code for easy referrals</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $referrals->count() }}</div>
                        <div class="text-sm font-medium text-gray-500">Total Referrals</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">
                            ${{ number_format($referrals->sum(function($referral) { return $referral->totalBonusEarned(); }), 2) }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">Total Earned</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            {{ $referrals->flatMap->referralBonuses->where('paid', true)->count() }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">Paid Bonuses</div>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">How Referral Program Works</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl mb-2">🔗</div>
                        <h4 class="font-semibold mb-2">1. Share Your Link</h4>
                        <p class="text-blue-100 text-sm">Share your unique referral link or QR code with friends and family</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">💰</div>
                        <h4 class="font-semibold mb-2">2. They Invest</h4>
                        <p class="text-blue-100 text-sm">When they sign up and make their first investment using your code</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl mb-2">🎉</div>
                        <h4 class="font-semibold mb-2">3. You Earn</h4>
                        <p class="text-blue-100 text-sm">Receive bonus percentages from their investment amounts instantly</p>
                    </div>
                </div>
            </div>

            <!-- Referral List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Referrals</h3>
                    
                    @if($referrals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referred User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bonuses</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($referrals as $referral)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ substr($referral->referee->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $referral->referee->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $referral->referee->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $referral->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                            ${{ number_format($referral->totalBonusEarned(), 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4">👥</div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No referrals yet</h4>
                            <p class="text-gray-500 mb-4">Start sharing your referral link to earn bonuses from your referrals' investments!</p>
                            <button onclick="copyToClipboard()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                Copy Referral Link
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const referralLink = document.getElementById('referralLink');
            referralLink.select();
            referralLink.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Show success message
            alert('Referral link copied to clipboard!');
        }

        function copyCode() {
            const referralCode = document.getElementById('referralCode');
            referralCode.select();
            referralCode.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Show success message
            alert('Referral code copied to clipboard!');
        }
    </script>
</x-app-layout>
