<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications — ENI Investment Platform</title>
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
</head>
<body class="bg-eni-charcoal min-h-screen font-sans">
  <!-- Header -->
  <header class="bg-eni-dark border-b border-white/10 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo and Back Button -->
        <div class="flex items-center gap-4">
          <a href="{{ route('user.dashboard') }}" class="text-white/70 hover:text-eni-yellow transition-colors">
            <i class="fas fa-arrow-left text-lg"></i>
          </a>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-eni-yellow rounded-lg flex items-center justify-center">
              <span class="text-eni-dark font-bold text-sm">E</span>
            </div>
            <h1 class="text-white font-bold text-xl">Notifications</h1>
          </div>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-3">
          <span class="text-white/70 text-sm">{{ Auth::user()->name }}</span>
          <div class="w-8 h-8 bg-eni-yellow rounded-full flex items-center justify-center">
            <span class="text-eni-dark font-semibold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Notifications Header -->
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-white mb-2">All Notifications</h2>
          <p class="text-white/60">Stay updated with your account activities and important announcements</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- Mark All as Read -->
          <button onclick="markAllAsRead()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-colors">
            <i class="fas fa-check-double text-xs mr-2"></i>
            Mark All Read
          </button>
          <!-- Filter Dropdown -->
          <div class="relative">
            <button onclick="toggleFilter()" class="px-4 py-2 bg-eni-dark border border-white/20 hover:bg-white/10 text-white text-sm rounded-lg transition-colors flex items-center gap-2">
              <i class="fas fa-filter text-xs"></i>
              {{ $categories[$filter] ?? 'Filter' }}
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
            <div id="filterMenu" class="absolute right-0 mt-2 w-48 bg-eni-dark border border-white/20 rounded-lg shadow-xl z-50 hidden">
              <div class="p-2">
                @foreach($categories as $key => $name)
                  <a href="{{ route('user.notifications', ['filter' => $key]) }}" 
                     class="block px-3 py-2 text-sm text-white hover:bg-white/10 rounded {{ $filter === $key ? 'bg-white/10' : '' }}">
                    {{ $name }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
      @forelse($notifications as $notification)
      <div class="bg-gradient-to-r from-{{ $notification->category_color }}-500/20 to-{{ $notification->category_color }}-600/20 border border-{{ $notification->category_color }}-400/30 rounded-xl p-6 hover:from-{{ $notification->category_color }}-500/30 hover:to-{{ $notification->category_color }}-600/30 transition-all duration-200 {{ $notification->action_url ? 'cursor-pointer' : '' }}"
           @if($notification->action_url) onclick="window.location.href='{{ $notification->action_url }}'" @endif>
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-{{ $notification->category_color }}-500/20 border border-{{ $notification->category_color }}-400/40 rounded-full flex items-center justify-center">
            <i class="{{ $notification->icon }} text-{{ $notification->category_color }}-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-{{ $notification->category_color }}-400">{{ $notification->title }}</h3>
                <span class="px-2 py-1 bg-{{ $notification->category_color }}-500/20 text-{{ $notification->category_color }}-300 text-xs rounded-full capitalize">{{ $notification->category }}</span>
                @if($notification->priority === 'high')
                  <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">High Priority</span>
                @endif
              </div>
              <span class="text-white/50 text-sm">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-white/70 mb-3">{{ $notification->message }}</p>
            <div class="flex items-center gap-2">
              @if(!$notification->is_read)
                <div class="w-2 h-2 bg-{{ $notification->category_color }}-400 rounded-full"></div>
                <span class="text-white/50 text-sm">Unread</span>
              @else
                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                <span class="text-white/50 text-sm">Read</span>
              @endif
              @if($notification->action_url)
                <span class="text-{{ $notification->category_color }}-300 text-sm font-medium ml-2">Click to view</span>
                <i class="fas fa-chevron-right text-{{ $notification->category_color }}-400/60"></i>
              @endif
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="text-center py-12">
        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-bell text-white/40 text-xl"></i>
        </div>
        <h3 class="text-white font-medium mb-2">No notifications yet</h3>
        <p class="text-white/60">We'll notify you when something important happens</p>
      </div>
      @endforelse

      <!-- PIN Setup Notification (if not set and no custom notification exists) -->
      @if(!Auth::user()->pin_hash && !$notifications->where('category', 'security')->where('title', 'like', '%PIN%')->count())
      <div class="bg-gradient-to-r from-purple-500/20 to-purple-600/20 border border-purple-400/30 rounded-xl p-6 cursor-pointer hover:from-purple-500/30 hover:to-purple-600/30 transition-all duration-200" onclick="window.location.href='{{ route('pin.setup.form') }}'">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-purple-500/20 border border-purple-400/40 rounded-full flex items-center justify-center">
            <i class="fas fa-shield-alt text-purple-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-purple-400">Set Up PIN Login</h3>
                <span class="px-2 py-1 bg-purple-500/20 text-purple-300 text-xs rounded-full">Security</span>
                <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">Action Required</span>
              </div>
              <span class="text-white/50 text-sm">Just now</span>
            </div>
            <p class="text-white/70 mb-3">Enable 4-digit PIN for quick and secure login on this device. This adds an extra layer of security to your account.</p>
            <div class="flex items-center gap-3">
              <span class="text-purple-300 text-sm font-medium">Click to set up</span>
              <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
              <i class="fas fa-chevron-right text-purple-400/60"></i>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if(Auth::user()->email_verified_at)
      <div class="bg-eni-dark/30 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/50 transition-all duration-200">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-green-500/20 border border-green-400/40 rounded-full flex items-center justify-center">
            <i class="fas fa-check-circle text-green-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-white">Account Successfully Verified</h3>
                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full">Account</span>
              </div>
              <span class="text-white/50 text-sm">{{ Auth::user()->email_verified_at->diffForHumans() }}</span>
            </div>
            <p class="text-white/70 mb-3">Your email address has been successfully verified. You now have full access to all platform features including investments and withdrawals.</p>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-white/30 rounded-full"></div>
              <span class="text-white/50 text-sm">Read</span>
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Investment Packages Update -->
      <div class="bg-eni-dark/30 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/50 transition-all duration-200">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-blue-500/20 border border-blue-400/40 rounded-full flex items-center justify-center">
            <i class="fas fa-chart-line text-blue-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-white">New Investment Packages Available</h3>
                <span class="px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded-full">Investments</span>
              </div>
              <span class="text-white/50 text-sm">1 day ago</span>
            </div>
            <p class="text-white/70 mb-3">We've updated our investment packages with improved returns. Check out the Energy, Growth, and Capital packages with competitive daily interest rates.</p>
            <div class="flex items-center gap-3">
              <div class="w-2 h-2 bg-white/30 rounded-full"></div>
              <span class="text-white/50 text-sm">Read</span>
              <a href="{{ route('user.packages') }}" class="text-blue-400 text-sm hover:underline ml-auto">View Packages</a>
            </div>
          </div>
        </div>
      </div>

      <!-- System Maintenance Notice -->
      <div class="bg-eni-dark/30 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/50 transition-all duration-200">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-orange-500/20 border border-orange-400/40 rounded-full flex items-center justify-center">
            <i class="fas fa-tools text-orange-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-white">Scheduled Maintenance</h3>
                <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded-full">System</span>
              </div>
              <span class="text-white/50 text-sm">2 days ago</span>
            </div>
            <p class="text-white/70 mb-3">We'll be performing system maintenance on September 10th from 2:00 AM to 4:00 AM UTC. Some features may be temporarily unavailable during this time.</p>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-white/30 rounded-full"></div>
              <span class="text-white/50 text-sm">Read</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Alert -->
      <div class="bg-eni-dark/30 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/50 transition-all duration-200">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-red-500/20 border border-red-400/40 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-red-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-3">
                <h3 class="font-semibold text-lg text-white">Security Reminder</h3>
                <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">Security</span>
              </div>
              <span class="text-white/50 text-sm">3 days ago</span>
            </div>
            <p class="text-white/70 mb-3">Always verify that you're accessing ENI through the official website. Never share your login credentials with anyone, and enable two-factor authentication for enhanced security.</p>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-white/30 rounded-full"></div>
              <span class="text-white/50 text-sm">Read</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Load More Button -->
    <div class="mt-8 text-center">
      <button class="px-6 py-3 bg-eni-dark border border-white/20 hover:bg-white/10 text-white rounded-lg transition-colors">
        <i class="fas fa-chevron-down text-sm mr-2"></i>
        Load More Notifications
      </button>
    </div>
  </main>

  <script>
    function toggleFilter() {
      const menu = document.getElementById('filterMenu');
      menu.classList.toggle('hidden');
    }

    // Mark all notifications as read
    async function markAllAsRead() {
      try {
        const response = await fetch('{{ route("user.notifications.mark-all-read") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
        
        if (response.ok) {
          location.reload();
        }
      } catch (error) {
        console.error('Error marking notifications as read:', error);
      }
    }

    // Mark individual notification as read
    async function markAsRead(notificationId) {
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

    // Close filter menu when clicking outside
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('filterMenu');
      const button = event.target.closest('button[onclick="toggleFilter()"]');
      
      if (!button && !menu.contains(event.target)) {
        menu.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
