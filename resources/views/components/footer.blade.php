<!-- Footer -->
<footer class="bg-eni-dark border-t border-white/10 mt-16">
    <div class="container mx-auto px-6 py-8">
        <div class="grid md:grid-cols-3 gap-8">
            
            <!-- Legal & Compliance -->
            <div class="text-center md:text-left">
                <h4 class="text-eni-yellow font-bold text-lg mb-4">Legal & Compliance</h4>
                <ul class="space-y-2 text-white/70 text-sm">
                    <li><a href="#" onclick="openTermsModal()" class="hover:text-eni-yellow transition-colors">Terms of Service</a></li>
                    <li><a href="#" onclick="openPrivacyModal()" class="hover:text-eni-yellow transition-colors">Privacy Policy</a></li>
                    <li><a href="#" onclick="openRiskModal()" class="hover:text-eni-yellow transition-colors">Risk Disclosure</a></li>
                    <li><a href="#" onclick="openAmlModal()" class="hover:text-eni-yellow transition-colors">Anti-Money Laundering</a></li>
                    <li><a href="#" onclick="openGuidelinesModal()" class="hover:text-eni-yellow transition-colors">Investment Guidelines</a></li>
                </ul>
            </div>

            <!-- Company & Support -->
            <div class="text-center md:text-left">
                <h4 class="text-eni-yellow font-bold text-lg mb-4">Company & Support</h4>
                <ul class="space-y-2 text-white/70 text-sm">
                    <li><a href="#" onclick="openAboutModal()" class="hover:text-eni-yellow transition-colors">About ENI</a></li>
                    <li><a href="#" onclick="openSupportModal()" class="hover:text-eni-yellow transition-colors">Contact Support</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Investment FAQ</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Security Center</a></li>
                </ul>
            </div>

            <!-- Regulatory Info -->
            <div class="text-center md:text-left">
                <h4 class="text-eni-yellow font-bold text-lg mb-4">Regulatory Info</h4>
                <ul class="space-y-2 text-white/70 text-sm">
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">License Information</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Regulatory Compliance</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Audit Reports</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Financial Disclosures</a></li>
                    <li><a href="#" class="hover:text-eni-yellow transition-colors">Investor Protection</a></li>
                </ul>
            </div>
            
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-t border-white/10 mt-8 pt-6 text-center">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-white/60 text-sm mb-4 md:mb-0">
                    © {{ date('Y') }} Eni Members. All rights reserved.
                </div>
                <div class="flex items-center gap-4 text-white/60 text-sm">
                    <span>Regulated Investment Platform</span>
                    <span>•</span>
                    <span>Secure & Compliant</span>
                    <span>•</span>
                    <span>Licensed & Insured</span>
                </div>
            </div>
            
            <!-- Disclaimer -->
            <div class="text-white/50 text-xs mt-4 max-w-4xl mx-auto">
                <p class="mb-2">
                    <strong>Risk Warning:</strong> Investment involves risk. Past performance is not indicative of future results. 
                    The value of investments may go up or down and investors may not get back the amount originally invested.
                </p>
                <p>
                    ENI is regulated by financial authorities and complies with international investment standards. 
                    All investments are subject to our terms and conditions and regulatory oversight.
                </p>
            </div>
        </div>
    </div>
</footer>
