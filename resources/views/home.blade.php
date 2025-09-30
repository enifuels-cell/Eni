@extends('layouts.app')

@section('title', 'ENI Corporate - Enterprise Investment Solutions')

@section('content')
<style>
    /* Enhanced Background System */
    .eni-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0B2241 0%, #121417 50%, #0B2241 100%);
        z-index: -1;
        overflow: hidden;
    }

    .eni-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 30%, rgba(255, 205, 0, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 70%, rgba(255, 205, 0, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(255, 205, 0, 0.08) 0%, transparent 50%);
        animation: ambient-glow 8s ease-in-out infinite alternate;
    }

    .geometric-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23FFCD00' fill-opacity='0.03'%3E%3Cpath d='M30 30l30-30v60l-30-30zm0 0L0 0v60l30-30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.6;
        animation: geometric-float 20s linear infinite;
    }

    .floating-orbs {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .orb {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 205, 0, 0.2), rgba(255, 205, 0, 0.05));
        animation: float-orb 15s infinite ease-in-out;
        backdrop-filter: blur(1px);
    }

    .orb:nth-child(1) { width: 150px; height: 150px; top: 10%; left: 5%; animation-delay: 0s; }
    .orb:nth-child(2) { width: 100px; height: 100px; top: 70%; right: 10%; animation-delay: -3s; }
    .orb:nth-child(3) { width: 80px; height: 80px; bottom: 20%; left: 15%; animation-delay: -6s; }
    .orb:nth-child(4) { width: 120px; height: 120px; top: 40%; right: 20%; animation-delay: -9s; }

    @keyframes ambient-glow {
        0%, 100% { opacity: 0.8; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
    }

    @keyframes geometric-float {
        0% { transform: translateX(0) translateY(0); }
        33% { transform: translateX(-10px) translateY(-5px); }
        66% { transform: translateX(10px) translateY(5px); }
        100% { transform: translateX(0) translateY(0); }
    }

    @keyframes float-orb {
        0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.5; }
        50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
    }

    /* Glass Morphism Cards */
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 205, 0, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .glass-card:hover::before {
        left: 100%;
    }

    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        border-color: rgba(255, 205, 0, 0.3);
    }

    /* Hero Section */
    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        padding: 2rem 0;
    }

    .hero-content {
        animation: fade-in-up 1.2s ease-out;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #FFCD00 0%, #FFE55C 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        line-height: 1.1;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        font-weight: 300;
        line-height: 1.4;
    }

    .hero-description {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 3rem;
        line-height: 1.6;
        max-width: 600px;
    }

    /* CTA Buttons */
    .cta-button {
        background: linear-gradient(135deg, #FFCD00 0%, #FFE55C 100%);
        color: #0B2241;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 205, 0, 0.3);
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    .cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(255, 205, 0, 0.4);
        color: #0B2241;
        text-decoration: none;
    }

    .cta-button.secondary {
        background: transparent;
        color: #FFCD00;
        border: 2px solid #FFCD00;
        box-shadow: 0 4px 15px rgba(255, 205, 0, 0.1);
    }

    .cta-button.secondary:hover {
        background: #FFCD00;
        color: #0B2241;
    }

    /* Stats Section */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 4rem 0;
    }

    .stat-card {
        text-align: center;
        padding: 2rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: scale(1.05);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #FFCD00;
        display: block;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Features Section */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 4rem 0;
    }

    .feature-card {
        padding: 2.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FFCD00 0%, #FFE55C 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #0B2241;
        box-shadow: 0 8px 25px rgba(255, 205, 0, 0.3);
    }

    .feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #FFCD00;
        margin-bottom: 1rem;
    }

    .feature-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
    }

    /* Navigation Enhancement */
    .nav-quicklinks {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 1.5rem;
        margin: 3rem 0;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .nav-item {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-item:hover {
        background: rgba(255, 205, 0, 0.2);
        transform: translateY(-5px);
        color: #fff;
        text-decoration: none;
        border-color: rgba(255, 205, 0, 0.3);
    }

    .nav-icon {
        font-size: 2rem;
        color: #FFCD00;
        margin-bottom: 1rem;
        display: block;
    }

    .nav-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .nav-desc {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Animations */
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .hero-description {
            font-size: 1rem;
        }

        .stats-grid,
        .features-grid,
        .nav-grid {
            grid-template-columns: 1fr;
        }

        .cta-button {
            display: block;
            text-align: center;
            margin-bottom: 1rem;
        }
    }

    /* Technology Innovation Elements */
    .tech-grid {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23FFCD00' fill-opacity='0.02'%3E%3Cpath d='M50 50L25 25h50L50 50zM50 50L75 75H25L50 50z'/%3E%3C/g%3E%3C/svg%3E");
        animation: tech-pulse 12s linear infinite;
    }

    @keyframes tech-pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 0.8; }
    }
</style>

<!-- Enhanced Background System -->
<div class="eni-background">
    <div class="geometric-overlay"></div>
    <div class="tech-grid"></div>
    <div class="floating-orbs">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>
</div>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container mx-auto px-6">
        <div class="hero-content max-w-4xl">
            <h1 class="hero-title">
                Enterprise Investment
                <span class="block">Innovation Platform</span>
            </h1>
            <p class="hero-subtitle">
                Empowering Financial Growth Through Technology
            </p>
            <p class="hero-description">
                Welcome to ENI's cutting-edge investment ecosystem. Our platform combines advanced financial technology with enterprise-grade security to deliver unprecedented investment opportunities and automated portfolio management.
            </p>
            <div class="flex flex-wrap">
                <a href="{{ route('user.packages') }}" class="cta-button">
                    <i class="fas fa-rocket mr-2"></i>
                    Explore Packages
                </a>
                <a href="{{ route('dashboard') }}" class="cta-button secondary">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="glass-card p-8 animate-on-scroll">
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number" data-target="99.9">0</span>
                    <span class="stat-label">Platform Uptime</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number" data-target="24">0</span>
                    <span class="stat-label">Hours Daily Interest</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number" data-target="256">0</span>
                    <span class="stat-label">Bit Encryption</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number" data-target="100">0</span>
                    <span class="stat-label">Success Rate</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Navigation -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-4xl font-bold text-white mb-4">
                Your Investment <span class="text-eni-yellow">Control Center</span>
            </h2>
            <p class="text-xl text-gray-300">
                Access all platform features with enterprise-grade efficiency
            </p>
        </div>

        <div class="nav-quicklinks animate-on-scroll">
            <div class="nav-grid">
                <a href="{{ route('user.packages') }}" class="nav-item">
                    <i class="fas fa-box-open nav-icon"></i>
                    <div class="nav-title">Investment Packages</div>
                    <div class="nav-desc">Browse available investment opportunities</div>
                </a>

                <a href="{{ route('user.investments') }}" class="nav-item">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <div class="nav-title">My Investments</div>
                    <div class="nav-desc">Track your active investments</div>
                </a>

                <a href="{{ route('user.transactions') }}" class="nav-item">
                    <i class="fas fa-receipt nav-icon"></i>
                    <div class="nav-title">Transactions</div>
                    <div class="nav-desc">View transaction history</div>
                </a>

                <a href="{{ route('user.referrals') }}" class="nav-item">
                    <i class="fas fa-users nav-icon"></i>
                    <div class="nav-title">Referral Program</div>
                    <div class="nav-desc">Earn through referrals</div>
                </a>

                <a href="{{ route('user.deposit') }}" class="nav-item">
                    <i class="fas fa-plus-circle nav-icon"></i>
                    <div class="nav-title">Deposit Funds</div>
                    <div class="nav-desc">Add money to your account</div>
                </a>

                <a href="{{ route('user.withdraw') }}" class="nav-item">
                    <i class="fas fa-money-bill-wave nav-icon"></i>
                    <div class="nav-title">Withdraw</div>
                    <div class="nav-desc">Cash out your earnings</div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl font-bold text-white mb-4">
                Technology-Driven <span class="text-eni-yellow">Investment Solutions</span>
            </h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                Our platform leverages cutting-edge technology to provide secure, automated, and profitable investment experiences
            </p>
        </div>

        <div class="features-grid">
            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h3 class="feature-title">Automated Interest</h3>
                <p class="feature-description">
                    AI-powered daily interest calculations ensure consistent returns on your investments with mathematical precision.
                </p>
            </div>

            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Enterprise Security</h3>
                <p class="feature-description">
                    Bank-grade encryption and multi-layer security protocols protect your investments and personal data.
                </p>
            </div>

            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-analytics"></i>
                </div>
                <h3 class="feature-title">Real-time Analytics</h3>
                <p class="feature-description">
                    Advanced analytics dashboard provides comprehensive insights into your investment performance and trends.
                </p>
            </div>

            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3 class="feature-title">Mobile Optimized</h3>
                <p class="feature-description">
                    Responsive design ensures seamless access to your investments from any device, anywhere, anytime.
                </p>
            </div>

            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3 class="feature-title">Referral Network</h3>
                <p class="feature-description">
                    Build your investment network through our advanced referral system with transparent tracking and rewards.
                </p>
            </div>

            <div class="glass-card feature-card animate-on-scroll">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="feature-title">24/7 Operations</h3>
                <p class="feature-description">
                    Our platform operates around the clock, ensuring your investments work for you even while you sleep.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Account Overview Section -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="glass-card p-8 animate-on-scroll">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-4">
                    Welcome back, <span class="text-eni-yellow">{{ auth()->user()->name }}</span>
                </h2>
                <p class="text-gray-300">
                    Your account overview and quick actions
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 rounded-xl border border-eni-yellow/20">
                    <div class="text-2xl font-bold text-eni-yellow mb-2">
                        @money(auth()->user()->account_balance)
                    </div>
                    <div class="text-gray-300">Account Balance</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-green-500/10 to-green-500/5 rounded-xl border border-green-500/20">
                    <div class="text-2xl font-bold text-green-400 mb-2">
                        {{ auth()->user()->investments()->where('active', true)->count() }}
                    </div>
                    <div class="text-gray-300">Active Investments</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-blue-500/10 to-blue-500/5 rounded-xl border border-blue-500/20">
                    <div class="text-2xl font-bold text-blue-400 mb-2">
                        {{ auth()->user()->referrals()->count() }}
                    </div>
                    <div class="text-gray-300">Total Referrals</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate numbers
    const statNumbers = document.querySelectorAll('[data-target]');

    const animateNumber = (element) => {
        const target = parseInt(element.dataset.target);
        const increment = target / 100;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + (target === 99.9 ? '%' : target === 256 ? '' : target === 100 ? '%' : '/7');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + (target === 99.9 ? '%' : target === 256 ? '' : target === 100 ? '%' : '/7');
            }
        }, 20);
    };

    // Intersection Observer for scroll animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');

                // Animate numbers when they come into view
                const statElements = entry.target.querySelectorAll('[data-target]');
                statElements.forEach(animateNumber);
            }
        });
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});
</script>
@endsection
