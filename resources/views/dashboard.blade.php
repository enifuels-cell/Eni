<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ENI Dashboard — Investment Platform</title>
  <meta name="theme-color" content="#FFCD00" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
  <style> body{font-family:Inter,ui-sans-serif,system-ui} </style>
</head>
<body class="bg-eni-charcoal min-h-screen text-white flex flex-col">
  <!-- Header -->
  <header class="bg-eni-dark px-6 py-4 flex items-center justify-between shadow-md">
    <div class="flex items-center gap-4">
      <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
      <div>
        <h1 class="font-extrabold text-xl tracking-tight">ENI Investment</h1>
        <p class="text-sm text-white/70">Welcome back, {{ Auth::user()->name }}</p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <button onclick="toggleNotifications()" class="relative p-2 rounded-full hover:bg-white/10" aria-label="notifications">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M8.25 21a3.75 3.75 0 0 0 7.5 0h-7.5ZM4.5 8.25A7.5 7.5 0 0 1 12 3a7.5 7.5 0 0 1 7.5 5.25v4.178l.932 2.8a1.125 1.125 0 0 1-1.068 1.472H4.636a1.125 1.125 0 0 1-1.068-1.472l.932-2.8V8.25Z"/></svg>
        <span class="absolute top-1 right-1 w-2 h-2 bg-eni-yellow rounded-full"></span>
      </button>
      <div class="relative">
        <button onclick="toggleProfileMenu()" class="block hover:opacity-80 transition-opacity relative">
          <img src="https://dummyimage.com/40x40/FFCD00/000000&text={{ substr(Auth::user()->name, 0, 1) }}" alt="user avatar" class="w-10 h-10 rounded-full border-2 border-eni-yellow cursor-pointer"/>
          <div class="absolute top-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-eni-charcoal"></div>
        </button>
        
        <!-- Profile Dropdown Menu -->
        <div id="profileMenu" class="absolute right-0 top-full mt-2 w-64 bg-eni-dark border border-white/20 rounded-lg shadow-lg z-50 hidden">
          <div class="p-4 border-b border-white/10">
            <p class="font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-sm text-white/60">{{ Auth::user()->email }}</p>
          </div>
          <div class="py-2">
            <a href="{{ route('profile.edit') }}#personal" class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors">
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
              </div>
            </a>
            <a href="{{ route('profile.edit') }}#bank" class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors">
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Bank Details for Withdrawals
              </div>
            </a>
            <a href="{{ route('profile.edit') }}#account" class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors">
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Account Information
              </div>
            </a>
            <a href="{{ route('profile.edit') }}#password" class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors">
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Change Password
              </div>
            </a>
          </div>
          <div class="border-t border-white/10 py-2">
            <form method="POST" action="{{ route('logout') }}" class="block">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm hover:bg-white/10 transition-colors text-red-400">
                <div class="flex items-center gap-3">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  Logout
                </div>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Balance -->
  <section class="bg-gradient-to-br from-eni-yellow to-yellow-400 text-eni-dark rounded-b-3xl p-6 shadow-glow">
    <h2 class="text-sm uppercase font-bold tracking-wide">Account Balance</h2>
    <p class="text-4xl font-extrabold mt-1">${{ number_format($account_balance ?? 0, 2) }}</p>
    <p class="text-sm text-eni-dark/70">
      @if(($total_interest ?? 0) > 0)
        + ${{ number_format($total_interest, 2) }} Interest Earned
      @else
        Ready to start investing
      @endif
    </p>
    <div class="flex gap-4 mt-6">
      <button onclick="window.location.href='{{ route('dashboard.packages') }}'" class="flex-1 bg-eni-dark text-white font-bold py-3 rounded-xl hover:bg-black/80">Invest</button>
      <button onclick="window.location.href='{{ route('dashboard.withdraw') }}'" class="flex-1 bg-white text-eni-dark font-bold py-3 rounded-xl hover:bg-gray-100">Withdraw</button>
    </div>
  </section>

  <!-- Circular KPI Cards -->
  <section class="grid grid-cols-3 gap-4 px-6 mt-6">
    <div class="bg-white/5 rounded-2xl p-4 text-center">
      <div class="mx-auto w-16 h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center font-extrabold text-sm">
        {{ ($active_investments ?? 0) }}
      </div>
      <p class="text-xs mt-2 text-white/70">Active Investments</p>
    </div>
    <div class="bg-white/5 rounded-2xl p-4 text-center">
      <div class="mx-auto w-16 h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center font-extrabold text-sm">
        ${{ number_format($total_invested ?? 0, 0) }}
      </div>
      <p class="text-xs mt-2 text-white/70">Total Invested</p>
    </div>
    <div class="bg-white/5 rounded-2xl p-4 text-center">
      <div class="mx-auto w-16 h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center font-extrabold text-sm">
        ${{ number_format($total_referral_bonus ?? 0, 0) }}
      </div>
      <p class="text-xs mt-2 text-white/70">Referral Bonus</p>
    </div>
  </section>

  <!-- Investment Packages -->
  <section class="px-6 mt-6">
    <div class="rounded-2xl overflow-hidden bg-gradient-to-r from-eni-dark to-eni-yellow text-white shadow-md">
      <div class="p-6">
        <h3 class="font-bold text-lg">Premium Investment Packages</h3>
        <p class="text-sm mt-1 text-white/80">Earn guaranteed daily returns with our secure investment options.</p>
        <button onclick="window.location.href='{{ route('dashboard.packages') }}'" class="mt-3 bg-white text-eni-dark px-4 py-2 rounded-lg font-semibold">Browse Packages</button>
      </div>
    </div>
  </section>

  <!-- Activity Timeline -->
  <section class="px-6 mt-6 flex-1 overflow-y-auto mb-20">
    <h4 class="font-bold text-lg mb-3">Recent Activity</h4>
    <ul class="space-y-3">
      @forelse($recent_transactions ?? [] as $transaction)
      <li class="flex items-center justify-between bg-white/5 p-4 rounded-xl">
        <div>
          <span class="capitalize">{{ str_replace('_', ' ', $transaction->type) }} — ${{ number_format($transaction->amount, 2) }}</span>
          @if($transaction->status !== 'completed')
            <span class="ml-2 text-xs bg-yellow-600 px-2 py-1 rounded">{{ ucfirst($transaction->status) }}</span>
          @endif
        </div>
        <span class="text-xs {{ $transaction->type === 'withdrawal' ? 'text-red-400' : 'text-green-400' }}">
          {{ $transaction->created_at->diffForHumans() }}
        </span>
      </li>
      @empty
      <li class="flex items-center justify-center bg-white/5 p-4 rounded-xl text-white/50">
        No recent transactions
      </li>
      @endforelse
    </ul>
  </section>

  <!-- Floating Navigation -->
  <nav class="fixed bottom-4 inset-x-0 flex justify-center">
    <div class="bg-eni-dark/90 backdrop-blur px-6 py-3 rounded-2xl flex gap-8 text-sm font-medium">
      <a href="{{ route('dashboard') }}" class="text-eni-yellow">Dashboard</a>
      <a href="{{ route('dashboard.packages') }}" class="text-white/70 hover:text-white">Invest</a>
      <a href="{{ route('dashboard.referrals') }}" class="text-white/70 hover:text-white">Referrals</a>
      <a href="{{ route('dashboard.transfer') }}" class="text-white/70 hover:text-white">Transfer</a>
      <a href="{{ route('dashboard.transactions') }}" class="text-white/70 hover:text-white">History</a>
    </div>
  </nav>

  <!-- Notifications Panel -->
  <div id="notificationsPanel" class="fixed top-16 right-6 w-80 bg-eni-dark rounded-2xl shadow-xl border border-white/10 z-50 hidden">
    <div class="p-6">
      <h3 class="font-bold text-lg text-eni-yellow mb-4">Notifications</h3>
      
      <div class="space-y-4">
        <div class="bg-white/10 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <div class="w-2 h-2 bg-eni-yellow rounded-full mt-2"></div>
            <div>
              <p class="font-semibold text-sm">Welcome to ENI!</p>
              <p class="text-white/70 text-xs mt-1">Complete your profile to get started with investments.</p>
              <p class="text-white/50 text-xs mt-2">Just now</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white/10 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <div class="w-2 h-2 bg-green-400 rounded-full mt-2"></div>
            <div>
              <p class="font-semibold text-sm">Account Verified</p>
              <p class="text-white/70 text-xs mt-1">Your account has been successfully verified.</p>
              <p class="text-white/50 text-xs mt-2">2 hours ago</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white/10 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
            <div>
              <p class="font-semibold text-sm">New Investment Packages</p>
              <p class="text-white/70 text-xs mt-1">Check out our latest high-yield investment options.</p>
              <p class="text-white/50 text-xs mt-2">1 day ago</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="mt-4 pt-4 border-t border-white/10">
        <button class="text-eni-yellow text-sm hover:underline">View All Notifications</button>
      </div>
    </div>
  </div>

  <script>
    function toggleNotifications() {
      const panel = document.getElementById('notificationsPanel');
      panel.classList.toggle('hidden');
    }

    function toggleProfileMenu() {
      const menu = document.getElementById('profileMenu');
      menu.classList.toggle('hidden');
    }

    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
      const panel = document.getElementById('notificationsPanel');
      const button = event.target.closest('[aria-label="notifications"]');
      
      if (!panel.contains(event.target) && !button) {
        panel.classList.add('hidden');
      }
    });
    
    // Close profile menu when clicking outside
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('profileMenu');
      const button = event.target.closest('button[onclick="toggleProfileMenu()"]');
      
      if (!button && !menu.contains(event.target)) {
        menu.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
