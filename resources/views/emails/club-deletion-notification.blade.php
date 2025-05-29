<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Deletion Notification</title>
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
        <h2>Club Deletion Alert</h2>
    </div>
    <div class="content">
        <p>Hello {{ $club->name }},</p>

        <p>We regret to inform you that your club has been temporarily removed from the ProGymHub platform.</p>

        <p><strong>Details:</strong></p>
        <ul>
            <li>Club Name: {{ $club->name }}</li>
            <li>Deletion Time: {{ now()->format('Y-m-d H:i:s') }}</li>
            <li>Admin: {{ $admin->name }}</li>
        </ul>

        <p>If you believe this action was taken in error or would like to discuss restoring your club account, please contact our support team immediately.</p>

        <p>Best regards,<br>The ProGymHub Team</p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>

</html>