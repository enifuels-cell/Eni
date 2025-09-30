<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - ENI Platform</title>
    <style>
        body {
            background: #1a1a1a;
            color: #fff;
            font-family: Inter, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .container {
            max-width: 500px;
            padding: 2rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #FFCD00;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }
        .back-link {
            display: inline-block;
            background: #FFCD00;
            color: #1a1a1a;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .back-link:hover {
            background: #ffd700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message">The page you're looking for doesn't exist.</div>
        <a href="{{ url('/') }}" class="back-link">Go Back Home</a>
    </div>
</body>
</html>
