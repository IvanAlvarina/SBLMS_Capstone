<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Borrow Request Rejected</title>
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
            color: #dc3545;
            font-size: 22px;
        }
        .details {
            margin: 20px 0;
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
        <h1>Borrow Request Rejected ❌</h1>

        <p>Hello <strong>{{ $borrow->user->fullname }}</strong>,</p>

        <p>Unfortunately, your request to borrow the book <strong>"{{ $borrow->book->book_title }}"</strong> has been <strong>rejected</strong>.</p>

        <p>If you believe this was an error or want to inquire further, please contact the library staff.</p>

        <div class="footer">
            <p>Thanks,<br>St. Bridget College Library Staff</p>
        </div>
    </div>
</body>
</html>
