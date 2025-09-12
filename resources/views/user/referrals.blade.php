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
            <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-white transition-colors" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        <!-- How Referral Program Works -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10 mb-8">
            <h2 class="text-xl font-bold text-eni-yellow mb-3">How the Referral Program Works</h2>
            <ul class="list-disc ml-6 text-white/80 text-sm space-y-1">
                <li>Share your unique referral link or QR code with friends, family, or colleagues.</li>
                <li>When someone registers using your link and makes a valid investment, you earn a <span class="text-eni-yellow font-semibold">commission based on their investment package</span> (5% to 15% depending on package tier).</li>
                <li>Your rewards are credited automatically once their investment is confirmed.</li>
                <li>You can track your referrals and earnings below.</li>
            </ul>
        </div>

        <!-- Commission Rate Breakdown -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-6 border border-white/10 mb-8">
            <h2 class="text-xl font-bold text-eni-yellow mb-4">Commission Rate Structure</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(isset($packages) && $packages->count() > 0)
                    @foreach($packages as $package)
                    <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                        <h3 class="font-semibold text-white mb-2">{{ $package->name }}</h3>
                        <p class="text-white/70 text-sm mb-1">${{ number_format($package->min_amount) }} - ${{ number_format($package->max_amount) }}</p>
                        <p class="text-eni-yellow font-bold text-lg">{{ $package->referral_bonus_rate }}% Commission</p>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-white/70">
                        <p>Commission rates: 5% (Starter) to 15% (VIP) based on investment package</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Share Your Link (always visible) -->
        <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal rounded-2xl p-8 border border-white/10 mb-8">
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Share Your Referral Link</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Referral Link -->
                <div>
                    <label class="block text-white/80 font-semibold mb-3">Your Referral Link</label>
                    <div class="flex">
                        <input type="text" id="referralLink" 
                               value="{{ $referralLink ?? route('register', ['ref' => Auth::user()->referral_code]) }}" 
                               class="flex-1 bg-white/10 border border-white/20 rounded-l-lg px-4 py-3 text-white focus:outline-none focus:border-eni-yellow" 
                               readonly>
                        <button type="button" onclick="copyReferralLink(event)" 
                                class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-r-lg font-semibold hover:bg-yellow-400 transition-colors">
                            Copy
                        </button>
                    </div>
                    <p class="text-white/60 text-sm mt-2">Share this link to earn variable commission (5%-15%) based on investment packages made by your referrals</p>
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
                    <button type="button" onclick="copyReferralLink(event)" 
                            class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Copy Referral Link
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function copyReferralLink(e) {
            const linkInput = document.getElementById('referralLink');
            if (!linkInput) return;
            // Try clipboard API first
            if (navigator.clipboard) {
                navigator.clipboard.writeText(linkInput.value).then(function() {
                    showCopyFeedback(e);
                }, function() {
                    fallbackCopy(linkInput, e);
                });
            } else {
                fallbackCopy(linkInput, e);
            }
        }
        function fallbackCopy(input, e) {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            showCopyFeedback(e);
        }
        function showCopyFeedback(e) {
            if (!e) return;
            const button = e.target;
            if (!button) return;
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
