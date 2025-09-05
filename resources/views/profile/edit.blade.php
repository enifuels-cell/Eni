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
                <div class="max-w-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-eni-yellow">PIN Security</h3>
                            <p class="text-gray-300 text-sm">Set up a 4-digit PIN for quick and secure login</p>
                        </div>
                        <i class="fas fa-shield-alt text-eni-yellow text-2xl"></i>
                    </div>

                    @if(Auth::user()->pin_hash)
                        <div class="bg-green-600/20 border border-green-500/30 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                                <div>
                                    <h4 class="text-green-400 font-medium">PIN Login Enabled</h4>
                                    <p class="text-green-300 text-sm">PIN set on {{ Auth::user()->pin_set_at?->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3">
                            <a href="{{ route('pin.setup.form') }}" 
                               class="bg-eni-yellow text-eni-dark px-4 py-2 rounded-lg font-medium hover:bg-yellow-400 transition-colors">
                                Change PIN
                            </a>
                            <form method="POST" action="{{ route('pin.remove') }}" 
                                  onsubmit="return confirm('Remove PIN login? You will need to use your password for all future logins.')" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <input type="password" name="password" placeholder="Current password" 
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white text-sm mr-2" 
                                       required>
                                <button type="submit" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    Remove PIN
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-4 mb-4">
                            <h4 class="text-blue-400 font-medium mb-2">Benefits of PIN Login:</h4>
                            <ul class="text-blue-300 text-sm space-y-1 mb-4">
                                <li>• Faster login on trusted devices</li>
                                <li>• No need to remember complex passwords</li>
                                <li>• Secure and encrypted</li>
                                <li>• Perfect for mobile devices</li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('pin.setup.form') }}" 
                           class="bg-eni-yellow text-eni-dark px-6 py-3 rounded-lg font-semibold hover:bg-yellow-400 transition-colors inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Set Up PIN Login
                        </a>
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
