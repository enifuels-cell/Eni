<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN Login - ENI Platform</title>
    <meta name="theme-color" content="#FFCD00">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-yellow': '#FFCD00',
                        'eni-dark': '#1a1a1a'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-eni-dark min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-16 w-auto">
            </div>
            <p class="text-white/70">Welcome back</p>
        </div>

        <!-- User Info -->
        <div class="bg-gray-800 border border-eni-yellow/20 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-eni-yellow rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-user text-eni-dark text-lg"></i>
                </div>
                <div>
                    <p class="text-white font-medium">{{ $user->name }}</p>
                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- PIN Entry Form -->
        <form method="POST" action="{{ route('pin.login') }}" id="pin-form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            
            <div class="mb-6">
                <label class="block text-white text-sm font-medium mb-4 text-center">
                    Enter your 4-digit PIN
                </label>
                
                <!-- PIN Display -->
                <div class="flex justify-center gap-3 mb-6">
                    <div class="w-14 h-14 flex items-center justify-center bg-gray-800 border-2 border-eni-yellow/30 rounded-lg">
                        <span class="pin-dot text-3xl text-eni-yellow opacity-0" id="dot-1">•</span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center bg-gray-800 border-2 border-eni-yellow/30 rounded-lg">
                        <span class="pin-dot text-3xl text-eni-yellow opacity-0" id="dot-2">•</span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center bg-gray-800 border-2 border-eni-yellow/30 rounded-lg">
                        <span class="pin-dot text-3xl text-eni-yellow opacity-0" id="dot-3">•</span>
                    </div>
                    <div class="w-14 h-14 flex items-center justify-center bg-gray-800 border-2 border-eni-yellow/30 rounded-lg">
                        <span class="pin-dot text-3xl text-eni-yellow opacity-0" id="dot-4">•</span>
                    </div>
                </div>

                <!-- Hidden PIN input -->
                <input type="hidden" name="pin" id="pin-input" value="">

                <!-- Number Pad -->
                <div class="grid grid-cols-3 gap-4 max-w-xs mx-auto mb-6">
                    <!-- Row 1 -->
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="1">1</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="2">2</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="3">3</button>
                    
                    <!-- Row 2 -->
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="4">4</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="5">5</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="6">6</button>
                    
                    <!-- Row 3 -->
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="7">7</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="8">8</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="9">9</button>
                    
                    <!-- Row 4 -->
                    <div></div> <!-- Empty space -->
                    <button type="button" class="dial-btn w-16 h-16 bg-gray-800 hover:bg-gray-700 border border-eni-yellow/30 rounded-full text-white text-xl font-bold transition-colors" data-number="0">0</button>
                    <button type="button" class="w-16 h-16 bg-red-600 hover:bg-red-500 border border-red-400 rounded-full text-white transition-colors" id="clear-btn" title="Clear">
                        <i class="fas fa-backspace"></i>
                    </button>
                </div>

                @error('pin')
                    <div class="text-red-400 text-sm text-center mb-4">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button type="submit" 
                        id="login-btn"
                        class="w-full bg-eni-yellow text-eni-dark font-semibold py-3 rounded-lg hover:bg-yellow-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login with PIN
                </button>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="window.location.href='{{ route('login') }}'"
                            class="flex-1 bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors text-sm">
                        Use Password Instead
                    </button>
                    
                    <button type="button" 
                            onclick="switchUser()"
                            class="flex-1 bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600 transition-colors text-sm">
                        Different User
                    </button>
                </div>
            </div>
        </form>

        <!-- Security Notice -->
        <div class="mt-6 text-center">
            <p class="text-gray-400 text-xs">
                <i class="fas fa-shield-alt mr-1"></i>
                Your PIN is encrypted and secure
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pinInput = document.getElementById('pin-input');
            const dots = document.querySelectorAll('.pin-dot');
            const dialBtns = document.querySelectorAll('.dial-btn');
            const clearBtn = document.getElementById('clear-btn');
            const loginBtn = document.getElementById('login-btn');
            const form = document.getElementById('pin-form');
            
            let currentPin = '';

            // Handle dial button clicks
            dialBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const number = this.getAttribute('data-number');
                    
                    if (currentPin.length < 4) {
                        currentPin += number;
                        updateDisplay();
                        
                        // Visual feedback
                        this.style.backgroundColor = '#FFCD00';
                        this.style.color = '#1a1a1a';
                        setTimeout(() => {
                            this.style.backgroundColor = '';
                            this.style.color = '';
                        }, 150);
                        
                        // Auto-submit when 4 digits entered
                        if (currentPin.length === 4) {
                            pinInput.value = currentPin;
                            setTimeout(() => {
                                form.submit();
                            }, 400);
                        }
                    }
                });
            });

            // Handle clear/backspace button
            clearBtn.addEventListener('click', function() {
                if (currentPin.length > 0) {
                    currentPin = currentPin.slice(0, -1);
                    updateDisplay();
                    
                    // Visual feedback
                    this.style.backgroundColor = '#dc2626';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 150);
                }
            });

            // Handle keyboard input
            document.addEventListener('keydown', function(e) {
                if (e.key >= '0' && e.key <= '9' && currentPin.length < 4) {
                    currentPin += e.key;
                    updateDisplay();
                    
                    if (currentPin.length === 4) {
                        pinInput.value = currentPin;
                        setTimeout(() => {
                            form.submit();
                        }, 400);
                    }
                } else if (e.key === 'Backspace') {
                    e.preventDefault();
                    if (currentPin.length > 0) {
                        currentPin = currentPin.slice(0, -1);
                        updateDisplay();
                    }
                } else if (e.key === 'Enter' && currentPin.length === 4) {
                    pinInput.value = currentPin;
                    form.submit();
                }
            });

            function updateDisplay() {
                // Update dots visibility with animation
                dots.forEach((dot, index) => {
                    if (index < currentPin.length) {
                        dot.style.opacity = '1';
                        dot.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            dot.style.transform = 'scale(1)';
                        }, 150);
                    } else {
                        dot.style.opacity = '0';
                        dot.style.transform = 'scale(1)';
                    }
                });
                
                // Update login button state
                loginBtn.disabled = currentPin.length !== 4;
                pinInput.value = currentPin;
            }

            // Prevent form submission with incomplete PIN
            form.addEventListener('submit', function(e) {
                if (currentPin.length !== 4) {
                    e.preventDefault();
                }
            });

            // Set initial state
            updateDisplay();
        });

        function switchUser() {
            // Clear the PIN device cookie and redirect to regular login
            document.cookie = 'pin_device=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            window.location.href = '{{ route("login") }}';
        }
    </script>
</body>
</html>
