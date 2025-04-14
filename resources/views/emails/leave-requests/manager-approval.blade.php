<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Request For Approval</title>
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
            background-color: #f5f5f5;
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
        .buttons {
            text-align: center;
            margin: 25px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-approve {
            background-color: #28a745;
        }
        .btn-reject {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Leave Request Awaiting Your Approval</h2>
        </div>
        
        <p>Dear {{ $manager->name }},</p>
        
        <p>A leave request from {{ $leaveRequest->user->name }} requires your approval. Here are the details:</p>
        
        <div class="leave-details">
            <table>
                <tr>
                    <th>Employee</th>
                    <td>{{ $leaveRequest->user->name }}</td>
                </tr>
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
        
        {{-- <div class="buttons">
            <a href="{{ url('/leave-requests/'.$leaveRequest->id.'/approve') }}" class="btn btn-approve">Approve</a>
            <a href="{{ url('/leave-requests/'.$leaveRequest->id.'/reject') }}" class="btn btn-reject">Reject</a>
        </div> --}}
        
        <p>Alternatively, you can log in to the leave management system to review this request.</p>
        
        <p>Thank you,<br>
        HR Team</p>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>