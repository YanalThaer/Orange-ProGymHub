<!DOCTYPE html>
<html>
<head>
    <title>Coach Account Restored</title>
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
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-bottom: 3px solid #28a745;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        h1 {
            color: #28a745;
        }
        .coach-details {
            background-color: #f8f9fa;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .coach-details table {
            width: 100%;
        }
        .coach-details table td {
            padding: 5px 10px;
        }
        .coach-details table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Coach Account Has Been Restored</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $coach->name }},</p>
            
            <p>Good news! Your coach account at ProGymHub has been restored by {{ $club->name }}. Your account is now active again and you can access all your previous data.</p>
            
            <p>Coach Account Information:</p>
            
            <div class="coach-details">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $coach->name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $coach->email }}</td>
                    </tr>
                    <tr>
                        <td>Club:</td>
                        <td>{{ $club->name }}</td>
                    </tr>
                    <tr>
                        <td>Restored On:</td>
                        <td>{{ now()->format('F j, Y, g:i a') }}</td>
                    </tr>
                </table>
            </div>
            
            <p>You can now log back into your account using your existing credentials.</p>
            
            <p>If you have any questions or concerns, please contact {{ $club->name }} at {{ $club->email }} or our support team.</p>
            
            <p>Welcome back to ProGymHub!</p>
            
            <p>Best regards,<br>The ProGymHub Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
