<!DOCTYPE html>
<html>
<head>
    <title>Coach Email Verified</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            background-color: #f04e3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
        }
        .details {
            background-color: #f5f5f5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .details table td:first-child {
            font-weight: bold;
            width: 120px;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Coach Email Verified</h2>
        </div>
        
        <p>Hello {{ $club->name }},</p>
        
        <p>Your coach <strong>{{ $coach->name }}</strong> has successfully verified their email address.</p>
        
        <div class="details">
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
                    <td>Verified On:</td>
                    <td>{{ $coach->email_verified_at->format('F j, Y, g:i a') }}</td>
                </tr>
            </table>
        </div>
        
        <p>The coach will now receive instructions to set up their password and access their dashboard.</p>
        
        <p>You can view full details by logging into your club dashboard.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
