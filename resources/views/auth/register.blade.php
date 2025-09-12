<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENI Investment Platform — Register</title>
    <meta name="theme-color" content="#FFCD00" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --eni-yellow: #FFCD00;
            --eni-dark-blue: #0B2241;
            --eni-charcoal: #121417;
            --radius: 16px;
        }
        
        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Inter, ui-sans-serif, system-ui;
            background: var(--eni-charcoal);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        /* Background Pattern */
        .background-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 25% 25%, rgba(255, 205, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(11, 34, 65, 0.3) 0%, transparent 50%),
                linear-gradient(135deg, var(--eni-charcoal) 0%, #1a1d23 100%);
            z-index: 1;
        }
        
        /* Register Container */
        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
            margin: auto;
            display: flex;
            flex-direction: column;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        @media (max-height: 800px) {
            .register-card {
                padding: 20px;
                max-height: 85vh;
            }
            
            .eni-logo {
                margin-bottom: 20px !important;
            }
            
            .form-group {
                margin-bottom: 15px !important;
            }
        }
        
        /* ENI Logo */
        .eni-logo {
            width: 120px;
            height: 60px;
            margin: 0 auto 30px;
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
        
        .register-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #ffffff;
        }
        
        .register-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 32px;
            font-size: 16px;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--eni-yellow);
            box-shadow: 0 0 0 3px rgba(255, 205, 0, 0.1);
            background: rgba(255, 255, 255, 0.12);
        }
        
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        /* Register Button */
        .register-button {
            width: 100%;
            padding: 14px;
            background: var(--eni-yellow);
            color: var(--eni-dark-blue);
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .register-button:hover {
            background: #f4c430;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 205, 0, 0.3);
        }
        
        .register-button:active {
            transform: translateY(0);
        }
        
        /* Links */
        .login-link {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .login-link a {
            color: var(--eni-yellow);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        /* Error Messages */
        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .register-card {
                padding: 30px 20px;
            }
            
            .register-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    
    <div class="register-container">
        <div class="register-card">
            <!-- ENI Logo -->
            <div class="eni-logo">
                <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" />
            </div>
            
            <!-- Title -->
            <h1 class="register-title">Join ENI</h1>
            <p class="register-subtitle">Create your investment account</p>
            
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                        id="name" 
                        class="form-input" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Enter your full name"
                    />
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username"
                        placeholder="Enter your email"
                    />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        id="username" 
                        class="form-input" 
                        type="text" 
                        name="username" 
                        value="{{ old('username') }}" 
                        required 
                        placeholder="Choose a unique username"
                        pattern="[a-zA-Z0-9_]+"
                        title="Username can only contain letters, numbers, and underscores"
                    />
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div style="color: #888; font-size: 12px; margin-top: 3px;">
                        Your username will be used for referral links (e.g., /register?ref=yourusername)
                    </div>
                </div>
                
                <!-- Phone -->
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input 
                        id="phone" 
                        class="form-input" 
                        type="tel" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="Enter your phone number"
                    />
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        class="form-input" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Create a strong password"
                    />
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        id="password_confirmation" 
                        class="form-input" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    />
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Referral Code (Optional) -->
                <div class="form-group">
                    <label for="referral_code" class="form-label">Referral Code (Optional)</label>
                    <input 
                        id="referral_code" 
                        class="form-input" 
                        type="text" 
                        name="referral_code" 
                        value="{{ old('referral_code') ?? $referralCode ?? '' }}" 
                        placeholder="Enter referral code if you have one"
                    />
                    @error('referral_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @if(isset($referralCode) && $referralCode)
                        <div id="referral-success-message" style="color: var(--eni-yellow); font-size: 14px; margin-top: 5px;">
                            @if(isset($referrerUser) && $referrerUser)
                                ✓ You were referred by {{ $referrerUser->username ?: $referrerUser->name }}! You'll both earn bonuses.
                            @else
                                ✓ You were referred by a friend! You'll both earn bonuses.
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Register Button -->
                <button type="submit" class="register-button">
                    Create Investment Account
                </button>
                
                <!-- Login Link -->
                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('login') }}">Sign in here</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Debug referral code functionality
        document.addEventListener('DOMContentLoaded', function() {
            const referralField = document.getElementById('referral_code');
            const urlParams = new URLSearchParams(window.location.search);
            const refParam = urlParams.get('ref');
            
            console.log('=== Enhanced Referral Code Debug ===');
            console.log('Current URL:', window.location.href);
            console.log('Query string:', window.location.search);
            console.log('All URL parameters:', Object.fromEntries(urlParams));
            console.log('URL ref parameter:', refParam);
            console.log('Referral field value:', referralField ? referralField.value : 'FIELD NOT FOUND');
            console.log('PHP referralCode variable:', @json($referralCode ?? null));
            
            // Check if field exists
            if (!referralField) {
                console.error('❌ Referral code field not found!');
                return;
            }
            
            // Show current state
            if (refParam) {
                console.log('✅ Found ref parameter in URL:', refParam);
                
                // Always populate the field with URL parameter
                referralField.value = refParam;
                console.log('✅ Set field value to:', refParam);
                
                // Show the success message (only if not already shown by PHP)
                const existingMsg = document.getElementById('referral-success-message') || 
                                  referralField.parentNode.querySelector('.referral-success-msg');
                if (!existingMsg) {
                    const successMsg = document.createElement('div');
                    successMsg.className = 'referral-success-msg';
                    successMsg.style.color = '#FFCD00';
                    successMsg.style.fontSize = '14px';
                    successMsg.style.marginTop = '5px';
                    successMsg.textContent = '✓ You were referred by a friend! You\'ll both earn bonuses.';
                    referralField.parentNode.appendChild(successMsg);
                    console.log('✅ Added referral success message');
                } else {
                    console.log('ℹ️ Referral message already exists (from PHP)');
                }
            } else {
                console.log('❌ No ref parameter found in URL');
                console.log('Expected URL format: ' + window.location.origin + '/register?ref=YOUR_CODE');
            }
            
            // Debug helper: show expected URL
            console.log('=== Troubleshooting ===');
            console.log('If you\'re not seeing a referral code, make sure your URL looks like:');
            console.log(window.location.origin + '/register?ref=KJOS0AJ3');
        });
    </script>
</body>
</html>
