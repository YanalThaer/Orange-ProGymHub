<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset Successful - ProGymHub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4e73df;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f8f9fc;
        }
        .button {
            display: inline-block;
            background-color: #4e73df;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ProGymHub</h1>
        </div>
        <div class="content">
            <h2>Password Reset Successful!</h2>
            <p>Dear {{ $club->name }},</p>
            <p>Your password has been successfully reset. You can now log in to your account with your new password.</p>
            <p>If you did not reset your password, please contact our support team immediately.</p>
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Go to Login</a>
            </div>
            <p>Thank you,<br>The ProGymHub Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
            <p>If you're having trouble clicking the "Go to Login" button, copy and paste the URL below into your web browser:</p>
            <p style="word-break: break-all;">{{ url('/login') }}</p>
        </div>
    </div>
</body>
</html>
