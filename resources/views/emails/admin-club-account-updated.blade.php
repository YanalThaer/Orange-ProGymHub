<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Club Account Updated - ProGymHub</title>
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

        .club-info {
            background-color: #ffffff;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #4e73df;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>ProGymHub</h1>
        </div>
        <div class="content">
            <h2>Club Account Updated</h2>
            <p>Dear {{ $admin->name }},</p>
            <p>This is to inform you that the following club account information has been updated:</p>

            <div class="club-info">
                <p><strong>Club Name:</strong> {{ $club->name }}</p>
                <p><strong>Club Email:</strong> {{ $club->email }}</p>
                <p><strong>Club Phone:</strong> {{ $club->phone }}</p>
                <p><strong>Update Time:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>

            <p>You are receiving this notification as the assigned administrator for this club.</p>

            <div style="text-align: center;">
                <a href="{{ url('/admin/clubs/' . $club->getEncodedId()) }}" class="button">View Club Details</a>
            </div>

            <p>Thank you,<br>The ProGymHub Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>