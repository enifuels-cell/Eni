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
  <header class="bg-eni-dark px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between shadow-md">
    <div class="flex items-center gap-3">
      <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-7 sm:h-8 w-auto" />
      <div>
        <h1 class="font-extrabold text-lg sm:text-xl tracking-tight text-eni-yellow">Eni Members</h1>
        <p class="text-xs sm:text-sm text-white/60 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
      </div>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
      <div class="relative">
        <button onclick="toggleNotifications()" class="relative p-1.5 sm:p-2 rounded-full hover:bg-white/10 transition-colors" aria-label="notifications">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 text-white"><path d="M8.25 21a3.75 3.75 0 0 0 7.5 0h-7.5ZM4.5 8.25A7.5 7.5 0 0 1 12 3a7.5 7.5 0 0 1 7.5 5.25v4.178l.932 2.8a1.125 1.125 0 0 1-1.068 1.472H4.636a1.125 1.125 0 0 1-1.068-1.472l.932-2.8V8.25Z"/></svg>
          @php
            $totalUnread = $unread_notifications_count + (!Auth::user()->pin_hash ? 1 : 0);
          @endphp
          @if($totalUnread > 0)
            @if($totalUnread > 9)
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-semibold animate-pulse">9+</span>
            @else
              <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center font-semibold animate-pulse">{{ $totalUnread }}</span>
            @endif
          @endif
        </button>
      </div>
      <div class="relative">
        <button onclick="toggleProfileMenu()" class="block hover:opacity-80 transition-opacity relative">
          <img src="https://dummyimage.com/40x40/FFCD00/000000&text={{ substr(Auth::user()->name, 0, 1) }}" alt="user avatar" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-eni-yellow cursor-pointer"/>
          <div class="absolute top-0 right-0 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-green-400 rounded-full border-2 border-eni-charcoal"></div>
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

  <!-- Hero Balance Card - Optimized Compact Design -->
  <section class="px-4 sm:px-6 mt-4">
    <div class="bg-gradient-to-br from-eni-yellow via-yellow-400 to-yellow-500 text-eni-dark rounded-2xl p-4 sm:p-5 shadow-[0_8px_30px_rgba(255,205,0,0.4)]">
      <!-- Balance Display -->
      <div class="mb-4">
        <h2 class="text-[10px] sm:text-xs uppercase font-bold tracking-wide text-eni-dark/70 mb-1">Account Balance</h2>
        <p class="text-2xl sm:text-3xl md:text-4xl font-extrabold leading-tight">$@money($account_balance ?? 0)</p>
        <p class="text-[10px] sm:text-xs text-eni-dark/60 mt-0.5 italic">
          @if(($total_interest ?? 0) > 0)
            + $@money($total_interest) Interest Earned
          @else
            Ready to start investing
          @endif
        </p>
      </div>

      <!-- Action Buttons - Compact & Responsive -->
      <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
        <button onclick="window.location.href='{{ route("dashboard.packages") }}'" class="flex-1 bg-eni-dark text-white font-semibold py-2.5 sm:py-3 rounded-xl hover:bg-black transition-all hover:shadow-lg active:scale-95 text-sm">
          <i class="fas fa-chart-line mr-2 text-xs"></i>Invest
        </button>
        <button onclick="window.location.href='{{ route("dashboard.withdraw") }}'" class="flex-1 bg-white text-eni-dark font-semibold py-2.5 sm:py-3 rounded-xl hover:bg-gray-100 transition-all hover:shadow-lg active:scale-95 text-sm">
          <i class="fas fa-wallet mr-2 text-xs"></i>Withdraw
        </button>
      </div>
    </div>
  </section>

  <!-- Stats Cards - Uniform Height & Spacing -->
  <section class="grid grid-cols-3 gap-3 sm:gap-4 px-4 sm:px-6 mt-6">
    <!-- Active Investments -->
    <div class="bg-white/5 backdrop-blur rounded-2xl p-3 sm:p-4 text-center flex flex-col items-center justify-center min-h-[130px] hover:bg-white/10 transition-all hover:scale-105">
      <div class="mx-auto w-14 h-14 sm:w-16 sm:h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center font-extrabold text-base sm:text-lg mb-2">
        {{ ($active_investments ?? 0) }}
      </div>
      <p class="text-[10px] sm:text-xs text-white/70 leading-tight">Active<br>Investments</p>
    </div>

    <!-- Total Invested -->
    <div class="bg-eni-yellow rounded-2xl p-3 sm:p-4 flex flex-col items-center justify-center min-h-[130px] hover:shadow-lg transition-all hover:scale-105">
      <div class="text-center w-full">
        <p class="text-lg sm:text-xl md:text-2xl font-bold text-blue-900 break-all leading-tight">${{ number_format($total_invested, 2) }}</p>
        <p class="text-[10px] sm:text-xs font-medium text-blue-900 mt-1.5 whitespace-nowrap">Total Invested</p>
      </div>
    </div>

    <!-- Referral Bonus -->
    <div class="bg-white/5 backdrop-blur rounded-2xl p-3 sm:p-4 text-center flex flex-col items-center justify-center min-h-[130px] hover:bg-white/10 transition-all hover:scale-105">
      <div class="mx-auto w-14 h-14 sm:w-16 sm:h-16 rounded-full border-4 border-eni-yellow flex items-center justify-center mb-2">
        <i class="fas fa-gift text-eni-yellow text-xl sm:text-2xl"></i>
      </div>
      <p class="text-base sm:text-lg font-bold text-eni-yellow leading-tight">${{ number_format($total_referral_bonus ?? 0, 2) }}</p>
      <p class="text-[10px] sm:text-xs text-white/70 leading-tight mt-1">Referral<br>Bonus</p>
    </div>
  </section>

  <!-- Investment Packages Carousel - Corporate Side-by-Side Layout -->
  <section class="px-4 sm:px-6 mt-6">
    <div class="relative bg-gradient-to-r from-eni-yellow to-yellow-400 rounded-2xl overflow-hidden">
      <div class="flex flex-row items-center">
        <!-- Text Content Section (Left) -->
        <div class="flex-1 p-4 sm:p-5 text-blue-900">
          <h3 class="text-base sm:text-lg font-bold leading-tight mb-1 sm:mb-2">Premium Investment Packages</h3>
          <p class="text-[10px] sm:text-xs leading-snug text-blue-900/80 mb-2 sm:mb-3">Earn guaranteed daily returns with our secure investment options.</p>

          <!-- Navigation Dots -->
          @if(isset($investmentPackages) && $investmentPackages->count() > 1)
          <div class="flex gap-1.5 mb-2 sm:mb-3">
            @foreach($investmentPackages as $index => $package)
              <button
                onclick="showPackage({{ $index }})"
                class="package-dot w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-blue-900 w-4 sm:w-6' : 'bg-blue-900/30' }}"
                data-dot-index="{{ $index }}">
              </button>
            @endforeach
          </div>
          @endif

          <!-- CTA Button -->
          <a href="{{ route('dashboard.packages') }}" class="inline-flex items-center gap-2 bg-blue-900 text-eni-yellow px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg font-semibold text-xs sm:text-sm hover:bg-blue-800 hover:shadow-lg active:scale-95 transition-all">
            Browse Packages
          </a>
        </div>

        <!-- Gradient Divider -->
        <div class="w-8 sm:w-12 h-full bg-gradient-to-r from-yellow-400/0 via-yellow-600/30 to-transparent"></div>

        <!-- Video Package Card (Right) -->
        <div class="relative w-32 sm:w-48 h-[120px] sm:h-[140px] flex-shrink-0 pr-3 sm:pr-4 group">
          @if(isset($investmentPackages) && $investmentPackages->count() > 0)
            @foreach($investmentPackages as $index => $package)
              @php
                $videoFile = match($index) {
                  0 => 'Energy.mp4',
                  1 => 'Growth.mp4',
                  2 => 'Capital.mp4',
                  default => 'Energy.mp4'
                };
              @endphp
              <div class="package-carousel-item absolute inset-0 transition-all duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $index }}">
                <div class="relative w-full h-full transform transition-all duration-300 group-hover:scale-105 group-hover:-translate-y-1">
                  <video
                    class="w-full h-full object-cover rounded-lg sm:rounded-xl shadow-lg group-hover:shadow-2xl transition-shadow duration-300"
                    autoplay
                    loop
                    muted
                  playsinline
                  webkit-playsinline
                  preload="{{ $index === 0 ? 'auto' : 'metadata' }}">
                  <source src="{{ asset($videoFile) }}" type="video/mp4">
                </video>

                  <!-- Hover Overlay -->
                  <div class="absolute inset-0 bg-blue-900/0 group-hover:bg-blue-900/10 rounded-lg sm:rounded-xl transition-colors duration-300 pointer-events-none"></div>

                  <!-- Shine Effect on Hover -->
                  <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none overflow-hidden rounded-lg sm:rounded-xl">
                    <div class="absolute inset-0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-12"></div>
                  </div>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </section>  @if(isset($investmentPackages) && $investmentPackages->count() > 1)
  <script>
    let currentPackageIndex = 0;
    const totalPackages = {{ $investmentPackages->count() }};

    function showPackage(index) {
      // Hide all packages
      document.querySelectorAll('.package-carousel-item').forEach((item, i) => {
        item.classList.toggle('opacity-100', i === index);
        item.classList.toggle('opacity-0', i !== index);
      });

      // Update dots
      document.querySelectorAll('.package-dot').forEach((dot, i) => {
        if (i === index) {
          dot.classList.remove('bg-blue-900/30', 'w-2');
          dot.classList.add('bg-blue-900', 'w-6');
        } else {
          dot.classList.remove('bg-blue-900', 'w-6');
          dot.classList.add('bg-blue-900/30', 'w-2');
        }
      });

      currentPackageIndex = index;
    }

    // Auto-rotate every 3 seconds
    setInterval(() => {
      currentPackageIndex = (currentPackageIndex + 1) % totalPackages;
      showPackage(currentPackageIndex);
    }, 3000);
  </script>
  @endif

  <!-- Franchise Opportunity - Compact Design -->
  <section class="px-4 sm:px-6 mt-6">
    <div class="rounded-2xl overflow-hidden bg-gradient-to-r from-gray-800 to-eni-dark border border-eni-yellow/20 text-white">
      <div class="p-4 sm:p-5">
        <div class="flex items-start gap-3 mb-3">
          <div class="w-10 h-10 sm:w-12 sm:h-12 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-gas-pump text-xl sm:text-2xl text-eni-yellow"></i>
          </div>
          <div class="flex-1">
            <h3 class="text-lg sm:text-xl font-bold text-eni-yellow leading-tight">ENI Fuel Station Franchise</h3>
          </div>
        </div>
        <p class="text-xs sm:text-sm text-white/80 leading-snug mb-3 pl-1">Open your own ENI fuel station. Join our network of service stations with proven business model and ongoing support.</p>
        <button onclick="window.location.href='{{ route('dashboard.franchise') }}'" class="inline-flex items-center gap-2 bg-eni-yellow text-eni-dark px-5 sm:px-6 py-2.5 rounded-full font-semibold text-xs sm:text-sm hover:bg-yellow-400 hover:shadow-lg active:scale-95 transition-all">
          <i class="fas fa-arrow-right text-xs"></i>
          Learn More
        </button>
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
    <div class="bg-eni-dark/95 backdrop-blur border border-eni-yellow/20 shadow-lg shadow-black/25 px-3 sm:px-6 py-3 rounded-2xl flex gap-2 sm:gap-4 text-xs sm:text-sm font-medium overflow-x-auto max-w-full">
      <!-- Home -->
      <a href="{{ route('dashboard') }}" class="text-eni-yellow flex flex-col items-center gap-1.5 hover:text-yellow-300 hover:bg-white/10 active:bg-white/20 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-home text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">Home</span>
      </a>
      <!-- Invest -->
      <a href="{{ route('dashboard.packages') }}" class="text-white/50 hover:text-white hover:bg-white/10 active:bg-white/20 flex flex-col items-center gap-1.5 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-chart-line text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">Invest</span>
      </a>
      <!-- Refer -->
      <a href="{{ route('dashboard.referrals') }}" class="text-white/50 hover:text-white hover:bg-white/10 active:bg-white/20 flex flex-col items-center gap-1.5 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-users text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">Refer</span>
      </a>
      <!-- Fuel -->
      <a href="{{ route('dashboard.franchise') }}" class="text-white/50 hover:text-white hover:bg-white/10 active:bg-white/20 flex flex-col items-center gap-1.5 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-gas-pump text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">Fuel</span>
      </a>
      <!-- Send -->
      <a href="{{ route('dashboard.transfer') }}" class="text-white/50 hover:text-white hover:bg-white/10 active:bg-white/20 flex flex-col items-center gap-1.5 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-exchange-alt text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">Send</span>
      </a>
      <!-- History -->
      <a href="{{ route('dashboard.transactions') }}" class="text-white/50 hover:text-white hover:bg-white/10 active:bg-white/20 flex flex-col items-center gap-1.5 transition-all duration-200 py-2 px-2.5 sm:px-3 rounded-lg touch-manipulation min-w-[50px]">
        <i class="fas fa-history text-base sm:text-lg"></i>
        <span class="text-[10px] sm:text-xs font-medium">History</span>
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
        @php
          $notificationData = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
          $isSignupBonus = isset($notificationData['type']) && $notificationData['type'] === 'signup_bonus';
        @endphp

        <div class="bg-white/5 border border-white/10 rounded-lg p-4 {{ $isSignupBonus ? '' : 'cursor-pointer hover:bg-white/10' }} transition-all duration-200" {{ $isSignupBonus ? '' : "onclick=window.location.href='" . route('user.notifications') . "'" }}>
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 {{ $isSignupBonus ? 'bg-eni-yellow/20 border-eni-yellow/40' : 'bg-white/10 border-white/20' }} border rounded-full flex items-center justify-center">
              <i class="{{ $notification->icon }} {{ $isSignupBonus ? 'text-eni-yellow' : 'text-eni-yellow' }} text-xs"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <p class="font-semibold text-sm text-white truncate">{{ $notification->title }}</p>
                @if(!$notification->is_read)
                  <div class="w-2 h-2 bg-eni-yellow rounded-full flex-shrink-0"></div>
                @endif
              </div>
              <p class="text-white/70 text-xs mt-1">{!! \Illuminate\Support\Str::limit($notification->message, 80) !!}</p>

              @if($isSignupBonus && !Auth::user()->signup_bonus_claimed)
                <!-- Signup Bonus Claim Button -->
                <div class="mt-3">
                  <button onclick="event.stopPropagation(); claimSignupBonus(this, event)"
                          class="w-full px-4 py-2 bg-eni-yellow text-eni-dark font-bold rounded-lg hover:bg-yellow-400 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-gift"></i>
                    <span>Claim $10 Bonus</span>
                  </button>
                </div>
              @else
                <div class="flex items-center justify-between mt-2">
                  <span class="text-white/50 text-xs">{{ $notification->created_at->diffForHumans() }}</span>
                  <span class="text-white/40 text-xs capitalize">{{ $notification->category }}</span>
                </div>
              @endif
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

    // Claim Signup Bonus Function
    async function claimSignupBonus(button, event) {
      event.stopPropagation();
      event.preventDefault();

      const originalText = button.innerHTML;
      button.disabled = true;
      button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Claiming...</span>';

      try {
        const response = await fetch('{{ route("user.claim-signup-bonus") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (data.success) {
          // Show success state
          button.innerHTML = '<i class="fas fa-check"></i> <span>Claimed!</span>';
          button.classList.remove('bg-eni-yellow', 'hover:bg-yellow-400');
          button.classList.add('bg-green-500');

          // Show alert
          alert('🎉 ' + data.message);

          // Reload page to update balance and notifications
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          throw new Error(data.message || 'Failed to claim bonus');
        }
      } catch (error) {
        console.error('Error claiming bonus:', error);
        alert('❌ ' + error.message);
        button.disabled = false;
        button.innerHTML = originalText;
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
