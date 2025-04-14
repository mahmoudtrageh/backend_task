<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Request Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
        .leave-details {
            margin-bottom: 20px;
        }
        .leave-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .leave-details table td, .leave-details table th {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .leave-details table th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .rejected-banner {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .rejection-reason {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            border-radius: 0 5px 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Leave Request Rejected</h2>
        </div>
        
        <div class="rejected-banner">
            Your leave request has been rejected
        </div>
        
        <p>Dear {{ $leaveRequest->user->name }},</p>
        
        <p>Your leave request has been rejected by your {{ $rejectedBy }}. Here are the details:</p>
        
        <div class="leave-details">
            <table>
                <tr>
                    <th>Leave Type</th>
                    <td>{{ $leaveRequest->leaveType->name }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $leaveRequest->department->name }}</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>{{ $leaveRequest->start_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>End Date</th>
                    <td>{{ $leaveRequest->end_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Total Days</th>
                    <td>{{ $leaveRequest->total_days }}</td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td>{{ $leaveRequest->reason }}</td>
                </tr>
            </table>
        </div>
        
        <div class="rejection-reason">
            <h3>Reason for Rejection:</h3>
            <p>{{ $rejectedBy == 'manager' ? $leaveRequest->manager_comment : $leaveRequest->hr_comment }}</p>
        </div>
        
        <p>If you have questions about this decision, please speak directly with your manager or the HR department.</p>
        
        <p>You can log in to the leave management system to submit a new leave request if needed.</p>
        
        <p>Thank you,<br>
        HR Team</p>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>