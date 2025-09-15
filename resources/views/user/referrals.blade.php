<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referrals - ENI Platform</title>
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
            <h2 class="text-2xl font-bold text-eni-yellow mb-6">Share Your Referral Links</h2>
            <div class="grid lg:grid-cols-2 gap-6">
                
                <!-- Username Referral Link (Only option) -->
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <label class="block text-white/80 font-semibold">Your Referral Link</label>
                        <span class="bg-eni-yellow text-eni-dark text-xs px-2 py-1 rounded-full font-bold">USERNAME BASED</span>
                    </div>
                    <div class="flex">
                        <input type="text" id="referralLink" 
                               value="{{ $referralLink }}" 
                               class="flex-1 bg-white/10 border border-white/20 rounded-l-lg px-4 py-3 text-white focus:outline-none focus:border-eni-yellow text-sm" 
                               readonly>
            <button type="button" data-action="copy-link" data-target="referralLink"
                class="bg-eni-yellow text-eni-dark px-4 py-3 rounded-r-lg font-semibold hover:bg-yellow-400 transition-colors">
                            Copy
                        </button>
                    </div>
                    <p class="text-white/60 text-xs mt-2">
                        @if(Auth::user()->username)
                            Easy to remember: /register?ref={{ Auth::user()->username }}
                        @else
                            Your referral code: {{ Auth::user()->id }}
                        @endif
                    </p>
                </div>

                <!-- QR Code Section -->
                <div class="text-center">
                    <label class="block text-white/80 font-semibold mb-3">QR Code</label>
                    
                    <!-- QR Code Container (Initially Hidden) -->
                    <div id="qrCodeContainer" class="hidden">
                        <div class="bg-white p-4 rounded-lg inline-block mb-3">
                            {!! $qrCode ?? '<div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-500">QR Code</div>' !!}
                        </div>
                        <p class="text-white/60 text-sm mb-4">
                            Scan to register with username: {{ Auth::user()->username }}
                        </p>
                    </div>
                    
                    <!-- Generate QR Code Button -->
            <button type="button" id="generateQrBtn" data-action="toggle-qr" 
                class="bg-eni-yellow text-eni-dark font-semibold px-6 py-3 rounded-lg hover:bg-yellow-400 transition-colors">
                        <i class="fas fa-qrcode mr-2"></i>Generate QR Code
                    </button>
                    
                    <!-- Hide QR Code Button (Initially Hidden) -->
            <button type="button" id="hideQrBtn" data-action="toggle-qr" 
                class="hidden bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-gray-500 transition-colors">
                        <i class="fas fa-eye-slash mr-2"></i>Hide QR Code
                    </button>
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
                    <button type="button" data-action="copy-referral" 
                            class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Copy Referral Link
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // New universal copy function
        function copyLink(inputId, buttonElement) {
            const linkInput = document.getElementById(inputId);
            if (!linkInput) return;
            
            // Try clipboard API first
            if (navigator.clipboard) {
                navigator.clipboard.writeText(linkInput.value).then(function() {
                    showCopyFeedback(buttonElement);
                }, function() {
                    fallbackCopy(linkInput, buttonElement);
                });
            } else {
                fallbackCopy(linkInput, buttonElement);
            }
        }

        // Legacy function for backward compatibility
        function copyReferralLink(e) {
            const linkInput = document.getElementById('referralLink');
            if (!linkInput) return;
            copyLink('referralLink', e.target);
        }

        function fallbackCopy(input, buttonElement) {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            showCopyFeedback(buttonElement);
        }

        function showCopyFeedback(button) {
            if (!button) return;
            const originalText = button.textContent;
            const originalClasses = Array.from(button.classList);
            
            button.textContent = 'Copied!';
            button.classList.remove('bg-eni-yellow', 'bg-gray-600');
            button.classList.add('bg-green-500');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500');
                // Restore original classes
                originalClasses.forEach(cls => {
                    if (cls.startsWith('bg-')) {
                        button.classList.add(cls);
                    }
                });
            }, 2000);
        }

        function toggleQrCode() {
            const qrContainer = document.getElementById('qrCodeContainer');
            const generateBtn = document.getElementById('generateQrBtn');
            const hideBtn = document.getElementById('hideQrBtn');
            
            if (qrContainer.classList.contains('hidden')) {
                // Show QR Code
                qrContainer.classList.remove('hidden');
                generateBtn.classList.add('hidden');
                hideBtn.classList.remove('hidden');
            } else {
                // Hide QR Code
                qrContainer.classList.add('hidden');
                generateBtn.classList.remove('hidden');
                hideBtn.classList.add('hidden');
            }
        }

        // Delegate data-action buttons
        document.addEventListener('click', function(e) {
            const el = e.target.closest('[data-action]');
            if (!el) return;
            const action = el.getAttribute('data-action');

            if (action === 'copy-link') {
                const target = el.getAttribute('data-target');
                copyLink(target, el);
                return;
            }

            if (action === 'toggle-qr') {
                toggleQrCode();
                return;
            }

            if (action === 'copy-referral') {
                const linkInput = document.getElementById('referralLink');
                if (linkInput) copyLink('referralLink', el);
                return;
            }
        });
    </script>
</body>
</html>
