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

        /* 🔹 1. Gradient Background Enhancements */
        .main-gradient-bg {
            background: linear-gradient(180deg, #0B2241 0%, #1a365d 40%, #121417 100%);
            position: relative;
            overflow: hidden;
        }

        /* 🔹 2. Abstract Geometric Pattern */
        .geometric-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.08;
            background-image:
                linear-gradient(45deg, rgba(255, 205, 0, 0.1) 1px, transparent 1px),
                linear-gradient(-45deg, rgba(255, 205, 0, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 205, 0, 0.05) 1px, transparent 1px);
            background-size: 60px 60px, 60px 60px, 30px 30px;
            animation: pattern-drift 60s linear infinite;
        }

        @keyframes pattern-drift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        /* 🔹 3. Radial Glow / Spotlight for Cards */
        .card-spotlight {
            position: relative;
        }

        .card-spotlight::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 205, 0, 0.12) 0%, rgba(255, 205, 0, 0.05) 30%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            transition: all 0.5s ease;
            opacity: 0;
        }

        .card-spotlight:hover::before {
            opacity: 1;
            transform: scale(1.1);
        }

        /* 🔹 4. Textured Background Overlay */
        .texture-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.05) 1px, transparent 0);
            background-size: 20px 20px;
            opacity: 0.06;
            animation: texture-drift 120s linear infinite;
        }

        @keyframes texture-drift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(20px, 20px); }
        }

        /* Additional texture layers for premium feel */
        .metallic-texture {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                linear-gradient(135deg,
                    transparent 25%,
                    rgba(255, 255, 255, 0.02) 25%,
                    rgba(255, 255, 255, 0.02) 50%,
                    transparent 50%,
                    transparent 75%,
                    rgba(255, 255, 255, 0.02) 75%);
            background-size: 40px 40px;
            opacity: 0.5;
        }

        /* 🔹 5. Split Background Sections */
        .header-content-section {
            background: linear-gradient(135deg, #0B2241 0%, #1e3a8a 50%, #0B2241 100%);
            position: relative;
        }

        .footer-section {
            background: linear-gradient(135deg, #1f2937 0%, #374151 50%, #1f2937 100%);
            position: relative;
        }

        /* Enhanced ambient lighting */
        .ambient-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(ellipse at top left, rgba(255, 205, 0, 0.03) 0%, transparent 50%),
                radial-gradient(ellipse at top right, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                radial-gradient(ellipse at bottom center, rgba(255, 205, 0, 0.02) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Floating orbs for depth */
        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            animation: float-orb 8s ease-in-out infinite;
            pointer-events: none;
        }

        .orb-1 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 205, 0, 0.08) 0%, transparent 70%);
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            top: 60%;
            right: 15%;
            animation-delay: -3s;
        }

        .orb-3 {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 205, 0, 0.05) 0%, transparent 70%);
            bottom: 20%;
            left: 60%;
            animation-delay: -6s;
        }

        @keyframes float-orb {
            0%, 100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.6;
            }
            33% {
                transform: translate(30px, -20px) scale(1.1);
                opacity: 0.8;
            }
            66% {
                transform: translate(-20px, 10px) scale(0.9);
                opacity: 0.4;
            }
        }

        /* Enhanced depth shadows */
        .depth-shadow {
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        /* Text glow effects */
        .text-glow {
            text-shadow: 0 0 20px rgba(255, 205, 0, 0.5);
        }

        /* Glass morphism effects */
        .glass-effect {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Enhanced hover effects for referral cards */
        .referral-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .referral-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 205, 0, 0.1);
        }

        /* QR Code enhancement */
        .qr-container {
            transition: all 0.5s ease;
        }

        .qr-container.show {
            animation: fadeInScale 0.5s ease;
        }

        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Make referral link selectable */
        #referralLink {
            user-select: all;
            -webkit-user-select: all;
            -moz-user-select: all;
            -ms-user-select: all;
        }

        #referralLink::selection {
            background-color: rgba(255, 205, 0, 0.3);
            color: #fff;
        }

        #referralLink::-moz-selection {
            background-color: rgba(255, 205, 0, 0.3);
            color: #fff;
        }
    </style>
</head>
<body class="text-white min-h-screen relative overflow-x-hidden">
    <!-- Main Content Wrapper with Enhanced Background Design -->
    <div class="header-content-section relative min-h-screen">
        <!-- Background Enhancement Layers -->
        <div class="geometric-pattern"></div>
        <div class="texture-overlay"></div>
        <div class="metallic-texture"></div>
        <div class="ambient-glow"></div>

        <!-- Floating Orbs for Depth -->
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>

        <!-- Header -->
        <header class="relative z-10 bg-black/20 backdrop-blur-sm border-b border-white/10 px-6 py-4 flex items-center justify-between depth-shadow">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
                <div>
                    <h1 class="font-extrabold text-xl tracking-tight text-glow">Referral Program</h1>
                    <p class="text-sm text-white/70">Earn rewards by inviting friends</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-eni-yellow transition-all duration-300 hover:scale-110" title="Back to Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>
        </header>

        <div class="container mx-auto px-6 py-8 relative z-10">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-eni-yellow mb-4 text-glow">Referral Program</h2>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">Invite friends and earn generous commissions on their investments. The more they invest, the more you earn!</p>
        </div>

        <!-- How Referral Program Works -->
        <div class="card-spotlight referral-card bg-gradient-to-br from-white/10 to-white/5 rounded-2xl p-8 border border-white/10 backdrop-blur-sm mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-eni-yellow to-yellow-500 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-eni-dark" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-eni-yellow">How the Referral Program Works</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-eni-yellow font-bold text-sm">1</span>
                        </div>
                        <p class="text-white/80">Share your unique referral link or QR code with friends, family, or colleagues.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-eni-yellow font-bold text-sm">2</span>
                        </div>
                        <p class="text-white/80">When someone registers using your link and makes a valid investment, you earn a <span class="text-eni-yellow font-semibold">commission based on their investment package</span> (5% to 15% depending on package tier).</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-eni-yellow font-bold text-sm">3</span>
                        </div>
                        <p class="text-white/80">Your rewards are credited automatically once their investment is confirmed.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-eni-yellow font-bold text-sm">4</span>
                        </div>
                        <p class="text-white/80">You can track your referrals and earnings in real-time below.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission Rate Breakdown -->
        <div class="card-spotlight referral-card bg-gradient-to-br from-white/10 to-white/5 rounded-2xl p-8 border border-white/10 backdrop-blur-sm mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-eni-yellow">Commission Rate Structure</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($packages) && $packages->count() > 0)
                    @foreach($packages as $index => $package)
                    <div class="card-spotlight bg-gradient-to-br from-white/10 to-white/5 rounded-xl p-6 border border-white/10 hover:border-eni-yellow/30 transition-all duration-300 group">
                        <!-- Package tier indicator -->
                        @if($index === 1)
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                            <div class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark px-3 py-1 rounded-full text-xs font-bold">
                                🔥 POPULAR
                            </div>
                        </div>
                        @endif

                        <div class="text-center">
                            <h3 class="font-bold text-white text-lg mb-3 group-hover:text-eni-yellow transition-colors">{{ $package->name }}</h3>
                            <div class="mb-4">
                                <div class="text-white/70 text-sm mb-2">Investment Range</div>
                                <div class="text-white font-semibold">${{ number_format($package->min_amount) }} - ${{ number_format($package->max_amount) }}</div>
                            </div>
                            <div class="bg-eni-yellow/10 rounded-xl p-4 border border-eni-yellow/20">
                                <div class="text-eni-yellow font-bold text-2xl mb-1">{{ $package->referral_bonus_rate }}%</div>
                                <div class="text-white/70 text-sm">Commission Rate</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-8">
                        <div class="w-16 h-16 bg-eni-yellow/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-eni-yellow" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <p class="text-white/70 text-lg">Commission rates: 5% (Starter) to 15% (VIP) based on investment package</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Share Your Link (always visible) -->
        <div class="card-spotlight referral-card bg-gradient-to-br from-white/10 to-white/5 rounded-2xl p-8 border border-white/10 backdrop-blur-sm mb-8">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-eni-yellow">Share Your Referral Link</h2>
                    <p class="text-white/70">Start earning commissions by sharing your unique link</p>
                </div>
            </div>
            <div class="grid lg:grid-cols-2 gap-8">

                <!-- Username Referral Link (Only option) -->
                <div class="card-spotlight bg-white/5 rounded-xl p-6 border border-white/10">
                    <div class="flex items-center gap-2 mb-4">
                        <label class="block text-white/80 font-semibold text-lg">Your Referral Link</label>
                        <span class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark text-xs px-3 py-1 rounded-full font-bold">USERNAME BASED</span>
                    </div>
                    <div class="flex mb-4">
                        <input type="text" id="referralLink"
                               value="{{ $referralLink }}"
                               class="flex-1 bg-white/10 border border-white/20 rounded-l-xl px-4 py-4 text-white focus:outline-none focus:border-eni-yellow text-sm backdrop-blur-sm select-all cursor-pointer"
                               readonly
                               onclick="this.select(); this.setSelectionRange(0, 99999);"
                               ontouchstart="this.select(); this.setSelectionRange(0, 99999);">
                        <button type="button" data-action="copy-link" data-target="referralLink"
                                class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark px-6 py-4 rounded-r-xl font-semibold hover:from-yellow-400 hover:to-yellow-400 transition-all duration-300 hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="bg-eni-yellow/10 rounded-lg p-3 border border-eni-yellow/20">
                        <p class="text-white/80 text-sm">
                            @if(Auth::user()->username)
                                <span class="text-eni-yellow font-semibold">Easy to remember:</span>
                                <span id="referral-code-text" class="text-white font-mono">{{ Auth::user()->username }}</span>
                            @else
                                <span class="text-eni-yellow font-semibold">Your referral code:</span>
                                <span id="referral-code-text" class="text-white font-mono">{{ Auth::user()->id }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="card-spotlight bg-white/5 rounded-xl p-6 border border-white/10 text-center">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-eni-yellow" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4z"/>
                        </svg>
                        <label class="block text-white/80 font-semibold text-lg">QR Code</label>
                    </div>

                    <!-- QR Code Container (Initially Hidden) -->
                    <div id="qrCodeContainer" class="qr-container hidden">
                        <div class="bg-white p-4 rounded-xl inline-block mb-4 shadow-lg">
                            {!! $qrCode ?? '<div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-500 rounded-lg">QR Code</div>' !!}
                        </div>
                        <div class="bg-eni-yellow/10 rounded-lg p-3 border border-eni-yellow/20 mb-4">
                            <p class="text-white/80 text-sm">
                                <span class="text-eni-yellow font-semibold">Scan to register with username:</span> {{ Auth::user()->username }}
                            </p>
                        </div>
                    </div>

                    <!-- Generate QR Code Button -->
                    <button type="button" id="generateQrBtn" data-action="toggle-qr"
                            class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark font-semibold px-6 py-3 rounded-xl hover:from-yellow-400 hover:to-yellow-400 transition-all duration-300 hover:scale-105 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4z"/>
                        </svg>
                        Generate QR Code
                    </button>

                    <!-- Hide QR Code Button (Initially Hidden) -->
                    <button type="button" id="hideQrBtn" data-action="toggle-qr"
                            class="hidden bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold px-6 py-3 rounded-xl hover:from-gray-500 hover:to-gray-600 transition-all duration-300 hover:scale-105 items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                        Hide QR Code
                    </button>
                </div>
            </div>
        </div>

        <!-- Your Referrals -->
        <div class="card-spotlight referral-card bg-gradient-to-br from-white/10 to-white/5 rounded-2xl p-8 border border-white/10 backdrop-blur-sm">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v2c0 .656.126 1.283.356 1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-eni-yellow">Your Referrals</h2>
                    <p class="text-white/70">Track your referral success and earnings</p>
                </div>
            </div>

            @forelse($referrals ?? [] as $referral)
                <div class="card-spotlight bg-white/5 rounded-xl p-6 border border-white/10 mb-4 last:mb-0 hover:border-eni-yellow/30 transition-all duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-eni-yellow to-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-eni-dark font-bold text-lg">{{ substr($referral->referee->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-white">{{ $referral->referee->name ?? 'Unknown User' }}</h3>
                                <p class="text-white/70 text-sm">Joined: {{ $referral->created_at->format('M d, Y') ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-eni-yellow/10 rounded-lg p-3 border border-eni-yellow/20">
                                <p class="font-bold text-eni-yellow text-xl">
                                    ${{ number_format($referral->referralBonuses->sum('amount') ?? 0, 2) }}
                                </p>
                                <p class="text-white/70 text-sm">Total Earned</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gradient-to-br from-eni-yellow/20 to-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-eni-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v2c0 .656.126 1.283.356 1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">No referrals yet</h3>
                    <p class="text-white/70 text-lg mb-6 max-w-md mx-auto">Start sharing your referral link to earn commissions when your friends invest!</p>
                    <button type="button" data-action="copy-referral"
                            class="bg-gradient-to-r from-eni-yellow to-yellow-500 text-eni-dark px-8 py-4 rounded-xl font-semibold hover:from-yellow-400 hover:to-yellow-400 transition-all duration-300 hover:scale-105 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                        </svg>
                        Copy Referral Link
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Close Main Content Section -->
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
                // Show QR Code with animation
                qrContainer.classList.remove('hidden');
                qrContainer.classList.add('show');
                generateBtn.style.display = 'none';
                hideBtn.style.display = 'inline-flex';
            } else {
                // Hide QR Code
                qrContainer.classList.add('hidden');
                qrContainer.classList.remove('show');
                generateBtn.style.display = 'inline-flex';
                hideBtn.style.display = 'none';
            }
        }

        function copyReferralCode() {
            const referralCodeText = document.getElementById('referral-code-text').textContent;
            const copyBtn = document.getElementById('copy-referral-btn');
            const copyIcon = document.getElementById('copy-icon');
            const checkIcon = document.getElementById('check-icon');
            const btnText = document.getElementById('copy-btn-text');

            // Copy to clipboard
            navigator.clipboard.writeText(referralCodeText).then(() => {
                // Show success state
                copyIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                btnText.textContent = 'Copied!';
                copyBtn.classList.add('bg-green-500', 'text-white');
                copyBtn.classList.remove('bg-eni-yellow', 'text-eni-dark');

                // Reset after 2 seconds
                setTimeout(() => {
                    copyIcon.classList.remove('hidden');
                    checkIcon.classList.add('hidden');
                    btnText.textContent = 'Copy';
                    copyBtn.classList.remove('bg-green-500', 'text-white');
                    copyBtn.classList.add('bg-eni-yellow', 'text-eni-dark');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = referralCodeText;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    // Show success state
                    copyIcon.classList.add('hidden');
                    checkIcon.classList.remove('hidden');
                    btnText.textContent = 'Copied!';
                    copyBtn.classList.add('bg-green-500', 'text-white');
                    copyBtn.classList.remove('bg-eni-yellow', 'text-eni-dark');

                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                        btnText.textContent = 'Copy';
                        copyBtn.classList.remove('bg-green-500', 'text-white');
                        copyBtn.classList.add('bg-eni-yellow', 'text-eni-dark');
                    }, 2000);
                } catch (err) {
                    alert('Failed to copy. Please copy manually: ' + referralCodeText);
                }
                document.body.removeChild(textArea);
            });
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
