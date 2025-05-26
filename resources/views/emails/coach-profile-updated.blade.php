<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Profile Updated</title>
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
            background-color: #4a6cf7;
            padding: 20px;
            color: white;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .changed {
            background-color: #fff3cd;
        }
        .coach-info {
            margin-bottom: 25px;
        }
        .coach-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Coach Profile Updated</h1>
    </div>
    <div class="content">
        <p>{{ $club ? 'Dear ' . $club->name . ' Administrator,' : 'Dear Admin,' }}</p>
        
        <p>The coach <strong>{{ $coach->name }}</strong> has updated their profile information.</p>

        <div class="coach-info">
            <h2>Coach Information</h2>
            <p><strong>Name:</strong> {{ $coach->name }}</p>
            <p><strong>Email:</strong> {{ $coach->email }}</p>
            <p><strong>Phone:</strong> {{ $coach->phone ?? 'Not provided' }}</p>
            @if($club)
                <p><strong>Club:</strong> {{ $club->name }}</p>
            @else
                <p><strong>Club:</strong> Not assigned</p>
            @endif
        </div>

        <h2>Updated Information</h2>
        <p>The following information has been updated:</p>

        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($changedFields as $field)
                    <tr class="changed">
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                        <td>
                            @if($field == 'certifications' || $field == 'specializations' || $field == 'working_hours')
                                {{ is_array($oldData[$field]) ? json_encode($oldData[$field]) : $oldData[$field] }}
                            @elseif($field == 'password')
                                [Hidden for security]
                            @elseif($field == 'profile_image')
                                [Image updated]
                            @else
                                {{ $oldData[$field] ?? 'Not set' }}
                            @endif
                        </td>
                        <td>
                            @if($field == 'certifications' || $field == 'specializations' || $field == 'working_hours')
                                {{ is_array($coach->$field) ? json_encode($coach->$field) : $coach->$field }}
                            @elseif($field == 'password')
                                [Hidden for security]
                            @elseif($field == 'profile_image')
                                [New image uploaded]
                            @else
                                {{ $coach->$field ?? 'Not set' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>You can view the complete profile by logging into the ProGymHub platform.</p>
        
        <p>Thank you for using ProGymHub!</p>
    </div>
    <div class="footer">
        <p>This is an automated message from ProGymHub. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} ProGymHub. All rights reserved.</p>
    </div>
</body>
</html>