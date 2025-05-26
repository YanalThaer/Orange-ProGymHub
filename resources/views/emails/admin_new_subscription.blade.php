<!DOCTYPE html>
<html>
<head>
    <title>New User Subscription</title>
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
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
        }
        .subscription-details {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 0.8em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 8px;
        }
        .attribute {
            font-weight: bold;
            width: 40%;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #4e73df;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New User Subscription</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $adminName }},</p>
            
            <p>A new subscription has been created in the ProGymHub system.</p>
            
            <div class="subscription-details">
                <h3>Subscription Details</h3>
                <table>
                    <tr>
                        <td class="attribute">User:</td>
                        <td>{{ $userName }}</td>
                    </tr>
                    <tr>
                        <td class="attribute">Club:</td>
                        <td>{{ $clubName }}</td>
                    </tr>
                    <tr>
                        <td class="attribute">Plan:</td>
                        <td>{{ $planName }}</td>
                    </tr>
                    <tr>
                        <td class="attribute">Start Date:</td>
                        <td>{{ $startDate }}</td>
                    </tr>
                    <tr>
                        <td class="attribute">End Date:</td>
                        <td>{{ $endDate }}</td>
                    </tr>
                </table>
            </div>
            
            <p>You can view more details about this club and user from your admin dashboard.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>
