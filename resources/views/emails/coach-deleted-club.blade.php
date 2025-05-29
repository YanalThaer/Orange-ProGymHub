<!DOCTYPE html>
<html>

<head>
    <title>Coach Deletion Notification - Club</title>
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
            <h1>Coach Deletion Confirmation</h1>
        </div>

        <div class="content">
            <p>Dear {{ $club->name }},</p>

            <p>This email confirms that you have successfully deleted the following coach from your club in ProGymHub:</p>

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
                        <td>Deleted On:</td>
                        <td>{{ now()->format('F j, Y, g:i a') }}</td>
                    </tr>
                </table>
            </div>

            <p>The coach has been notified about this action. If you deleted this coach by mistake, you can restore them from the "Deleted Coaches" section in your dashboard.</p>

            <p>Best regards,<br>The ProGymHub Team</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>