<!DOCTYPE html>
<html>

<head>
    <title>Reset Your Password</title>
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

        .button {
            display: inline-block;
            background-color: #f04e3c;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
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
            <h2>Reset Your Password</h2>
        </div>

        <p>Hello {{ $coach->name }},</p>

        <p>You are receiving this email because we received a password reset request for your account.</p>

        <p>Click the button below to reset your password:</p>

        <p>
            <a href="{{ route('coach.password.reset', ['token' => $token]) }}" class="button">Reset Password</a>
        </p>

        <p>This password reset link will expire in 60 minutes.</p>

        <p>If you did not request a password reset, no further action is required.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>