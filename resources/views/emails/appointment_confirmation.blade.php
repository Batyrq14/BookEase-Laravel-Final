<h1>Appointment Confirmation</h1>

<p>Your appointment has been successfully booked.</p>

<p><strong>Service:</strong> {{ $appointment->service->name }}</p>
<p><strong>Date:</strong> {{ $appointment->scheduled_at->format('F j, Y, g:i A') }}</p>
<p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>

@if($appointment->notes)
    <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
@endif

<p>Thank you for using BookEase.</p>