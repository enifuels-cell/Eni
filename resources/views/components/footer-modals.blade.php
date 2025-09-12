<!-- Modal Scripts -->
<script>
// Terms of Service Modal
function openTermsModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Terms of Service</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <h3 class="text-eni-yellow font-semibold">1. Agreement to Terms</h3>
                    <p>By accessing and using Eni Members, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">2. Investment Services</h3>
                    <p>ENI provides investment opportunities through our regulated platform. All investments carry risk and past performance does not guarantee future results.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">3. User Responsibilities</h3>
                    <p>Users must provide accurate information, maintain account security, and comply with all applicable laws and regulations.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">4. Risk Acknowledgment</h3>
                    <p>You acknowledge that investments may result in partial or total loss of capital. You should only invest amounts you can afford to lose.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">5. Regulatory Compliance</h3>
                    <p>ENI operates under strict regulatory oversight and complies with all applicable financial services regulations.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Privacy Policy Modal
function openPrivacyModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Privacy Policy</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <h3 class="text-eni-yellow font-semibold">Data Collection</h3>
                    <p>We collect personal information necessary to provide investment services, including identification, financial information, and transaction data.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Data Usage</h3>
                    <p>Your data is used to process investments, comply with regulations, prevent fraud, and provide customer support.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Data Protection</h3>
                    <p>We implement industry-standard security measures to protect your personal and financial information.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Regulatory Sharing</h3>
                    <p>We may share information with regulatory authorities as required by law for compliance and oversight purposes.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Risk Disclosure Modal
function openRiskModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Risk Disclosure</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <div class="bg-red-900/20 border border-red-500/30 rounded-lg p-4">
                        <h3 class="text-red-400 font-semibold mb-2">⚠️ Important Risk Warning</h3>
                        <p class="text-red-300">All investments carry significant risk of loss. You should only invest money you can afford to lose completely.</p>
                    </div>
                    
                    <h3 class="text-eni-yellow font-semibold">Market Risk</h3>
                    <p>Investment values fluctuate based on market conditions, economic factors, and company performance.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Liquidity Risk</h3>
                    <p>Some investments may have limited liquidity, making it difficult to withdraw funds quickly.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Operational Risk</h3>
                    <p>Platform operations, while regulated and monitored, may be subject to technical or operational challenges.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Regulatory Risk</h3>
                    <p>Changes in regulations may affect investment operations and returns.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// AML Modal
function openAmlModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Anti-Money Laundering Policy</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <h3 class="text-eni-yellow font-semibold">AML Compliance</h3>
                    <p>ENI maintains strict anti-money laundering procedures in accordance with international standards and regulatory requirements.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Customer Due Diligence</h3>
                    <p>We verify customer identity and monitor transactions to prevent money laundering and terrorist financing.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Suspicious Activity Reporting</h3>
                    <p>We report suspicious activities to relevant authorities as required by law.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Source of Funds</h3>
                    <p>Customers may be required to provide documentation regarding the source of investment funds.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Investment Guidelines Modal
function openGuidelinesModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Investment Guidelines</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <h3 class="text-eni-yellow font-semibold">Investment Process</h3>
                    <p>Investments are processed according to our established procedures and regulatory requirements.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Minimum Investments</h3>
                    <p>Each investment package has specific minimum investment amounts as outlined in the package details.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Returns and Withdrawals</h3>
                    <p>Returns are calculated and distributed according to each package's terms. Withdrawals are subject to our policies and regulatory requirements.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Portfolio Management</h3>
                    <p>Our investment team manages portfolios according to professional standards and risk management principles.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// About ENI Modal
function openAboutModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">About ENI</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-4 text-sm">
                    <h3 class="text-eni-yellow font-semibold">Our Mission</h3>
                    <p>ENI provides secure, regulated investment opportunities with a focus on energy sector investments and sustainable growth.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Regulatory Status</h3>
                    <p>We operate under strict regulatory oversight and maintain all required licenses for investment services.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Investment Focus</h3>
                    <p>Our investment strategies focus on energy sector opportunities, infrastructure development, and sustainable growth initiatives.</p>
                    
                    <h3 class="text-eni-yellow font-semibold">Security & Compliance</h3>
                    <p>We maintain the highest standards of security and regulatory compliance to protect our investors.</p>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Support Modal
function openSupportModal() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
    modal.innerHTML = `
        <div class="bg-eni-dark border border-eni-yellow rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-eni-yellow">Contact Support</h2>
                    <button onclick="this.closest('.fixed').remove()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div class="text-white/80 space-y-6 text-sm">
                    <div>
                        <h3 class="text-eni-yellow font-semibold mb-2">Customer Support</h3>
                        <p>📧 Email: support@eni-investments.com</p>
                        <p>📞 Phone: +1 (555) 123-4567</p>
                        <p>🕒 Hours: Monday - Friday, 9:00 AM - 6:00 PM EST</p>
                    </div>
                    
                    <div>
                        <h3 class="text-eni-yellow font-semibold mb-2">Investment Support</h3>
                        <p>📧 Email: investments@eni-investments.com</p>
                        <p>📞 Phone: +1 (555) 123-4568</p>
                    </div>
                    
                    <div>
                        <h3 class="text-eni-yellow font-semibold mb-2">Compliance Inquiries</h3>
                        <p>📧 Email: compliance@eni-investments.com</p>
                    </div>
                    
                    <div class="bg-eni-yellow/10 border border-eni-yellow/30 rounded-lg p-4">
                        <h4 class="text-eni-yellow font-semibold mb-2">Emergency Support</h4>
                        <p>For urgent account security issues, please call our 24/7 emergency line: +1 (555) 911-HELP</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}
</script>
