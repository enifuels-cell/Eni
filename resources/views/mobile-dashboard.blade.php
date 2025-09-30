<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>ENI - Investment Platform</title>
    <meta name="theme-color" content="#1E3A8A">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-blue': '#1E3A8A',
                        'eni-yellow': '#FBBF24',
                        'eni-dark': '#0F172A',
                        'eni-gray': '#1E293B',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            scroll-behavior: smooth;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        }
        .glass-effect {
            background: rgba(30, 58, 138, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .bottom-nav-safe {
            padding-bottom: max(1rem, env(safe-area-inset-bottom, 0px));
        }

        /* Micro-animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(251, 191, 36, 0.5);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .section-divider {
            position: relative;
            margin: 2rem 0;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        /* Enhanced glassmorphism for bottom nav */
        .bottom-nav-glass {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Button hover effects */
        .btn-glow:hover {
            animation: pulse-glow 2s infinite;
        }
    </style>
</head>
<body class="bg-eni-dark text-white min-h-screen overflow-x-hidden">
    <!-- Header -->
    <header class="gradient-bg px-4 py-6 pb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-eni-yellow rounded-full flex items-center justify-center">
                    <span class="text-eni-dark font-bold text-lg">E</span>
                </div>
                <div>
                    <h1 class="text-white font-semibold text-lg">ENI Investment</h1>
                    <p class="text-blue-200 text-sm">Hi, {{ Auth::user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-white"></i>
                </button>
                <div class="w-10 h-10 bg-eni-yellow rounded-full flex items-center justify-center">
                    <span class="text-eni-dark font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="mt-6 bg-gradient-to-br from-eni-yellow to-yellow-500 rounded-2xl p-6 text-center relative overflow-hidden shadow-xl">
            <!-- Subtle glow effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-yellow-400/30 to-yellow-600/30 blur-lg"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <i class="fas fa-wallet text-eni-dark text-lg"></i>
                    <p class="text-eni-dark font-medium text-sm">Account Balance</p>
                </div>
                <h2 class="text-eni-dark text-4xl font-bold mb-4 drop-shadow-sm">${{ number_format($account_balance ?? 0, 2) }}</h2>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-center">
                    <button onclick="window.location.href='{{ route('user.packages') }}'" class="bg-eni-dark/10 hover:bg-eni-dark/20 text-eni-dark px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition-all duration-200 hover:scale-105">
                        <i class="fas fa-arrow-down text-sm"></i>
                        Invest
                    </button>
                    <button onclick="window.location.href='#'" class="bg-eni-dark/10 hover:bg-eni-dark/20 text-eni-dark px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition-all duration-200 hover:scale-105">
                        <i class="fas fa-arrow-up text-sm"></i>
                        Withdraw
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-4 py-6 pb-24">
        <div class="section-divider"></div>
        <div class="section-divider"></div>

        <!-- Franchise Section -->
        <div class="bg-gradient-to-br from-eni-gray to-slate-800 rounded-2xl p-6 mb-6 shadow-lg border border-gray-600/30 fade-in-up">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-eni-yellow to-yellow-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i class="fas fa-gas-pump text-eni-dark text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-bold text-lg mb-2 flex items-center gap-2">
                        🛢️ ENI Fuel Station Franchise
                        <span class="px-2 py-1 bg-eni-yellow/20 text-eni-yellow text-xs rounded-full">New</span>
                    </h3>
                    <p class="text-gray-300 text-sm mb-4 leading-relaxed">Open your own ENI fuel station. Join our network of service stations with proven business model and ongoing support.</p>
                    <button onclick="window.location.href='{{ route('user.franchise') }}'" class="bg-eni-yellow hover:bg-yellow-400 text-eni-dark px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-200 hover:scale-105 shadow-lg">
                        Learn More
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- Recent Activity -->
        <div class="mb-6 fade-in-up">
            <h3 class="text-white font-semibold text-lg mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($recent_transactions ?? [] as $transaction)
                <div class="bg-eni-gray rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($transaction->type === 'deposit')
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-500/20">
                                <i class="fas fa-arrow-down text-green-400"></i>
                            </div>
                        @elseif($transaction->type === 'withdrawal')
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-500/20">
                                <i class="fas fa-arrow-up text-red-400"></i>
                            </div>
                        @elseif($transaction->type === 'interest')
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-eni-yellow/20">
                                <i class="fas fa-percentage text-eni-yellow"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-500/20">
                                <i class="fas fa-exchange-alt text-blue-400"></i>
                            </div>
                        @endif
                        </div>
                        <div>
                            <p class="text-white font-medium capitalize">{{ str_replace('_', ' ', $transaction->type) }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-white font-semibold">
                                    @if($transaction->type === 'withdrawal')
                                        -${{ number_format($transaction->amount->toFloat(), 2) }}
                                    @else
                                        +${{ number_format($transaction->amount->toFloat(), 2) }}
                                    @endif
                                </span>
                                @if($transaction->status !== 'completed')
                                    @if($transaction->status === 'pending')
                                        <span class="px-2 py-1 rounded-full text-xs bg-yellow-500/20 text-yellow-400">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    @elseif($transaction->status === 'processing')
                                        <span class="px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-400">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="text-gray-400 text-sm">{{ $transaction->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="bg-eni-gray rounded-xl p-6 text-center">
                    <div class="w-16 h-16 bg-gray-600/50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-history text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-400">No recent activity</p>
                    <p class="text-gray-500 text-sm mt-1">Your transactions will appear here</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-3 mb-6 fade-in-up">
            <!-- Active Investments -->
            <div class="bg-gradient-to-br from-eni-gray to-slate-800 rounded-xl p-4 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-l-4 border-green-400">
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-green-400"></i>
                </div>
                <p class="text-gray-400 text-xs">Active</p>
                <p class="text-white text-lg font-bold">{{ $active_investments ?? 0 }}</p>
            </div>

            <!-- Total Invested -->
            <div class="bg-gradient-to-br from-eni-gray to-slate-800 rounded-xl p-4 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-l-4 border-blue-400">
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-dollar-sign text-blue-400"></i>
                </div>
                <p class="text-gray-400 text-xs">Invested</p>
                <p class="text-white text-lg font-bold">${{ number_format($total_invested ?? 0, 0) }}</p>
            </div>

            <!-- Referral Bonus -->
            <div class="bg-gradient-to-br from-eni-gray to-slate-800 rounded-xl p-4 text-center shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-l-4 border-orange-400">
                <div class="w-12 h-12 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-users text-orange-400"></i>
                </div>
                <p class="text-gray-400 text-xs">Referrals</p>
                <p class="text-white text-lg font-bold">${{ number_format($referral_bonus ?? 0, 0) }}</p>
            </div>
        </div>

        <!-- Premium Investment Packages Section -->
        <div class="bg-gradient-to-br from-eni-blue to-blue-900 rounded-2xl p-6 mb-6 relative overflow-hidden shadow-xl fade-in-up">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-eni-yellow rounded-lg flex items-center justify-center">
                        <i class="fas fa-gem text-eni-dark text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Premium Investment Packages</h3>
                        <p class="text-blue-200 text-sm">Higher returns, exclusive benefits</p>
                    </div>
                </div>
                <p class="text-blue-100 text-sm mb-4">Unlock premium investment opportunities with higher daily returns and priority support.</p>
                <button onclick="window.location.href='{{ route('user.packages') }}'" class="bg-eni-yellow hover:bg-yellow-400 text-eni-dark px-6 py-3 rounded-xl font-semibold transition-colors duration-200 flex items-center gap-2">
                    <i class="fas fa-rocket text-sm"></i>
                    View Packages
                </button>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bottom-nav-glass bottom-nav-safe shadow-2xl">
        <div class="flex justify-around py-3 px-2">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('dashboard') ? 'fas' : 'far' }} fa-home text-lg"></i>
                <span class="text-xs font-medium">Home</span>
            </a>
            <a href="{{ route('user.packages') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('user.packages') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('user.packages') ? 'fas' : 'far' }} fa-chart-line text-lg"></i>
                <span class="text-xs font-medium">Invest</span>
            </a>
            <a href="{{ route('user.referrals') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('user.referrals') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('user.referrals') ? 'fas' : 'far' }} fa-users text-lg"></i>
                <span class="text-xs font-medium">Refer</span>
            </a>
            <a href="{{ route('user.franchise') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('user.franchise') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('user.franchise') ? 'fas' : 'far' }} fa-gas-pump text-lg"></i>
                <span class="text-xs font-medium">Fuel</span>
            </a>
            <a href="{{ route('dashboard.transfer') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard.transfer') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('dashboard.transfer') ? 'fas' : 'far' }} fa-exchange-alt text-lg"></i>
                <span class="text-xs font-medium">Send</span>
            </a>
            <a href="{{ route('user.transactions') }}" class="flex flex-col items-center gap-1 px-3 py-2 rounded-xl transition-all duration-200 {{ request()->routeIs('user.transactions') ? 'text-eni-yellow bg-eni-yellow/10' : 'text-gray-400 hover:text-white' }}">
                <i class="{{ request()->routeIs('user.transactions') ? 'fas' : 'far' }} fa-history text-lg"></i>
                <span class="text-xs font-medium">History</span>
            </a>
        </div>
    </nav>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
