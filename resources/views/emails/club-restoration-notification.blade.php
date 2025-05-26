<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Restoration Notification</title>
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
            background-color: #4CAF50;
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
        <h2>Club Restoration Confirmation</h2>
    </div>
    <div class="content">
        <p>Hello {{ $club->name }},</p>
        
        <p>We are pleased to inform you that your club has been successfully restored on the ProGymHub platform.</p>
        
        <p><strong>Details:</strong></p>
        <ul>
            <li>Club Name: {{ $club->name }}</li>
            <li>Restoration Time: {{ now()->format('Y-m-d H:i:s') }}</li>
            <li>Admin: {{ $admin->name }}</li>
        </ul>
        
        <p>Your club profile and all associated data are now available again. You can log in to your account and resume your operations as normal.</p>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Welcome back to ProGymHub!</p>
        
        <p>Best regards,<br>The ProGymHub Team</p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>
</html>
