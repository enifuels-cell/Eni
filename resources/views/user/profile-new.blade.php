<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - Eni Members</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-black to-gray-800 text-white">
  <!-- Header -->
  <header class="bg-black/50 backdrop-blur border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center gap-4">
          <img src="/images/eni-logo.svg" alt="ENI" class="h-8 w-auto">
          <div>
            <h1 class="text-xl font-bold text-eni-yellow">Profile Settings</h1>
            <p class="text-sm text-white/60">Manage your account and bank details</p>
          </div>
        </div>
        
        <div class="flex items-center gap-4">
          <a href="{{ route('dashboard') }}" class="text-eni-yellow hover:text-yellow-300 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
          </a>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Status Messages -->
    @if (session('status'))
      <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Profile Sections -->
    <div class="space-y-6">
      
      <!-- Personal Information Section -->
      <div class="bg-gradient-to-r from-blue-900/30 to-purple-900/30 backdrop-blur border border-white/10 rounded-2xl overflow-hidden">
        <button onclick="toggleSection('personal')" class="w-full p-6 text-left flex items-center justify-between hover:bg-white/5 transition-colors">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">Personal Information</h3>
              <p class="text-white/60">Update your personal details</p>
            </div>
          </div>
          <svg id="personal-chevron" class="w-6 h-6 text-white/60 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        
        <div id="personal-content" class="hidden border-t border-white/10">
          <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
              @csrf
              @method('patch')
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium text-eni-yellow mb-2">Full Name</label>
                  <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors" 
                         required>
                </div>
                
                <div>
                  <label for="email" class="block text-sm font-medium text-eni-yellow mb-2">Email Address</label>
                  <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors" 
                         required>
                </div>
                
                <div>
                  <label for="phone" class="block text-sm font-medium text-eni-yellow mb-2">Phone Number</label>
                  <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
                
                <div>
                  <label for="address" class="block text-sm font-medium text-eni-yellow mb-2">Address</label>
                  <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
              </div>
              
              <div class="flex justify-end">
                <button type="submit" class="bg-eni-yellow text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors">
                  Update Personal Info
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Bank Details Section -->
      <div class="bg-gradient-to-r from-green-900/30 to-teal-900/30 backdrop-blur border border-white/10 rounded-2xl overflow-hidden">
        <button onclick="toggleSection('bank')" class="w-full p-6 text-left flex items-center justify-between hover:bg-white/5 transition-colors">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">Bank Details for Withdrawals</h3>
              <p class="text-white/60">Enter your banking information to enable withdrawal requests</p>
            </div>
          </div>
          <svg id="bank-chevron" class="w-6 h-6 text-white/60 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        
        <div id="bank-content" class="hidden border-t border-white/10">
          <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
              @csrf
              @method('patch')
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="bank_name" class="block text-sm font-medium text-eni-yellow mb-2">Bank Name</label>
                  <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
                
                <div>
                  <label for="account_number" class="block text-sm font-medium text-eni-yellow mb-2">Account Number</label>
                  <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $user->account_number) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
                
                <div>
                  <label for="account_holder_name" class="block text-sm font-medium text-eni-yellow mb-2">Account Holder Name</label>
                  <input type="text" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name', $user->account_holder_name) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
                
                <div>
                  <label for="routing_number" class="block text-sm font-medium text-eni-yellow mb-2">Routing Number</label>
                  <input type="text" id="routing_number" name="routing_number" value="{{ old('routing_number', $user->routing_number) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
                
                <div class="md:col-span-2">
                  <label for="swift_code" class="block text-sm font-medium text-eni-yellow mb-2">SWIFT/BIC Code (for international transfers)</label>
                  <input type="text" id="swift_code" name="swift_code" value="{{ old('swift_code', $user->swift_code) }}" 
                         class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors">
                </div>
              </div>
              
              <div class="flex justify-end">
                <button type="submit" class="bg-eni-yellow text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors">
                  Update Bank Details
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Account Information Section -->
      <div class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 backdrop-blur border border-white/10 rounded-2xl overflow-hidden">
        <button onclick="toggleSection('account')" class="w-full p-6 text-left flex items-center justify-between hover:bg-white/5 transition-colors">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">Account Information</h3>
              <p class="text-white/60">View your account details and status</p>
            </div>
          </div>
          <svg id="account-chevron" class="w-6 h-6 text-white/60 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        
        <div id="account-content" class="hidden border-t border-white/10">
          <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-black/20 rounded-xl p-4">
                <label class="block text-sm font-medium text-eni-yellow mb-1">Account Balance</label>
                <p class="text-2xl font-bold text-white">${{ number_format($user->balance, 2) }}</p>
              </div>
              
              <div class="bg-black/20 rounded-xl p-4">
                <label class="block text-sm font-medium text-eni-yellow mb-1">Member Since</label>
                <p class="text-lg text-white">{{ $user->created_at->format('F j, Y') }}</p>
              </div>
              
              <div class="bg-black/20 rounded-xl p-4">
                <label class="block text-sm font-medium text-eni-yellow mb-1">Account Status</label>
                <p class="text-lg text-green-400">Active</p>
              </div>
              
              <div class="bg-black/20 rounded-xl p-4">
                <label class="block text-sm font-medium text-eni-yellow mb-1">Email Verified</label>
                <p class="text-lg {{ $user->email_verified_at ? 'text-green-400' : 'text-red-400' }}">
                  {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Change Password Section -->
      <div class="bg-gradient-to-r from-red-900/30 to-orange-900/30 backdrop-blur border border-white/10 rounded-2xl overflow-hidden">
        <button onclick="toggleSection('password')" class="w-full p-6 text-left flex items-center justify-between hover:bg-white/5 transition-colors">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">Change Password</h3>
              <p class="text-white/60">Update your account password</p>
            </div>
          </div>
          <svg id="password-chevron" class="w-6 h-6 text-white/60 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>
        
        <div id="password-content" class="hidden border-t border-white/10">
          <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
              @csrf
              @method('put')
              
              <div>
                <label for="current_password" class="block text-sm font-medium text-eni-yellow mb-2">Current Password</label>
                <input type="password" id="current_password" name="current_password" 
                       class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors" 
                       required>
              </div>
              
              <div>
                <label for="password" class="block text-sm font-medium text-eni-yellow mb-2">New Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors" 
                       required>
              </div>
              
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-eni-yellow mb-2">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full bg-black/30 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-colors" 
                       required>
              </div>
              
              <div class="flex justify-end">
                <button type="submit" class="bg-eni-yellow text-black px-6 py-3 rounded-xl font-semibold hover:bg-yellow-400 transition-colors">
                  Update Password
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script>
    function toggleSection(sectionName) {
      const content = document.getElementById(`${sectionName}-content`);
      const chevron = document.getElementById(`${sectionName}-chevron`);
      
      // Close all other sections
      const allContents = document.querySelectorAll('[id$="-content"]');
      const allChevrons = document.querySelectorAll('[id$="-chevron"]');
      
      allContents.forEach((c) => {
        if (c.id !== `${sectionName}-content`) {
          c.classList.add('hidden');
        }
      });
      
      allChevrons.forEach((ch) => {
        if (ch.id !== `${sectionName}-chevron`) {
          ch.classList.remove('rotate-180');
        }
      });
      
      // Toggle current section
      content.classList.toggle('hidden');
      chevron.classList.toggle('rotate-180');
    }

    // Handle URL fragments and keep sections open after form submissions
    document.addEventListener('DOMContentLoaded', function() {
      // Check for URL fragment first
      const hash = window.location.hash.substring(1);
      if (hash && ['personal', 'bank', 'account', 'password'].includes(hash)) {
        toggleSection(hash);
        return;
      }
      
      // If there's a success message, keep the relevant section open
      const status = '{{ session("status") }}';
      if (status === 'profile-updated') {
        toggleSection('personal');
      } else if (status === 'password-updated') {
        toggleSection('password');
      }
    });
  </script>
</body>
</html>
