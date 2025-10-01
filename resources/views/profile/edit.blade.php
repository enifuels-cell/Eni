<x-app-layout>
    <x-slot name="header">
        {{ __('Profile') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- PIN Security Settings -->
            <div class="p-4 sm:p-8 bg-gray-800 border border-eni-yellow/20 shadow sm:rounded-lg">
                <div class="max-w-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-eni-yellow">
                                <i class="fas fa-shield-alt mr-2"></i>
                                PIN Security
                            </h3>
                            <p class="text-gray-300 text-sm">Set up a 4-digit PIN for quick and secure login</p>
                        </div>
                    </div>

                    @if(session('pin_success'))
                        <div class="bg-green-600/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6">
                            {{ session('pin_success') }}
                        </div>
                    @endif

                    @if(Auth::user()->pin_hash)
                        <!-- PIN Already Set -->
                        <div class="bg-green-600/20 border border-green-500/30 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                                <div>
                                    <h4 class="text-green-400 font-medium">PIN Login Enabled</h4>
                                    <p class="text-green-300 text-sm">You can now use your 4-digit PIN for quick login</p>
                                    <p class="text-green-200 text-xs mt-1">PIN set on: {{ Auth::user()->pin_set_at?->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Remove PIN Form -->
                        <form method="POST" action="{{ route('pin.remove') }}"
                              onsubmit="return confirm('Are you sure you want to remove PIN login? You will need to use your password for all future logins.')">
                            @csrf
                            @method('DELETE')

                            <div class="mb-4">
                                <label for="remove-password" class="block text-gray-300 text-sm font-medium mb-2">
                                    Confirm your password to remove PIN
                                </label>
                                <input type="password"
                                       id="remove-password"
                                       name="password"
                                       class="w-full max-w-md px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none"
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
                            <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-4 mb-6">
                                <h4 class="text-blue-400 font-medium mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Benefits of PIN Login:
                                </h4>
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
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="setup-pin-1"
                                           name="pin_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="setup-pin-2"
                                           name="pin_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="setup-pin-3"
                                           name="pin_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="setup-pin-4"
                                           name="pin_display[]"
                                           autocomplete="off"
                                           data-pin-input>
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
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="confirm-pin-1"
                                           name="pin_confirm_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="confirm-pin-2"
                                           name="pin_confirm_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="confirm-pin-3"
                                           name="pin_confirm_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                    <input type="tel"
                                           inputmode="numeric"
                                           pattern="[0-9]"
                                           class="w-14 h-14 text-center text-2xl font-bold bg-gray-700 border-2 border-gray-600 rounded-lg text-white focus:border-eni-yellow focus:outline-none transition-colors"
                                           maxlength="1"
                                           id="confirm-pin-4"
                                           name="pin_confirm_display[]"
                                           autocomplete="off"
                                           data-pin-input>
                                </div>
                            </div>

                            <div class="flex flex-wrap justify-between items-center gap-4">
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

                        <script>
                            // CSS to mask PIN inputs as dots
                            const style = document.createElement('style');
                            style.textContent = `
                                input[data-pin-input] {
                                    -webkit-text-security: disc;
                                    text-security: disc;
                                }

                                /* Remove number spinners */
                                input[data-pin-input]::-webkit-outer-spin-button,
                                input[data-pin-input]::-webkit-inner-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }

                                input[data-pin-input] {
                                    -moz-appearance: textfield;
                                }
                            `;
                            document.head.appendChild(style);

                            // PIN Setup Logic
                            const pinInputs = document.querySelectorAll('input[name="pin_display[]"]');
                            const confirmInputs = document.querySelectorAll('input[name="pin_confirm_display[]"]');
                            const setupBtn = document.getElementById('setup-pin-btn');
                            const pinCombined = document.getElementById('pin-combined');
                            const pinConfirmation = document.getElementById('pin-confirmation');

                            function setupPinNavigation(inputs, isConfirm = false) {
                                inputs.forEach((input, index) => {
                                    // Prevent any non-numeric input
                                    input.addEventListener('keypress', function(e) {
                                        if (!/[0-9]/.test(e.key)) {
                                            e.preventDefault();
                                        }
                                    });

                                    input.addEventListener('input', function(e) {
                                        // Force only numbers
                                        this.value = this.value.replace(/[^0-9]/g, '');

                                        // Limit to 1 character
                                        if (this.value.length > 1) {
                                            this.value = this.value.slice(0, 1);
                                        }

                                        // Auto-advance to next input
                                        if (this.value && index < inputs.length - 1) {
                                            inputs[index + 1].focus();
                                        }

                                        checkSetupComplete();
                                    });

                                    input.addEventListener('keydown', function(e) {
                                        // Backspace navigation
                                        if (e.key === 'Backspace' && !this.value && index > 0) {
                                            inputs[index - 1].focus();
                                            inputs[index - 1].value = '';
                                        }
                                    });

                                    // Prevent paste of non-numeric content
                                    input.addEventListener('paste', function(e) {
                                        e.preventDefault();
                                        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                                        const numbers = pastedText.replace(/[^0-9]/g, '');

                                        if (numbers.length > 0) {
                                            // Distribute pasted numbers across inputs
                                            for (let i = 0; i < Math.min(numbers.length, inputs.length - index); i++) {
                                                inputs[index + i].value = numbers[i];
                                            }

                                            // Focus the last filled input or next empty one
                                            const nextIndex = Math.min(index + numbers.length, inputs.length - 1);
                                            inputs[nextIndex].focus();

                                            checkSetupComplete();
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
                            }
                        </script>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
