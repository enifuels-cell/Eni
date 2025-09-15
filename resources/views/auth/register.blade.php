<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENI Investment Platform — Register</title>
    <meta name="theme-color" content="#FFCD00" />
    
    
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
            overflow: hidden;
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
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
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
                        autocomplete="username"
                        placeholder="Choose a unique username"
                    />
                    @error('username')
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
                    <label for="referral_code" class="form-label">Referral Username (Optional)</label>
                    <input 
                        id="referral_code" 
                        class="form-input" 
                        type="text" 
                        name="referral_code" 
                        value="{{ old('referral_code') ?? $referralCode ?? '' }}" 
                        placeholder="Enter the username of who referred you"
                    />
                    @error('referral_code')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @if(isset($referralCode) && $referralCode)
                        <div style="color: var(--eni-yellow); font-size: 14px; margin-top: 5px;">
                            ✓ You were referred by a friend! You'll both earn bonuses.
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
</body>
</html>
