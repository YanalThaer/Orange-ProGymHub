<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #ff0000;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            border: 1px solid #dddddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Admin Login Alert</h2>
    </div>
    <div class="content">
        <p>Hello {{ $club->name }},</p>

        <p>We are writing to inform you that an admin has logged into the ProGymHub system.</p>

        <p><strong>Admin Details:</strong></p>
        <ul>
            <li>Name: {{ $admin->name }}</li>
            <li>Login Time: {{ now()->format('Y-m-d H:i:s') }}</li>
        </ul>

        <p>This is a routine notification to keep you informed about system access. If you have any concerns or questions about your club subscription status, please contact us.</p>

        <p>Thank you for being a valued member of ProGymHub.</p>

        <p>Best regards,<br>The ProGymHub Team</p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>

</html>