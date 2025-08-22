<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Borrow Request Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            font-size: 22px;
        }
        .details {
            margin: 20px 0;
        }
        .details li {
            margin-bottom: 6px;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: white !important;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Borrow Request Approved ✅</h1>

        <p>Hello <strong>{{ $borrow->user->fullname }}</strong>,</p>

        <p>Your request to borrow the book <strong>"{{ $borrow->book->book_title }}"</strong> has been <strong>approved</strong>.</p>

        <ul class="details">
            <li><strong>Start Date:</strong> {{ $borrow->approved_at->format('M d, Y h:i A') }}</li>
            <li><strong>Due Date:</strong> {{ $borrow->due_date->format('M d, Y h:i A') }}</li>
            <li><strong>Duration:</strong>
                @if($borrow->user->role === 'Student')
                    1 week
                @elseif($borrow->user->role === 'Faculty')
                    1 month
                @endif
            </li>
        </ul>

        <p>Please make sure to return the book on or before the due date.</p>

        <div class="footer">
            <p>Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
