<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Eni Members</title>
    <meta name="theme-color" content="#FFCD00" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
                    colors: {
                        eni: {
                            yellow: '#FFCD00',
                            dark: '#0B2241', 
                            charcoal: '#121417'
                        }
                    },
                    boxShadow: {
                        glow: '0 0 20px rgba(255,205,0,0.45)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui; }
    </style>
</head>
<body class="bg-eni-charcoal font-sans min-h-screen text-white">
    <!-- Admin Navigation -->
    <nav class="bg-eni-dark border-b border-eni-yellow/20 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo">
                        <span class="ml-3 text-eni-yellow font-bold text-lg">Admin Panel</span>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="border-transparent text-gray-300 hover:text-eni-yellow hover:border-eni-yellow/50 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-eni-yellow text-eni-yellow' : '' }}">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        
                        <!-- Financial Management Dropdown -->
                        <div class="relative group">
                            <button class="border-transparent text-gray-300 hover:text-eni-yellow hover:border-eni-yellow/50 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                <i class="fas fa-dollar-sign mr-2"></i>Financial <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-eni-dark rounded-md shadow-lg border border-eni-yellow/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.deposits.pending') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-clock mr-2"></i>Pending Deposits
                                    </a>
                                    <a href="{{ route('admin.deposits.approved') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-check mr-2"></i>Approved Deposits
                                    </a>
                                    <a href="{{ route('admin.withdrawals.pending') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-arrow-up mr-2"></i>Pending Withdrawals
                                    </a>
                                    <a href="{{ route('admin.withdrawals.approved') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-check-circle mr-2"></i>Approved Withdrawals
                                    </a>
                                    <a href="{{ route('admin.request-funds.pending') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-hand-holding-usd mr-2"></i>Request Funds
                                    </a>
                                    <a href="{{ route('admin.interest.daily') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-chart-line mr-2"></i>Daily Interest Log
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Management -->
                        <a href="{{ route('admin.users.manage') }}" 
                           class="border-transparent text-gray-300 hover:text-eni-yellow hover:border-eni-yellow/50 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'border-eni-yellow text-eni-yellow' : '' }}">
                            <i class="fas fa-users mr-2"></i>Users
                        </a>
                        
                        <!-- Platform Management Dropdown -->
                        <div class="relative group">
                            <button class="border-transparent text-gray-300 hover:text-eni-yellow hover:border-eni-yellow/50 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                <i class="fas fa-cogs mr-2"></i>Platform <i class="fas fa-chevron-down ml-1"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-56 bg-eni-dark rounded-md shadow-lg border border-eni-yellow/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    <a href="{{ route('admin.packages.slots') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-box mr-2"></i>Package Slots
                                    </a>
                                    <a href="{{ route('admin.franchise.applications') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-handshake mr-2"></i>Franchise Apps
                                    </a>
                                    <a href="{{ route('admin.transfer-funds.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-exchange-alt mr-2"></i>Transfer Funds
                                    </a>
                                    <a href="{{ route('admin.activation-fund.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-power-off mr-2"></i>Activation Fund
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin User Menu -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-gray-300 text-sm mr-4">
                            <i class="fas fa-shield-alt mr-1 text-eni-yellow"></i>Admin: {{ Auth::user()->name }}
                        </span>
                    </div>
                    
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="bg-eni-yellow/20 border border-eni-yellow/30 text-eni-yellow p-2 rounded-full hover:bg-eni-yellow hover:text-eni-dark transition-colors">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-eni-dark border border-eni-yellow/20 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                    <i class="fas fa-user-circle mr-2"></i>User Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-eni-yellow/10 hover:text-eni-yellow transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
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
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-900/20 border border-green-500/50 text-green-400 px-4 py-3 rounded relative backdrop-blur-sm">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer hover:text-green-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-900/20 border border-red-500/50 text-red-400 px-4 py-3 rounded relative backdrop-blur-sm">
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer hover:text-red-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-900/20 border border-red-500/50 text-red-400 px-4 py-3 rounded relative backdrop-blur-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer hover:text-red-300" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-eni-dark border-t border-eni-yellow/20 text-gray-300 mt-12">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="text-sm">
                    © 2025 <span class="text-eni-yellow font-semibold">Eni Members</span> - Admin Panel
                </div>
                <div class="text-sm">
                    Session: <span class="text-eni-yellow">{{ Auth::user()->name }}</span> | IP: {{ request()->ip() }}
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
