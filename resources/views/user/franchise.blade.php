<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Opportunity - ENI Platform</title>
    <meta name="theme-color" content="#FFCD00">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-dark': '#0f172a',
                        'eni-darker': '#020617',
                        'eni-yellow': '#FFCD00',
                        'eni-yellow-light': '#FFF9E6',
                        'eni-gray': '#64748b',
                        'eni-light-gray': '#f8fafc'
                    },
                    boxShadow: {
                        'eni': '0 4px 20px rgba(255,205,0,0.08)',
                        'corporate': '0 2px 12px rgba(0,0,0,0.08)',
                        'soft': '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)'
                    },
                    fontFamily: {
                        'corporate': ['Inter', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-eni-dark min-h-screen text-white">
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="mb-12">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-eni-gray hover:text-eni-yellow mb-4 transition-all duration-200 text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-gradient-to-r from-eni-dark to-eni-darker rounded-2xl p-8 border border-slate-700/50">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-eni-yellow rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-gas-pump text-eni-dark text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">ENI Fuel Station Franchise</h1>
                            <p class="text-eni-yellow font-semibold">Premium Mini-Station Package — ₱2,000,000</p>
                        </div>
                    </div>
                    <p class="text-slate-300 leading-relaxed">
                        Partner with ENI to establish a modern, efficient fuel station. Our comprehensive franchise package includes everything needed to operate a successful mini-station with full corporate support and proven business systems.
                    </p>
                </div>
                <div class="flex justify-center">
                    <div class="relative">
                        <img src="{{ asset('Fuel.png') }}" alt="ENI Fuel Station" class="w-full max-w-md h-auto rounded-xl shadow-lg">
                        <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/20 to-transparent rounded-xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-900/50 border border-green-600 text-green-200 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-900/50 border border-red-600 text-red-200 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-8">

        <!-- LEFT: Information & Inclusions -->
        <div class="space-y-8">

            <!-- Key Metrics -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white/5 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-peso-sign text-slate-300 text-sm"></i>
                        </div>
                        <div class="text-xs uppercase tracking-wide text-slate-400 font-medium">Investment</div>
                    </div>
                    <div class="text-2xl font-bold text-white mb-1">₱2.0M</div>
                    <div class="text-xs text-slate-500">Complete turnkey solution</div>
                </div>

                <div class="bg-white/5 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-handshake text-slate-300 text-sm"></i>
                        </div>
                        <div class="text-xs uppercase tracking-wide text-slate-400 font-medium">Franchise Fee</div>
                    </div>
                    <div class="text-2xl font-bold text-white mb-1">₱500K</div>
                    <div class="text-xs text-slate-500">One-time licensing</div>
                </div>

                <div class="bg-white/5 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-file-contract text-slate-300 text-sm"></i>
                        </div>
                        <div class="text-xs uppercase tracking-wide text-slate-400 font-medium">Contract</div>
                    </div>
                    <div class="text-2xl font-bold text-white mb-1">2 Years</div>
                    <div class="text-xs text-slate-500">Renewable terms</div>
                </div>

                <div class="bg-white/5 backdrop-blur-sm border border-slate-700/50 rounded-xl p-5 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-line text-slate-300 text-sm"></i>
                        </div>
                        <div class="text-xs uppercase tracking-wide text-slate-400 font-medium">Target ROI</div>
                    </div>
                    <div class="text-2xl font-bold text-white mb-1">12-15%</div>
                    <div class="text-xs text-slate-500">Annual returns</div>
                </div>
            </div>

            <!-- Station Concept Visual -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-eye text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Station Concept</h2>
                </div>
                <div class="text-center">
                    <img src="{{ asset('Fuel.png') }}" alt="ENI Mini Fuel Station Concept" class="w-full max-w-lg mx-auto rounded-lg shadow-lg mb-4">
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Modern, compact fuel station design with professional ENI branding, efficient layout, and customer-focused amenities.
                    </p>
                </div>
            </div>

            <!-- Overview Table -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Franchise Overview</h2>
                </div>
                <div class="overflow-hidden rounded-lg border border-slate-700/50">
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-slate-700/50">
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-slate-400 font-medium">Franchise Type</td>
                            <td class="px-4 py-4 text-white">Mini fuel station / satellite (2–3 pump hoses)</td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-slate-400 font-medium">Brand Support</td>
                            <td class="px-4 py-4 text-white">Marketing • Training • Operations • Supply Chain • Signage</td>
                        </tr>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-slate-400 font-medium">Royalty / Marketing</td>
                            <td class="px-4 py-4 text-white">2–3% royalty • 1–2% marketing (if applicable)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Land & Location -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-map-marker-alt text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Land & Location Requirements</h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-6 text-sm">
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-vector-square text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white mb-2">Lot Size</div>
                            <div class="text-slate-400 leading-relaxed">300–400 sqm • 15–20 m frontage • 2–3 cars fueling concurrently</div>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-road text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white mb-2">Access</div>
                            <div class="text-slate-400 leading-relaxed">Easy entry/exit • Good sight lines • Drainage & lighting</div>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-building text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white mb-2">Zoning / Utilities</div>
                            <div class="text-slate-400 leading-relaxed">Commercial/roadside zoning • Power • Water • Internet</div>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-shield-halved text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white mb-2">Compliance</div>
                            <div class="text-slate-400 leading-relaxed">Local permits, fire safety, environmental clearances</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Infrastructure -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-gas-pump text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Station Infrastructure & Materials</h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-6 text-sm">
                    <div class="bg-white/5 rounded-lg p-4 border border-slate-700/30">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gas-pump text-slate-300 text-sm"></i>
                            </div>
                            <div class="font-semibold text-white">Pump Island & Canopy</div>
                        </div>
                        <ul class="text-slate-400 space-y-2 text-sm">
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Steel canopy with ENI branding & LED lighting</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>2–3 digital dispensers (auto shut-off)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Anti-slip concrete forecourt & bollards</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4 border border-slate-700/30">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-database text-slate-300 text-sm"></i>
                            </div>
                            <div class="font-semibold text-white">Storage & Safety</div>
                        </div>
                        <ul class="text-slate-400 space-y-2 text-sm">
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>1 double-walled underground tank (15,000–20,000 L)</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Emergency shut-off, leak detection, fire extinguishers</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>CCTV coverage & perimeter lighting</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4 border border-slate-700/30">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-store text-slate-300 text-sm"></i>
                            </div>
                            <div class="font-semibold text-white">Mini Kiosk (Optional)</div>
                        </div>
                        <ul class="text-slate-400 space-y-2 text-sm">
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>12–15 sqm pre-fab kiosk for snacks / lubricants</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Counter, shelving, small chiller (as needed)</span>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4 border border-slate-700/30">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-desktop text-slate-300 text-sm"></i>
                            </div>
                            <div class="font-semibold text-white">POS & Branding</div>
                        </div>
                        <ul class="text-slate-400 space-y-2 text-sm">
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Basic POS with inventory & sales reporting</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <div class="w-1.5 h-1.5 bg-slate-400 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Backlit ENI signs (1–2), pump decals & banners</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Fuel & Supply -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-truck text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Fuel & Product Supply</h2>
                </div>
                <div class="overflow-hidden rounded-lg border border-slate-700/50">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-800/50">
                            <tr>
                                <th class="px-4 py-4 text-left font-semibold text-slate-300">Product</th>
                                <th class="px-4 py-4 text-left font-semibold text-slate-300">Terms</th>
                                <th class="px-4 py-4 text-left font-semibold text-slate-300">Typical Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-4 font-medium text-white">Gasoline (Regular / Premium)</td>
                                <td class="px-4 py-4 text-slate-300">Weekly or on-demand delivery; HQ price guidance</td>
                                <td class="px-4 py-4 text-slate-300">2–4%</td>
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-4 font-medium text-white">Diesel (optional)</td>
                                <td class="px-4 py-4 text-slate-300">Add if local demand supports</td>
                                <td class="px-4 py-4 text-slate-300">2–4%</td>
                            </tr>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-4 font-medium text-white">Lubricants / Retail (optional)</td>
                                <td class="px-4 py-4 text-slate-300">Sell via kiosk; ENI-approved SKUs</td>
                                <td class="px-4 py-4 text-slate-300">15–20%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Operational Support -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-hands-helping text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Operational Support (Included)</h2>
                </div>
                <ul class="grid sm:grid-cols-2 gap-4 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-helmet-safety text-slate-300 text-sm"></i>
                        </div>
                        <span class="text-slate-300">Staff training: fuel handling, safety, POS, customer service</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-bullhorn text-slate-300 text-sm"></i>
                        </div>
                        <span class="text-slate-300">Marketing support: local promos & brand assets</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-book text-slate-300 text-sm"></i>
                        </div>
                        <span class="text-slate-300">Operations manual: SOPs, reporting, compliance</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-truck-fast text-slate-300 text-sm"></i>
                        </div>
                        <span class="text-slate-300">Pre-opening: site inspection, setup, initial fuel delivery</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-cloud text-slate-300 text-sm"></i>
                        </div>
                        <span class="text-slate-300">Cloud reporting: sales & inventory (via POS)</span>
                    </li>
                </ul>
            </div>

            <!-- Cost Breakdown -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-eni-yellow/20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calculator text-eni-yellow"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Investment Breakdown</h2>
                </div>
                <div class="overflow-hidden rounded-lg border border-slate-700/50">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-800/50">
                        <tr>
                            <th class="px-4 py-4 text-left font-semibold text-slate-300">Item</th>
                            <th class="px-4 py-4 text-right font-semibold text-slate-300">Amount (PHP)</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/50">
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Franchise Fee</td><td class="px-4 py-4 text-right font-semibold text-white">250,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Land Lease / Lot Development</td><td class="px-4 py-4 text-right font-semibold text-white">600,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Construction (canopy, forecourt)</td><td class="px-4 py-4 text-right font-semibold text-white">400,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Fuel Dispensers (2–3 hoses)</td><td class="px-4 py-4 text-right font-semibold text-white">400,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Underground Tank & Safety</td><td class="px-4 py-4 text-right font-semibold text-white">300,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Branding & Signage</td><td class="px-4 py-4 text-right font-semibold text-white">100,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Initial Fuel Stock</td><td class="px-4 py-4 text-right font-semibold text-white">200,000</td></tr>
                        <tr class="hover:bg-white/5 transition-colors"><td class="px-4 py-4 text-slate-300">Misc (POS, kiosk setup, utilities)</td><td class="px-4 py-4 text-right font-semibold text-white">150,000</td></tr>
                        </tbody>
                        <tfoot class="bg-slate-800/50">
                        <tr>
                            <td class="px-4 py-4 font-bold text-right text-slate-300">Total Investment</td>
                            <td class="px-4 py-4 font-bold text-right text-eni-yellow text-lg">₱2,000,000</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <p class="text-xs text-slate-500 mt-4 leading-relaxed">* Figures are indicative and may vary by city, supplier, design choices, and permitting requirements.</p>
            </div>

            <!-- Inclusions -->
            <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-check-double text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Package Inclusions</h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">ENI brand license (10 years)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">2–3 pump dispensers</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">Underground storage tank & safety gear</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">Optional 12–15 sqm mini-kiosk</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">POS & inventory management</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">Operations manual & staff training</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">Marketing support & signage package</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-check text-slate-300 text-xs"></i>
                        </div>
                        <span class="text-slate-300">Initial fuel stock</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT: Application / Status -->
        <div class="bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
            @if(isset($existingApplication) && $existingApplication)
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-clipboard-check text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Application Status</h2>
                </div>
                <div class="bg-white/10 border border-slate-600/50 rounded-lg p-5 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-medium text-white">Current Status:</span>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold
                            {{ $existingApplication->status === 'approved' ? 'bg-green-500/20 border border-green-500/50 text-green-400' :
                               ($existingApplication->status === 'rejected' ? 'bg-red-500/20 border border-red-500/50 text-red-400' : 'bg-eni-yellow/20 border border-eni-yellow/50 text-eni-yellow') }}">
                            {{ ucfirst($existingApplication->status) }}
                        </span>
                    </div>
                    <div class="text-sm text-slate-300 space-y-2">
                        <p><span class="font-medium text-white">Station Location:</span> ENI {{ $existingApplication->company_name }}</p>
                        <p><span class="font-medium text-white">Franchisee:</span> {{ $existingApplication->contact_person }}</p>
                        <p><span class="font-medium text-white">Application Date:</span> {{ $existingApplication->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($existingApplication->status === 'pending')
                    <div class="bg-white/10 border border-slate-600/50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-slate-300 text-sm"></i>
                            </div>
                            <p class="font-medium text-white">Under Review</p>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">Your fuel station application is currently under review. Our franchise team will contact you soon with an update.</p>
                    </div>
                @elseif($existingApplication->status === 'approved')
                    <div class="bg-white/10 border border-slate-600/50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-eni-yellow text-sm"></i>
                            </div>
                            <p class="font-medium text-white">Application Approved!</p>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">Congratulations! Your fuel station franchise application has been approved. Our franchise development team will contact you shortly to begin the setup process.</p>
                    </div>
                @elseif($existingApplication->status === 'rejected')
                    <div class="bg-white/10 border border-slate-600/50 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-times-circle text-slate-400 text-sm"></i>
                            </div>
                            <p class="font-medium text-white">Application Not Approved</p>
                        </div>
                        <p class="text-sm text-slate-300 leading-relaxed">Unfortunately, your previous fuel station application was not approved at this time. You may submit a new application after addressing any concerns.</p>
                    </div>
                @endif
            @else
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-file-alt text-slate-300"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Submit Your Application</h2>
                </div>

                <div class="mb-6 rounded-lg border border-slate-600/50 bg-white/10 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-700/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-info-circle text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                This application is for the <span class="font-semibold text-white">ENI Mini-Franchise Package (₱2M)</span> with a
                                <span class="font-semibold text-white">₱250K franchise fee</span> and <span class="font-semibold text-white">2-year renewable term</span>.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('user.franchise.process') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="company_name" class="block text-sm font-semibold mb-2 text-white">Station Location Identifier *</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-slate-600/50 text-white placeholder-slate-400 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-all"
                               placeholder="e.g., Downtown, Highway 95, Mall Plaza">
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">Shown publicly as "ENI [Identifier]" (e.g., ENI Downtown).</p>
                    </div>

                    <div>
                        <label for="contact_person" class="block text-sm font-semibold mb-2 text-white">Franchisee Name *</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-slate-600/50 text-white placeholder-slate-400 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-all">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-white">Contact Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-slate-600/50 text-white placeholder-slate-400 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-all">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2 text-white">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-slate-600/50 text-white placeholder-slate-400 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-all">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold mb-2 text-white">Proposed Station Location *</label>
                        <textarea id="address" name="address" rows="3" required
                                  class="w-full px-4 py-3 rounded-lg bg-white/10 border border-slate-600/50 text-white placeholder-slate-400 focus:border-eni-yellow focus:outline-none focus:ring-2 focus:ring-eni-yellow/20 transition-all resize-none"
                                  placeholder="Full address of proposed fuel station location">{{ old('address') }}</textarea>
                    </div>

                    <div class="pt-4 space-y-4">
                        <div class="text-xs text-slate-500 leading-relaxed">
                            By submitting this application, you acknowledge that indicative costs may vary based on site conditions, local regulations, and permitting requirements.
                        </div>
                        <button type="submit"
                                class="w-full bg-gradient-to-r from-eni-yellow to-yellow-400 text-eni-dark font-bold py-3.5 px-6 rounded-lg hover:from-yellow-400 hover:to-eni-yellow transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane text-sm"></i>
                            <span>Submit Franchise Application</span>
                        </button>
                    </div>
                </form>
            @endif

            <!-- Contact Information -->
            <div class="mt-8 bg-white/5 backdrop-blur-sm border border-slate-700/30 rounded-xl p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-slate-700/50 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-question-circle text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white">Need Support?</h3>
                </div>
                <div class="grid md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <div class="bg-slate-700/50 backdrop-blur-sm rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-phone text-slate-300 text-lg"></i>
                        </div>
                        <p class="font-semibold text-white mb-1">Call Us</p>
                        <p class="text-slate-400">+1 (555) 123-4567</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-slate-700/50 backdrop-blur-sm rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-envelope text-slate-300 text-lg"></i>
                        </div>
                        <p class="font-semibold text-white mb-1">Email</p>
                        <p class="text-slate-400">franchise@eni.com</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-slate-700/50 backdrop-blur-sm rounded-xl w-14 h-14 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-calendar text-slate-300 text-lg"></i>
                        </div>
                        <p class="font-semibold text-white mb-1">Schedule Meeting</p>
                        <p class="text-slate-400">Site visit consultation</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Fine print -->
    <div class="mt-12 bg-white/5 border border-slate-700/30 rounded-lg p-4">
        <p class="text-xs text-slate-500 leading-relaxed">
            <span class="font-semibold text-slate-400">Disclaimer:</span> This page summarizes the ENI mini-franchise concept with approximate figures for planning purposes. Final costs and terms depend on engineering assessments, permits, supplier pricing, and location specifics. All information is subject to change and should be verified during the formal application process.
        </p>
    </div>

</div>

<!-- Global Footer -->
@include('components.footer')

<!-- Footer Modals -->
@include('components.footer-modals')
</body>
</html>
