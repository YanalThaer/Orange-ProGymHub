<!DOCTYPE html>
<html>

<head>
    <title>Contact Form Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .message-container {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-top: 20px;
        }

        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>New Contact Form Message</h2>
    </div>

    <p><strong>From:</strong> {{ $name }} ({{ $email }})</p>
    <p><strong>Subject:</strong> {{ $subject }}</p>

    <div class="message-container">
        <h3>Message:</h3>
        <p>{{ $messageContent }}</p>
    </div>

    <div class="footer">
        <p>This email was sent from the contact form on your ProGymHub website.</p>
    </div>
</body>

</html>