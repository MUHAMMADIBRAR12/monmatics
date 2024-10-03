<p>Subject: {{ $ticket->subject ?? 'No Subject' }} - {{ $ticket->number ?? 'No Number'}}</p>

@php
    $user = DB::table('users')->where('id',$ticket->assign_to ?? '')->first();
@endphp
<p>Dear {{ $user->firstName }} {{ $user->lastName }}</p>

<p>I hope this message finds you well. I wanted to inform you that a new ticket has been assigned to you. Here are the details:</p>

<p>Ticket Number: #{{ $ticket->number ?? '' }}</p>
<p>Description: {{ $ticket->body ?? '' }}</p>
<p>Priority: {{ $ticket->priority ?? '' }}</p>

<p>Please take the necessary actions to address and resolve the ticket promptly. If you have any questions or need additional information, feel free to reach out.</p>

<p>Thank you for your attention to this matter.</p>

<p>Best regards,</p>

<p>Monmatics Team</p>
