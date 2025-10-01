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

    /* Animated Background Overlay */
    .hero-animated-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            135deg,
            rgba(255, 205, 0, 0.05) 0%,
            transparent 50%,
            rgba(11, 34, 65, 0.3) 100%
        );
        animation: gradient-shift 15s ease infinite;
        pointer-events: none;
    }

    @keyframes gradient-shift {
        0%, 100% {
            background-position: 0% 50%;
            opacity: 0.6;
        }
        50% {
            background-position: 100% 50%;
            opacity: 0.8;
        }
    }

    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 30;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        animation: bounce 2s infinite;
    }

    .scroll-indicator-text {
        color: #FFCD00;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .scroll-arrow {
        width: 30px;
        height: 50px;
        border: 2px solid #FFCD00;
        border-radius: 25px;
        position: relative;
    }

    .scroll-arrow::before {
        content: '';
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: #FFCD00;
        border-radius: 50%;
        animation: scroll-dot 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateX(-50%) translateY(0);
        }
        40% {
            transform: translateX(-50%) translateY(-10px);
        }
        60% {
            transform: translateX(-50%) translateY(-5px);
        }
    }

    @keyframes scroll-dot {
        0% {
            top: 10px;
            opacity: 1;
        }
        100% {
            top: 30px;
            opacity: 0;
        }
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
<section class="hero-section relative overflow-hidden">
    <!-- Background Video/Image Overlay -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-eni-dark/95 via-eni-dark/90 to-transparent z-10"></div>
        <!-- Animated Overlay -->
        <div class="hero-animated-overlay z-15"></div>
        <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=1920&q=80"
             alt="Energy Infrastructure"
             class="w-full h-full object-cover opacity-40">
    </div>

    <div class="container mx-auto px-6 relative z-20">
        <div class="hero-content max-w-4xl py-32">
            <h1 class="text-6xl md:text-7xl font-bold text-white mb-6 leading-tight">
                Driving the Energy
                <span class="block text-eni-yellow">Transition</span>
            </h1>
            <p class="text-2xl text-gray-200 mb-8 leading-relaxed">
                From traditional energy to renewables, ENI leads with innovation and sustainability
            </p>
            <p class="text-lg text-gray-400 mb-10 max-w-2xl">
                A global energy company committed to carbon neutrality by 2050, pioneering the future of sustainable energy through cutting-edge technology and strategic partnerships.
            </p>
            <div class="flex flex-wrap gap-4">
                <!-- Primary CTA Button -->
                <a href="#strategy" class="px-8 py-4 bg-eni-yellow text-eni-dark font-bold rounded-lg hover:bg-yellow-400 transition-all shadow-glow hover:shadow-xl transform hover:-translate-y-1 hover:scale-105">
                    <i class="fas fa-chart-line mr-2"></i>
                    Discover Our Strategy
                </a>
                <!-- Secondary Outlined Button -->
                <a href="#sustainability" class="px-8 py-4 bg-transparent border-2 border-eni-yellow text-eni-yellow font-bold rounded-lg hover:bg-eni-yellow hover:text-eni-dark transition-all transform hover:-translate-y-1">
                    <i class="fas fa-leaf mr-2"></i>
                    Sustainability Report 2025
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator" onclick="document.getElementById('about').scrollIntoView({behavior: 'smooth'})">
        <span class="scroll-indicator-text">Scroll Down</span>
        <div class="scroll-arrow"></div>
    </div>
</section>

<!-- Company Overview Section -->
<section id="about" class="py-20 bg-eni-dark">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Global Energy <span class="text-eni-yellow">Leader</span>
            </h2>
            <p class="text-xl text-gray-400 leading-relaxed">
                Founded in Italy, ENI operates across the entire energy value chain with a global presence spanning oil, gas, renewables, and groundbreaking innovation. We are committed to the energy transition and achieving full decarbonization, powering a sustainable future for generations to come.
            </p>
        </div>

        <!-- Key Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 animate-on-scroll">
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/20 rounded-xl p-8 text-center hover:scale-105 transition-all duration-300 hover:shadow-glow">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-globe text-eni-yellow text-3xl"></i>
                    </div>
                </div>
                <div class="text-5xl font-bold text-eni-yellow mb-2 counter" data-target="60">0</div>
                <div class="text-gray-400 uppercase tracking-wide text-sm font-semibold">Countries of Operation</div>
            </div>
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/20 rounded-xl p-8 text-center hover:scale-105 transition-all duration-300 hover:shadow-glow">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-eni-yellow text-3xl"></i>
                    </div>
                </div>
                <div class="text-5xl font-bold text-eni-yellow mb-2 counter" data-target="31000">0</div>
                <div class="text-gray-400 uppercase tracking-wide text-sm font-semibold">Employees Worldwide</div>
            </div>
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/20 rounded-xl p-8 text-center hover:scale-105 transition-all duration-300 hover:shadow-glow">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-euro-sign text-eni-yellow text-3xl"></i>
                    </div>
                </div>
                <div class="text-5xl font-bold text-eni-yellow mb-2">
                    <span class="counter" data-target="93.7">0</span>B
                </div>
                <div class="text-gray-400 uppercase tracking-wide text-sm font-semibold">Annual Revenue</div>
            </div>
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/20 rounded-xl p-8 text-center hover:scale-105 transition-all duration-300 hover:shadow-glow">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-leaf text-eni-yellow text-3xl"></i>
                    </div>
                </div>
                <div class="text-5xl font-bold text-eni-yellow mb-2">
                    <span class="counter" data-target="8.5">0</span>B
                </div>
                <div class="text-gray-400 uppercase tracking-wide text-sm font-semibold">Renewable Investment</div>
            </div>
        </div>

        <!-- Coral South Project Video Showcase -->
        <div class="mt-20 animate-on-scroll">
            <div class="relative rounded-2xl overflow-hidden border-2 border-eni-yellow/30 shadow-2xl">
                <!-- Video Container with 16:9 Aspect Ratio -->
                <div class="relative w-full" style="aspect-ratio: 16/9;">
                    <video
                        class="absolute inset-0 w-full h-full object-cover"
                        autoplay
                        muted
                        loop
                        playsinline
                        poster="https://images.unsplash.com/photo-1565567934436-d7aca2346c34?w=1200&q=80"
                        preload="auto">
                        <source src="{{ asset('The Coral South Project.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Video Overlay Info -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-eni-dark/95 to-transparent p-4 md:p-6 pointer-events-none">
                    <h3 class="text-xl md:text-2xl font-bold text-eni-yellow mb-1 md:mb-2">
                        <i class="fas fa-play-circle mr-2"></i>
                        The Coral South Project
                    </h3>
                    <p class="text-white text-xs md:text-sm">
                        Pioneering floating LNG technology in Mozambique - A testament to ENI's innovation in offshore energy production
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Energy Portfolio Section -->
<section id="portfolio" class="py-20 bg-gradient-to-b from-eni-charcoal to-eni-dark">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Our Energy <span class="text-eni-yellow">Portfolio</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                A diversified energy portfolio spanning traditional and renewable sources, driving the global energy transition
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-on-scroll">
            <!-- Exploration & Production -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/20 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&q=80" alt="Oil Rig" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-oil-well text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Exploration & Production</h3>
                    <p class="text-gray-400 mb-4">
                        Strategic upstream operations in oil and gas across key global basins with cutting-edge extraction technologies.
                    </p>
                    <a href="#" class="text-eni-yellow hover:text-yellow-400 font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Renewable Energy -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-white/10 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1509391366360-2e959784a276?w=600&q=80" alt="Wind Turbines" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-solar-panel text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-eni-yellow mb-3">Renewable Energy</h3>
                    <p class="text-gray-400 mb-4">
                        Leading the clean energy revolution with solar, wind, and biomass projects delivering sustainable power globally.
                    </p>
                    <a href="#" class="text-white hover:text-eni-yellow font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Natural Gas & LNG -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-white/10 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1592659762303-90081d34b277?w=600&q=80" alt="LNG Terminal" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-fire-flame-curved text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-eni-yellow mb-3">Natural Gas & LNG</h3>
                    <p class="text-gray-400 mb-4">
                        World-class LNG infrastructure and natural gas operations providing cleaner energy transition solutions.
                    </p>
                    <a href="#" class="text-white hover:text-eni-yellow font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Hydrogen & New Technologies -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-white/10 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" alt="Hydrogen Plant" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-atom text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-eni-yellow mb-3">Hydrogen & New Technologies</h3>
                    <p class="text-gray-400 mb-4">
                        Pioneering hydrogen production and innovative energy solutions for a carbon-neutral future.
                    </p>
                    <a href="#" class="text-white hover:text-eni-yellow font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Retail & Power -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-white/10 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=600&q=80" alt="Power Grid" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bolt text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-eni-yellow mb-3">Retail & Power</h3>
                    <p class="text-gray-400 mb-4">
                        Comprehensive retail energy services and power generation serving millions of customers worldwide.
                    </p>
                    <a href="#" class="text-white hover:text-eni-yellow font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Carbon Capture & Storage -->
            <div class="group relative bg-gradient-to-br from-eni-dark to-eni-charcoal border border-white/10 rounded-xl p-8 hover:border-eni-yellow/50 transition-all duration-500 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-opacity duration-500">
                    <img src="https://images.unsplash.com/photo-1611273426858-450d8e3c9fce?w=600&q=80" alt="Carbon Capture" class="w-full h-full object-cover">
                </div>
                <div class="relative z-10">
                    <div class="text-5xl mb-6 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-cloud text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-eni-yellow mb-3">Carbon Capture & Storage</h3>
                    <p class="text-gray-400 mb-4">
                        Advanced CCS technology removing emissions and supporting climate neutrality goals.
                    </p>
                    <a href="#" class="text-white hover:text-eni-yellow font-semibold inline-flex items-center group-hover:translate-x-2 transition-transform">
                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ENI Philippines Expansion Section -->
<section id="philippines-expansion" class="py-20 bg-gradient-to-br from-eni-charcoal via-eni-dark to-eni-charcoal relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 right-0 w-96 h-96 bg-eni-yellow rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-20 left-0 w-96 h-96 bg-eni-yellow rounded-full filter blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16 animate-on-scroll">
            <div class="inline-block mb-4">
                <span class="px-6 py-2 bg-eni-yellow/10 border border-eni-yellow/30 rounded-full text-eni-yellow font-semibold text-sm uppercase tracking-wider">
                    Our Journey in the Philippines
                </span>
            </div>
            <h2 class="text-5xl md:text-6xl font-bold text-white mb-6">
                ENI's <span class="text-eni-yellow">Philippine Expansion</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Building a sustainable energy future in the Philippines, starting with premium lubricant distribution and expanding into comprehensive energy solutions
            </p>
        </div>

        <!-- Story Timeline -->
        <div class="grid lg:grid-cols-2 gap-12 mb-16 items-center">
            <!-- Left: Story Content -->
            <div class="animate-on-scroll">
                <div class="bg-gradient-to-br from-white/5 to-white/0 border border-white/10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-eni-yellow rounded-full flex items-center justify-center">
                            <i class="fas fa-flag text-eni-dark text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-white">The Beginning</h3>
                            <p class="text-eni-yellow font-semibold">Lubricant Distribution Pioneer</p>
                        </div>
                    </div>

                    <p class="text-gray-300 text-lg leading-relaxed mb-6">
                        ENI's Philippine journey began with a strategic focus on distributing premium lubricants across the nation. Recognizing the growing demand for high-quality energy products, we established a robust distribution network to serve Filipino industries and consumers.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-eni-yellow"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Premium Quality Products</h4>
                                <p class="text-gray-400 text-sm">World-class lubricants engineered for tropical climates and demanding conditions</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-eni-yellow"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Nationwide Distribution Network</h4>
                                <p class="text-gray-400 text-sm">Strategic partnerships ensuring product availability across all major islands</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-eni-yellow"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Technical Support & Service</h4>
                                <p class="text-gray-400 text-sm">Expert teams providing consultation and after-sales support to customers</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-eni-yellow/20 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas fa-check text-eni-yellow"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Community Engagement</h4>
                                <p class="text-gray-400 text-sm">Creating jobs and supporting local communities through sustainable business practices</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Featured Image -->
            <div class="animate-on-scroll">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-eni-yellow to-yellow-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000"></div>
                    <div class="relative">
                        <img src="{{ asset('548003252_122223226046091369_1383863442854504664_n.jpg') }}"
                             alt="ENI Philippines Lubricant Distribution"
                             class="w-full h-[500px] object-cover rounded-2xl shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/80 via-transparent to-transparent rounded-2xl"></div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <p class="text-white font-semibold text-lg">ENI Lubricants in the Philippines</p>
                            <p class="text-gray-300 text-sm">Premium quality for Filipino industries</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Gallery Grid -->
        <div class="animate-on-scroll mb-12">
            <h3 class="text-3xl font-bold text-white text-center mb-8">
                <span class="text-eni-yellow">Our Operations</span> in Action
            </h3>
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Gallery Image 1 -->
                <div class="group relative overflow-hidden rounded-xl">
                    <img src="{{ asset('546961280_122223226226091369_6117151309338851552_n.jpg') }}"
                         alt="ENI Philippines Operations"
                         class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/90 via-eni-dark/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-white font-semibold">Distribution Excellence</p>
                    </div>
                </div>

                <!-- Gallery Image 2 -->
                <div class="group relative overflow-hidden rounded-xl">
                    <img src="{{ asset('550909789_122224593830091369_1310127139799660935_n.jpg') }}"
                         alt="ENI Philippines Team"
                         class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/90 via-eni-dark/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-white font-semibold">Quality Assurance</p>
                    </div>
                </div>

                <!-- Gallery Image 3 -->
                <div class="group relative overflow-hidden rounded-xl">
                    <img src="{{ asset('550995072_122224593836091369_820995601659429653_n.jpg') }}"
                         alt="ENI Philippines Growth"
                         class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/90 via-eni-dark/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <p class="text-white font-semibold">Customer Service</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Future Vision -->
        <div class="text-center animate-on-scroll mb-12">
            <div class="bg-gradient-to-r from-eni-yellow/10 via-eni-yellow/5 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-10 max-w-4xl mx-auto">
                <div class="w-20 h-20 bg-eni-yellow rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-rocket text-eni-dark text-3xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-4">Looking Forward</h3>
                <p class="text-gray-300 text-lg leading-relaxed mb-6">
                    Our Philippine expansion represents just the beginning. Building on our success in lubricant distribution,
                    we're committed to expanding our energy solutions portfolio, investing in renewable energy projects,
                    and contributing to the Philippines' sustainable energy transition.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="px-6 py-3 bg-eni-dark border border-eni-yellow/30 rounded-lg">
                        <div class="text-2xl font-bold text-eni-yellow">50+</div>
                        <div class="text-sm text-gray-400">Distribution Points</div>
                    </div>
                    <div class="px-6 py-3 bg-eni-dark border border-eni-yellow/30 rounded-lg">
                        <div class="text-2xl font-bold text-eni-yellow">1,000+</div>
                        <div class="text-sm text-gray-400">Business Partners</div>
                    </div>
                    <div class="px-6 py-3 bg-eni-dark border border-eni-yellow/30 rounded-lg">
                        <div class="text-2xl font-bold text-eni-yellow">100%</div>
                        <div class="text-sm text-gray-400">Customer Satisfaction</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gas Station Expansion Plan -->
        <div class="animate-on-scroll">
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border-2 border-eni-yellow/40 rounded-2xl overflow-hidden max-w-5xl mx-auto shadow-2xl">
                <div class="bg-gradient-to-r from-eni-yellow to-yellow-500 px-8 py-4">
                    <div class="flex items-center justify-center gap-3">
                        <i class="fas fa-gas-pump text-eni-dark text-2xl"></i>
                        <h3 class="text-2xl font-bold text-eni-dark">Next Milestone: ENI Fuel Stations</h3>
                    </div>
                </div>

                <div class="p-8">
                    <p class="text-gray-300 text-lg text-center mb-8 leading-relaxed">
                        We are excited to announce our strategic plan to establish <span class="text-eni-yellow font-bold">ENI-branded fuel stations</span> across the Philippines,
                        bringing world-class fuel quality and service excellence directly to Filipino motorists and businesses.
                    </p>

                    <!-- Fuel Station Image -->
                    <div class="mb-8 animate-on-scroll">
                        <div class="relative group max-w-4xl mx-auto">
                            <div class="absolute -inset-1 bg-gradient-to-r from-eni-yellow to-yellow-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                            <div class="relative">
                                <img src="{{ asset('Fuel.png') }}"
                                     alt="ENI Fuel Station"
                                     class="w-full h-auto rounded-2xl shadow-2xl">
                                <div class="absolute inset-0 bg-gradient-to-t from-eni-dark/60 via-transparent to-transparent rounded-2xl"></div>
                                <div class="absolute bottom-6 left-6 right-6 text-center">
                                    <p class="text-white font-bold text-xl mb-1">ENI Fuel Station</p>
                                    <p class="text-gray-200 text-sm">Coming Soon to the Philippines</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <!-- Left: Key Features -->
                        <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                            <h4 class="text-xl font-bold text-eni-yellow mb-4 flex items-center gap-2">
                                <i class="fas fa-star"></i>
                                Premium Fuel Offerings
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">High-performance gasoline and diesel formulations</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">Advanced engine cleaning additives</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">Premium lubricants available on-site</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">Eco-friendly fuel options</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Right: Station Features -->
                        <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                            <h4 class="text-xl font-bold text-eni-yellow mb-4 flex items-center gap-2">
                                <i class="fas fa-building"></i>
                                Modern Station Amenities
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">State-of-the-art fueling infrastructure</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">Convenience stores and cafés</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">EV charging stations (future-ready)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-eni-yellow mt-1"></i>
                                    <span class="text-gray-300">24/7 customer service and security</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Rollout Timeline -->
                    <div class="bg-gradient-to-r from-eni-yellow/5 to-transparent border-l-4 border-eni-yellow rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-eni-yellow rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-alt text-eni-dark text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-white mb-2">Rollout Timeline</h4>
                                <p class="text-gray-300 mb-3">
                                    Our phased expansion plan aims to establish a network of ENI fuel stations across key metropolitan areas
                                    and strategic highways, with the first stations planned for Metro Manila and major provincial cities.
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="text-center p-3 bg-eni-dark rounded-lg">
                                        <div class="text-eni-yellow font-bold text-lg">Phase 1</div>
                                        <div class="text-gray-400 text-sm">2026-2027</div>
                                        <div class="text-white text-xs mt-1">5-10 Stations</div>
                                    </div>
                                    <div class="text-center p-3 bg-eni-dark rounded-lg">
                                        <div class="text-eni-yellow font-bold text-lg">Phase 2</div>
                                        <div class="text-gray-400 text-sm">2028-2029</div>
                                        <div class="text-white text-xs mt-1">25+ Stations</div>
                                    </div>
                                    <div class="text-center p-3 bg-eni-dark rounded-lg">
                                        <div class="text-eni-yellow font-bold text-lg">Phase 3</div>
                                        <div class="text-gray-400 text-sm">2030+</div>
                                        <div class="text-white text-xs mt-1">50+ Stations</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="text-center mt-8">
                        <p class="text-gray-400 mb-4">Interested in partnership opportunities or franchise information?</p>
                        <a href="#" class="inline-flex items-center gap-2 px-8 py-4 bg-eni-yellow text-eni-dark font-bold rounded-lg hover:bg-yellow-400 transition-all shadow-lg hover:shadow-eni-yellow/30 hover:scale-105">
                            <i class="fas fa-handshake"></i>
                            <span>Become a Partner</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sustainability & ESG Section -->
<section id="sustainability" class="py-20 bg-eni-dark relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-96 h-96 bg-eni-yellow rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-eni-yellow rounded-full filter blur-3xl"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-eni-yellow mb-4">
                Sustainability & <span class="text-white">ESG Commitments</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Leading the industry toward a sustainable future with measurable climate action and responsible business practices
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Net Zero Target -->
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
                <div class="flex items-start mb-6">
                    <div class="text-5xl mr-6">
                        <i class="fas fa-bullseye text-eni-yellow"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-eni-yellow mb-2">Net-Zero by 2050</h3>
                        <p class="text-lg text-white font-semibold">Absolute Carbon Neutrality Target</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">
                    Committed to achieving net-zero greenhouse gas emissions across all operations (Scope 1, 2 & 3) by 2050, with interim targets of 35% reduction by 2030 and 80% by 2040.
                </p>
                <div class="flex items-center text-eni-yellow">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span class="text-sm">Progress: 25% reduction achieved since 2018</span>
                </div>
            </div>

            <!-- Carbon Capture & Storage -->
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
                <div class="flex items-start mb-6">
                    <div class="text-5xl mr-6">
                        <i class="fas fa-cloud-arrow-down text-eni-yellow"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Carbon Capture</h3>
                        <p class="text-lg text-eni-yellow font-semibold">30+ Million Tonnes CO₂/Year</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">
                    Operating and developing CCS facilities capable of capturing and permanently storing over 30 million tonnes of CO₂ annually, including major projects in Italy, UK, and Australia.
                </p>
                <div class="flex items-center text-eni-yellow">
                    <i class="fas fa-industry mr-2"></i>
                    <span class="text-sm">12 active CCS projects globally</span>
                </div>
            </div>

            <!-- Circular Economy -->
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
                <div class="flex items-start mb-6">
                    <div class="text-5xl mr-6">
                        <i class="fas fa-recycle text-eni-yellow"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Circular Economy</h3>
                        <p class="text-lg text-eni-yellow font-semibold">Waste-to-Value Innovation</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">
                    Transforming waste into valuable resources through bio-refineries converting used cooking oil and organic waste into sustainable aviation fuel and bio-products.
                </p>
                <div class="flex items-center text-eni-yellow">
                    <i class="fas fa-leaf mr-2"></i>
                    <span class="text-sm">4 bio-refineries processing 1M+ tonnes annually</span>
                </div>
            </div>

            <!-- Biodiversity & Nature -->
            <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
                <div class="flex items-start mb-6">
                    <div class="text-5xl mr-6">
                        <i class="fas fa-seedling text-eni-yellow"></i>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-white mb-2">Nature-Based Solutions</h3>
                        <p class="text-lg text-eni-yellow font-semibold">20 Million Hectares Protected</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4">
                    Investing in forest conservation, reforestation, and ecosystem restoration projects that sequester carbon while protecting biodiversity and supporting local communities.
                </p>
                <div class="flex items-center text-eni-yellow">
                    <i class="fas fa-tree mr-2"></i>
                    <span class="text-sm">200M+ trees planted since 2020</span>
                </div>
            </div>
        </div>

        <!-- ESG Report CTA -->
        <div class="text-center animate-on-scroll">
            <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-8 inline-block">
                <h4 class="text-2xl font-bold text-white mb-4">Explore Our Full ESG Performance</h4>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#" class="px-8 py-3 bg-eni-yellow text-eni-dark font-bold rounded-lg hover:bg-yellow-400 transition-all inline-flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Download Sustainability Report 2025
                    </a>
                    <a href="#" class="px-8 py-3 bg-eni-yellow text-white font-bold rounded-lg hover:bg-green-600 transition-all inline-flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        View ESG Dashboard
                    </a>
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
            <p class="text-xl text-gray-400">
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

<!-- Innovation & Technology Section -->
<section id="innovation" class="py-20 bg-gradient-to-b from-eni-dark to-eni-charcoal">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Innovation & <span class="text-eni-yellow">Technology</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Pioneering research and development in next-generation energy technologies to power a sustainable future
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Carbon Capture & Storage -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-2xl p-8 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-cloud-arrow-down text-3xl text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Carbon Capture & Storage</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Advanced CCS technology capturing and permanently storing CO₂ emissions deep underground. Our HyNet North West project in the UK will capture 10 million tonnes annually by 2030.
                </p>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>Ravenna CCS Hub - Italy's largest offshore CO₂ storage facility</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>HyNet North West - 10M tonnes/year capacity by 2030</span>
                    </li>
                </ul>
            </div>

            <!-- Hydrogen Production -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-2xl p-8 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-atom text-3xl text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Green & Blue Hydrogen</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Developing both green hydrogen (from renewable electricity) and blue hydrogen (from natural gas with CCS) to decarbonize hard-to-abate sectors like steel, chemicals, and heavy transport.
                </p>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>Target: 4 GW of renewable hydrogen capacity by 2030</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>Partnerships with industries for hydrogen adoption</span>
                    </li>
                </ul>
            </div>

            <!-- Biofuels & Biorefining -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-2xl p-8 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-leaf text-3xl text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Sustainable Biofuels</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Converting waste feedstocks and residues into advanced biofuels including sustainable aviation fuel (SAF), biodiesel, and bio-naphtha through our proprietary Ecofining™ technology.
                </p>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>Venice and Gela bio-refineries in Italy</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>2M tonnes/year biofuel production capacity</span>
                    </li>
                </ul>
            </div>

            <!-- Digital & AI Technologies -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-2xl p-8 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eni-yellow/20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-brain text-3xl text-eni-yellow"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">AI & Digital Innovation</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Leveraging artificial intelligence, machine learning, and advanced analytics to optimize energy production, predict equipment failures, and enhance operational efficiency across our global assets.
                </p>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>AI-powered predictive maintenance systems</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-eni-yellow mr-3 mt-1"></i>
                        <span>Digital twins for offshore platform optimization</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- R&D Investment Callout -->
        <div class="mt-12 text-center animate-on-scroll">
            <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-8 inline-block">
                <div class="flex items-center justify-center gap-8 flex-wrap">
                    <div>
                        <div class="text-4xl font-bold text-eni-yellow mb-2">€850M</div>
                        <div class="text-gray-400">Annual R&D Investment</div>
                    </div>
                    <div class="hidden md:block w-px h-16 bg-eni-yellow/30"></div>
                    <div>
                        <div class="text-4xl font-bold text-eni-yellow mb-2">200+</div>
                        <div class="text-gray-400">Active Research Projects</div>
                    </div>
                    <div class="hidden md:block w-px h-16 bg-eni-yellow/30"></div>
                    <div>
                        <div class="text-4xl font-bold text-eni-yellow mb-2">15+</div>
                        <div class="text-gray-400">Innovation Partnerships</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News & Updates Section -->
<section id="news" class="py-20 bg-eni-dark">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-12 animate-on-scroll">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Latest <span class="text-eni-yellow">News</span>
                </h2>
                <p class="text-xl text-gray-400">Stay updated with ENI's global developments and strategic initiatives</p>
            </div>
            <!-- Stock Ticker -->
            <div class="hidden lg:block bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/5 border border-eni-yellow/30 rounded-xl p-4">
                <div class="text-sm text-gray-400 mb-1">ENI S.p.A. (BIT: ENI)</div>
                <div class="flex items-center gap-3">
                    <span class="text-3xl font-bold text-white">€14.52</span>
                    <span class="text-eni-yellow flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +0.85 (6.22%)
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- News Article 1 -->
            <div class="group bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/20 rounded-xl overflow-hidden hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="h-48 bg-gradient-to-br from-eni-yellow/20 to-eni-yellow/5 flex items-center justify-center">
                    <i class="fas fa-wind text-6xl text-eni-yellow"></i>
                </div>
                <div class="p-6">
                    <div class="text-sm text-eni-yellow mb-2">October 1, 2025</div>
                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-eni-yellow transition-colors">
                        ENI Expands Offshore Wind Capacity in the North Sea
                    </h3>
                    <p class="text-gray-400 mb-4">
                        New 1.2 GW offshore wind project approved off the coast of Scotland, supporting UK renewable energy targets.
                    </p>
                    <a href="#" class="text-eni-yellow hover:text-yellow-400 font-semibold inline-flex items-center">
                        Read More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- News Article 2 -->
            <div class="group bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/20 rounded-xl overflow-hidden hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="h-48 bg-gradient-to-br from-eni-yellow/20 to-eni-yellow/5 flex items-center justify-center">
                    <i class="fas fa-handshake text-6xl text-eni-yellow"></i>
                </div>
                <div class="p-6">
                    <div class="text-sm text-eni-yellow mb-2">September 28, 2025</div>
                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-eni-yellow transition-colors">
                        Strategic Partnership with TotalEnergies for Mozambique LNG
                    </h3>
                    <p class="text-gray-400 mb-4">
                        Joint venture to develop Area 4 Coral South FLNG project, strengthening East Africa energy infrastructure.
                    </p>
                    <a href="#" class="text-eni-yellow hover:text-yellow-400 font-semibold inline-flex items-center">
                        Read More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- News Article 3 -->
            <div class="group bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/20 rounded-xl overflow-hidden hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="h-48 bg-gradient-to-br from-eni-yellow/20 to-eni-yellow/5 flex items-center justify-center">
                    <i class="fas fa-atom text-6xl text-eni-yellow"></i>
                </div>
                <div class="p-6">
                    <div class="text-sm text-eni-yellow mb-2">September 25, 2025</div>
                    <h3 class="text-xl font-bold text-white mb-3 group-hover:text-eni-yellow transition-colors">
                        Breakthrough in Green Hydrogen Production Technology
                    </h3>
                    <p class="text-gray-400 mb-4">
                        ENI unveils new electrolyzer technology reducing production costs by 40%, accelerating hydrogen economy.
                    </p>
                    <a href="#" class="text-eni-yellow hover:text-yellow-400 font-semibold inline-flex items-center">
                        Read More <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-12 animate-on-scroll">
            <a href="#" class="inline-flex items-center px-8 py-3 border-2 border-eni-yellow text-eni-yellow font-bold rounded-lg hover:bg-eni-yellow hover:text-eni-dark transition-all">
                View All News <i class="fas fa-newspaper ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Investor Relations Section -->
<section id="investors" class="py-20 bg-gradient-to-b from-eni-charcoal to-eni-dark">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Investor <span class="text-eni-yellow">Relations</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Comprehensive financial information and resources for shareholders and investors
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Annual Reports -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="text-4xl text-eni-yellow mb-4">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Annual Reports</h3>
                <p class="text-gray-400 mb-4 text-sm">Download financial statements and performance reports</p>
                <a href="#" class="text-eni-yellow hover:text-eni-yellow font-semibold text-sm inline-flex items-center">
                    Access Reports <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>

            <!-- Stock Information -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="text-4xl text-eni-yellow mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Stock Information</h3>
                <p class="text-gray-400 mb-4 text-sm">Real-time quotes, historical data, and dividends</p>
                <a href="#" class="text-eni-yellow hover:text-eni-yellow font-semibold text-sm inline-flex items-center">
                    View Stock Data <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>

            <!-- Investor Events -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="text-4xl text-eni-yellow mb-4">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Investor Events</h3>
                <p class="text-gray-400 mb-4 text-sm">Upcoming earnings calls and presentations</p>
                <a href="#" class="text-eni-yellow hover:text-eni-yellow font-semibold text-sm inline-flex items-center">
                    Event Calendar <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>

            <!-- ESG Metrics -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all animate-on-scroll">
                <div class="text-4xl text-eni-yellow mb-4">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">ESG Metrics</h3>
                <p class="text-gray-400 mb-4 text-sm">Sustainability performance and ratings</p>
                <a href="#" class="text-eni-yellow hover:text-yellow-400 font-semibold text-sm inline-flex items-center">
                    ESG Dashboard <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Key Financial Highlights -->
        <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
            <h3 class="text-2xl font-bold text-white mb-6 text-center">2024 Financial Highlights</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-eni-yellow mb-2">€93.7B</div>
                    <div class="text-gray-400 text-sm">Total Revenue</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-eni-yellow mb-2">€13.8B</div>
                    <div class="text-gray-400 text-sm">EBITDA</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-eni-yellow mb-2">€0.94</div>
                    <div class="text-gray-400 text-sm">Dividend per Share</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-eni-yellow mb-2">14.2%</div>
                    <div class="text-gray-400 text-sm">ROE</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Careers Section -->
<section id="careers" class="py-20 bg-eni-dark">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left: Content -->
            <div class="animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Join the Energy <span class="text-eni-yellow">Revolution</span>
                </h2>
                <p class="text-xl text-gray-400 mb-8">
                    Build your career with a global leader committed to innovation, sustainability, and excellence. ENI offers opportunities across engineering, technology, research, and business operations.
                </p>

                <!-- Career Opportunities -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-eni-yellow/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-graduation-cap text-eni-yellow text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1">Graduate Programs</h4>
                            <p class="text-gray-400">18-month rotational programs for recent graduates in engineering, science, and business</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-eni-yellow/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-briefcase text-eni-yellow text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1">Professional Roles</h4>
                            <p class="text-gray-400">Experienced positions in renewable energy, exploration, digital transformation, and more</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-eni-yellow/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-globe text-eni-yellow text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1">International Opportunities</h4>
                            <p class="text-gray-400">Work across 60+ countries with diverse, multicultural teams</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-eni-yellow/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-users text-eni-yellow text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1">Diversity & Inclusion</h4>
                            <p class="text-gray-400">Committed to gender balance, equal opportunity, and inclusive workplace culture</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="#" class="px-8 py-3 bg-eni-yellow text-eni-dark font-bold rounded-lg hover:bg-yellow-400 transition-all inline-flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Search Jobs
                    </a>
                    <a href="#" class="px-8 py-3 border-2 border-eni-yellow text-eni-yellow font-bold rounded-lg hover:bg-eni-yellow hover:text-eni-dark transition-all inline-flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Learn More
                    </a>
                </div>
            </div>

            <!-- Right: Stats & Image -->
            <div class="animate-on-scroll">
                <div class="bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-8">
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-eni-yellow mb-2">31,000+</div>
                            <div class="text-gray-400">Employees</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-eni-yellow mb-2">120+</div>
                            <div class="text-gray-400">Nationalities</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-eni-yellow mb-2">42%</div>
                            <div class="text-gray-400">Women in Workforce</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-eni-yellow mb-2">35hrs</div>
                            <div class="text-gray-400">Training/Year/Employee</div>
                        </div>
                    </div>

                    <!-- Quote -->
                    <div class="bg-eni-dark/50 rounded-xl p-6 border border-eni-yellow/20">
                        <p class="text-gray-400 italic mb-4">
                            "At ENI, we empower our people to drive the energy transition. Innovation, sustainability, and excellence are at the heart of everything we do."
                        </p>
                        <div class="text-eni-yellow font-semibold">— Chief Human Resources Officer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Global Presence Section -->
<section id="global-presence" class="py-20 bg-gradient-to-b from-eni-charcoal to-eni-dark">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Our Global <span class="text-eni-yellow">Footprint</span>
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Operating across 60+ countries on 5 continents, delivering energy solutions to communities worldwide
            </p>
        </div>

        <!-- Regional Presence -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 animate-on-scroll">
            <!-- Europe -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all">
                <div class="text-5xl text-eni-yellow mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Europe</h3>
                <p class="text-gray-400 mb-4">25+ countries including Italy, UK, Norway, and Germany</p>
                <div class="text-eni-yellow text-sm">
                    Major operations: North Sea oil & gas, renewable energy projects
                </div>
            </div>

            <!-- Africa -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all">
                <div class="text-5xl text-eni-yellow mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Africa</h3>
                <p class="text-gray-400 mb-4">20+ countries including Egypt, Nigeria, Mozambique, Angola</p>
                <div class="text-eni-yellow text-sm">
                    Major operations: Offshore exploration, LNG projects
                </div>
            </div>

            <!-- Asia & Oceania -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all">
                <div class="text-5xl text-eni-yellow mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Asia & Oceania</h3>
                <p class="text-gray-400 mb-4">15+ countries including Kazakhstan, Indonesia, Australia</p>
                <div class="text-eni-yellow text-sm">
                    Major operations: Natural gas, renewable energy
                </div>
            </div>

            <!-- Americas -->
            <div class="bg-gradient-to-br from-eni-dark to-eni-charcoal border border-eni-yellow/30 rounded-xl p-6 hover:border-eni-yellow/50 transition-all">
                <div class="text-5xl text-eni-yellow mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Americas</h3>
                <p class="text-gray-400 mb-4">10+ countries including USA, Mexico, Venezuela</p>
                <div class="text-eni-yellow text-sm">
                    Major operations: Upstream, biofuels, retail
                </div>
            </div>
        </div>

        <!-- Country Spotlight -->
        <div class="bg-gradient-to-r from-eni-yellow/10 to-eni-yellow/10 border border-eni-yellow/30 rounded-2xl p-8 animate-on-scroll">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <div class="inline-block bg-eni-yellow/20 rounded-lg px-4 py-2 mb-4">
                        <span class="text-eni-yellow font-semibold">Country Spotlight</span>
                    </div>
                    <h3 class="text-3xl font-bold text-white mb-4">
                        ENI in Mozambique: <span class="text-eni-yellow">Coral South FLNG</span>
                    </h3>
                    <p class="text-gray-400 mb-6">
                        Operating Africa's first floating LNG facility, producing 3.4 million tonnes per year from the Coral South gas field. This landmark project brings sustainable energy development to East Africa while supporting local communities.
                    </p>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <div class="text-2xl font-bold text-eni-yellow">3.4MT</div>
                            <div class="text-sm text-gray-400">LNG per Year</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-eni-yellow">450</div>
                            <div class="text-sm text-gray-400">Bcm Gas Reserves</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-eni-yellow">5,000+</div>
                            <div class="text-sm text-gray-400">Local Jobs</div>
                        </div>
                    </div>
                </div>
                <div class="bg-eni-dark/50 rounded-xl p-6 border border-eni-yellow/30">
                    <div class="aspect-video bg-gradient-to-br from-eni-yellow/20 to-eni-yellow/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ship text-6xl text-eni-yellow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
                <p class="text-gray-400">
                    Your account overview and quick actions
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 rounded-xl border border-eni-yellow/20">
                    <div class="text-2xl font-bold text-eni-yellow mb-2">
                        @money(auth()->user()->account_balance)
                    </div>
                    <div class="text-gray-400">Account Balance</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 rounded-xl border border-eni-yellow/20">
                    <div class="text-2xl font-bold text-eni-yellow mb-2">
                        {{ auth()->user()->investments()->where('active', true)->count() }}
                    </div>
                    <div class="text-gray-400">Active Investments</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-br from-eni-yellow/10 to-eni-yellow/5 rounded-xl border border-eni-yellow/20">
                    <div class="text-2xl font-bold text-eni-yellow mb-2">
                        {{ auth()->user()->referrals()->count() }}
                    </div>
                    <div class="text-gray-400">Total Referrals</div>
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

                // Animate counters
                const counters = entry.target.querySelectorAll('.counter');
                counters.forEach(counter => {
                    const target = parseFloat(counter.getAttribute('data-target'));
                    const duration = 2000;
                    const increment = target / (duration / 16);
                    let current = 0;

                    const updateCounter = () => {
                        current += increment;
                        if (current < target) {
                            if (target > 1000) {
                                counter.textContent = Math.floor(current).toLocaleString();
                            } else {
                                counter.textContent = current.toFixed(1);
                            }
                            requestAnimationFrame(updateCounter);
                        } else {
                            if (target > 1000) {
                                counter.textContent = Math.floor(target).toLocaleString() + '+';
                            } else {
                                counter.textContent = target.toFixed(1);
                            }
                        }
                    };

                    updateCounter();
                });
            }
        });
    });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});
</script>
@endsection
