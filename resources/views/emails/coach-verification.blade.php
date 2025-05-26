<!DOCTYPE html>
<html>
<head>
    <title>Verify your email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #f04e3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
        }
        .verification-code {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>ProGymHub Coach Email Verification</h2>
        </div>
        
        <p>Hello {{ $coach->name }},</p>
        
        <p>Welcome to ProGymHub! You've been registered as a coach for <strong>{{ $club->name }}</strong>.</p>
        
        <p>Please verify your email address by entering the following verification code:</p>
        
        <div class="verification-code">{{ $verification_code }}</div>
        
        <p>This code will expire in 30 minutes.</p>
        
        <p>Once verified, you'll receive instructions to set up your password and access your coach dashboard.</p>
        
        <p>If you did not request this verification, please ignore this email.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
