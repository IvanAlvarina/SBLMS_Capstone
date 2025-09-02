<p>Dear {{ $borrow->user->fullname }},</p>

<p>Unfortunately, the book <strong>"{{ $bookTitle }}"</strong> you requested 
is currently borrowed by another user.</p>

<p>Expected return date: <strong>{{ \Carbon\Carbon::parse($dueDate)->timezone('Asia/Manila')->format('M d, Y h:i A') }}</strong></p>

<p>Please try again after the return date. Thank you for your understanding.</p>

<p>Library Team</p>
<p>{{ config('app.name') }}</p>