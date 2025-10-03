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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
  <style>
    body{font-family:Inter,ui-sans-serif,system-ui}

    /* Mobile safe area considerations */
    @media (max-width: 640px) {
      #floatingNav {
        bottom: max(1rem, env(safe-area-inset-bottom, 0px));
      }
    }

    /* Ensure floating nav doesn't cause horizontal scroll */
    #floatingNav > div {
      max-width: calc(100vw - 2rem);
    }
  </style>
</head>
<body class="bg-eni-charcoal min-h-screen text-white flex flex-col pb-20 sm:pb-8">
  <!-- Header -->
  <header class="bg-eni-dark px-6 py-4 flex items-center justify-between shadow-md">
    <div class="flex items-center gap-4">
      <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
      <div>
        <h1 class="font-extrabold text-xl tracking-tight">Eni Members</h1>
        <p class="text-sm text-white/70">Welcome back, {{ Auth::user()->name }}</p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="relative">
        <button onclick="toggleNotifications()" class="relative p-2 rounded-full hover:bg-white/10" aria-label="notifications">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white"><path d="M8.25 21a3.75 3.75 0 0 0 7.5 0h-7.5ZM4.5 8.25A7.5 7.5 0 0 1 12 3a7.5 7.5 0 0 1 7.5 5.25v4.178l.932 2.8a1.125 1.125 0 0 1-1.068 1.472H4.636a1.125 1.125 0 0 1-1.068-1.472l.932-2.8V8.25Z"/></svg>
          @php
            $totalUnread = $unread_notifications_count + (!Auth::user()->pin_hash ? 1 : 0);
          @endphp
          @if($totalUnread > 0)
            @if($totalUnread > 9)
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold animate-pulse">9+</span>
            @else
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold animate-pulse">{{ $totalUnread }}</span>
            @endif
          @endif
        </button>
      </div>
      <div class="relative">
        <button onclick="toggleProfileMenu()" class="block hover:opacity-80 transition-opacity relative">
          <img src="https://dummyimage.com/40x40/FFCD00/000000&text={{ substr(Auth::user()->name, 0, 1) }}" alt="user avatar" class="w-10 h-10 rounded-full border-2 border-eni-yellow cursor-pointer"/>
          <div class="absolute top-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-eni-charcoal"></div>
        </button>

        <!-- Profile Dropdown Menu -->
        <div id="profileMenu" class="absolute right-0 top-full mt-2 w-64 bg-eni-dark border border-white/20 rounded-lg shadow-lg z-50 hidden">
          <div class="p-4 border-b border-white/10">
            <p class="font-semibold">{{ Auth::user()->name }}</p>
            <p class="text-sm text-white/60">ID: {{ Auth::user()->account_id }}</p>
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
            <a href="/pin-setup" class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors">
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                PIN Settings
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
    <p class="text-4xl font-extrabold mt-1">$@money($account_balance ?? 0)</p>
    <p class="text-sm text-eni-dark/70">
      @if(($total_interest ?? 0) > 0)
        + $@money($total_interest) Interest Earned
      @else
        Ready to start investing
      @endif
    </p>
    <div class="flex gap-4 mt-6">
      <button onclick="window.location.href='{{ route("dashboard.packages") }}'" class="flex-1 bg-eni-dark text-white font-bold py-3 rounded-xl hover:bg-black/80">Invest</button>
      <button onclick="window.location.href='{{ route("dashboard.withdraw") }}'" class="flex-1 bg-white text-eni-dark font-bold py-3 rounded-xl hover:bg-gray-100">Withdraw</button>
    </div>
  </section>

  <!-- Circular KPI Cards -->
  <section class="grid grid-cols-3 gap-4 px-6 mt-6">
    <div class="bg-white/5 rounded-2xl p-4 text-center">
      <div class="mx-auto w-16 h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center font-extrabold text-lg">
        {{ ($active_investments ?? 0) }}
      </div>
      <p class="text-xs mt-2 text-white/70">Active Investments</p>
    </div>
            <!-- Total Invested (Yellow Background) -->
        <div class="bg-eni-yellow rounded-2xl p-4 md:p-6 flex flex-col items-center justify-center">
            <div class="text-center">
                <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-900 break-words">${{ number_format($total_invested, 2) }}</p>
                <p class="text-xs sm:text-sm font-medium text-blue-900 mt-1 md:mt-2">Total Invested</p>
            </div>
        </div>
    <div class="bg-white/5 rounded-2xl p-4 text-center">
      <div class="mx-auto w-16 h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center">
        <i class="fas fa-gift text-eni-yellow text-2xl"></i>
      </div>
      <p class="text-lg font-bold mt-2 text-eni-yellow">$@money($total_referral_bonus ?? 0)</p>
      <p class="text-xs text-white/70">Referral Bonus</p>
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

  <!-- Franchise Opportunity -->
  <section class="px-6 mt-6">
    <div class="rounded-2xl overflow-hidden bg-gradient-to-r from-gray-800 to-eni-dark border border-eni-yellow/20 text-white shadow-md">
      <div class="p-6">
        <div class="flex items-center mb-2">
          <i class="fas fa-gas-pump text-2xl mr-3 text-eni-yellow"></i>
          <h3 class="font-bold text-lg">ENI Fuel Station Franchise</h3>
        </div>
        <p class="text-sm mt-1 text-white/80">Open your own ENI fuel station. Join our network of service stations with proven business model and ongoing support.</p>
        <div class="mt-4">
          <button onclick="window.location.href='{{ route('dashboard.franchise') }}'" class="bg-eni-yellow text-eni-dark px-4 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">Learn More</button>
        </div>
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
          <span class="capitalize">{{ str_replace('_', ' ', $transaction->type) }} — $@money($transaction->amount)</span>
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
  <nav id="floatingNav" class="fixed bottom-4 sm:bottom-6 inset-x-4 sm:inset-x-0 flex justify-center z-40 transform transition-transform duration-300 ease-in-out" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
    <div class="bg-eni-dark/95 backdrop-blur border border-eni-yellow/20 shadow-lg shadow-black/25 px-3 sm:px-6 py-3 rounded-2xl flex gap-2 sm:gap-6 text-xs sm:text-sm font-medium overflow-x-auto max-w-full">
      <a href="{{ route('dashboard') }}" class="text-eni-yellow flex items-center gap-1 hover:text-yellow-300 hover:bg-white/10 active:bg-white/20 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-home text-xs"></i>
        <span class="text-xs font-medium">Home</span>
      </a>
      <a href="{{ route('dashboard.packages') }}" class="text-white/70 hover:text-white hover:bg-white/10 active:bg-white/20 flex items-center gap-1 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-chart-line text-xs"></i>
        <span class="text-xs font-medium">Invest</span>
      </a>
      <a href="{{ route('dashboard.referrals') }}" class="text-white/70 hover:text-white hover:bg-white/10 active:bg-white/20 flex items-center gap-1 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-users text-xs"></i>
        <span class="text-xs font-medium">Refer</span>
      </a>
      <a href="{{ route('dashboard.franchise') }}" class="text-white/70 hover:text-white hover:bg-white/10 active:bg-white/20 flex items-center gap-1 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-gas-pump text-xs"></i>
        <span class="text-xs font-medium">Fuel</span>
      </a>
      <a href="{{ route('dashboard.transfer') }}" class="text-white/70 hover:text-white hover:bg-white/10 active:bg-white/20 flex items-center gap-1 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-exchange-alt text-xs"></i>
        <span class="text-xs font-medium">Send</span>
      </a>
      <a href="{{ route('dashboard.transactions') }}" class="text-white/70 hover:text-white hover:bg-white/10 active:bg-white/20 flex items-center gap-1 transition-all duration-200 whitespace-nowrap py-2 px-3 rounded-lg min-w-0 touch-manipulation">
        <i class="fas fa-history text-xs"></i>
        <span class="text-xs font-medium">History</span>
      </a>
    </div>
  </nav>

  <!-- Notifications Panel -->
  <div id="notificationsPanel" class="fixed top-16 right-6 w-80 bg-eni-dark rounded-2xl shadow-xl border border-white/10 z-50 hidden">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-lg text-eni-yellow">Notifications</h3>
        @if($unread_notifications_count > 0)
          <span class="px-2 py-1 bg-eni-yellow/20 text-eni-yellow text-xs rounded-full">
            {{ $unread_notifications_count }} unread
          </span>
        @endif
      </div>

      <div class="space-y-3">
        @if(!Auth::user()->pin_hash)
        <!-- PIN Setup Notification -->
        <div class="bg-eni-dark/50 border border-eni-yellow/30 rounded-lg p-4 cursor-pointer hover:bg-eni-dark/70 transition-all duration-200" onclick="window.location.href='{{ route('pin.setup.form') }}'">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-eni-yellow/20 border border-eni-yellow/40 rounded-full flex items-center justify-center">
              <i class="fas fa-shield-alt text-eni-yellow text-xs"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <p class="font-semibold text-sm text-white">Set Up PIN Login</p>
                <i class="fas fa-chevron-right text-white/40 text-xs"></i>
              </div>
              <p class="text-white/70 text-xs mt-1">{{ \Illuminate\Support\Str::limit('Enable 4-digit PIN for quick and secure login on this device.', 60) }}</p>
              <div class="flex items-center gap-2 mt-2">
                <div class="w-1 h-1 bg-eni-yellow rounded-full animate-pulse"></div>
                <span class="text-white/50 text-xs">Action required</span>
              </div>
            </div>
          </div>
        </div>
        @endif

        <!-- Raffle Notification -->
        <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-lg p-4 cursor-pointer hover:bg-eni-yellow/10 transition-all duration-200" onclick="document.getElementById('attendance-modal')?.classList.remove('hidden')">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-eni-yellow/20 border border-eni-yellow/40 rounded-full flex items-center justify-center">
              <i class="fas fa-trophy text-eni-yellow text-xs"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <p class="font-semibold text-sm text-white">🏆 iPhone Air Raffle Active!</p>
                <div class="w-2 h-2 bg-eni-yellow rounded-full animate-pulse flex-shrink-0"></div>
              </div>
              <p class="text-white/70 text-xs mt-1">Login daily to earn raffle tickets and win the iPhone Air this month!</p>
              <div class="flex items-center gap-2 mt-2">
                <div class="w-1 h-1 bg-eni-yellow rounded-full animate-pulse"></div>
                <span class="text-eni-yellow text-xs font-medium">{{ $currentMonthTickets ?? 0 }} tickets earned</span>
              </div>
            </div>
          </div>
        </div>

        @forelse($recent_notifications as $notification)
        <div class="bg-white/5 border border-white/10 rounded-lg p-4 cursor-pointer hover:bg-white/10 transition-all duration-200" onclick="window.location.href='{{ route('user.notifications') }}'">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-white/10 border border-white/20 rounded-full flex items-center justify-center">
              <i class="{{ $notification->icon }} text-eni-yellow text-xs"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <p class="font-semibold text-sm text-white truncate">{{ $notification->title }}</p>
                @if(!$notification->is_read)
                  <div class="w-2 h-2 bg-eni-yellow rounded-full flex-shrink-0"></div>
                @endif
              </div>
              <p class="text-white/70 text-xs mt-1">{!! \Illuminate\Support\Str::limit($notification->message, 80) !!}</p>
              <div class="flex items-center justify-between mt-2">
                <span class="text-white/50 text-xs">{{ $notification->created_at->diffForHumans() }}</span>
                <span class="text-white/40 text-xs capitalize">{{ $notification->category }}</span>
              </div>
            </div>
          </div>
        </div>
        @empty
        <div class="text-center py-6">
          <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-bell text-white/40"></i>
          </div>
          <p class="text-white/60 text-sm">No notifications yet</p>
          <p class="text-white/40 text-xs mt-1">We'll notify you when something important happens</p>
        </div>
        @endforelse
      </div>

      <div class="mt-4 pt-4 border-t border-white/10">
        <a href="{{ route('user.notifications') }}" class="text-eni-yellow text-sm hover:underline flex items-center justify-between">
          <span>View All Notifications</span>
          <i class="fas fa-arrow-right text-xs"></i>
        </a>
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

    // Mark notification as read when clicked (for quick dropdown actions)
    async function markNotificationAsRead(notificationId) {
      try {
        await fetch(`{{ route("user.notifications.mark-read", ":id") }}`.replace(':id', notificationId), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
      } catch (error) {
        console.error('Error marking notification as read:', error);
      }
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

    // Auto-hide floating navigation on scroll
    let lastScrollTop = 0;
    let scrollThreshold = 10; // Minimum scroll distance to trigger hide/show
    let isScrolling = false;

    window.addEventListener('scroll', function() {
      if (!isScrolling) {
        window.requestAnimationFrame(function() {
          const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
          const floatingNav = document.getElementById('floatingNav');

          // Only react to significant scroll movements
          if (Math.abs(currentScroll - lastScrollTop) > scrollThreshold) {
            if (currentScroll > lastScrollTop && currentScroll > 100) {
              // Scrolling down & past initial viewport - hide nav
              floatingNav.style.transform = 'translateY(120%)';
            } else {
              // Scrolling up or near top - show nav
              floatingNav.style.transform = 'translateY(0)';
            }
            lastScrollTop = currentScroll;
          }

          isScrolling = false;
        });
      }
      isScrolling = true;
    });

    // Show navigation when user stops scrolling for a moment
    let scrollTimer = null;
    window.addEventListener('scroll', function() {
      const floatingNav = document.getElementById('floatingNav');

      // Clear the timer if it exists
      if (scrollTimer !== null) {
        clearTimeout(scrollTimer);
      }

      // Set a timer to show nav after scrolling stops
      scrollTimer = setTimeout(function() {
        floatingNav.style.transform = 'translateY(0)';
      }, 1500); // Show nav 1.5 seconds after scrolling stops
    });
  </script>

  <!-- Attendance Modal Component -->
  @include('components.attendance-modal', [
      'showModal' => $showAttendanceModal ?? false,
      'currentMonthTickets' => $currentMonthTickets ?? 0,
      'currentMonthAttendance' => $currentMonthAttendance ?? 0,
      'currentMonthDays' => $currentMonthDays ?? now()->daysInMonth,
      'attendanceDates' => $attendanceDates ?? []
  ])
</body>
</html>
