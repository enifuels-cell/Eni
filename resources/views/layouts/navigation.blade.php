<nav x-data="{ open: false, aiModalOpen: false }" class="bg-eni-dark border-b border-eni-yellow/20">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <span class="text-eni-yellow font-bold text-xl">ENI</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/90 hover:text-eni-yellow border-transparent hover:border-eni-yellow font-medium">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('investments.index')" :active="request()->routeIs('investments.*')" class="text-white/90 hover:text-eni-yellow border-transparent hover:border-eni-yellow font-medium">
                        {{ __('Investments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard.franchise')" :active="request()->routeIs('dashboard.franchise')" class="text-white/90 hover:text-eni-yellow border-transparent hover:border-eni-yellow font-medium">
                        {{ __('Franchise') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Center: energIA Button -->
            <div class="hidden sm:flex sm:items-center">
                <button @click="aiModalOpen = true" class="inline-flex items-center gap-2 px-6 py-2 bg-eni-yellow text-eni-dark rounded-full font-bold text-sm hover:bg-eni-yellow/90 transition-all duration-300 shadow-lg hover:shadow-eni-yellow/30 hover:scale-105">
                    <i class="fas fa-sparkles"></i>
                    <span>energIA</span>
                </button>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-eni-dark hover:text-eni-yellow focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 hover:text-eni-dark hover:bg-eni-yellow">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link href="/pin-setup" class="text-gray-700 hover:text-eni-dark hover:bg-eni-yellow">
                            <i class="fas fa-shield-alt mr-2"></i>
                            {{ __('PIN Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-gray-700 hover:text-eni-dark hover:bg-eni-yellow">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-eni-yellow hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-eni-yellow transition duration-150 ease-in-out border border-white/20">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-eni-dark/95 backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('investments.index')" :active="request()->routeIs('investments.*')" class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                {{ __('Investments') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard.franchise')" :active="request()->routeIs('dashboard.franchise')" class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                {{ __('Franchise') }}
            </x-responsive-nav-link>
        </div>

        <!-- AI Helper Button (Mobile) -->
        <div class="px-4 py-3 border-t border-eni-yellow/20">
            <button @click="aiModalOpen = true" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-eni-yellow text-eni-dark rounded-lg font-semibold text-sm hover:bg-eni-yellow/90 transition-all duration-300 shadow-lg">
                <i class="fas fa-sparkles"></i>
                <span>energIA - AI Assistant</span>
            </button>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-eni-yellow/20">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('pin.setup.form')" class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                    <i class="fas fa-shield-alt mr-2"></i>
                    {{ __('PIN Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-white/90 hover:text-eni-yellow hover:bg-white/10 font-medium">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <!-- energIA Sidebar Modal -->
    <div x-show="aiModalOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden"
         style="display: none;">

        <!-- Backdrop -->
        <div @click="aiModalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Sidebar Panel -->
        <div x-show="aiModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="absolute right-0 top-0 h-full w-full sm:w-[500px] bg-white shadow-2xl flex flex-col">

            <!-- Header -->
            <div class="bg-gradient-to-r from-eni-dark to-eni-charcoal text-white p-6 flex items-center justify-between border-b border-eni-yellow/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-eni-yellow rounded-full flex items-center justify-center">
                        <i class="fas fa-sparkles text-eni-dark text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">energIA</h2>
                        <p class="text-xs text-white/70">A new window into Eni's world</p>
                    </div>
                </div>
                <button @click="aiModalOpen = false" class="text-white/70 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <!-- Welcome Message -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <h3 class="text-xl font-bold text-eni-dark mb-3">A new window into Eni's world</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">
                        EnergIA is an innovative tool based on artificial intelligence capabilities, which can help you navigate the contents of eni.com, quickly finding answers to your questions. EnergIA can also perform a search on a specific topic, providing the most up-to-date data available, or it can invite you to delve deeper into a topic of your interest by suggesting links and specific readings. Start now!
                    </p>
                </div>

                <!-- Question Input -->
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-eni-dark mb-3">Type your question here, and I'll do my best to answer you!</h4>
                    <div class="relative">
                        <input type="text"
                               placeholder="Ask a question"
                               class="w-full px-4 py-4 pr-12 border-2 border-gray-300 rounded-lg focus:border-eni-yellow focus:ring-2 focus:ring-eni-yellow/20 outline-none transition-all text-gray-700">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-eni-yellow rounded-full flex items-center justify-center hover:bg-eni-yellow/90 transition-all">
                            <i class="fas fa-sparkles text-eni-dark"></i>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mb-6">
                    <button class="px-4 py-2 bg-teal-600 text-white rounded-full text-sm font-medium hover:bg-teal-700 transition-colors flex items-center gap-2">
                        Disclaimer
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <button class="px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-full text-sm font-medium hover:border-gray-400 transition-colors">
                        Find out how it works
                    </button>
                </div>

                <!-- Disclaimer Box -->
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h5 class="text-lg font-bold text-eni-dark mb-3">Disclaimer</h5>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        The content made available by Eni through the use of EnergIA ("<strong>AI Contents</strong>") is generated by artificial intelligence. By using this service, the user acknowledges that they have read and accepted the terms and conditions for AI Contents available at the following link:
                        <a href="#" class="text-blue-600 hover:underline">terms and conditions of use</a>.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 bg-white border-t border-gray-200">
                <button @click="aiModalOpen = false" class="w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</nav>
