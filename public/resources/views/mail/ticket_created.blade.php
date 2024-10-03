@if (!function_exists('insertLineBreaks'))
@php
    function insertLineBreaks($text)
    {
        // Replace newlines with <br> except within HTML tags
        $lines = preg_split('/(<[^>]*>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $formattedText = '';
        $withinTag = false;

        foreach ($lines as $line) {
            if (strpos($line, '<') === 0) {
                // Entering an HTML tag
                $withinTag = true;
                $formattedText .= $line;
            } elseif (strpos($line, '>') === 0) {
                // Exiting an HTML tag
                $withinTag = false;
                $formattedText .= $line;
            } else {
                // Inside text content
                $formattedText .= $withinTag ? $line : nl2br($line);
            }
        }

        return $formattedText;
    }
@endphp
@endif


<p>Subject: New Ticket Confirmation - {{ $ticket->number ?? $ticket['number'] ?? ''}} {{ $ticket->subject ??  $ticket['subject'] ?? ''}}</p>

<p>Dear Customer,</p>

<p>Thank you for reaching out. We've received your new ticket (#{{ $ticket->number ??  $ticket['number'] ??  '' }}) and are actively working on it.</p>
<p> Our team will investigate the matter and provide a prompt resolution.</p>
<p>If you have any additional information to share or questions,</p>
<p> please let us know. We appreciate your patience.</p>
<p>Best regards,</p>
<p>Monmatics Team</p>


    {{-- {!! insertLineBreaks($ticket['body']) !!} --}}
