<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ENI Investment Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #FFCD00 0%, #0B2241 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-title {
            color: #0B2241;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 25px;
            color: #333;
        }
        
        .intro-text {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
            color: #555;
        }
        
        .benefits {
            background-color: #f8f9fa;
            border-left: 4px solid #FFCD00;
            padding: 25px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .benefits h3 {
            color: #0B2241;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .benefits ul {
            list-style: none;
            padding: 0;
        }
        
        .benefits li {
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
            color: #555;
        }
        
        .benefits li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #FFCD00;
            font-weight: bold;
            font-size: 16px;
        }
        
        .referral-bonus {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4f4dd 100%);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        
        .referral-bonus h3 {
            color: #155724;
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .referral-bonus p {
            color: #155724;
            font-size: 16px;
            margin-bottom: 0;
        }
        
        .cta-section {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #FFCD00 0%, #e6b800 100%);
            color: #0B2241;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(255, 205, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 205, 0, 0.4);
        }
        
        .next-steps {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .next-steps h3 {
            color: #0B2241;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .next-steps ol {
            color: #555;
            padding-left: 20px;
        }
        
        .next-steps li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .support-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .support-section h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .support-section p {
            color: #856404;
            margin-bottom: 0;
        }
        
        .footer {
            background-color: #0B2241;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .footer .company-info {
            font-size: 14px;
            opacity: 0.7;
            margin-top: 20px;
            line-height: 1.6;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: #FFCD00;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
            
            .cta-button {
                padding: 12px 25px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">ENI</div>
            <div class="header-subtitle">Investment Platform</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <h1 class="welcome-title">Welcome to ENI!</h1>
            
            <div class="greeting">
                <strong>Dear {{ $user->name }},</strong>
            </div>
            
            <div class="intro-text">
                Congratulations and welcome to the ENI Investment Platform! We're thrilled to have you join our community of successful investors who are building their financial future with us.
            </div>
            
            @if($wasReferred && $referrer)
            <div class="referral-bonus">
                <h3>🎉 Special Welcome Bonus!</h3>
                <p>You were referred by <strong>{{ $referrer->name }}</strong>! Both you and your referrer will earn bonus commissions when you make your first investment.</p>
            </div>
            @endif
            
            <div class="benefits">
                <h3>What You Can Expect:</h3>
                <ul>
                    <li><strong>Daily Interest Earnings</strong> - Watch your investments grow every single day</li>
                    <li><strong>Multiple Investment Packages</strong> - Choose from Energy Saver (0.5%), Growth Power (0.7%), or Capital Prime (0.9%)</li>
                    <li><strong>Secure Platform</strong> - Your investments are protected with bank-level security</li>
                    <li><strong>Referral Rewards</strong> - Earn 5%-15% commission by referring friends</li>
                    <li><strong>24/7 Support</strong> - Our team is here to help you succeed</li>
                    <li><strong>Transparent Tracking</strong> - Monitor your portfolio and earnings in real-time</li>
                </ul>
            </div>
            
            <div class="cta-section">
                <a href="{{ config('app.url') }}/login" class="cta-button">Access Your Dashboard</a>
            </div>
            
            <div class="next-steps">
                <h3>Your Next Steps:</h3>
                <ol>
                    <li><strong>Complete Your Profile</strong> - Add any missing information to secure your account</li>
                    <li><strong>Explore Investment Packages</strong> - Review our different investment options</li>
                    <li><strong>Make Your First Investment</strong> - Start with any amount that fits your budget</li>
                    <li><strong>Share & Earn</strong> - Use your referral link to earn bonus commissions</li>
                    <li><strong>Track Your Progress</strong> - Watch your daily earnings accumulate</li>
                </ol>
            </div>
            
            <div class="support-section">
                <h3>Need Help Getting Started?</h3>
                <p>Our support team is ready to assist you! Contact us anytime if you have questions about investments, withdrawals, or using the platform. We're committed to your success.</p>
            </div>
            
            <div class="intro-text">
                Thank you for choosing ENI Investment Platform. We look forward to helping you achieve your financial goals!
            </div>
            
            <div style="margin-top: 30px;">
                <strong>Best regards,</strong><br>
                <strong>The ENI Investment Team</strong><br>
                <em>Your Partners in Financial Growth</em>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>ENI Investment Platform</strong></p>
            <p>Building Your Financial Future, One Investment at a Time</p>
            
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a> |
                <a href="#">Support</a>
            </div>
            
            <div class="company-info">
                This email was sent to {{ $user->email }} because you registered for an ENI Investment Platform account.<br>
                Please add our email address to your contacts to ensure delivery of important updates.<br>
                <br>
                © {{ date('Y') }} ENI Investment Platform. All rights reserved.<br>
                <em>Invest responsibly. Past performance does not guarantee future results.</em>
            </div>
        </div>
    </div>
</body>
</html>
