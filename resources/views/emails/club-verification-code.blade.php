<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f2f2f2;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            padding: 20px 0;
        }
        .verification-code {
            text-align: center;
            font-size: 32px;
            letter-spacing: 5px;
            font-weight: bold;
            margin: 30px 0;
            padding: 15px;
            background-color: #f7f7f7;
            border-radius: 8px;
            color: #333;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #f2f2f2;
            font-size: 14px;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4A77D4;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .expiry {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .info {
            background-color: #f8f9fa;
            border-left: 4px solid #4A77D4;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>ProGymHub Club Email Verification</h2>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>Thank you for registering with ProGymHub. Please use the following verification code to confirm your email address:</p>
            
            <div class="verification-code">{{ $verificationCode }}</div>
            
            <div class="expiry">This code will expire in 30 minutes.</div>
            
            <p>Enter this code on the verification page to complete your registration. If you did not request this verification, please ignore this email.</p>
            
            <div class="info">
                <p><strong>Why verify your email?</strong></p>
                <p>Email verification helps us ensure the security of your account and provides a way to recover your password if needed.</p>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} ProGymHub. All rights reserved.</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>
