<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications — Eni Members</title>
  <meta name="theme-color" content="#FFCD00" />
  
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
    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    
    .prose p {
      margin-bottom: 1rem;
      line-height: 1.7;
    }
    
    .prose p:last-child {
      margin-bottom: 0;
    }
  </style>
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
          <button type="button" data-action="mark-all-read" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-colors">
            <i class="fas fa-check-double text-xs mr-2"></i>
            Mark All Read
          </button>
          <!-- Filter Dropdown -->
          <div class="relative">
            <button type="button" data-action="toggle-filter" class="px-4 py-2 bg-eni-dark border border-white/20 hover:bg-white/10 text-white text-sm rounded-lg transition-colors flex items-center gap-2">
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
      <div class="bg-eni-dark/50 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer"
             role="button"
             data-action="open-notification"
             data-id="{{ $notification->id }}"
             data-title="{{ e($notification->title) }}"
             data-message="{{ e($notification->message) }}"
             data-category="{{ e($notification->category) }}"
             data-date="{{ $notification->created_at->format('M j, Y \a\t g:i A') }}"
             data-is-read="{{ $notification->is_read ? 'true' : 'false' }}"
             data-action-url="{{ e($notification->action_url ?? '') }}">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="{{ $notification->icon }} text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">{{ $notification->title }}</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize flex-shrink-0">{{ $notification->category }}</span>
                @if($notification->priority === 'high')
                  <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full flex-shrink-0">High Priority</span>
                @endif
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">{{ \Illuminate\Support\Str::limit($notification->message, 120) }}</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                @if(!$notification->is_read)
                  <div class="w-2 h-2 bg-eni-yellow rounded-full"></div>
                  <span class="text-white/50 text-sm">Unread</span>
                @else
                  <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                  <span class="text-white/50 text-sm">Read</span>
                @endif
              </div>
              <div class="flex items-center gap-2 text-white/40">
                <span class="text-sm">Click to view details</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
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

      <!-- iPhone Air Raffle Notification -->
      <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-xl p-6 hover:bg-eni-yellow/10 transition-all duration-200 cursor-pointer" onclick="showAttendanceModal()">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-eni-yellow/20 border border-eni-yellow/40 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-trophy text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">🏆 iPhone Air Raffle Active!</h3>
                <span class="px-2 py-1 bg-eni-yellow/20 text-eni-yellow text-xs rounded-full flex-shrink-0">Raffle</span>
                <span class="px-2 py-1 bg-green-500/20 text-green-300 text-xs rounded-full flex-shrink-0">Active</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">Ongoing</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">Login daily to earn raffle tickets and win the iPhone Air this month! More consecutive logins = better chances of winning!</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-eni-yellow rounded-full animate-pulse"></div>
                <span class="text-eni-yellow text-sm font-medium">{{ $currentMonthTickets ?? 0 }} tickets earned</span>
              </div>
              <div class="flex items-center gap-2 text-eni-yellow/80">
                <span class="text-sm font-medium">Click to view calendar</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PIN Setup Notification (if not set and no custom notification exists) -->
  @if(!Auth::user()->pin_hash && !$notifications->where('category', 'security')->where('title', 'like', '%PIN%')->count())
  <div class="bg-eni-dark/50 border border-eni-yellow/30 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer" data-action="goto" data-url="{{ route('pin.setup.form') }}">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-eni-yellow/20 border border-eni-yellow/40 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shield-alt text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">Set Up PIN Login</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full flex-shrink-0">security</span>
                <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full flex-shrink-0">Action Required</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">Just now</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">Enable 4-digit PIN for quick and secure login on this device. This adds an extra layer of security...</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-eni-yellow rounded-full animate-pulse"></div>
                <span class="text-white/50 text-sm">Unread</span>
              </div>
              <div class="flex items-center gap-2 text-eni-yellow/80">
                <span class="text-sm font-medium">Click to set up</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if(Auth::user()->email_verified_at)
      <div class="bg-eni-dark/50 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer"
           onclick="openNotificationModal('verified', 'Account Successfully Verified', 'Your email address has been successfully verified. You now have full access to all platform features including investments and withdrawals.', 'account', '{{ Auth::user()->email_verified_at->format('M j, Y \a\t g:i A') }}', true, '')">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">Account Successfully Verified</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize flex-shrink-0">account</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">{{ Auth::user()->email_verified_at->diffForHumans() }}</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">Your email address has been successfully verified. You now have full access to all platform features...</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                <span class="text-white/50 text-sm">Read</span>
              </div>
              <div class="flex items-center gap-2 text-white/40">
                <span class="text-sm">Click to view details</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif

      <!-- Investment Packages Update -->
      <div class="bg-eni-dark/50 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer"
           onclick="openNotificationModal('packages', 'New Investment Packages Available', 'We\'ve updated our investment packages with improved returns. Check out the Energy, Growth, and Capital packages with competitive daily interest rates and secure investment options.', 'investment', '{{ now()->subDay()->format('M j, Y \a\t g:i A') }}', true, '{{ route('user.packages') }}')">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-line text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">New Investment Packages Available</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize flex-shrink-0">investment</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">1 day ago</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">We've updated our investment packages with improved returns. Check out the Energy, Growth, and Capital packages...</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                <span class="text-white/50 text-sm">Read</span>
              </div>
              <div class="flex items-center gap-2 text-white/40">
                <span class="text-sm">Click to view details</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- System Maintenance Notice -->
      <div class="bg-eni-dark/50 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer"
           onclick="openNotificationModal('maintenance', 'Scheduled Maintenance', 'We will be performing system maintenance on September 10th from 2:00 AM to 4:00 AM UTC. Some features may be temporarily unavailable during this time. We apologize for any inconvenience and appreciate your patience.', 'system', '{{ now()->subDays(2)->format('M j, Y \a\t g:i A') }}', true, '')">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-tools text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">Scheduled Maintenance</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize flex-shrink-0">system</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">2 days ago</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">We'll be performing system maintenance on September 10th from 2:00 AM to 4:00 AM UTC. Some features may be temporarily...</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                <span class="text-white/50 text-sm">Read</span>
              </div>
              <div class="flex items-center gap-2 text-white/40">
                <span class="text-sm">Click to view details</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Alert -->
      <div class="bg-eni-dark/50 border border-white/10 rounded-xl p-6 hover:bg-eni-dark/70 transition-all duration-200 cursor-pointer"
           onclick="openNotificationModal('security', 'Security Reminder', 'Always verify that you are accessing Eni Members through the official website. Never share your login credentials with anyone, and enable two-factor authentication for enhanced security. Be cautious of phishing attempts and report any suspicious activity.', 'security', '{{ now()->subDays(3)->format('M j, Y \a\t g:i A') }}', true, '')">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shield-alt text-eni-yellow"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between mb-2">
              <div class="flex items-center gap-3 flex-wrap">
                <h3 class="font-semibold text-lg text-white truncate">Security Reminder</h3>
                <span class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize flex-shrink-0">security</span>
              </div>
              <span class="text-white/50 text-sm flex-shrink-0 ml-2">3 days ago</span>
            </div>
            <p class="text-white/70 mb-3 line-clamp-2">Always verify that you're accessing Eni Members through the official website. Never share your login credentials...</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-white/30 rounded-full"></div>
                <span class="text-white/50 text-sm">Read</span>
              </div>
              <div class="flex items-center gap-2 text-white/40">
                <span class="text-sm">Click to view details</span>
                <i class="fas fa-chevron-right text-xs"></i>
              </div>
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

  <!-- Notification Detail Modal -->
  <div id="notificationModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div id="notificationModalContent" class="bg-eni-dark border border-white/20 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 border border-white/20 rounded-full flex items-center justify-center">
              <i id="modalIcon" class="fas fa-bell text-eni-yellow"></i>
            </div>
            <div>
              <h3 id="modalTitle" class="text-xl font-bold text-white">Notification Title</h3>
              <div class="flex items-center gap-2 mt-1">
                <span id="modalCategory" class="px-2 py-1 bg-white/10 text-white/70 text-xs rounded-full capitalize">category</span>
                <span id="modalStatus" class="px-2 py-1 bg-eni-yellow/20 text-eni-yellow text-xs rounded-full">Unread</span>
              </div>
            </div>
          </div>
          <button data-action="close-modal" class="text-white/60 hover:text-white text-2xl font-bold leading-none transition-colors" title="Close">
            ×
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
          <div class="mb-4">
            <div class="flex items-center gap-2 text-white/50 text-sm mb-4">
              <i class="fas fa-clock text-xs"></i>
              <span id="modalDate">Date and time</span>
            </div>
            <div class="prose prose-invert max-w-none">
              <p id="modalMessage" class="text-white/80 leading-relaxed">
                Notification message content will appear here...
              </p>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-white/10 bg-eni-dark/50">
          <div class="flex items-center gap-3">
            <button id="markReadBtn" data-action="mark-read" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-colors">
              <i class="fas fa-check text-xs mr-2"></i>
              Mark as Read
            </button>
          </div>
          <div class="flex items-center gap-3">
            <button id="actionBtn" data-action="action" class="px-4 py-2 bg-eni-yellow text-eni-dark font-semibold rounded-lg hover:bg-yellow-400 transition-colors hidden">
              <i class="fas fa-external-link-alt text-xs mr-2"></i>
              View Details
            </button>
            <button data-action="close-modal" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-lg transition-colors">
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentNotificationId = null;
    let currentNotificationActionUrl = null;

    function toggleFilter() {
      const menu = document.getElementById('filterMenu');
      menu.classList.toggle('hidden');
    }

    // Open notification detail modal
    function openNotificationModal(id, title, message, category, date, isRead, actionUrl) {
      currentNotificationId = id;
      currentNotificationActionUrl = actionUrl;
      
      // Update modal content
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalMessage').textContent = message;
      document.getElementById('modalCategory').textContent = category;
      document.getElementById('modalDate').textContent = date;
      
      // Update status
      const statusEl = document.getElementById('modalStatus');
      const markReadBtn = document.getElementById('markReadBtn');
      if (isRead) {
        statusEl.textContent = 'Read';
        statusEl.className = 'px-2 py-1 bg-white/20 text-white/70 text-xs rounded-full';
        markReadBtn.style.display = 'none';
      } else {
        statusEl.textContent = 'Unread';
        statusEl.className = 'px-2 py-1 bg-eni-yellow/20 text-eni-yellow text-xs rounded-full';
        markReadBtn.style.display = 'inline-flex';
      }
      
      // Update icon based on category
      const iconEl = document.getElementById('modalIcon');
      const iconMap = {
        'security': 'fas fa-shield-alt',
        'investment': 'fas fa-chart-line',
        'account': 'fas fa-user',
        'system': 'fas fa-cog',
        'welcome': 'fas fa-hand-wave',
        'referral': 'fas fa-users',
        'transaction': 'fas fa-exchange-alt'
      };
      iconEl.className = iconMap[category] || 'fas fa-bell';
      iconEl.className += ' text-eni-yellow';
      
      // Show/hide action button
      const actionBtn = document.getElementById('actionBtn');
      if (actionUrl && actionUrl.trim() !== '') {
        actionBtn.style.display = 'inline-flex';
      } else {
        actionBtn.style.display = 'none';
      }
      
      // Show modal
      document.getElementById('notificationModal').classList.remove('hidden');
      
      // Auto-mark as read if unread
      if (!isRead) {
        setTimeout(() => {
          markNotificationAsReadSilent(id);
        }, 2000); // Mark as read after 2 seconds of viewing
      }
    }

    // Close notification modal
    function closeNotificationModal(event) {
      document.getElementById('notificationModal').classList.add('hidden');
      currentNotificationId = null;
      currentNotificationActionUrl = null;
    }

    // Handle notification action
    function handleNotificationAction() {
      if (currentNotificationActionUrl && currentNotificationActionUrl.trim() !== '') {
        window.location.href = currentNotificationActionUrl;
      }
    }

    // Mark notification as read (with UI update)
    async function markNotificationAsRead() {
      if (!currentNotificationId) return;
      
      try {
        const response = await fetch(`{{ route("user.notifications.mark-read", ":id") }}`.replace(':id', currentNotificationId), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
        
        if (response.ok) {
          // Update modal UI
          const statusEl = document.getElementById('modalStatus');
          const markReadBtn = document.getElementById('markReadBtn');
          statusEl.textContent = 'Read';
          statusEl.className = 'px-2 py-1 bg-white/20 text-white/70 text-xs rounded-full';
          markReadBtn.style.display = 'none';
          
          // Reload page to update the notification list
          setTimeout(() => {
            location.reload();
          }, 1000);
        }
      } catch (error) {
        console.error('Error marking notification as read:', error);
      }
    }

    // Mark notification as read silently (no UI update)
    async function markNotificationAsReadSilent(id) {
      try {
        await fetch(`{{ route("user.notifications.mark-read", ":id") }}`.replace(':id', id), {
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

    // Delegate UI actions for elements using data-action
    document.addEventListener('click', function(event) {
      const el = event.target.closest('[data-action]');
      if (!el) return;
      const action = el.getAttribute('data-action');

      if (action === 'mark-all-read') return markAllAsRead();
      if (action === 'toggle-filter') return toggleFilter();
      if (action === 'goto') {
        const url = el.getAttribute('data-url');
        if (url) window.location.href = url;
        return;
      }
      if (action === 'open-notification') {
        const id = el.getAttribute('data-id');
        const title = el.getAttribute('data-title') || '';
        const message = el.getAttribute('data-message') || '';
        const category = el.getAttribute('data-category') || '';
        const date = el.getAttribute('data-date') || '';
        const isRead = el.getAttribute('data-is-read') === 'true' || el.getAttribute('data-is-read') === '1';
        const actionUrl = el.getAttribute('data-action-url') || '';
        openNotificationModal(id, title, message, category, date, isRead, actionUrl);
        return;
      }
      if (action === 'close-modal') return closeNotificationModal();
      if (action === 'mark-read') return markNotificationAsRead();
      if (action === 'action') return handleNotificationAction();
    });

    // Modal overlay handling (click outside to close)
    (function() {
      const modal = document.getElementById('notificationModal');
      const modalContent = document.getElementById('notificationModalContent');
      if (!modal || !modalContent) return;
      modal.addEventListener('click', function(e) {
        if (e.target === modal) closeNotificationModal();
      });
      modalContent.addEventListener('click', function(e) { e.stopPropagation(); });
    })();

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') closeNotificationModal();
    });

    // Attendance Modal Function
    function showAttendanceModal() {
      // Redirect to dashboard with attendance modal flag
      window.location.href = '{{ route("user.dashboard") }}#attendance-modal';
    }
  </script>

  <!-- Include Attendance Modal -->
  @include('components.attendance-modal', [
      'showModal' => false,
      'currentMonthTickets' => $currentMonthTickets ?? 0,
      'currentMonthAttendance' => $currentMonthAttendance ?? 0,
      'currentMonthDays' => $currentMonthDays ?? now()->daysInMonth,
      'attendanceDates' => $attendanceDates ?? []
  ])
</body>
</html>
