<!DOCTYPE html>
<html>
<head>
    <title>Coach Account Deletion Notification</title>
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
            border-bottom: 3px solid #007bff;
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
            color: #007bff;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Coach Account Deletion Notification</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $admin->name }},</p>
            
            <p>This is to notify you that a coach account has been deleted from the ProGymHub platform by {{ $club->name }}.</p>
            
            <p>Coach Account Information:</p>
            
            <div class="coach-details">
                <table>
                    <tr>
                        <td>Coach Name:</td>
                        <td>{{ $coach->name }}</td>
                    </tr>
                    <tr>
                        <td>Coach Email:</td>
                        <td>{{ $coach->email }}</td>
                    </tr>
                    <tr>
                        <td>Club Name:</td>
                        <td>{{ $club->name }}</td>
                    </tr>
                    <tr>
                        <td>Club Email:</td>
                        <td>{{ $club->email }}</td>
                    </tr>
                    <tr>
                        <td>Deleted On:</td>
                        <td>{{ now()->format('F j, Y, g:i a') }}</td>
                    </tr>
                </table>
            </div>
            
            <p>No action is required on your part. This is for your information only.</p>
            
            <p>Best regards,<br>The ProGymHub Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
