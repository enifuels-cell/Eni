<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENI Investment Platform — Login</title>
    <meta name="theme-color" content="#FFCD00" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        /* Login Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .login-card {
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
        
        .login-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #ffffff;
        }
        
        .login-subtitle {
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
        
        /* Checkbox */
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .checkbox-input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }
        
        .checkbox-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }
        
        /* Login Button */
        .login-button {
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
        
        .login-button:hover {
            background: #f4c430;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 205, 0, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        /* Links */
        .forgot-password {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .forgot-password a {
            color: var(--eni-yellow);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .register-link {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        
        .register-link a {
            color: var(--eni-yellow);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Error Messages */
        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 5px;
        }
        
        /* Status Messages */
        .status-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- ENI Logo -->
            <div class="eni-logo">
                <img src="{{ asset('images/eni-logo.svg') }}" alt="ENI Logo" />
            </div>
            
            <!-- Title -->
            <h1 class="login-title">Welcome Back</h1>
            <p class="login-subtitle">Sign in to your investment account</p>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Suspension Error -->
            @error('suspended')
                <div class="error-message" style="margin-bottom: 20px; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 8px; color: #ef4444; text-align: center;">
                    {{ $message }}
                </div>
            @enderror
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email or Username -->
                <div class="form-group">
                    <label for="email" class="form-label">Email or Username</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="text" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="Enter your email or username"
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
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me -->
                <div class="checkbox-container">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="checkbox-input" 
                        name="remember"
                    >
                    <label for="remember_me" class="checkbox-label">Remember me for 30 days</label>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="login-button">
                    Sign In to Dashboard
                </button>
                
                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    </div>
                @endif
                
                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="register-link">
                        Don't have an account?
                        <a href="{{ route('register') }}">Create one now</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>
</html>
