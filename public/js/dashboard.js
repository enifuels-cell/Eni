// Moved from inline scripts in dashboard.blade.php to satisfy CSP
function toggleNotifications(){
  var el = document.getElementById('notificationsPanel');
  if(!el) return;
  el.classList.toggle('hidden');
}
function toggleProfileMenu(){
  var el = document.getElementById('profileMenu');
  if(!el) return;
  el.classList.toggle('hidden');
}
document.addEventListener('click', function(e){
  var profile = document.getElementById('profileMenu');
  var btn = e.target.closest('[onclick*="toggleProfileMenu"]');
  if(!btn && profile && !profile.classList.contains('hidden')){
    profile.classList.add('hidden');
  }
});

// Mark notification as read via fetch (uses CSRF token in meta)
async function markNotificationAsRead(notificationId) {
  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    await fetch(window.route ? window.route('user.notifications.mark-read', notificationId) : `/notifications/${notificationId}/mark-read`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
      }
    });
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
}

// Auto-hide floating navigation on scroll (copied from blade)
let lastScrollTop = 0;
let scrollThreshold = 10; // Minimum scroll distance to trigger hide/show
let isScrolling = false;
window.addEventListener('scroll', function() {
  if (!isScrolling) {
    window.requestAnimationFrame(function() {
      const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
      const floatingNav = document.getElementById('floatingNav');
      if (!floatingNav) return;
      if (Math.abs(currentScroll - lastScrollTop) > scrollThreshold) {
        if (currentScroll > lastScrollTop && currentScroll > 100) {
          floatingNav.style.transform = 'translateY(120%)';
        } else {
          floatingNav.style.transform = 'translateY(0)';
        }
        lastScrollTop = currentScroll;
      }
      isScrolling = false;
    });
  }
  isScrolling = true;
});

let scrollTimer = null;
window.addEventListener('scroll', function() {
  const floatingNav = document.getElementById('floatingNav');
  if (!floatingNav) return;
  if (scrollTimer !== null) clearTimeout(scrollTimer);
  scrollTimer = setTimeout(function() {
    floatingNav.style.transform = 'translateY(0)';
  }, 1500);
});