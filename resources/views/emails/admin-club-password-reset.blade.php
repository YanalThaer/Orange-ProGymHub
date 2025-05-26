<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Club Password Reset Notification - ProGymHub</title>
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
            <h2>Club Password Reset Notification</h2>
            <p>Dear {{ $admin->name }},</p>
            <p>This is to inform you that the club <strong>{{ $club->name }}</strong> has reset their password.</p>
            <p>Details:</p>
            <ul>
                <li>Club Name: {{ $club->name }}</li>
                <li>Club Email: {{ $club->email }}</li>
                <li>Reset Time: {{ now()->format('Y-m-d H:i:s') }}</li>
            </ul>
            <p>No action is required from your side. This is just a notification for your records.</p>
            <div style="text-align: center;">
                <a href="{{ url('/dashboard/admin') }}" class="button">Go to Admin Dashboard</a>
            </div>
            <p>Thank you,<br>The ProGymHub Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
