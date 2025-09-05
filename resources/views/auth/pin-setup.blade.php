<x-app-layout>
    <x-slot name="header">
        Security Settings
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-eni-yellow/20 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-eni-yellow mb-6">PIN Login Setup</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-600/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(Auth::user()->pin_hash)
                        <!-- PIN Already Set -->
                        <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-eni-yellow mr-3"></i>
                                <div>
                                    <h4 class="text-eni-yellow font-medium">PIN Login Enabled</h4>
                                    <p class="text-gray-300 text-sm">You can now use your 4-digit PIN for quick login</p>
                                    <p class="text-gray-400 text-xs mt-1">PIN set on: {{ Auth::user()->pin_set_at?->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Remove PIN Form -->
                        <form method="POST" action="{{ route('pin.remove') }}" onsubmit="return confirm('Are you sure you want to remove PIN login? You will need to use your password for all future logins.')">
                            @csrf
                            @method('DELETE')
                            
                            <div class="mb-4">
                                <label for="password" class="block text-gray-300 text-sm font-medium mb-2">
                                    Confirm your password to remove PIN
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none" 
                                       required>
                                @error('password')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>
                                Remove PIN Login
                            </button>
                        </form>

                    @else
                        <!-- Set Up PIN -->
                        <div class="mb-6">
                            <p class="text-gray-300 mb-4">Set up a 4-digit PIN for quick and convenient login on this device.</p>
                            
                            <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-4 mb-6">
                                <h4 class="text-blue-400 font-medium mb-2">Benefits of PIN Login:</h4>
                                <ul class="text-blue-300 text-sm space-y-1">
                                    <li>• Faster login on trusted devices</li>
                                    <li>• No need to remember complex passwords</li>
                                    <li>• Secure and encrypted</li>
                                    <li>• Perfect for mobile devices</li>
                                </ul>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('pin.setup') }}" id="pin-setup-form">
                            @csrf
                            
                            <div class="mb-6">
                                <label class="block text-gray-300 text-sm font-medium mb-4">
                                    Choose your 4-digit PIN
                                </label>
                                
                                <div class="flex justify-start gap-3 mb-4">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="setup-pin-1" 
                                           name="pin_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="setup-pin-2" 
                                           name="pin_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="setup-pin-3" 
                                           name="pin_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="setup-pin-4" 
                                           name="pin_display[]" 
                                           autocomplete="off">
                                </div>

                                <input type="hidden" id="pin-combined" name="pin">
                                <input type="hidden" id="pin-confirmation" name="pin_confirmation">

                                @error('pin')
                                    <div class="text-red-400 text-sm mb-4">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-300 text-sm font-medium mb-4">
                                    Confirm your PIN
                                </label>
                                
                                <div class="flex justify-start gap-3 mb-4">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="confirm-pin-1" 
                                           name="pin_confirm_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="confirm-pin-2" 
                                           name="pin_confirm_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="confirm-pin-3" 
                                           name="pin_confirm_display[]" 
                                           autocomplete="off">
                                    <input type="password" 
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors" 
                                           maxlength="1" 
                                           id="confirm-pin-4" 
                                           name="pin_confirm_display[]" 
                                           autocomplete="off">
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <button type="submit" 
                                        id="setup-pin-btn"
                                        class="bg-eni-yellow text-eni-dark font-semibold px-6 py-3 rounded-lg hover:bg-yellow-400 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        disabled>
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Set Up PIN Login
                                </button>

                                <div class="text-gray-400 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Avoid sequential or repeated numbers
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // PIN Setup Logic
        const pinInputs = document.querySelectorAll('input[name="pin_display[]"]');
        const confirmInputs = document.querySelectorAll('input[name="pin_confirm_display[]"]');
        const setupBtn = document.getElementById('setup-pin-btn');
        const pinCombined = document.getElementById('pin-combined');
        const pinConfirmation = document.getElementById('pin-confirmation');

        function setupPinNavigation(inputs, isConfirm = false) {
            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    
                    checkSetupComplete();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        }

        function checkSetupComplete() {
            const pinComplete = Array.from(pinInputs).every(input => input.value.length === 1);
            const confirmComplete = Array.from(confirmInputs).every(input => input.value.length === 1);
            
            if (pinComplete) {
                const pin = Array.from(pinInputs).map(input => input.value).join('');
                pinCombined.value = pin;
            }
            
            if (confirmComplete) {
                const confirmPin = Array.from(confirmInputs).map(input => input.value).join('');
                pinConfirmation.value = confirmPin;
            }
            
            setupBtn.disabled = !(pinComplete && confirmComplete);
            
            // Check if PINs match
            if (pinComplete && confirmComplete) {
                const pin = pinCombined.value;
                const confirm = pinConfirmation.value;
                
                if (pin !== confirm) {
                    confirmInputs.forEach(input => {
                        input.style.borderColor = '#ef4444';
                    });
                } else {
                    confirmInputs.forEach(input => {
                        input.style.borderColor = '#10b981';
                    });
                }
            }
        }

        if (pinInputs.length > 0) {
            setupPinNavigation(pinInputs);
            setupPinNavigation(confirmInputs, true);
            
            pinInputs[0].focus();
        }
    </script>
</x-app-layout>
