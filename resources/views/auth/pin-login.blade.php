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
                        'eni-dark': '#121212',
                        'eni-gray': '#1E1E1E',
                        'eni-light-gray': '#2A2A2A',
                        'eni-text-primary': '#E0E0E0',
                        'eni-text-secondary': '#A0A0A0'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
</head>
<body class="bg-eni-dark text-eni-text-primary font-sans min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm p-8 bg-eni-gray rounded-2xl shadow-xl border border-eni-light-gray">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-14 w-auto">
            </div>
            <h2 class="text-2xl font-semibold text-white mb-1">PIN Login</h2>
            <p class="text-eni-text-secondary">Welcome back to the ENI Platform</p>
        </div>

        <div class="bg-eni-light-gray border border-eni-light-gray rounded-xl p-4 mb-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-eni-yellow rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-user text-eni-dark text-lg"></i>
                </div>
                <div>
                    <p class="text-white font-medium truncate">{{ $user->name }}</p>
                    <p class="text-eni-text-secondary text-sm truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('pin.login') }}" id="pin-form">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            
            <div class="mb-6">
                <label class="block text-eni-text-secondary text-sm font-medium mb-4 text-center">
                    Enter your 4-digit PIN
                </label>
                
                <div class="flex justify-center gap-3 mb-8">
                    <div class="w-12 h-16 flex items-center justify-center bg-eni-light-gray rounded-xl transition-all duration-200">
                        <span class="pin-dot text-4xl text-eni-yellow opacity-0 transform scale-0 transition-all duration-200" id="dot-1">•</span>
                    </div>
                    <div class="w-12 h-16 flex items-center justify-center bg-eni-light-gray rounded-xl transition-all duration-200">
                        <span class="pin-dot text-4xl text-eni-yellow opacity-0 transform scale-0 transition-all duration-200" id="dot-2">•</span>
                    </div>
                    <div class="w-12 h-16 flex items-center justify-center bg-eni-light-gray rounded-xl transition-all duration-200">
                        <span class="pin-dot text-4xl text-eni-yellow opacity-0 transform scale-0 transition-all duration-200" id="dot-3">•</span>
                    </div>
                    <div class="w-12 h-16 flex items-center justify-center bg-eni-light-gray rounded-xl transition-all duration-200">
                        <span class="pin-dot text-4xl text-eni-yellow opacity-0 transform scale-0 transition-all duration-200" id="dot-4">•</span>
                    </div>
                </div>

                <input type="hidden" name="pin" id="pin-input" value="">

                <div class="grid grid-cols-3 gap-3 max-w-xs mx-auto mb-6">
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="1">1</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="2">2</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="3">3</button>
                    
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="4">4</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="5">5</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="6">6</button>
                    
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="7">7</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="8">8</button>
                    <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="9">9</button>
                    
                    <div></div> <button type="button" class="dial-btn w-16 h-16 bg-eni-light-gray hover:bg-eni-yellow hover:text-eni-dark rounded-full text-white text-xl font-medium transition-colors" data-number="0">0</button>
                    <button type="button" class="w-16 h-16 bg-eni-light-gray hover:bg-red-500 rounded-full text-eni-text-secondary hover:text-white transition-colors" id="clear-btn" title="Clear">
                        <i class="fas fa-backspace text-lg"></i>
                    </button>
                </div>

                @error('pin')
                    <div class="text-red-400 text-sm text-center">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="space-y-3">
                <button type="submit" 
                        id="login-btn"
                        class="w-full bg-eni-yellow text-eni-dark font-semibold py-3 rounded-xl hover:bg-yellow-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login with PIN
                </button>

                <div class="flex gap-2">
                    <button type="button" 
                            onclick="window.location.href='{{ route('login') }}'"
                            class="flex-1 bg-transparent border border-eni-light-gray text-eni-text-secondary py-2 rounded-xl hover:bg-eni-light-gray transition-colors text-sm">
                        Use Password
                    </button>
                    
                    <button type="button" 
                            onclick="switchUser()"
                            class="flex-1 bg-transparent border border-eni-light-gray text-eni-text-secondary py-2 rounded-xl hover:bg-eni-light-gray transition-colors text-sm">
                        Different User
                    </button>
                </div>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-eni-text-secondary text-xs">
                <i class="fas fa-lock mr-1"></i>
                Your PIN is encrypted and secure.
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

            dialBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const number = this.getAttribute('data-number');
                    if (currentPin.length < 4) {
                        currentPin += number;
                        updateDisplay();
                        
                        this.classList.add('scale-110');
                        setTimeout(() => this.classList.remove('scale-110'), 150);
                        
                        if (currentPin.length === 4) {
                            pinInput.value = currentPin;
                            setTimeout(() => form.submit(), 300);
                        }
                    }
                });
            });

            clearBtn.addEventListener('click', function() {
                if (currentPin.length > 0) {
                    currentPin = currentPin.slice(0, -1);
                    updateDisplay();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key >= '0' && e.key <= '9' && currentPin.length < 4) {
                    currentPin += e.key;
                    updateDisplay();
                    
                    if (currentPin.length === 4) {
                        pinInput.value = currentPin;
                        setTimeout(() => form.submit(), 300);
                    }
                } else if (e.key === 'Backspace') {
                    e.preventDefault();
                    if (currentPin.length > 0) {
                        currentPin = currentPin.slice(0, -1);
                        updateDisplay();
                    }
                }
            });

            function updateDisplay() {
                dots.forEach((dot, index) => {
                    if (index < currentPin.length) {
                        dot.style.opacity = '1';
                        dot.style.transform = 'scale(1)';
                    } else {
                        dot.style.opacity = '0';
                        dot.style.transform = 'scale(0)';
                    }
                });
                
                loginBtn.disabled = currentPin.length !== 4;
                pinInput.value = currentPin;
            }

            form.addEventListener('submit', function(e) {
                if (currentPin.length !== 4) {
                    e.preventDefault();
                }
            });

            updateDisplay();
        });

        function switchUser() {
            document.cookie = 'pin_device=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            window.location.href = '{{ route("login") }}';
        }
    </script>
</body>
</html>
