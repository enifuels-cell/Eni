<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENI Investment Platform — Enterprise Investment Solutions</title>
    <meta name="description" content="Enterprise-level investment platform offering secure deposit solutions, automated daily interest calculations, and comprehensive portfolio management.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --eni-yellow:#FFD100;
            --eni-dark-blue:#0b2241;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: Inter, sans-serif;
            color: #ffffff;
            background-color: var(--eni-dark-blue);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .eni-splash-screen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 10;
        }
        .eni-logo {
            width: 150px;
            height: 80px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .eni-logo img {
            width: 100%;
            height: auto;
            max-height: 100%;
            object-fit: contain;
        }
        .eni-tagline {
            font-size: 1.2em;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 40px;
            color: var(--eni-yellow);
        }
        /* Abstract Background Lines (using CSS gradients for a subtle pattern) */
        .background-lines {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                linear-gradient(90deg, transparent 50%, rgba(255, 255, 255, 0.05) 50%, transparent 51%),
                linear-gradient(0deg, transparent 50%, rgba(255, 255, 255, 0.05) 50%, transparent 51%);
            background-size: 20px 20px;
            opacity: 0.1;
            z-index: 1;
        }
        /* Loading Spinner */
        .loading-spinner {
            position: relative;
            width: 50px;
            height: 50px;
            margin: 0 auto;
            animation: rotate 1.5s linear infinite;
        }
        .spinner-segment {
            position: absolute;
            width: 8px;
            height: 8px;
            background-color: var(--eni-yellow);
            border-radius: 50%;
            opacity: 0;
            animation: fade-in-out 1.5s ease-in-out infinite;
        }
        /* Positioning for segments */
        .spinner-segment:nth-child(1) { transform: rotate(0deg) translate(20px); animation-delay: 0s; }
        .spinner-segment:nth-child(2) { transform: rotate(30deg) translate(20px); animation-delay: -0.125s; }
        .spinner-segment:nth-child(3) { transform: rotate(60deg) translate(20px); animation-delay: -0.25s; }
        .spinner-segment:nth-child(4) { transform: rotate(90deg) translate(20px); animation-delay: -0.375s; }
        .spinner-segment:nth-child(5) { transform: rotate(120deg) translate(20px); animation-delay: -0.5s; }
        .spinner-segment:nth-child(6) { transform: rotate(150deg) translate(20px); animation-delay: -0.625s; }
        .spinner-segment:nth-child(7) { transform: rotate(180deg) translate(20px); animation-delay: -0.75s; }
        .spinner-segment:nth-child(8) { transform: rotate(210deg) translate(20px); animation-delay: -0.875s; }
        .spinner-segment:nth-child(9) { transform: rotate(240deg) translate(20px); animation-delay: -1s; }
        .spinner-segment:nth-child(10) { transform: rotate(270deg) translate(20px); animation-delay: -1.125s; }
        .spinner-segment:nth-child(11) { transform: rotate(300deg) translate(20px); animation-delay: -1.25s; }
        .spinner-segment:nth-child(12) { transform: rotate(330deg) translate(20px); animation-delay: -1.375s; }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes fade-in-out {
            0%, 100% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="eni-splash-screen">
        {{-- ENI Logo --}}
        <div class="eni-logo">
            <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" />
        </div>
        <div class="eni-tagline">ENTERPRISE INVESTMENT PLATFORM</div>
        <div style="margin-bottom: 20px; color: #cccccc; font-size: 16px;">
            Secure • Automated • Professional
        </div>
        <div class="loading-spinner">
            @for ($i = 0; $i < 12; $i++)
                <div class="spinner-segment"></div>
            @endfor
        </div>
        {{-- Loading message --}}
        <div style="margin-top: 30px; color: #cccccc; font-size: 14px; text-align: center;">
            <div id="loading-message">Initializing secure connection...</div>
            <div style="margin-top: 15px;">
                <button onclick="skipSplash()" 
                        style="background: transparent; border: 1px solid var(--eni-yellow); color: var(--eni-yellow); padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 12px; transition: all 0.3s ease;"
                        onmouseover="this.style.background='var(--eni-yellow)'; this.style.color='var(--eni-dark-blue)'"
                        onmouseout="this.style.background='transparent'; this.style.color='var(--eni-yellow)'">
                    Skip Intro
                </button>
            </div>
        </div>
    </div>
    <div class="background-lines"></div>
    <script>
        function skipSplash() {
            window.location.href = '{{ route('login') }}';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Update loading message
            const messages = [
                'Initializing secure connection...',
                'Loading enterprise platform...',
                'Preparing your workspace...',
                'Welcome to ENI Investment...'
            ];
            let messageIndex = 0;
            
            const messageElement = document.getElementById('loading-message');
            
            const messageInterval = setInterval(function() {
                messageIndex = (messageIndex + 1) % messages.length;
                messageElement.textContent = messages[messageIndex];
            }, 700);
            
            setTimeout(function() {
                clearInterval(messageInterval);
                messageElement.textContent = 'Redirecting to platform...';
                setTimeout(function() {
                    window.location.href = '{{ route('login') }}';
                }, 500);
            }, 3000);
        });
    </script>
</body>
</html>
