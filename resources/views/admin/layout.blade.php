<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - ENI Investment Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --eni-yellow: #FFCD00;
            --eni-dark: #0B2241;
            --eni-charcoal: #121417;
            --eni-dark-blue: #1a1a2e;
        }
        
        body {
            font-family: 'Inter', ui-sans-serif, system-ui;
            background: linear-gradient(135deg, var(--eni-charcoal) 0%, var(--eni-dark) 100%);
            min-height: 100vh;
        }
        
        .eni-gradient {
            background: linear-gradient(135deg, var(--eni-dark) 0%, var(--eni-charcoal) 100%);
        }
        
        .eni-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .eni-button {
            background: var(--eni-yellow);
            color: var(--eni-dark);
            transition: all 0.3s ease;
        }
        
        .eni-button:hover {
            background: #e6b800;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 205, 0, 0.3);
        }
        
        .dropdown-enter {
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .dropdown-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.2s ease;
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--eni-yellow);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-yellow': '#FFCD00',
                        'eni-dark': '#0B2241', 
                        'eni-charcoal': '#121417',
                        'eni-dark-blue': '#1a1a2e'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-eni-charcoal text-white font-sans">
    <!-- Admin Navigation -->
    <nav class="eni-gradient border-b border-white/10 sticky top-0 z-50 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo">
                        <div class="ml-3">
                            <span class="text-eni-yellow font-bold text-xl">ENI</span>
                            <span class="text-white/80 font-medium text-sm ml-2">Admin Panel</span>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:ml-8 md:flex md:space-x-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active text-eni-yellow' : 'text-white/70 hover:text-white' }} px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        
                        <!-- Financial Management Dropdown -->
                        <div class="relative group">
                            <button class="nav-link text-white/70 hover:text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-dollar-sign mr-2"></i>Financial 
                                <i class="fas fa-chevron-down ml-2 text-xs transform group-hover:rotate-180 transition-transform"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-64 eni-card rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="p-2">
                                    <a href="{{ route('admin.deposits.pending') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-clock mr-3 text-orange-400"></i>Pending Deposits
                                    </a>
                                    <a href="{{ route('admin.deposits.approved') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-check mr-3 text-green-400"></i>Approved Deposits
                                    </a>
                                    <a href="{{ route('admin.withdrawals.pending') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-arrow-up mr-3 text-blue-400"></i>Pending Withdrawals
                                    </a>
                                    <a href="{{ route('admin.withdrawals.approved') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-check-circle mr-3 text-green-400"></i>Approved Withdrawals
                                    </a>
                                    <a href="{{ route('admin.request-funds.pending') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-hand-holding-usd mr-3 text-purple-400"></i>Request Funds
                                    </a>
                                    <a href="{{ route('admin.interest.daily') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-chart-line mr-3 text-eni-yellow"></i>Daily Interest Log
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Management -->
                        <a href="{{ route('admin.users.manage') }}" 
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active text-eni-yellow' : 'text-white/70 hover:text-white' }} px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-all">
                            <i class="fas fa-users mr-2"></i>Users
                        </a>
                        
                        <!-- Platform Management Dropdown -->
                        <div class="relative group">
                            <button class="nav-link text-white/70 hover:text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-all">
                                <i class="fas fa-cogs mr-2"></i>Platform 
                                <i class="fas fa-chevron-down ml-2 text-xs transform group-hover:rotate-180 transition-transform"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-64 eni-card rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="p-2">
                                    <a href="{{ route('admin.packages.slots') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-box mr-3 text-cyan-400"></i>Package Slots
                                    </a>
                                    <a href="{{ route('admin.franchise.applications') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-handshake mr-3 text-indigo-400"></i>Franchise Apps
                                    </a>
                                    <a href="{{ route('admin.transfer-funds.index') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-exchange-alt mr-3 text-pink-400"></i>Transfer Funds
                                    </a>
                                    <a href="{{ route('admin.activation-fund.index') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-power-off mr-3 text-red-400"></i>Activation Fund
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Admin Badge -->
                    <div class="hidden md:flex items-center space-x-2 bg-white/5 rounded-lg px-3 py-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white/80 text-sm font-medium">
                            {{ Auth::user()->name }}
                        </span>
                        <span class="text-eni-yellow text-xs font-semibold bg-eni-yellow/20 px-2 py-1 rounded-full">
                            ADMIN
                        </span>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="eni-card hover:bg-white/10 text-white p-3 rounded-xl transition-all duration-300 hover:shadow-lg">
                            <i class="fas fa-user-shield text-eni-yellow"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 eni-card rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="p-2">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-white/80 hover:text-eni-yellow hover:bg-white/5 rounded-lg transition-all">
                                    <i class="fas fa-user-circle mr-3"></i>User Dashboard
                                </a>
                                <div class="border-t border-white/10 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-white/80 hover:text-red-400 hover:bg-white/5 rounded-lg transition-all">
                                        <i class="fas fa-sign-out-alt mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-8 px-6">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 eni-card border-l-4 border-green-400 bg-green-400/10 text-green-300 px-6 py-4 rounded-lg relative">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button class="absolute top-4 right-4 text-green-300/60 hover:text-green-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 eni-card border-l-4 border-red-400 bg-red-400/10 text-red-300 px-6 py-4 rounded-lg relative">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button class="absolute top-4 right-4 text-red-300/60 hover:text-red-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 eni-card border-l-4 border-red-400 bg-red-400/10 text-red-300 px-6 py-4 rounded-lg relative">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-400 mt-1"></i>
                    <div>
                        <p class="font-medium mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button class="absolute top-4 right-4 text-red-300/60 hover:text-red-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="eni-gradient border-t border-white/10 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="text-white/60 text-sm">
                        © 2025 <span class="text-eni-yellow font-semibold">ENI Investment Platform</span> - Admin Panel
                    </div>
                </div>
                <div class="flex items-center space-x-6 text-sm text-white/60">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-shield text-eni-yellow"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-globe text-blue-400"></i>
                        <span>{{ request()->ip() }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-green-400"></i>
                        <span>{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="border-green-400"], [class*="border-red-400"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Mobile menu toggle (if needed)
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
