<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password</title>
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
            background-color: #4e73df;
            color: #ffffff;
            border-radius: 8px 8px 0 0;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .content {
            padding: 30px 20px;
            background-color: #ffffff;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4e73df;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #2e59d9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .expiry {
            font-size: 14px;
            color: #e74c3c;
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            border-top: 2px solid #f2f2f2;
            font-size: 14px;
            color: #6c757d;
            background-color: #f8f9fc;
            border-radius: 0 0 8px 8px;
        }

        .info {
            background-color: #f8f9fa;
            border-left: 4px solid #4e73df;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }

        .club-name {
            font-weight: bold;
            color: #4e73df;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>ProGymHub</h1>
        </div>

        <div class="content">
            <h2>Congratulations on your new club registration!</h2>
            <p>Dear <span class="club-name">{{ $club->name }}</span>,</p>
            <p>Your club account has been successfully verified on ProGymHub. To get started, you'll need to set your password.</p>

            <div class="info">
                <p><strong>Next Steps:</strong></p>
                <p>After setting your password, you'll be able to log in to your club dashboard and start managing your club on ProGymHub. You can add trainers, programs, and track member activity all from one place.</p>
            </div>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">Set Your Password</a>
            </div>

            <div class="expiry">This link will expire in 60 minutes.</div>

            <p>If you didn't request this account, please ignore this email or contact our support team.</p>

            <p>Thank you,<br>The ProGymHub Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
            <p>If you're having trouble clicking the "Set Your Password" button, copy and paste the URL below into your web browser:</p>
            <p style="word-break: break-all;">{{ $resetUrl }}</p>
        </div>
    </div>
</body>

</html>