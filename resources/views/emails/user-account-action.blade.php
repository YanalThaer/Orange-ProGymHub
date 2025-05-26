<!DOCTYPE html>
<html>
<head>
    <title>User Account Action</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #ff6347;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #ff6347;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 15px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>ProGymHub Account Notification</h2>
    </div>
    
    <div class="content">
        <p>Dear {{ $user->name }},</p>
        
        @if($action == 'deleted')
        <p>We regret to inform you that your ProGymHub account has been <strong>deleted</strong> by an administrator.</p>
        
        <p>If you believe this was done in error, please contact your club administrator or our support team.</p>
        @else
        <p>Great news! Your ProGymHub account has been <strong>restored</strong>.</p>
        
        <p>You can now log in and continue using all the features of ProGymHub.</p>
        @endif
        
        <p><strong>Account Details:</strong></p>
        <ul>
            <li>Name: {{ $user->name }}</li>
            <li>Email: {{ $user->email }}</li>
            <li>Club: {{ $user->club ? $user->club->name : 'Not Assigned' }}</li>
        </ul>
        
        <p>For any questions or concerns, please reach out to our support team.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>
</html>
