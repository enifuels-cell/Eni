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
          <a href="{{ route('dashboard') }}" class="text-eni-yellow hover:text-yellow-300 transition-colors" title="Back to Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
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

      <!-- PIN Setup Section -->
      <div class="bg-gradient-to-r from-purple-900/30 to-pink-900/30 backdrop-blur border border-white/10 rounded-2xl overflow-hidden">
        <button onclick="toggleSection('pin')" class="w-full p-6 text-left flex items-center justify-between hover:bg-white/5 transition-colors">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-eni-yellow/20 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-white">PIN Setup & Security</h3>
              <p class="text-white/60">
                @if(auth()->user()->pin_hash)
                  <span class="text-green-400">✓ PIN Enabled</span> - Manage your security PIN
                @else
                  <span class="text-yellow-400">⚠ PIN Not Set</span> - Set up a 4-digit PIN for quick login
                @endif
              </p>
            </div>
          </div>
          <svg id="pin-chevron" class="w-6 h-6 text-white/60 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <div id="pin-content" class="hidden border-t border-white/10">
          <div class="p-6">
            @if(auth()->user()->pin_hash)
              <!-- PIN Already Set -->
              <div class="space-y-6">
                <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-4">
                  <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                      <h4 class="text-green-400 font-semibold">PIN is Active</h4>
                      <p class="text-green-300/80 text-sm">Your account is secured with a 4-digit PIN</p>
                      @if(auth()->user()->pin_set_at)
                        <p class="text-green-300/60 text-xs mt-1">Set on {{ auth()->user()->pin_set_at->format('M d, Y') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="bg-black/20 rounded-xl p-4 space-y-3">
                  <h5 class="text-white font-semibold">What can you do with your PIN?</h5>
                  <ul class="space-y-2 text-white/70 text-sm">
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Quick login without typing your password
                    </li>
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Secure access on trusted devices
                    </li>
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      Enhanced security for your investments
                    </li>
                  </ul>
                </div>

                <!-- Change PIN Form (hidden by default) -->
                <div id="change-pin-form" class="hidden mb-4 p-4 bg-black/20 rounded-xl border border-eni-yellow/20">
                  <form method="POST" action="{{ route('pin.setup') }}" class="space-y-4">
                    @csrf
                    <h5 class="text-white font-semibold mb-4">Enter New PIN</h5>

                    <div>
                      <label class="block text-white/80 text-sm mb-2">New PIN</label>
                      <div class="flex justify-center gap-2 mb-4">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-pin-input data-index="0" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-pin-input data-index="1" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-pin-input data-index="2" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-pin-input data-index="3" autocomplete="off">
                      </div>
                      <input type="hidden" id="change-pin-value" name="pin">
                    </div>

                    <div>
                      <label class="block text-white/80 text-sm mb-2">Confirm New PIN</label>
                      <div class="flex justify-center gap-2 mb-4">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-confirm-input data-index="0" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-confirm-input data-index="1" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-confirm-input data-index="2" autocomplete="off">
                        <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                               class="w-12 h-12 text-center text-xl font-bold bg-black/30 border-2 border-white/20 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                               data-change-confirm-input data-index="3" autocomplete="off">
                      </div>
                      <input type="hidden" id="change-pin-confirmation" name="pin_confirmation">
                    </div>

                    <div id="change-pin-mismatch-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 px-3 py-2 rounded-lg text-sm">
                      PINs do not match.
                    </div>

                    <div class="flex gap-2">
                      <button type="submit" id="change-pin-btn"
                              class="flex-1 bg-eni-yellow hover:bg-yellow-400 text-black px-4 py-2 rounded-lg font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                              disabled>
                        Update PIN
                      </button>
                      <button type="button" onclick="document.getElementById('change-pin-form').classList.add('hidden')"
                              class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                        Cancel
                      </button>
                    </div>
                  </form>
                </div>

                <div class="flex gap-4">
                  <button type="button" onclick="document.getElementById('change-pin-form').classList.toggle('hidden')"
                     class="flex-1 bg-eni-yellow/20 hover:bg-eni-yellow/30 text-eni-yellow border border-eni-yellow/30 px-6 py-3 rounded-xl font-semibold text-center transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Change PIN
                  </button>

                  <form method="POST" action="{{ route('pin.remove') }}" class="flex-1" onsubmit="return confirm('Are you sure you want to remove your PIN? You will need to use your password to login.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 px-6 py-3 rounded-xl font-semibold transition-colors">
                      <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                      Remove PIN
                    </button>
                  </form>
                </div>
              </div>
            @else
              <!-- PIN Not Set -->
              <div class="space-y-6">
                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4">
                  <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                      <h4 class="text-yellow-400 font-semibold">PIN Not Configured</h4>
                      <p class="text-yellow-300/80 text-sm">Set up a 4-digit PIN for faster and more secure login</p>
                    </div>
                  </div>
                </div>

                <div class="bg-black/20 rounded-xl p-4 space-y-3">
                  <h5 class="text-white font-semibold">Why set up a PIN?</h5>
                  <ul class="space-y-2 text-white/70 text-sm">
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                      </svg>
                      <strong>Lightning Fast Login:</strong> Access your account in seconds with just 4 digits
                    </li>
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                      </svg>
                      <strong>Extra Security Layer:</strong> Add an additional authentication method
                    </li>
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                      </svg>
                      <strong>Mobile Friendly:</strong> Perfect for quick access on your smartphone
                    </li>
                    <li class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                      </svg>
                      <strong>Your Control:</strong> You can change or remove it anytime
                    </li>
                  </ul>
                </div>

                <div class="bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-500/20 rounded-xl p-4">
                  <p class="text-white/80 text-sm">
                    <svg class="w-4 h-4 inline-block mr-1 text-eni-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>Note:</strong> Your PIN is securely encrypted and stored. Even we can't see it. Never share your PIN with anyone.
                  </p>
                </div>

                <!-- Inline PIN Setup Form -->
                <form method="POST" action="{{ route('pin.setup') }}" id="profile-pin-setup-form" class="space-y-6">
                  @csrf

                  <div>
                    <label class="block text-white font-semibold mb-4">Choose your 4-digit PIN</label>
                    <div class="flex justify-center gap-3 mb-6">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-setup-input data-index="0" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-setup-input data-index="1" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-setup-input data-index="2" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-setup-input data-index="3" autocomplete="off">
                    </div>
                    <input type="hidden" id="profile-pin-value" name="pin">
                  </div>

                  <div>
                    <label class="block text-white font-semibold mb-4">Confirm your PIN</label>
                    <div class="flex justify-center gap-3 mb-6">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-confirm-input data-index="0" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-confirm-input data-index="1" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-confirm-input data-index="2" autocomplete="off">
                      <input type="tel" inputmode="numeric" pattern="[0-9]" maxlength="1"
                             class="w-14 h-14 text-center text-2xl font-bold bg-black/30 border-2 border-white/20 rounded-xl text-white focus:border-eni-yellow focus:outline-none transition-colors"
                             data-pin-confirm-input data-index="3" autocomplete="off">
                    </div>
                    <input type="hidden" id="profile-pin-confirmation" name="pin_confirmation">
                  </div>

                  <div id="pin-mismatch-error" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-2 rounded-xl text-sm">
                    PINs do not match. Please try again.
                  </div>

                  <button type="submit" id="profile-setup-pin-btn"
                          class="w-full bg-gradient-to-r from-eni-yellow to-yellow-400 hover:from-yellow-400 hover:to-eni-yellow text-black px-6 py-4 rounded-xl font-bold text-center transition-all transform hover:scale-105 shadow-lg shadow-eni-yellow/20 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                          disabled>
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Set Up PIN Now
                  </button>
                </form>
              </div>
            @endif
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
      if (hash && ['personal', 'bank', 'account', 'password', 'pin'].includes(hash)) {
        toggleSection(hash);
        return;
      }

      // If there's a success message, keep the relevant section open
      const status = '{{ session("status") }}';
      if (status === 'profile-updated') {
        toggleSection('personal');
      } else if (status === 'password-updated') {
        toggleSection('password');
      } else if (status === 'pin-updated' || status === 'pin-removed') {
        toggleSection('pin');
      }
    });

    // PIN Setup Form Logic
    const setupInputs = document.querySelectorAll('[data-pin-setup-input]');
    const confirmInputs = document.querySelectorAll('[data-pin-confirm-input]');
    const pinValue = document.getElementById('profile-pin-value');
    const pinConfirmation = document.getElementById('profile-pin-confirmation');
    const setupBtn = document.getElementById('profile-setup-pin-btn');
    const mismatchError = document.getElementById('pin-mismatch-error');

    function setupPinInput(inputs, hiddenInput) {
      inputs.forEach((input, index) => {
        // Only allow numbers
        input.addEventListener('keypress', function(e) {
          if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
          }
        });

        input.addEventListener('input', function(e) {
          this.value = this.value.replace(/[^0-9]/g, '');

          if (this.value.length > 1) {
            this.value = this.value.slice(0, 1);
          }

          // Auto-advance
          if (this.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
          }

          updatePinValues();
        });

        input.addEventListener('keydown', function(e) {
          if (e.key === 'Backspace' && !this.value && index > 0) {
            inputs[index - 1].focus();
            inputs[index - 1].value = '';
          }
        });

        // Paste support
        input.addEventListener('paste', function(e) {
          e.preventDefault();
          const pastedText = (e.clipboardData || window.clipboardData).getData('text');
          const numbers = pastedText.replace(/[^0-9]/g, '');

          if (numbers.length > 0) {
            for (let i = 0; i < Math.min(numbers.length, inputs.length - index); i++) {
              inputs[index + i].value = numbers[i];
            }
            const nextIndex = Math.min(index + numbers.length, inputs.length - 1);
            inputs[nextIndex].focus();
            updatePinValues();
          }
        });
      });
    }

    function updatePinValues() {
      const setupComplete = Array.from(setupInputs).every(input => input.value.length === 1);
      const confirmComplete = Array.from(confirmInputs).every(input => input.value.length === 1);

      if (setupComplete) {
        pinValue.value = Array.from(setupInputs).map(i => i.value).join('');
      }

      if (confirmComplete) {
        pinConfirmation.value = Array.from(confirmInputs).map(i => i.value).join('');
      }

      if (setupComplete && confirmComplete) {
        const pin = pinValue.value;
        const confirm = pinConfirmation.value;

        if (pin === confirm) {
          confirmInputs.forEach(input => {
            input.style.borderColor = '#10b981'; // Green
          });
          mismatchError.classList.add('hidden');
          setupBtn.disabled = false;
        } else {
          confirmInputs.forEach(input => {
            input.style.borderColor = '#ef4444'; // Red
          });
          mismatchError.classList.remove('hidden');
          setupBtn.disabled = true;
        }
      } else {
        setupBtn.disabled = true;
        mismatchError.classList.add('hidden');
        confirmInputs.forEach(input => {
          input.style.borderColor = '';
        });
      }
    }

    if (setupInputs.length > 0) {
      setupPinInput(setupInputs, pinValue);
      setupPinInput(confirmInputs, pinConfirmation);
    }

    // Change PIN Form Logic
    const changeInputs = document.querySelectorAll('[data-change-pin-input]');
    const changeConfirmInputs = document.querySelectorAll('[data-change-confirm-input]');
    const changePinValue = document.getElementById('change-pin-value');
    const changePinConfirmation = document.getElementById('change-pin-confirmation');
    const changeBtn = document.getElementById('change-pin-btn');
    const changeMismatchError = document.getElementById('change-pin-mismatch-error');

    function updateChangePinValues() {
      const setupComplete = Array.from(changeInputs).every(input => input.value.length === 1);
      const confirmComplete = Array.from(changeConfirmInputs).every(input => input.value.length === 1);

      if (setupComplete) {
        changePinValue.value = Array.from(changeInputs).map(i => i.value).join('');
      }

      if (confirmComplete) {
        changePinConfirmation.value = Array.from(changeConfirmInputs).map(i => i.value).join('');
      }

      if (setupComplete && confirmComplete) {
        const pin = changePinValue.value;
        const confirm = changePinConfirmation.value;

        if (pin === confirm) {
          changeConfirmInputs.forEach(input => {
            input.style.borderColor = '#10b981';
          });
          changeMismatchError.classList.add('hidden');
          changeBtn.disabled = false;
        } else {
          changeConfirmInputs.forEach(input => {
            input.style.borderColor = '#ef4444';
          });
          changeMismatchError.classList.remove('hidden');
          changeBtn.disabled = true;
        }
      } else {
        changeBtn.disabled = true;
        changeMismatchError.classList.add('hidden');
        changeConfirmInputs.forEach(input => {
          input.style.borderColor = '';
        });
      }
    }

    if (changeInputs.length > 0) {
      changeInputs.forEach((input, index) => {
        input.addEventListener('keypress', function(e) {
          if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
          }
        });

        input.addEventListener('input', function(e) {
          this.value = this.value.replace(/[^0-9]/g, '');
          if (this.value.length > 1) {
            this.value = this.value.slice(0, 1);
          }
          if (this.value && index < changeInputs.length - 1) {
            changeInputs[index + 1].focus();
          }
          updateChangePinValues();
        });

        input.addEventListener('keydown', function(e) {
          if (e.key === 'Backspace' && !this.value && index > 0) {
            changeInputs[index - 1].focus();
            changeInputs[index - 1].value = '';
          }
        });

        input.addEventListener('paste', function(e) {
          e.preventDefault();
          const pastedText = (e.clipboardData || window.clipboardData).getData('text');
          const numbers = pastedText.replace(/[^0-9]/g, '');
          if (numbers.length > 0) {
            for (let i = 0; i < Math.min(numbers.length, changeInputs.length - index); i++) {
              changeInputs[index + i].value = numbers[i];
            }
            const nextIndex = Math.min(index + numbers.length, changeInputs.length - 1);
            changeInputs[nextIndex].focus();
            updateChangePinValues();
          }
        });
      });

      changeConfirmInputs.forEach((input, index) => {
        input.addEventListener('keypress', function(e) {
          if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
          }
        });

        input.addEventListener('input', function(e) {
          this.value = this.value.replace(/[^0-9]/g, '');
          if (this.value.length > 1) {
            this.value = this.value.slice(0, 1);
          }
          if (this.value && index < changeConfirmInputs.length - 1) {
            changeConfirmInputs[index + 1].focus();
          }
          updateChangePinValues();
        });

        input.addEventListener('keydown', function(e) {
          if (e.key === 'Backspace' && !this.value && index > 0) {
            changeConfirmInputs[index - 1].focus();
            changeConfirmInputs[index - 1].value = '';
          }
        });

        input.addEventListener('paste', function(e) {
          e.preventDefault();
          const pastedText = (e.clipboardData || window.clipboardData).getData('text');
          const numbers = pastedText.replace(/[^0-9]/g, '');
          if (numbers.length > 0) {
            for (let i = 0; i < Math.min(numbers.length, changeConfirmInputs.length - index); i++) {
              changeConfirmInputs[index + i].value = numbers[i];
            }
            const nextIndex = Math.min(index + numbers.length, changeConfirmInputs.length - 1);
            changeConfirmInputs[nextIndex].focus();
            updateChangePinValues();
          }
        });
      });
    }
  </script>
</body>
</html>

```
