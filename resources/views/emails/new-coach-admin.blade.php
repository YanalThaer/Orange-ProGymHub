<!DOCTYPE html>
<html>

<head>
    <title>New Coach Registration</title>
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
            <h2>New Coach Registration</h2>
        </div>

        <p>Hello {{ $admin->name }},</p>

        <p>A new coach has been registered at <strong>{{ $club->name }}</strong>. Email verification is pending.</p>

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
                    <td>Club:</td>
                    <td>{{ $club->name }}</td>
                </tr>
                <tr>
                    <td>Registered On:</td>
                    <td>{{ $coach->created_at->format('F j, Y, g:i a') }}</td>
                </tr>
            </table>
        </div>

        <p>You will be notified when the coach verifies their email.</p>

        <p>You can view full details by logging into your admin dashboard.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>