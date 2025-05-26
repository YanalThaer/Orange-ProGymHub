<!DOCTYPE html>
<html>
<head>
    <title>Club Member Action Notification</title>
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
            background-color: #4682B4;
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
            background-color: #4682B4;
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
        <h2>ProGymHub Club Notification</h2>
    </div>
    
    <div class="content">
        <p>Dear Club Admin,</p>
        
        @if($action == 'deleted')
        <p>This is to inform you that a member of your club has been <strong>deleted</strong> by a system administrator:</p>
        @else
        <p>This is to inform you that a member of your club has been <strong>restored</strong> by a system administrator:</p>
        @endif
        
        <p><strong>Member Details:</strong></p>
        <ul>
            <li>Name: {{ $user->name }}</li>
            <li>Email: {{ $user->email }}</li>
            <li>Member Since: {{ $user->created_at->format('F j, Y') }}</li>
        </ul>
        
        <p>If you have any questions about this action, please contact ProGymHub system administration.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>
</html>
