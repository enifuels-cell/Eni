<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .welcome-text {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .highlight {
            color: #667eea;
            font-weight: bold;
        }
        .features {
            background: #f8f9ff;
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }
        .features h3 {
            color: #667eea;
            margin-top: 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .cta {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .footer {
            background: #f1f1f1;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to {{ config('app.name') }}!</h1>
        </div>
        
        <div class="content">
            <p class="welcome-text">
                Hello <span class="highlight">{{ $user->name }}</span>,
            </p>
            
            <p>
                Welcome to our investment platform! We're thrilled to have you join our community of smart investors.
            </p>
            
            <div class="features">
                <h3>🚀 What you can do with your account:</h3>
                <ul class="feature-list">
                    <li>💼 Browse and invest in premium investment packages</li>
                    <li>📊 Track your investments and returns in real-time</li>
                    <li>💰 Earn daily interest on your investments</li>
                    <li>🎯 Refer friends and earn bonus rewards</li>
                    <li>📈 Access detailed investment analytics</li>
                </ul>
            </div>
            
            <p>
                Your account has been successfully created with the email: <strong>{{ $user->email }}</strong>
            </p>
            
            <div class="cta">
                <a href="{{ config('app.url') }}/login" class="btn">Get Started Now</a>
            </div>
            
            <p>
                If you have any questions or need assistance, our support team is here to help. Simply reply to this email or contact us through our platform.
            </p>
            
            <p>
                Happy investing!<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>
                This email was sent to {{ $user->email }} because you registered for an account on {{ config('app.name') }}.
            </p>
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
