<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Subscription Plan Added - ProGymHub</title>
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
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fc;
        }

        .button {
            display: inline-block;
            background-color: #4e73df;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }

        .plan-details {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
        }

        .plan-name {
            font-size: 18px;
            font-weight: bold;
            color: #4e73df;
        }

        .plan-price {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>ProGymHub</h1>
        </div>

        <div class="content">
            <h2>New Subscription Plan Added</h2>

            <p>Hello {{ $admin->name ?? 'Admin' }},</p>

            <p>A club in your system has added a new subscription plan.</p>

            <div class="plan-details">
                <p><strong>Club:</strong> {{ $club->name }}</p>
                <p class="plan-name">{{ $plan->name }}</p>
                <p><strong>Type:</strong> {{ ucfirst($plan->type) }}</p>
                <p><strong>Duration:</strong> {{ $plan->duration_days }} days</p>
                <p class="plan-price">Price: {{ number_format($plan->price, 2) }} JOD</p>
                @if($plan->description)
                <p><strong>Description:</strong> {{ $plan->description }}</p>
                @endif
                <p><strong>Status:</strong> {{ $plan->is_active ? 'Active' : 'Inactive' }}</p>
            </div>

            <a href="{{ route('clubs.show', $club->encoded_id) }}" class="button">View Club</a>
        </div>

        <div class="footer">
            <p>This is an automated message from ProGymHub. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
        </div>
    </div>
</body>

</html>