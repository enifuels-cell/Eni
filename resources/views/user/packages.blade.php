<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Investment Packages - ENI Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-yellow': '#FFCD00',
                        'eni-dark': '#0B2241',
                        'eni-charcoal': '#121417'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui; }
        
        /* Payment Modal Animations */
        #paymentNotAvailableModal {
            transition: opacity 0.3s ease, backdrop-filter 0.3s ease;
        }
        
        #paymentNotAvailableModal.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        #paymentNotAvailableModal > div {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        
        #paymentNotAvailableModal.hidden > div {
            transform: scale(0.95) translateY(-10px);
            opacity: 0;
        }
        
        /* Pulse animation for unavailable options */
        .payment-unavailable {
            animation: pulse-eni 0.6s ease-in-out;
        }
        
        @keyframes pulse-eni {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(255, 205, 0, 0.7);
            }
            50% { 
                box-shadow: 0 0 0 10px rgba(255, 205, 0, 0);
            }
        }
        
        .package-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .package-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(255, 205, 0, 0.2);
        }
        .package-card.selected {
            border: 2px solid #FFCD00;
            box-shadow: 0 0 20px rgba(255, 205, 0, 0.3);
        }
        
        /* Loading animation enhancements */
        .bank-option {
            transition: all 0.3s ease;
        }
        .bank-option:hover {
            transform: translateY(-2px);
        }
        
        /* Pulse animation for loading states */
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; box-shadow: 0 0 5px rgba(255, 205, 0, 0.5); }
            50% { opacity: 0.7; box-shadow: 0 0 20px rgba(255, 205, 0, 0.8); }
        }
        
        .loading-pulse {
            animation: pulse-glow 1.5s ease-in-out infinite;
        }
        
        /* Enhanced bounce animation */
        @keyframes enhanced-bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-8px,0); }
            70% { transform: translate3d(0,-4px,0); }
            90% { transform: translate3d(0,-2px,0); }
        }
        
        .enhanced-bounce {
            animation: enhanced-bounce 1.4s ease-in-out infinite;
        }
        
        /* Shimmer effect for loading */
        @keyframes shimmer {
            0% { background-position: -468px 0; }
            100% { background-position: 468px 0; }
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255, 205, 0, 0.2) 50%, transparent 100%);
            background-size: 468px 100%;
            animation: shimmer 1.5s infinite;
        }
    </style>
</head>
<body class="bg-eni-charcoal text-white min-h-screen">
    <!-- Header -->
    <header class="bg-eni-dark px-6 py-4 flex items-center justify-between shadow-md">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" class="h-8 w-auto" />
            <div>
                <h1 class="font-extrabold text-xl tracking-tight">Investment Packages</h1>
                <p class="text-sm text-white/70">Choose your investment strategy</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-white/70 hover:text-white transition-colors" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6">
                <p class="text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6">
                <ul class="text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Investment Packages Grid -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-eni-yellow mb-2">Investment Packages</h2>
            <p class="text-white/70 mb-8">Click any package image to start investing</p>
            
            <!-- Dynamic Packages from Database -->
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                
                @foreach($packages as $package)
                <div class="package-card cursor-pointer hover:scale-105 transition-all duration-300" 
                     onclick='openPaymentForm({{ $package->id }}, {!! json_encode($package->name) !!}, {{ $package->min_amount }}, {{ $package->max_amount }}, {{ $package->daily_shares_rate }})'>
                    
                    <div class="text-center relative min-h-[400px]">
                        <!-- Elevated loading placeholder -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-white/5 backdrop-blur-md rounded-2xl border-2 border-eni-yellow/40 shadow-2xl flex items-center justify-center z-10" id="placeholder-{{ $package->id }}">
                            <div class="text-center p-12 bg-eni-dark/60 rounded-xl border border-eni-yellow/30">
                                <div class="w-20 h-20 bg-gradient-to-br from-eni-yellow to-eni-yellow/60 rounded-full mb-6 mx-auto flex items-center justify-center shadow-xl">
                                    <div class="animate-pulse w-12 h-12 bg-white/30 rounded-full"></div>
                                </div>
                                <div class="text-eni-yellow font-bold text-lg">Investment Package</div>
                                <div class="text-white/80 text-sm mt-2">Loading your options...</div>
                                <div class="mt-4 px-6 py-2 bg-eni-yellow/20 rounded-full border border-eni-yellow/50">
                                    <span class="text-eni-yellow text-xs font-semibold">ENI Platform</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Direct image from database with no text overlay -->
                        <img src="{{ asset($package->image) }}" 
                             alt="{{ $package->name }} Investment Package" 
                             class="w-full max-w-sm mx-auto rounded-lg object-contain shadow-lg hover:opacity-80 transition-opacity duration-300 relative z-20"
                             onload="setTimeout(function(){ document.getElementById('placeholder-{{ $package->id }}').style.display='none'; }, 1000)"
                             onerror="document.getElementById('placeholder-{{ $package->id }}').innerHTML='<div class=\'text-center p-12 bg-eni-dark/60 rounded-xl border border-eni-yellow/30\'><div class=\'w-20 h-20 bg-gradient-to-br from-eni-yellow/30 to-eni-yellow/10 rounded-xl mx-auto mb-6 flex items-center justify-center shadow-lg border border-eni-yellow/30\'><div class=\'w-8 h-8 bg-eni-yellow/60 rounded-lg\'></div></div><div class=\'text-eni-yellow font-bold text-lg\'>Investment Package</div><div class=\'text-white/60 text-sm mt-2\'>Preview unavailable</div></div>'">
                    </div>
                </div>
                @endforeach

            </div>
        </div>        <!-- Investment Form -->
        <div id="investment-form" class="bg-white/5 rounded-2xl p-8 backdrop-blur" style="display: none;">
            <h3 class="text-2xl font-bold text-eni-yellow mb-6">Complete Your Investment</h3>
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-red-400 mb-2">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside text-red-300 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500/50 rounded-lg p-4 mb-6">
                    <p class="text-red-300">{{ session('error') }}</p>
                </div>
            @endif
            
            <!-- Selected Package Info -->
            <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-eni-yellow mb-2">Selected Package</h4>
                <div id="package-info" class="text-white/80 text-sm"></div>
            </div>
            
            <!-- Account Balance Info -->
            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-400 mb-2">Your Account Balance</h4>
                <div class="text-white/80 text-lg font-semibold">${{ number_format($accountBalance ?? 0, 2) }}</div>
                <p class="text-white/60 text-sm">Available for instant investment</p>
            </div>
            
            <form id="investment-form-element" method="POST" action="{{ route('user.deposit.process') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" id="selected_package_id">
                
                <!-- Investment Amount -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Investment Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 text-white/60">$</span>
                        <input type="number" name="amount" id="investment_amount" step="0.01" required
                               class="w-full bg-white/10 border border-white/20 rounded-lg pl-8 pr-4 py-4 text-white placeholder-white/40 focus:ring-2 focus:ring-eni-yellow focus:border-transparent"
                               placeholder="Enter amount">
                    </div>
                    <p id="amount-limits" class="text-white/60 text-sm mt-2"></p>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-white/80 font-semibold mb-3">Payment Method</label>
                    <div class="relative">
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="payment_method" id="paymentMethodInput" required>
                        
                        <!-- Custom dropdown trigger -->
                        <div id="paymentMethodDropdown" onclick="togglePaymentDropdown()" 
                             class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-4 text-white focus:ring-2 focus:ring-eni-yellow focus:border-transparent cursor-pointer flex items-center justify-between min-h-[50px]">
                            <span id="selectedPaymentText" class="text-white/60">Select payment method</span>
                            <svg class="w-5 h-5 text-white/60 transform transition-transform" id="dropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        <!-- Dropdown options -->
                        <div id="paymentMethodOptions" class="absolute top-full left-0 right-0 bg-eni-dark border border-white/20 rounded-lg mt-1 z-50 hidden max-h-64 overflow-y-auto">
                            <div class="p-2 space-y-1">
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('', 'Select payment method', '')">
                                    <span class="text-white/60">Select payment method</span>
                                </div>
                                
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('account_balance', '💰 Account Balance (${{ number_format($accountBalance ?? 0, 2) }} available)', '')">
                                    <span class="text-2xl mr-3">💰</span>
                                    <span class="text-white">Account Balance (${{ number_format($accountBalance ?? 0, 2) }} available)</span>
                                </div>
                                
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('bank_transfer', '🏦 Bank Transfer', '')">
                                    <span class="text-2xl mr-3">🏦</span>
                                    <span class="text-white">Bank Transfer</span>
                                </div>
                                
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('credit_card', 'Credit Card', '')">
                                    <svg class="w-6 h-4 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                        <line x1="1" y1="10" x2="23" y2="10"/>
                                    </svg>
                                    <span class="text-white">Credit Card</span>
                                </div>
                                
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('paypal', 'PayPal', '{{ asset('Paypal.png') }}')">
                                    <img src="{{ asset('Paypal.png') }}" alt="PayPal" class="w-8 h-6 mr-3 object-contain">
                                    <span class="text-white">PayPal</span>
                                </div>
                                
                                <div class="payment-option flex items-center p-3 hover:bg-white/10 rounded-lg cursor-pointer transition-colors" 
                                     onclick="selectPaymentMethod('cryptocurrency', 'Cryptocurrency', '{{ asset('crypto.jpg') }}')">
                                    <img src="{{ asset('crypto.jpg') }}" alt="Cryptocurrency" class="w-8 h-6 mr-3 object-contain rounded">
                                    <span class="text-white">Cryptocurrency</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bank Selection (Hidden by default) -->
                <div id="bankSelection" class="mb-6 hidden">
                    <label class="block text-white/80 font-semibold mb-3">Select Bank</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bank-option bg-white/10 border border-white/20 rounded-lg p-4 cursor-pointer hover:bg-white/20 transition-colors"
                             onclick="selectBank('landbank')">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🏦</div>
                                <div class="text-white font-semibold">LandBank</div>
                                <div class="text-white/60 text-sm">Land Bank of the Philippines</div>
                            </div>
                        </div>
                        <div class="bank-option bg-white/10 border border-white/20 rounded-lg p-4 cursor-pointer hover:bg-white/20 transition-colors"
                             onclick="selectBank('bpi')">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🏛️</div>
                                <div class="text-white font-semibold">BPI</div>
                                <div class="text-white/60 text-sm">Bank of the Philippine Islands</div>
                            </div>
                        </div>
                        <div class="bank-option bg-white/10 border border-white/20 rounded-lg p-4 cursor-pointer hover:bg-white/20 transition-colors"
                             onclick="selectBank('rcbc')">
                            <div class="text-center">
                                <div class="text-2xl mb-2">🏪</div>
                                <div class="text-white font-semibold">RCBC</div>
                                <div class="text-white/60 text-sm">Rizal Commercial Banking Corporation</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="selected_bank" id="selectedBank">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-eni-yellow text-eni-dark font-bold py-4 rounded-lg hover:bg-yellow-400 transition-colors text-lg">
                    Proceed with Investment
                </button>
            </form>
        </div>
    </div>

    <!-- Terms of Service Modal -->
    <div id="termsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        📜 ENI Investment Platform — Terms of Service
                    </h2>
                    <button onclick="closeTermsModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-4 text-sm leading-relaxed">
                        
                        <div class="text-white/80 mb-6">
                            <p><strong>Effective Date:</strong> September 1, 2025</p>
                            <p><strong>Last Updated:</strong> September 1, 2025</p>
                        </div>

                        <p class="text-white/90">
                            These Terms of Service ("Terms") constitute a legally binding agreement between you ("User," "Investor," or "Client") and ENI Investment Platform ("ENI," "we," "our," or "us") governing your access to and use of our website, mobile application, products, and investment services (collectively, the "Services").
                        </p>

                        <p class="text-white/90">
                            By creating an account, accessing, or using the Services, you acknowledge that you have read, understood, and agreed to these Terms, our Privacy Policy, and Risk Disclosure Statement. If you do not agree, you must discontinue use immediately.
                        </p>

                        <div class="space-y-6 mt-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">1. Eligibility & Client Verification</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Users must be at least 18 years of age and possess the legal capacity to enter into binding contracts.</li>
                                    <li>• ENI reserves the right to conduct identity verification (KYC) and anti-money laundering (AML) checks in compliance with applicable laws and regulations.</li>
                                    <li>• Provision of false, incomplete, or misleading information may result in account suspension or termination.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">2. Nature of Investments & Risk Acknowledgement</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• All investments involve market risk, including but not limited to the loss of principal capital.</li>
                                    <li>• Past performance is not indicative of future results.</li>
                                    <li>• By investing, you acknowledge that you have the necessary knowledge and financial capacity to undertake such risks and that ENI is not liable for individual investment decisions.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">3. Accounts, Security & Confidentiality</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Clients are responsible for safeguarding their login credentials and ensuring account security.</li>
                                    <li>• ENI shall not be held liable for losses resulting from unauthorized account access unless directly caused by our negligence.</li>
                                    <li>• Clients must notify ENI immediately of any suspected security breach.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">4. Investment Packages & Returns</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI may offer multiple investment packages with specified terms, returns, referral incentives, and maturity dates.</li>
                                    <li>• Interest and yield projections are illustrative only and subject to change based on market and operational conditions.</li>
                                    <li>• ENI reserves the right to amend, suspend, or discontinue any investment product with prior notice to clients.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">5. Referral & Incentive Program</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Referral incentives (5%-15% commission based on package tier) apply only to verified and compliant accounts.</li>
                                    <li>• Abuse of the referral system, including fraudulent registrations or self-referrals, will result in forfeiture of benefits and potential account suspension.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">6. Permitted & Prohibited Use</h3>
                                <p class="text-white/80 mb-2">Clients agree to use the Services strictly for lawful investment purposes. The following are strictly prohibited:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Engaging in money laundering, fraud, or market manipulation.</li>
                                    <li>• Unauthorized access, system tampering, or exploitation of the platform.</li>
                                    <li>• Misrepresentation of affiliation with ENI.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">7. Fees, Charges & Taxes</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI may impose service fees, withdrawal charges, or administrative costs as disclosed in product documentation.</li>
                                    <li>• Clients are solely responsible for reporting and paying applicable taxes on investment income in accordance with their jurisdiction.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">8. Termination & Suspension</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI may suspend or terminate a client's access if they breach these Terms, fail compliance checks, or engage in misconduct.</li>
                                    <li>• Clients may close their accounts by submitting a written request, subject to completion of outstanding obligations.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">9. Limitation of Liability</h3>
                                <p class="text-white/80 mb-2">To the fullest extent permitted by law, ENI shall not be liable for:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Losses arising from market volatility or investment risks.</li>
                                    <li>• Downtime, interruptions, or technical issues beyond our reasonable control.</li>
                                    <li>• Indirect, incidental, or consequential damages.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">10. Governing Law & Dispute Resolution</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• These Terms are governed by the laws of [Insert Jurisdiction].</li>
                                    <li>• Any dispute shall be resolved through arbitration or the competent courts of [Insert Location], unless otherwise required by law.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">11. Amendments</h3>
                                <p class="text-white/80">ENI reserves the right to update these Terms from time to time. Clients will be notified of material changes, and continued use of the Services constitutes acceptance of revised Terms.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">12. Contact Information</h3>
                                <p class="text-white/80 mb-2">For questions, concerns, or compliance matters, please contact:</p>
                                <div class="text-white/80 ml-4">
                                    <p><strong>ENI Investment Platform Legal Department</strong></p>
                                    <p>📧 Email: legal@eni-investment.com</p>
                                    <p>📞 Phone: +1 (555) 123-4567</p>
                                    <p>🏢 Address: 123 Financial District, Investment Tower, Suite 4500</p>
                                </div>
                            </div>

                            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mt-6">
                                <p class="text-red-400 font-semibold">⚖️ Disclaimer:</p>
                                <p class="text-white/80">Investments carry risk, and there is no guarantee of profit. Clients should seek independent financial advice before making investment decisions.</p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeTermsModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        🔒 ENI Investment Platform — Privacy Policy
                    </h2>
                    <button onclick="closePrivacyModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-4 text-sm leading-relaxed">
                        
                        <div class="text-white/80 mb-6">
                            <p><strong>Effective Date:</strong> September 1, 2025</p>
                            <p><strong>Last Updated:</strong> September 1, 2025</p>
                        </div>

                        <p class="text-white/90">
                            At ENI Investment Platform ("ENI," "we," "our," "us"), we are committed to protecting the privacy, confidentiality, and security of our clients' personal and financial information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you access or use our services.
                        </p>

                        <p class="text-white/90">
                            By using our platform, you agree to the practices described in this Privacy Policy.
                        </p>

                        <div class="space-y-6 mt-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">1. Information We Collect</h3>
                                <p class="text-white/80 mb-2">We may collect the following categories of information:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• <strong>Personal Identification:</strong> Name, date of birth, address, phone number, email, nationality.</li>
                                    <li>• <strong>Verification Documents:</strong> Government-issued IDs, proof of address, KYC/AML documents.</li>
                                    <li>• <strong>Financial Information:</strong> Bank details, payment records, transaction history.</li>
                                    <li>• <strong>Platform Usage:</strong> Login activity, IP address, device type, cookies, and browsing behavior.</li>
                                    <li>• <strong>Communication Data:</strong> Records of correspondence via email, chat, or phone.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">2. How We Use Your Information</h3>
                                <p class="text-white/80 mb-2">We use collected data for the following purposes:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• To create and maintain your account.</li>
                                    <li>• To process investments, deposits, withdrawals, and referral incentives.</li>
                                    <li>• To comply with legal, regulatory, and AML/KYC obligations.</li>
                                    <li>• To communicate important updates, policy changes, or service improvements.</li>
                                    <li>• To enhance security, detect fraud, and ensure compliance.</li>
                                    <li>• To provide customer support and resolve inquiries.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">3. Data Sharing & Disclosure</h3>
                                <p class="text-white/80 mb-2">We may share information in the following circumstances:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• <strong>Regulatory Authorities:</strong> To comply with laws, court orders, or regulatory reporting.</li>
                                    <li>• <strong>Service Providers:</strong> Trusted third parties that support our IT, payment, and compliance operations.</li>
                                    <li>• <strong>Business Transfers:</strong> In the event of a merger, acquisition, or restructuring.</li>
                                    <li>• <strong>Legal Protection:</strong> To enforce our Terms of Service, protect our rights, or prevent unlawful activity.</li>
                                </ul>
                                <p class="text-white/80 mt-2 font-semibold">We do not sell, rent, or trade your personal data to third parties for marketing purposes.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">4. Data Security</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• We implement industry-standard encryption, secure servers, and multi-factor authentication.</li>
                                    <li>• Access to personal information is restricted to authorized personnel only.</li>
                                    <li>• While we take all reasonable precautions, no online transmission is 100% secure.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">5. Data Retention</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• We retain personal data only as long as necessary to fulfill the purposes outlined in this Policy or as required by law.</li>
                                    <li>• Upon account closure, certain records may be stored for compliance with financial regulations.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">6. Your Rights</h3>
                                <p class="text-white/80 mb-2">Depending on your jurisdiction, you may have the right to:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Access the personal data we hold about you.</li>
                                    <li>• Request correction or deletion of your data.</li>
                                    <li>• Restrict or object to certain data processing activities.</li>
                                    <li>• Withdraw consent for optional data uses (e.g., marketing).</li>
                                    <li>• Request a copy of your data in a portable format.</li>
                                </ul>
                                <p class="text-white/80 mt-2">Requests can be submitted via privacy@eni-investment.com.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">7. Cookies & Tracking</h3>
                                <p class="text-white/80 mb-2">We use cookies and similar technologies to:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Improve site functionality.</li>
                                    <li>• Analyze traffic and usage patterns.</li>
                                    <li>• Personalize your user experience.</li>
                                </ul>
                                <p class="text-white/80 mt-2">You may adjust your browser settings to manage or disable cookies.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">8. International Data Transfers</h3>
                                <p class="text-white/80">Your information may be processed or stored in jurisdictions outside your home country, subject to local laws and protections.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">9. Updates to This Policy</h3>
                                <p class="text-white/80">We may update this Privacy Policy periodically. Material changes will be communicated via the platform or email. Continued use of our Services constitutes acceptance of the updated Policy.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">10. Contact Information</h3>
                                <p class="text-white/80 mb-2">If you have questions, concerns, or requests regarding this Privacy Policy, please contact:</p>
                                <div class="text-white/80 ml-4">
                                    <p><strong>ENI Investment Platform — Data Protection Office</strong></p>
                                    <p>📧 Email: privacy@eni-investment.com</p>
                                    <p>📞 Phone: +1 (555) 123-4567</p>
                                    <p>🏢 Address: 123 Financial District, Investment Tower, Suite 4500</p>
                                </div>
                            </div>

                            <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mt-6">
                                <p class="text-blue-400 font-semibold">⚖️ Note:</p>
                                <p class="text-white/80">This Privacy Policy is provided for informational purposes and should be reviewed by legal counsel to ensure compliance with local regulations such as GDPR, CCPA, or applicable financial privacy laws.</p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closePrivacyModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Risk Disclosure Modal -->
    <div id="riskModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        ⚠️ ENI Investment Platform — Risk Disclosure Statement
                    </h2>
                    <button onclick="closeRiskModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-4 text-sm leading-relaxed">
                        
                        <div class="text-white/80 mb-6">
                            <p><strong>Effective Date:</strong> September 1, 2025</p>
                            <p><strong>Last Updated:</strong> September 1, 2025</p>
                        </div>

                        <p class="text-white/90">
                            Investing through the ENI Investment Platform ("ENI," "we," "our," or "us") involves financial risk. This Risk Disclosure Statement is designed to help you understand the nature of these risks. By using our Services, you acknowledge and accept the risks outlined below.
                        </p>

                        <div class="space-y-6 mt-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">1. General Investment Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• All investments carry the potential for both profit and loss.</li>
                                    <li>• There is no guarantee of returns or protection of invested capital.</li>
                                    <li>• Market volatility, global events, and economic conditions may negatively affect your investments.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">2. Market Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Investment values may fluctuate due to changes in supply and demand, government policies, energy sector developments, or unforeseen global events.</li>
                                    <li>• Energy-related investments (renewables, biofuels, conventional energy) may be more sensitive to regulatory or environmental changes.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">3. Liquidity Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Some investment products may not be easily sold or withdrawn before maturity.</li>
                                    <li>• Early withdrawal may result in penalties or reduced returns.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">4. Credit & Counterparty Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI relies on third-party partners, custodians, and financial institutions.</li>
                                    <li>• Failure of a counterparty to meet its obligations may cause losses to investors.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">5. Operational Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Technology systems, cybersecurity incidents, or platform outages may disrupt services.</li>
                                    <li>• While ENI employs safeguards, unforeseen technical issues may delay transactions.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">6. Regulatory & Legal Risk</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Changes in laws, regulations, or government policies may impact investment performance.</li>
                                    <li>• Compliance requirements (AML, KYC) may restrict access if documentation is incomplete or inaccurate.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">7. Past Performance Disclaimer</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Historical returns are not a guarantee of future performance.</li>
                                    <li>• Illustrative APRs or yield projections shown in marketing materials are for example purposes only.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">8. Investor Responsibility</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• You are solely responsible for evaluating investment suitability given your financial condition and risk tolerance.</li>
                                    <li>• ENI strongly recommends seeking independent financial advice before committing funds.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">9. Limitation of Liability</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI is not liable for losses resulting from market movements, client decisions, or force majeure events.</li>
                                    <li>• Your capital is at risk and you may lose part or all of your investment.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">10. Acknowledgement</h3>
                                <p class="text-white/80 mb-2">By using ENI's Services, you confirm that you:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Have read and understood this Risk Disclosure Statement.</li>
                                    <li>• Accept and assume the risks associated with investing.</li>
                                    <li>• Will not hold ENI liable for losses incurred due to normal investment risks.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">📩 Contact Information</h3>
                                <p class="text-white/80 mb-2">For inquiries regarding this Risk Disclosure Statement, please contact:</p>
                                <div class="text-white/80 ml-4">
                                    <p><strong>ENI Investment Platform — Risk & Compliance Department</strong></p>
                                    <p>📧 Email: risk@eni-investment.com</p>
                                    <p>📞 Phone: +1 (555) 123-4567</p>
                                    <p>🏢 Address: 123 Financial District, Investment Tower, Suite 4500</p>
                                </div>
                            </div>

                            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mt-6">
                                <p class="text-red-400 font-semibold">⚖️ Disclaimer:</p>
                                <p class="text-white/80">This document is provided for informational purposes only. It does not constitute financial advice. Investors should consult licensed advisors in their jurisdiction before making decisions.</p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeRiskModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Anti-Money Laundering Modal -->
    <div id="amlModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        🛡️ ENI Investment Platform — Anti-Money Laundering (AML) Policy
                    </h2>
                    <button onclick="closeAmlModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-4 text-sm leading-relaxed">
                        
                        <div class="text-white/80 mb-6">
                            <p><strong>Effective Date:</strong> September 1, 2025</p>
                            <p><strong>Last Updated:</strong> September 1, 2025</p>
                        </div>

                        <p class="text-white/90">
                            ENI Investment Platform ("ENI," "we," "our," or "us") is committed to conducting business in accordance with the highest ethical standards and in full compliance with applicable Anti-Money Laundering (AML) and Counter-Terrorist Financing (CTF) regulations. This AML Policy outlines the principles and procedures we follow to prevent money laundering, terrorist financing, and other financial crimes.
                        </p>

                        <div class="space-y-6 mt-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">1. Policy Objective</h3>
                                <p class="text-white/80 mb-2">The purpose of this AML Policy is to:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Protect ENI, its clients, and partners from being used for money laundering or terrorist financing.</li>
                                    <li>• Comply with relevant AML/CTF laws and regulations in all jurisdictions where ENI operates.</li>
                                    <li>• Promote transparency, accountability, and integrity in all investment activities.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">2. Know Your Customer (KYC) Requirements</h3>
                                <p class="text-white/80 mb-2">ENI enforces strict KYC protocols before onboarding clients:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Verification of identity (government-issued ID, passport, driver's license).</li>
                                    <li>• Proof of address (utility bill, bank statement, lease agreement).</li>
                                    <li>• Screening against international watchlists (OFAC, UN, EU, PEPs).</li>
                                    <li>• Enhanced due diligence (EDD) for high-risk clients or transactions.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">3. Risk-Based Approach</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI adopts a risk-based approach (RBA) to AML compliance.</li>
                                    <li>• Clients and transactions are categorized as low, medium, or high risk.</li>
                                    <li>• High-risk clients may be subject to additional verification, ongoing monitoring, or refusal of service.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">4. Monitoring & Reporting</h3>
                                <p class="text-white/80 mb-2">All transactions are monitored for suspicious or unusual activity, including:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Large, unexplained deposits/withdrawals.</li>
                                    <li>• Multiple accounts under the same client.</li>
                                    <li>• Transactions inconsistent with the client's profile.</li>
                                </ul>
                                <p class="text-white/80 mt-2">Suspicious Activity Reports (SARs) will be filed with regulatory authorities where required.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">5. Record Keeping</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI retains client identification, transaction records, and communication logs for a minimum of 5 years (or longer if required by law).</li>
                                    <li>• Records must be securely stored and accessible to regulators upon request.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">6. Employee Training</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• All ENI employees and representatives receive ongoing AML training.</li>
                                    <li>• Staff are trained to recognize red flags, escalate suspicious activity, and comply with reporting requirements.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">7. Prohibited Activities</h3>
                                <p class="text-white/80 mb-2">ENI strictly prohibits:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Anonymous or fictitious accounts.</li>
                                    <li>• Transactions involving sanctioned individuals, countries, or entities.</li>
                                    <li>• Use of ENI's platform for criminal activity, terrorist financing, or tax evasion.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">8. Compliance & Oversight</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI maintains a dedicated AML Compliance Officer (AMLCO) responsible for policy implementation, oversight, and regulatory liaison.</li>
                                    <li>• The AMLCO reports directly to senior management and ensures continuous improvement of compliance programs.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">9. Consequences of Non-Compliance</h3>
                                <p class="text-white/80 mb-2">Clients found in violation of AML laws or ENI's AML Policy may face:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Account suspension or termination.</li>
                                    <li>• Freezing of funds pending investigation.</li>
                                    <li>• Reporting to relevant regulatory and law enforcement agencies.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">10. Policy Review</h3>
                                <p class="text-white/80">This AML Policy is reviewed annually and updated as necessary to remain compliant with evolving laws, regulations, and best practices.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">📩 Contact Information</h3>
                                <p class="text-white/80 mb-2">For questions or concerns regarding this AML Policy, please contact:</p>
                                <div class="text-white/80 ml-4">
                                    <p><strong>ENI Investment Platform — Compliance Department</strong></p>
                                    <p>📧 Email: compliance@eni-investment.com</p>
                                    <p>📞 Phone: +1 (555) 123-4567</p>
                                    <p>🏢 Address: 123 Financial District, Investment Tower, Suite 4500</p>
                                </div>
                            </div>

                            <div class="bg-orange-500/10 border border-orange-500/30 rounded-lg p-4 mt-6">
                                <p class="text-orange-400 font-semibold">⚖️ Disclaimer:</p>
                                <p class="text-white/80">This AML Policy is designed to comply with global AML/CTF standards. Local regulations may vary, and ENI will adjust implementation as required.</p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeAmlModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Investment Guidelines Modal -->
    <div id="guidelinesModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        📊 ENI Investment Platform — Investment Guidelines
                    </h2>
                    <button onclick="closeGuidelinesModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-4 text-sm leading-relaxed">
                        
                        <div class="text-white/80 mb-6">
                            <p><strong>Effective Date:</strong> September 1, 2025</p>
                            <p><strong>Last Updated:</strong> September 1, 2025</p>
                        </div>

                        <p class="text-white/90">
                            The following Investment Guidelines outline the principles, rules, and best practices governing the use of the ENI Investment Platform ("ENI," "we," "our," or "us"). These guidelines are designed to protect investors, ensure transparency, and promote responsible investment aligned with ENI's commitment to sustainability and innovation.
                        </p>

                        <div class="space-y-6 mt-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">1. Purpose</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• To provide clear rules and standards for investors using ENI's products and services.</li>
                                    <li>• To promote responsible, compliant, and sustainable investment practices.</li>
                                    <li>• To align client objectives with ENI's energy-focused portfolio and growth strategies.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">2. Investor Eligibility</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Investors must be at least 18 years of age and legally capable of entering into binding contracts.</li>
                                    <li>• All clients must complete Know Your Customer (KYC) verification in compliance with ENI's AML policy.</li>
                                    <li>• Only verified accounts are eligible to participate in investment packages.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">3. Investment Packages</h3>
                                <p class="text-white/80 mb-2">ENI provides tiered investment options, such as:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• <strong>Capital Package</strong> (conservative, entry-level)</li>
                                    <li>• <strong>Energy Package</strong> (balanced growth)</li>
                                    <li>• <strong>Growth Package</strong> (premium, high-yield)</li>
                                </ul>
                                <p class="text-white/80 mt-3 mb-2">Each package has defined:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Minimum investment amount</li>
                                    <li>• Daily interest yield</li>
                                    <li>• Capital release duration</li>
                                    <li>• Referral incentives (e.g., 5%)</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">4. Risk Disclosure</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• All investments involve risk, including the potential loss of capital.</li>
                                    <li>• Past performance and projected APRs are illustrative only and not guaranteed.</li>
                                    <li>• Investors should review ENI's Risk Disclosure Statement before making commitments.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">5. Investor Responsibilities</h3>
                                <p class="text-white/80 mb-2">Investors are expected to:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Review all package details before investing.</li>
                                    <li>• Maintain accurate and up-to-date account information.</li>
                                    <li>• Comply with ENI's Terms of Service, Privacy Policy, and AML/KYC requirements.</li>
                                    <li>• Seek independent financial advice if uncertain about risk tolerance.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">6. Diversification & Allocation</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Investors are encouraged to diversify across multiple packages or projects to manage risk.</li>
                                    <li>• ENI may adjust allocations based on market performance, sustainability targets, and compliance with investment limits.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">7. Withdrawals & Payouts</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Withdrawal requests must comply with package-specific terms (e.g., 30 days, 45 days, 60 days).</li>
                                    <li>• Early withdrawal may be subject to penalties or reduced returns.</li>
                                    <li>• Payouts (interest/dividends) will be credited to the client's account balance according to the package schedule.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">8. Referral Program</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Investors may earn referral incentives for introducing new verified clients.</li>
                                    <li>• Referral benefits are subject to compliance checks and fair use rules.</li>
                                    <li>• Misuse of the referral system will result in forfeiture of rewards.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">9. Prohibited Activities</h3>
                                <p class="text-white/80 mb-2">Investors must not:</p>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• Use the platform for fraudulent or illegal activity.</li>
                                    <li>• Attempt to manipulate or exploit platform systems.</li>
                                    <li>• Circumvent KYC, AML, or regulatory requirements.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">10. Governance & Compliance</h3>
                                <ul class="space-y-2 text-white/80 ml-4">
                                    <li>• ENI reserves the right to modify investment products, rates, and terms with prior notice.</li>
                                    <li>• All investments are subject to local and international regulations.</li>
                                    <li>• ENI's Compliance Department oversees adherence to these guidelines.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">11. Disclaimer</h3>
                                <p class="text-white/80">Investing with ENI involves risk, including potential loss of capital. ENI makes no guarantees of profitability. All investors should evaluate their financial situation and risk appetite before participating.</p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">📩 Contact Information</h3>
                                <p class="text-white/80 mb-2">For questions regarding these Investment Guidelines, please contact:</p>
                                <div class="text-white/80 ml-4">
                                    <p><strong>ENI Investment Platform — Investment Advisory Team</strong></p>
                                    <p>📧 Email: advisory@eni-investment.com</p>
                                    <p>📞 Phone: +1 (555) 123-4567</p>
                                    <p>🏢 Address: 123 Financial District, Investment Tower, Suite 4500</p>
                                </div>
                            </div>

                            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 mt-6">
                                <p class="text-green-400 font-semibold">⚖️ Note:</p>
                                <p class="text-white/80">These guidelines are for general governance purposes. They should be reviewed by legal counsel to ensure compliance with your local financial regulations.</p>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeGuidelinesModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- About ENI Modal -->
    <div id="aboutModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        🏢 About ENI Investment Platform
                    </h2>
                    <button onclick="closeAboutModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    <div class="space-y-6 text-sm leading-relaxed">
                        
                        <p class="text-white/90 text-base">
                            ENI Investment Platform operates as part of ENI's global strategy to integrate traditional energy expertise with forward-looking, sustainable investment opportunities. As a multinational energy company, ENI S.p.A. is headquartered in Italy and maintains operations across more than 60 countries, supported by a workforce of over 30,000 professionals.
                        </p>

                        <div class="space-y-6">
                            
                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">Corporate Profile</h3>
                                <p class="text-white/80 mb-3">
                                    ENI is a vertically integrated energy company engaged in the exploration, production, refining, and distribution of energy resources. In line with its corporate transformation agenda, ENI has expanded its portfolio to include renewable energy projects, biofuels, hydrogen, and innovative low-carbon solutions.
                                </p>
                                <p class="text-white/80">
                                    The ENI Investment Platform reflects this diversification, offering structured investment products that allow qualified investors to participate in ENI-driven projects while adhering to international financial regulations, compliance frameworks, and corporate governance standards.
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">Mission</h3>
                                <p class="text-white/80">
                                    Our mission is to provide secure, transparent, and compliant investment opportunities that align with ENI's broader objective of achieving net-zero carbon emissions by 2050. By combining financial performance with environmental responsibility, the platform seeks to create long-term value for investors, stakeholders, and the communities in which ENI operates.
                                </p>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">Strategic Objectives</h3>
                                <ul class="space-y-2 text-white/80">
                                    <li>• To leverage ENI's global expertise in energy development and innovation to deliver risk-adjusted returns.</li>
                                    <li>• To ensure all investment products comply with anti-money laundering (AML), know your customer (KYC), and international reporting standards.</li>
                                    <li>• To facilitate investor participation in renewable and transitional energy projects, contributing to both portfolio growth and environmental impact.</li>
                                    <li>• To reinforce ENI's reputation as a responsible corporate citizen committed to transparency, accountability, and sustainability.</li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-eni-yellow mb-3">Corporate Commitment</h3>
                                <p class="text-white/80">
                                    ENI Investment Platform is governed by ENI's principles of integrity, operational excellence, and corporate responsibility. Our commitment is to provide investors with structured opportunities supported by robust risk management, strong ethical standards, and a dedication to sustainable energy transformation.
                                </p>
                            </div>

                            <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4">
                                <p class="text-eni-yellow font-semibold mb-2">📌 Investment Alignment</p>
                                <p class="text-white/80">
                                    By participating in ENI Investment Platform, investors align with one of the world's leading energy companies in its pursuit of financial growth, operational excellence, and a sustainable energy future.
                                </p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mt-6">
                                <div class="bg-white/5 rounded-lg p-4">
                                    <h4 class="text-eni-yellow font-semibold mb-2">🌍 Global Presence</h4>
                                    <p class="text-white/70 text-sm">Operations in 60+ countries with 30,000+ professionals worldwide</p>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4">
                                    <h4 class="text-eni-yellow font-semibold mb-2">🏭 Energy Leadership</h4>
                                    <p class="text-white/70 text-sm">Vertically integrated energy company with traditional and renewable focus</p>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4">
                                    <h4 class="text-eni-yellow font-semibold mb-2">🌱 Sustainability</h4>
                                    <p class="text-white/70 text-sm">Net-zero carbon commitment by 2050 with sustainable energy transition</p>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4">
                                    <h4 class="text-eni-yellow font-semibold mb-2">⚖️ Compliance</h4>
                                    <p class="text-white/70 text-sm">Full adherence to international AML, KYC, and reporting standards</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeAboutModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Contact Support Modal -->
    <div id="supportModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-eni-charcoal border border-white/20 rounded-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                
                <!-- Modal Header -->
                <div class="bg-eni-dark px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-eni-yellow flex items-center gap-2">
                        � ENI Investment Platform — Help Center
                    </h2>
                    <button onclick="closeSupportModal()" class="text-white/60 hover:text-white text-2xl">×</button>
                </div>
                
                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[75vh] text-white">
                    
                    <div class="mb-6">
                        <p class="text-white/90 text-lg mb-4">Welcome to the ENI Help Center</p>
                        <p class="text-white/80 mb-6">Here, you will find answers to frequently asked questions, guides on using our platform, and resources for account, investment, and compliance support.</p>
                    </div>

                    <!-- Help Center Content -->
                    <div class="space-y-8">
                        
                        <!-- 1. Getting Started -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                                1. Getting Started
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I create an account?</p>
                                    <p class="text-white/80 text-sm">→ Register using your full name, email, and secure password. Complete KYC verification to activate your account.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">What documents are required for verification?</p>
                                    <p class="text-white/80 text-sm">→ Government-issued ID, proof of address, and any additional documentation requested by our Compliance Team.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I make my first investment?</p>
                                    <p class="text-white/80 text-sm">→ Select a package (Energy Saver, Growth Power, Capital Prime), review terms, and fund your account.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Account Management -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                                2. Account Management
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I reset my password?</p>
                                    <p class="text-white/80 text-sm">→ Use the "Forgot Password" link at login or contact Support for assistance.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Can I change my registered email or phone number?</p>
                                    <p class="text-white/80 text-sm">→ Yes, via your account settings. Certain changes may require re-verification.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I close my account?</p>
                                    <p class="text-white/80 text-sm">→ Submit a closure request through Support. Pending investments must reach maturity before withdrawal.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Investment & Returns -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></span>
                                3. Investment FAQ
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">What is the minimum investment required?</p>
                                    <p class="text-white/80 text-sm mb-2">The minimum amount depends on the selected package:</p>
                                    <ul class="text-white/70 text-sm ml-4 space-y-1">
                                        <li>• Energy Saver — Starts at $200</li>
                                        <li>• Growth Power — Starts at $900</li>
                                        <li>• Capital Prime — Starts at $7,000</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How are daily returns calculated?</p>
                                    <p class="text-white/80 text-sm">→ Returns are calculated based on the daily interest rate (APR divided across 365 days) applicable to each package (0.5% – 0.9%). Daily earnings are credited to your account balance automatically.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">When will my capital be released?</p>
                                    <p class="text-white/80 text-sm mb-2">Your invested capital is released at the end of the package duration:</p>
                                    <ul class="text-white/70 text-sm ml-4 space-y-1">
                                        <li>• Energy Saver → 6 Months</li>
                                        <li>• Growth Power → 8 Months</li>
                                        <li>• Capital Prime → 12 Months</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Can I withdraw funds before maturity?</p>
                                    <p class="text-white/80 text-sm">→ Early withdrawal is not permitted under standard package terms. This ensures consistent returns for all investors and protects the stability of project funding.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I withdraw my earnings?</p>
                                    <p class="text-white/80 text-sm mb-2">Follow these steps:</p>
                                    <ul class="text-white/70 text-sm ml-4 space-y-1">
                                        <li>• Log in to your dashboard</li>
                                        <li>• Submit a withdrawal request</li>
                                        <li>• Select your preferred payout method (e.g., bank transfer, wallet credit)</li>
                                        <li>• Processing times vary depending on the provider but are typically 1–5 business days.</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Are there any fees?</p>
                                    <p class="text-white/80 text-sm mb-2">Fee structure:</p>
                                    <ul class="text-white/70 text-sm ml-4 space-y-1">
                                        <li>• Deposit Fees — May apply depending on your payment provider</li>
                                        <li>• Withdrawal Fees — Nominal administrative fees may be charged</li>
                                        <li>• All fees will be clearly displayed before you confirm any transaction</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Are the returns guaranteed?</p>
                                    <p class="text-white/80 text-sm">→ No. All investments carry risk. While ENI provides structured packages with daily yields, returns are subject to market, operational, and regulatory factors. Please review our Risk Disclosure Statement before investing.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How is my investment secured?</p>
                                    <p class="text-white/80 text-sm mb-2">Security measures include:</p>
                                    <ul class="text-white/70 text-sm ml-4 space-y-1">
                                        <li>• ENI employs strict AML/KYC compliance, encryption, and data security</li>
                                        <li>• Funds are allocated only to approved, regulated projects</li>
                                        <li>• Risk management procedures are in place to mitigate exposure</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Can international clients invest?</p>
                                    <p class="text-white/80 text-sm">→ Yes. ENI welcomes international investors, subject to local regulations and compliance checks. Certain jurisdictions may be restricted.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Withdrawals & Deposits -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-purple-500 rounded-full mr-3"></span>
                                4. Withdrawals & Deposits
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I deposit funds?</p>
                                    <p class="text-white/80 text-sm">→ Log into your account and choose your preferred funding method.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How do I withdraw returns or principal?</p>
                                    <p class="text-white/80 text-sm">→ Submit a withdrawal request from your dashboard. Processing times vary by method.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">Are there withdrawal fees?</p>
                                    <p class="text-white/80 text-sm">→ Yes, fees may apply depending on payment provider and investment package terms.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Referral Program -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-pink-500 rounded-full mr-3"></span>
                                5. Referral Program
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">How does the referral program work?</p>
                                    <p class="text-white/80 text-sm">→ Invite new verified clients using your referral code. Earn variable commission rates (5%-15%) based on investment package tiers.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">When will I receive referral rewards?</p>
                                    <p class="text-white/80 text-sm">→ Rewards are credited once the referred client makes a valid investment.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Security Center -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                                6. Security Center
                            </h4>
                            <div class="mb-4">
                                <p class="text-white/90 text-sm mb-4">At ENI Investment Platform, safeguarding your data, funds, and trust is our top priority. We employ industry-leading security standards, strict compliance measures, and continuous monitoring to ensure a safe investment environment.</p>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-white/5 rounded-lg p-4 border border-blue-500/30">
                                    <p class="text-blue-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        1. Data Protection
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>End-to-End Encryption:</strong> All communication and transactions are protected with SSL/TLS encryption</li>
                                        <li>• <strong>Secure Storage:</strong> Personal and financial data is stored in encrypted databases with restricted access</li>
                                        <li>• <strong>Privacy Compliance:</strong> We adhere to GDPR, CCPA, and other global data privacy frameworks</li>
                                    </ul>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4 border border-green-500/30">
                                    <p class="text-green-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        2. Account Security
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>Two-Factor Authentication (2FA):</strong> Strong authentication required for logins and transactions</li>
                                        <li>• <strong>Login Monitoring:</strong> Suspicious or unusual login attempts trigger automated security checks</li>
                                        <li>• <strong>Session Timeouts:</strong> Automatic logouts protect accounts from unauthorized access</li>
                                    </ul>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4 border border-yellow-500/30">
                                    <p class="text-yellow-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                        3. Transaction Security
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>Fraud Detection Systems:</strong> AI-driven monitoring for unusual deposits, withdrawals, or transfers</li>
                                        <li>• <strong>Withdrawal Verification:</strong> Identity confirmation is required before releasing funds</li>
                                        <li>• <strong>Multi-Signature Approval:</strong> High-value transactions may require layered authorization</li>
                                    </ul>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4 border border-purple-500/30">
                                    <p class="text-purple-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                        4. Compliance & Oversight
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>AML / KYC Enforcement:</strong> All clients undergo verification to prevent money laundering or fraudulent activity</li>
                                        <li>• <strong>Regulatory Monitoring:</strong> Transactions comply with international standards and financial regulations</li>
                                        <li>• <strong>Independent Audits:</strong> Regular third-party audits validate our systems and processes</li>
                                    </ul>
                                </div>
                                <div class="bg-white/5 rounded-lg p-4 border border-pink-500/30">
                                    <p class="text-pink-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-pink-500 rounded-full mr-2"></span>
                                        5. Investor Protection
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>Risk Mitigation:</strong> Diversified projects reduce exposure to volatility</li>
                                        <li>• <strong>Capital Safeguards:</strong> Funds are allocated only to verified, ENI-approved initiatives</li>
                                        <li>• <strong>Transparency:</strong> Real-time dashboards provide visibility of balances, yields, and investment activity</li>
                                    </ul>
                                </div>
                                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                                    <p class="text-red-400 font-semibold mb-2 flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        6. Emergency Response
                                    </p>
                                    <ul class="text-white/80 text-sm ml-4 space-y-1">
                                        <li>• <strong>24/7 Incident Monitoring:</strong> Security teams track platform activity around the clock</li>
                                        <li>• <strong>Rapid Response Protocol:</strong> In the event of a breach, accounts are locked and investigated immediately</li>
                                        <li>• <strong>Dedicated Emergency Contact:</strong> Priority hotline for urgent client security issues</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg">
                                <p class="text-eni-yellow text-sm text-center font-medium">
                                    📌 ENI Investment Platform integrates advanced technology, strict compliance, and continuous oversight to ensure the highest level of security for all clients.
                                </p>
                            </div>
                        </div>

                        <!-- 7. Security & Compliance -->
                        <div class="bg-white/5 rounded-lg p-6 border border-white/10">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-red-500 rounded-full mr-3"></span>
                                7. Security & Compliance
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-white font-semibold mb-1">Why do I need to complete KYC/AML checks?</p>
                                    <p class="text-white/80 text-sm">→ To comply with international financial regulations and protect against fraud.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">How does ENI protect my data?</p>
                                    <p class="text-white/80 text-sm">→ Through encryption, secure servers, and strict access controls. See our Privacy Policy.</p>
                                </div>
                                <div>
                                    <p class="text-white font-semibold mb-1">What should I do if I suspect unauthorized activity?</p>
                                    <p class="text-white/80 text-sm">→ Contact our Emergency Support line immediately.</p>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Contact & Support -->
                        <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-6">
                            <h4 class="text-eni-yellow font-bold text-lg mb-4 flex items-center">
                                <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                                9. Contact & Support
                            </h4>
                            <div class="space-y-4">
                                <p class="text-white font-semibold mb-3">If your question is not covered here:</p>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div class="text-center bg-white/5 rounded-lg p-4">
                                        <p class="text-eni-yellow font-semibold mb-1">📧 Email</p>
                                        <p class="text-white/80 text-sm">support@eni-investment.com</p>
                                    </div>
                                    <div class="text-center bg-white/5 rounded-lg p-4">
                                        <p class="text-eni-yellow font-semibold mb-1">� Hotline</p>
                                        <p class="text-white/80 text-sm">+1 (555) 911-ENI1</p>
                                    </div>
                                    <div class="text-center bg-white/5 rounded-lg p-4">
                                        <p class="text-eni-yellow font-semibold mb-1">💬 Live Chat</p>
                                        <p class="text-white/80 text-sm">9:00 AM – 6:00 PM, Mon–Fri</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy Links -->
                    <div class="mt-8 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <p class="text-blue-400 text-sm text-center">
                            📌 The ENI Help Center is continuously updated. For the most recent policies, refer to our 
                            <span class="text-eni-yellow cursor-pointer hover:underline" onclick="closeSupportModal(); openTermsModal()">Terms of Service</span>, 
                            <span class="text-eni-yellow cursor-pointer hover:underline" onclick="closeSupportModal(); openPrivacyModal()">Privacy Policy</span>, 
                            <span class="text-eni-yellow cursor-pointer hover:underline" onclick="closeSupportModal(); openAmlModal()">AML Policy</span>, and 
                            <span class="text-eni-yellow cursor-pointer hover:underline" onclick="closeSupportModal(); openRiskModal()">Risk Disclosure</span>.
                        </p>
                    </div>

                </div>
                
                <!-- Modal Footer -->
                <div class="bg-eni-dark px-6 py-4 border-t border-white/10 flex justify-end">
                    <button onclick="closeSupportModal()" class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-yellow-400 transition-colors">
                        Close Help Center
                    </button>
                </div>

            </div>
        </div>
    </div>
                
            </div>
        </div>
    </div>

    <!-- Bank QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden" onclick="closeQrModalOnOutsideClick(event)">
        <div class="flex justify-center items-start min-h-screen p-4 overflow-y-auto">
            <div class="bg-eni-dark rounded-2xl p-8 m-4 max-w-md w-full border border-white/10 relative max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                <!-- Close Button -->
                <button type="button" onclick="closeQrModal()" 
                        class="absolute top-4 right-4 text-white/60 hover:text-white text-2xl w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                    &times;
                </button>
                
                <div class="text-center">
                    <h3 class="text-xl font-bold mb-6 text-eni-yellow">Bank Transfer Payment</h3>
                    
                    <div id="qrContent">
                        <!-- QR Code will be displayed here -->
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-white/60 text-xs mb-6">
                            After completing the transfer, upload your payment receipt below.
                        </p>
                        
                        <!-- Receipt Upload Section -->
                        <div class="mb-6">
                            <label class="block text-white/80 text-sm font-medium mb-2">Upload Payment Receipt</label>
                            <input type="file" id="receiptInput" name="receipt" 
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-eni-yellow file:text-eni-dark hover:file:bg-yellow-400 bg-white/10 border border-white/20 rounded-lg">
                            <p class="text-white/50 text-xs mt-1">Accepted formats: JPG, PNG, PDF (Max 2MB)</p>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="uploadReceipt()" 
                                    class="flex-1 bg-white/10 text-white py-3 rounded-lg hover:bg-white/20 transition-colors">
                                Upload Receipt
                            </button>
                            <button type="button" onclick="confirmBankTransfer()" 
                                    class="flex-1 bg-eni-yellow text-eni-dark font-bold py-3 rounded-lg hover:bg-yellow-400 transition-colors">
                                Complete Investment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // @ts-nocheck
        // Custom dropdown functionality
        function togglePaymentDropdown() {
            const dropdown = document.getElementById('paymentMethodOptions');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                dropdown.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        function selectPaymentMethod(value, displayText, logoUrl) {
            const input = document.getElementById('paymentMethodInput');
            const displayElement = document.getElementById('selectedPaymentText');
            const dropdown = document.getElementById('paymentMethodOptions');
            const arrow = document.getElementById('dropdownArrow');
            
            input.value = value;
            
            // Update display text with logo if available
            if (logoUrl && logoUrl !== '') {
                displayElement.innerHTML = '<div class="flex items-center"><img src="' + logoUrl + '" alt="' + displayText + '" class="w-6 h-4 mr-3 object-contain' + (value === 'cryptocurrency' ? ' rounded' : '') + '"><span>' + displayText.replace(/^[^a-zA-Z]*\s*/, '') + '</span></div>';
            } else if (value === 'account_balance') {
                displayElement.innerHTML = '<div class="flex items-center"><span class="text-xl mr-3">💰</span><span>Account Balance</span></div>';
            } else if (value === 'bank_transfer') {
                displayElement.innerHTML = '<div class="flex items-center"><span class="text-xl mr-3">🏦</span><span>Bank Transfer</span></div>';
            } else if (value === 'credit_card') {
                displayElement.innerHTML = '<div class="flex items-center"><svg class="w-6 h-4 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg><span>Credit Card</span></div>';
            } else {
                displayElement.textContent = displayText;
            }
            
            // Close dropdown
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
            
            // Handle payment method logic
            handlePaymentMethodChange(value);
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('paymentMethodDropdown');
            const options = document.getElementById('paymentMethodOptions');
            const arrow = document.getElementById('dropdownArrow');
            
            if (dropdown && options && !dropdown.contains(event.target) && !options.contains(event.target)) {
                options.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        });

        // Bank transfer functionality
        function handlePaymentMethodChange(paymentMethod) {
            if (!paymentMethod) {
                paymentMethod = document.getElementById('paymentMethodInput').value;
            }
            
            const bankSelection = document.getElementById('bankSelection');
            
            // Check for unavailable payment methods
            const unavailableMethods = {
                'credit_card': {
                    name: 'Credit Card',
                    reason: 'Credit card payments are not available in your region due to local banking regulations.'
                },
                'paypal': {
                    name: 'PayPal',
                    reason: 'PayPal services are currently restricted in your area. Please use alternative payment methods.'
                },
                'cryptocurrency': {
                    name: 'Cryptocurrency',
                    reason: 'Cryptocurrency payments are not supported in your region due to regulatory compliance.'
                }
            };
            
            if (unavailableMethods[paymentMethod]) {
                // Show not available modal
                showPaymentNotAvailableModal(
                    unavailableMethods[paymentMethod].name,
                    unavailableMethods[paymentMethod].reason
                );
                // Reset payment method selection
                selectPaymentMethod('', 'Select payment method', '');
                // Hide bank selection if it was shown
                bankSelection.classList.add('hidden');
                return;
            }
            
            // Handle available payment methods
            if (paymentMethod === 'bank_transfer') {
                bankSelection.classList.remove('hidden');
            } else {
                bankSelection.classList.add('hidden');
                // Clear bank selection
                document.getElementById('selectedBank').value = '';
                clearBankSelection();
            }
        }

        function selectBank(bankName) {
            // Clear previous selections
            clearBankSelection();
            
            // Mark selected bank and add immediate loading effect
            const bankOptions = document.querySelectorAll('.bank-option');
            let selectedBankElement = null;
            
            bankOptions.forEach(option => {
                if (option.onclick.toString().includes(bankName)) {
                    option.classList.add('ring-2', 'ring-eni-yellow', 'bg-white/20');
                    selectedBankElement = option;
                }
            });
            
            // Add loading effect to the selected bank card
            if (selectedBankElement) {
                const originalContent = selectedBankElement.innerHTML;
                selectedBankElement.innerHTML = `
                    <div class="text-center">
                        <div class="mb-2">
                            <div class="inline-flex items-center justify-center w-8 h-8">
                                <div class="w-4 h-4 border-2 border-eni-yellow border-t-transparent rounded-full animate-spin"></div>
                            </div>
                        </div>
                        <div class="text-white font-semibold">Connecting...</div>
                        <div class="text-white/60 text-sm">Preparing QR Code</div>
                    </div>
                `;
                
                // Restore original content after 2 seconds
                setTimeout(() => {
                    selectedBankElement.innerHTML = originalContent;
                }, 2000);
            }
            
            // Set hidden input value
            document.getElementById('selectedBank').value = bankName;
            
            // Show loading effect and generate QR code
            showQrLoadingThenGenerate(bankName);
        }

        function clearBankSelection() {
            const bankOptions = document.querySelectorAll('.bank-option');
            bankOptions.forEach(option => {
                option.classList.remove('ring-2', 'ring-eni-yellow', 'bg-white/20');
            });
        }

        function showQrLoadingThenGenerate(bankName) {
            // Get bank display name for the loading screen
            let bankDisplayName = '';
            switch(bankName) {
                case 'landbank':
                    bankDisplayName = 'LandBank of the Philippines';
                    break;
                case 'bpi':
                    bankDisplayName = 'Bank of the Philippine Islands';
                    break;
                case 'rcbc':
                    bankDisplayName = 'RCBC';
                    break;
                default:
                    bankDisplayName = 'Selected Bank';
            }

            // Show modal with loading animation
            const qrContent = document.getElementById('qrContent');
            qrContent.innerHTML = `
                <div class="text-center py-8">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-eni-yellow/30 to-eni-yellow/10 rounded-full mb-4 loading-pulse">
                            <div class="w-10 h-10 border-4 border-eni-yellow border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">Generating QR Code</h4>
                        <p class="text-white/70 text-sm mb-4">Please wait while we prepare your ${bankDisplayName} payment QR code...</p>
                        
                        <!-- Enhanced loading dots -->
                        <div class="flex justify-center space-x-2 mb-6">
                            <div class="w-3 h-3 bg-eni-yellow rounded-full enhanced-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-3 h-3 bg-eni-yellow rounded-full enhanced-bounce" style="animation-delay: 200ms;"></div>
                            <div class="w-3 h-3 bg-eni-yellow rounded-full enhanced-bounce" style="animation-delay: 400ms;"></div>
                        </div>
                    </div>
                    
                    <!-- Simulated progress steps with enhanced styling -->
                    <div class="text-left max-w-sm mx-auto">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center p-2 bg-white/5 rounded-lg">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-3 animate-pulse"></div>
                                <span class="text-green-400">✓ Connecting to ${bankDisplayName}...</span>
                            </div>
                            <div class="flex items-center p-2 bg-white/5 rounded-lg shimmer" id="loading-step-2">
                                <div class="w-3 h-3 bg-white/30 rounded-full mr-3" id="loading-dot-2"></div>
                                <span class="text-white/40" id="loading-text-2">Generating secure payment code...</span>
                            </div>
                            <div class="flex items-center p-2 bg-white/5 rounded-lg" id="loading-step-3">
                                <div class="w-3 h-3 bg-white/30 rounded-full mr-3" id="loading-dot-3"></div>
                                <span class="text-white/30" id="loading-text-3">Creating QR code...</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Show the modal
            document.getElementById('qrModal').classList.remove('hidden');
            
            // Simulate loading steps with delays
            setTimeout(() => {
                // Step 2: Generating secure payment code
                const step2 = document.getElementById('loading-step-2');
                const dot2 = document.getElementById('loading-dot-2');
                const text2 = document.getElementById('loading-text-2');
                
                step2.classList.remove('shimmer');
                step2.classList.add('bg-green-500/10');
                dot2.classList.remove('bg-white/30');
                dot2.classList.add('bg-green-400', 'animate-pulse');
                text2.classList.remove('text-white/40');
                text2.classList.add('text-green-400');
                text2.innerHTML = '✓ Generating secure payment code...';
                
                // Add shimmer to step 3
                document.getElementById('loading-step-3').classList.add('shimmer');
            }, 800);
            
            setTimeout(() => {
                // Step 3: Creating QR code
                const step3 = document.getElementById('loading-step-3');
                const dot3 = document.getElementById('loading-dot-3');
                const text3 = document.getElementById('loading-text-3');
                
                step3.classList.remove('shimmer');
                step3.classList.add('bg-green-500/10');
                dot3.classList.remove('bg-white/30');
                dot3.classList.add('bg-green-400', 'animate-pulse');
                text3.classList.remove('text-white/30');
                text3.classList.add('text-green-400');
                text3.innerHTML = '✓ Creating QR code...';
            }, 1600);
            
            // After loading animation (2.5 seconds), show the actual QR code
            setTimeout(() => {
                showQrCode(bankName);
            }, 2500);
        }

        function showQrCode(bankName) {
            const qrContent = document.getElementById('qrContent');
            let qrImagePath = '';
            let bankDisplayName = '';
            
            switch(bankName) {
                case 'landbank':
                    qrImagePath = '/Landbank QR.png';
                    bankDisplayName = 'LandBank of the Philippines';
                    break;
                case 'bpi':
                    qrImagePath = '/bpi_qr.jpg';
                    bankDisplayName = 'Bank of the Philippine Islands';
                    break;
                case 'rcbc':
                    qrImagePath = '/rcbc_qr.jpg';
                    bankDisplayName = 'RCBC';
                    break;
            }
            
            // Create QR content with fade-in animation
            qrContent.innerHTML = `
                <div class="opacity-0 transform scale-95 transition-all duration-500 ease-out" id="qrCodeContent">
                    <div class="mb-6">
                        <!-- Success checkmark animation -->
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500/20 rounded-full mb-4">
                            <div class="w-8 h-8 text-green-400">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">QR Code Ready!</h4>
                        <p class="text-eni-yellow font-semibold mb-4">${bankDisplayName}</p>
                    </div>
                    
                    <div class="mb-4 p-4 bg-white rounded-lg">
                        <img src="${qrImagePath}" alt="${bankDisplayName} QR Code for Bank Transfer" 
                             class="mx-auto w-full max-w-xs h-auto object-contain">
                    </div>
                    
                    <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4 mb-4">
                        <p class="text-eni-yellow text-sm font-medium text-center">
                            📱 Scan this QR code with your banking app to transfer to ${bankDisplayName}
                        </p>
                    </div>
                </div>
            `;
            
            // Trigger fade-in animation
            setTimeout(() => {
                const qrCodeContent = document.getElementById('qrCodeContent');
                if (qrCodeContent) {
                    qrCodeContent.classList.remove('opacity-0', 'scale-95');
                    qrCodeContent.classList.add('opacity-100', 'scale-100');
                }
            }, 100);
            
            const modal = document.getElementById('qrModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            const panel = modal.querySelector('.max-w-md');
            if (panel) panel.scrollTop = 0;
        }

        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function closeQrModalOnOutsideClick(event) {
            // Only close if clicking the backdrop (not the modal content)
            if (event.target === event.currentTarget) {
                closeQrModal();
            }
        }

        // Add keyboard support for closing modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('qrModal');
                if (!modal.classList.contains('hidden')) {
                    closeQrModal();
                }
            }
        });

        function confirmBankTransfer() {
            const receiptInput = document.getElementById('receiptInput');
            
            if (!receiptInput.files.length) {
                alert('Please upload your payment receipt before completing the investment.');
                return;
            }
            
            // Submit the form with the uploaded receipt
            submitInvestmentWithReceipt();
        }

        function uploadReceipt() {
            // Trigger file input click
            document.getElementById('receiptInput').click();
        }

        function submitInvestmentWithReceipt() {
            const form = document.getElementById('investment-form-element');
            const receiptInput = document.getElementById('receiptInput');
            
            // Validate required fields before submission
            const packageId = document.getElementById('selected_package_id').value;
            const amount = document.getElementById('investment_amount').value;
            const paymentMethod = document.getElementById('paymentMethodInput').value;
            
            console.log('Form validation:', {
                packageId: packageId,
                amount: amount,
                paymentMethod: paymentMethod
            });
            
            if (!packageId) {
                alert('Please select an investment package first.');
                return;
            }
            
            if (!amount || parseFloat(amount) < 10) {
                alert('Please enter a valid investment amount.');
                return;
            }
            
            if (!paymentMethod) {
                alert('Please select a payment method.');
                return;
            }
            
            // Create FormData to handle file upload
            const formData = new FormData(form);
            
            // Add the receipt file if selected
            if (receiptInput.files.length > 0) {
                formData.set('receipt', receiptInput.files[0]);
            }
            
            // Submit via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                if (response.ok) {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If it's not JSON, get the text to see what error page was returned
                        return response.text().then(text => {
                            console.log('Non-JSON response:', text);
                            throw new Error('Server returned HTML instead of JSON. Check server logs for detailed error.');
                        });
                    }
                } else {
                    // Try to parse JSON error, but fallback to text if it fails
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(errorData => {
                            console.log('JSON Error response:', errorData);
                            throw new Error(errorData.message || `Server error: ${response.status}`);
                        });
                    } else {
                        return response.text().then(text => {
                            console.log('HTML Error response:', text);
                            // Try to extract meaningful error from HTML
                            const match = text.match(/<title>(.*?)<\/title>/i);
                            const errorTitle = match ? match[1] : 'Unknown server error';
                            throw new Error(`Server error (${response.status}): ${errorTitle}`);
                        });
                    }
                }
            })
            .then(data => {
                console.log('Success response:', data);
                if (data.success) {
                    // Redirect to the specified URL (receipt page)
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Investment submission failed. Please check your details.');
                }
            })
            .catch(error => {
                console.error('Investment submission error:', error);
                
                // Show more specific error message
                let errorMessage = 'Error submitting investment: ';
                if (error.message.includes('422')) {
                    errorMessage += 'Please check all required fields are filled correctly.';
                } else if (error.message.includes('419')) {
                    errorMessage += 'Session expired. Please refresh the page and try again.';
                } else if (error.message.includes('500')) {
                    errorMessage += 'Server error. Please contact support.';
                } else {
                    errorMessage += error.message;
                }
                
                alert(errorMessage);
            });
        }

        // Modify form submission to handle bank transfer
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('investment-form-element');
            const receiptInput = document.getElementById('receiptInput');
            
            // Add file change listener for visual feedback
            if (receiptInput) {
                receiptInput.addEventListener('change', function() {
                    const uploadBtn = document.querySelector('button[onclick="uploadReceipt()"]');
                    if (this.files.length > 0) {
                        uploadBtn.textContent = '✓ Receipt Selected';
                        uploadBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                        uploadBtn.classList.remove('bg-white/10', 'hover:bg-white/20');
                    } else {
                        uploadBtn.textContent = 'Upload Receipt';
                        uploadBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        uploadBtn.classList.add('bg-white/10', 'hover:bg-white/20');
                    }
                });
            }
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validate all required fields first
                    const packageId = document.getElementById('selected_package_id').value;
                    const amount = document.getElementById('investment_amount').value;
                    const paymentMethod = document.getElementById('paymentMethodInput').value;
                    
                    console.log('Form submission validation:', { packageId, amount, paymentMethod });
                    
                    if (!packageId) {
                        e.preventDefault();
                        alert('Please select an investment package first.');
                        return;
                    }
                    
                    if (!amount) {
                        e.preventDefault();
                        alert('Please enter an investment amount.');
                        return;
                    }
                    
                    if (!paymentMethod) {
                        e.preventDefault();
                        alert('Please select a payment method.');
                        return;
                    }
                    
                    // Additional validation for amount
                    const amountValue = parseFloat(amount);
                    const minAmount = parseFloat(document.getElementById('investment_amount').min);
                    const maxAmount = parseFloat(document.getElementById('investment_amount').max);
                    
                    if (isNaN(amountValue) || amountValue < minAmount || amountValue > maxAmount) {
                        e.preventDefault();
                        alert(`Please enter a valid amount between $${minAmount.toLocaleString()} and $${maxAmount.toLocaleString()}.`);
                        return;
                    }
                    
                    if (paymentMethod === 'bank_transfer') {
                        const selectedBank = document.getElementById('selectedBank').value;
                        
                        if (!selectedBank) {
                            e.preventDefault();
                            alert('Please select a bank for transfer.');
                            return;
                        }
                        
                        // Show loading animation then QR code instead of submitting immediately
                        e.preventDefault();
                        showQrLoadingThenGenerate(selectedBank);
                    }
                });
            }
        });

        function openPaymentForm(packageId, packageName, minAmount, maxAmount, dailyRate) {
            // Update form with package details
            document.getElementById('selected_package_id').value = packageId;
            document.getElementById('investment_amount').min = minAmount;
            document.getElementById('investment_amount').max = maxAmount;
            document.getElementById('investment_amount').placeholder = `Enter amount (min: $${minAmount.toLocaleString()})`;
            
            // Update package info display
            document.getElementById('package-info').innerHTML = `
                <strong>${packageName}</strong><br>
                Daily Returns: ${dailyRate}% | Range: $${minAmount.toLocaleString()} - $${maxAmount.toLocaleString()}
            `;
            
            // Update amount limits text
            document.getElementById('amount-limits').textContent = `Minimum: $${minAmount.toLocaleString()} - Maximum: $${maxAmount.toLocaleString()}`;
            
            // Show investment form
            document.getElementById('investment-form').style.display = 'block';
            document.getElementById('investment-form').scrollIntoView({ behavior: 'smooth' });
        }

        function openTermsModal() {
            document.getElementById('termsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeTermsModal() {
            document.getElementById('termsModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openPrivacyModal() {
            document.getElementById('privacyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closePrivacyModal() {
            document.getElementById('privacyModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openRiskModal() {
            document.getElementById('riskModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeRiskModal() {
            document.getElementById('riskModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openAmlModal() {
            document.getElementById('amlModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeAmlModal() {
            document.getElementById('amlModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openGuidelinesModal() {
            document.getElementById('guidelinesModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeGuidelinesModal() {
            document.getElementById('guidelinesModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openAboutModal() {
            document.getElementById('aboutModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeAboutModal() {
            document.getElementById('aboutModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        function openSupportModal() {
            document.getElementById('supportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeSupportModal() {
            document.getElementById('supportModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modals when clicking outside of them
        document.getElementById('termsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTermsModal();
            }
        });

        document.getElementById('privacyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePrivacyModal();
            }
        });

        document.getElementById('riskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRiskModal();
            }
        });

        document.getElementById('amlModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAmlModal();
            }
        });

        document.getElementById('guidelinesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGuidelinesModal();
            }
        });

        document.getElementById('aboutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAboutModal();
            }
        });

        document.getElementById('supportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSupportModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTermsModal();
                closePrivacyModal();
                closeRiskModal();
                closeAmlModal();
                closeGuidelinesModal();
                closeAboutModal();
                closeSupportModal();
                closePaymentModal();
            }
        });
        
        // Debug form submission
        document.getElementById('investment-form-element').addEventListener('submit', function(e) {
            console.log('Form submission attempted');
            console.log('Form action:', this.action);
            console.log('Package ID:', document.getElementById('selected_package_id').value);
            console.log('Amount:', document.getElementById('investment_amount').value);
            console.log('Payment Method:', document.getElementById('paymentMethodInput').value);
            
            // Check if required fields are filled
            const packageId = document.getElementById('selected_package_id').value;
            const amount = document.getElementById('investment_amount').value;
            const paymentMethod = document.getElementById('paymentMethodInput').value;
            
            if (!packageId || !amount || !paymentMethod) {
                console.error('Missing required fields');
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
        });

        // Payment Not Available Modal Functions
        function showPaymentNotAvailableModal(paymentMethodName, reason) {
            const modal = document.getElementById('paymentNotAvailableModal');
            const paymentSelect = document.getElementById('paymentMethodInput');
            
            // Update modal content
            document.getElementById('paymentMethodName').textContent = paymentMethodName;
            document.getElementById('paymentMethodReason').textContent = reason || 'This payment method is currently not available in your region.';
            
            // Add pulse animation to the select dropdown
            paymentSelect.classList.add('payment-unavailable');
            setTimeout(() => {
                paymentSelect.classList.remove('payment-unavailable');
            }, 600);
            
            // Show modal with animation
            modal.classList.remove('hidden');
            // Force a reflow to ensure the initial state is rendered
            modal.offsetHeight;
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentNotAvailableModal');
            
            // Add hidden class to trigger animation
            modal.classList.add('hidden');
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        const paymentModal = document.getElementById('paymentNotAvailableModal');
        if (paymentModal) {
            paymentModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePaymentModal();
                }
            });
        }
    </script>

    <!-- Payment Method Not Available Modal -->
    <div id="paymentNotAvailableModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display: flex;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-eni-dark to-eni-charcoal text-white px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-eni-yellow" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-xl font-bold">Payment Method Unavailable</h3>
                    </div>
                    <button onclick="closePaymentModal()" class="text-white/80 hover:text-eni-yellow transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 text-eni-dark">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 mx-auto mb-4 bg-eni-dark/10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-eni-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold mb-2 text-eni-dark" id="paymentMethodName">Payment Method</h4>
                    <p class="text-gray-600 mb-4 text-sm" id="paymentMethodReason">This payment method is currently not available in your region.</p>
                </div>
                
                <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-eni-dark mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-eni-dark">
                            <p class="font-semibold mb-2">Available Payment Methods:</p>
                            <ul class="space-y-1 text-gray-600">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-eni-yellow rounded-full mr-2"></span>
                                    💰 Account Balance
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-eni-yellow rounded-full mr-2"></span>
                                    🏦 Bank Transfer (LandBank, BPI, RCBC)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex gap-3 justify-center">
                <button onclick="closePaymentModal()" 
                        class="bg-eni-yellow text-eni-dark px-6 py-2 rounded-lg font-semibold hover:bg-eni-yellow/90 transition-colors">
                    I Understand
                </button>
                <button onclick="closePaymentModal(); openSupportModal();" 
                        class="bg-eni-dark text-white px-6 py-2 rounded-lg font-semibold hover:bg-eni-charcoal transition-colors">
                    Contact Support
                </button>
            </div>
        </div>
    </div>

    <!-- Global Footer -->
    @include('components.footer')

    <!-- Footer Modals -->
    @include('components.footer-modals')
</body>
</html>
