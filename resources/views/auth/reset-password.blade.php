<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ENI Investment Platform — Set New Password</title>
    <meta name="theme-color" content="#FFCD00" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* Container */
        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        /* Icon */
        .reset-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 205, 0, 0.1);
            border-radius: 50%;
            border: 2px solid rgba(255, 205, 0, 0.2);
            font-size: 40px;
        }

        .auth-title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .auth-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 28px;
            font-size: 14px;
            line-height: 1.5;
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

        /* Submit Button */
        .submit-button {
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
            margin-top: 8px;
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .submit-button:hover {
            background: #f4c430;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 205, 0, 0.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        /* Links */
        .back-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .back-link a {
            color: var(--eni-yellow);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Error Messages */
        .error-message {
            color: #ff6b6b;
            font-size: 13px;
            margin-top: 5px;
            padding: 10px 12px;
            background: rgba(255, 107, 107, 0.1);
            border-left: 3px solid #ff6b6b;
            border-radius: 4px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-card {
                padding: 30px 20px;
            }

            .auth-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="background-pattern"></div>

    <div class="auth-container">
        <div class="auth-card">
            <!-- Icon -->
            <div class="reset-icon">
                🔐
            </div>

            <!-- Title -->
            <h1 class="auth-title">Create New Password</h1>
            <p class="auth-subtitle">
                Enter your email and set a strong new password for your ENI account.
            </p>

            <!-- Reset Form -->
            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Enter your email address"
                    />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input
                        id="password"
                        class="form-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Enter a strong password"
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
                        placeholder="Confirm your new password"
                    />
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">
                    Reset Password
                </button>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="{{ route('login') }}">← Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
