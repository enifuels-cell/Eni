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
        body { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bottom-nav-safe {
            padding-bottom: max(1rem, env(safe-area-inset-bottom, 0px));
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
        <div class="mt-6 glass-effect rounded-2xl p-6 text-center">
            <p class="text-blue-200 text-sm mb-2">Total Balance</p>
            <h2 class="text-white text-3xl font-bold mb-4">${{ number_format($account_balance ?? 0, 2) }}</h2>
            <button onclick="window.location.href='{{ route('user.packages') }}'" class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-xl font-semibold">
                Browse Packages
            </button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-4 py-6 pb-24">
        <!-- Franchise Section -->
        <div class="bg-eni-gray rounded-2xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-eni-yellow rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gas-pump text-eni-dark text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-lg mb-2">🛢️ ENI Fuel Station Franchise</h3>
                    <p class="text-gray-400 text-sm mb-4">Open your own ENI fuel station. Join our network of service stations with proven business model and ongoing support.</p>
                    <button onclick="window.location.href='{{ route('user.franchise') }}'" class="bg-eni-yellow text-eni-dark px-4 py-2 rounded-lg font-medium">
                        Learn More
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mb-6">
            <h3 class="text-white font-semibold text-lg mb-4">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($recent_transactions ?? [] as $transaction)
                <div class="bg-eni-gray rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($transaction->type === 'deposit') bg-green-500/20
                            @elseif($transaction->type === 'withdrawal') bg-red-500/20
                            @elseif($transaction->type === 'interest') bg-eni-yellow/20
                            @else bg-blue-500/20 @endif">
                            @if($transaction->type === 'deposit')
                                <i class="fas fa-arrow-down text-green-400"></i>
                            @elseif($transaction->type === 'withdrawal')
                                <i class="fas fa-arrow-up text-red-400"></i>
                            @elseif($transaction->type === 'interest')
                                <i class="fas fa-percentage text-eni-yellow"></i>
                            @else
                                <i class="fas fa-exchange-alt text-blue-400"></i>
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
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($transaction->status === 'pending') bg-yellow-500/20 text-yellow-400
                                        @elseif($transaction->status === 'processing') bg-blue-500/20 text-blue-400
                                        @else bg-red-500/20 text-red-400 @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
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
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-eni-gray rounded-xl p-4 text-center">
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-green-400"></i>
                </div>
                <p class="text-gray-400 text-sm">Active Investments</p>
                <p class="text-white text-xl font-bold">{{ $active_investments ?? 0 }}</p>
            </div>
            <div class="bg-eni-gray rounded-xl p-4 text-center">
                <div class="w-12 h-12 bg-eni-yellow/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-coins text-eni-yellow"></i>
                </div>
                <p class="text-gray-400 text-sm">Total Interest</p>
                <p class="text-white text-xl font-bold">${{ number_format($total_interest ?? 0, 2) }}</p>
            </div>
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-eni-gray border-t border-gray-600 bottom-nav-safe">
        <div class="flex justify-around py-3">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-home text-xl"></i>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ route('user.packages') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('user.packages') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-chart-line text-xl"></i>
                <span class="text-xs">Invest</span>
            </a>
            <a href="{{ route('user.referrals') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('user.referrals') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-users text-xl"></i>
                <span class="text-xs">Refer</span>
            </a>
            <a href="{{ route('user.franchise') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('user.franchise') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-gas-pump text-xl"></i>
                <span class="text-xs">Fuel</span>
            </a>
            <a href="{{ route('dashboard.transfer') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard.transfer') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-exchange-alt text-xl"></i>
                <span class="text-xs">Send</span>
            </a>
            <a href="{{ route('user.transactions') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('user.transactions') ? 'text-eni-yellow' : 'text-gray-400' }}">
                <i class="fas fa-history text-xl"></i>
                <span class="text-xs">History</span>
            </a>
        </div>
    </nav>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
