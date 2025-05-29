<!DOCTYPE html>
<html>

<head>
    <title>Your Profile Has Been Updated</title>
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
            border-bottom: 3px solid #17a2b8;
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
            color: #17a2b8;
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
            background-color: #17a2b8;
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
            <h1>Your Profile Has Been Updated</h1>
        </div>

        <div class="content">
            <p>Dear {{ $coach->name }},</p>

            <p>We wanted to inform you that your coach profile at ProGymHub has been updated by {{ $club->name }}.</p>

            <p>Coach Profile Information:</p>

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
                        <td>Updated On:</td>
                        <td>{{ now()->format('F j, Y, g:i a') }}</td>
                    </tr>
                </table>
            </div>

            <p>You can log in to your dashboard to review the updated information.</p>

            <p>If you believe these changes were made in error, please contact {{ $club->name }} at {{ $club->email }} or our support team.</p>

            <p>Best regards,<br>The ProGymHub Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>