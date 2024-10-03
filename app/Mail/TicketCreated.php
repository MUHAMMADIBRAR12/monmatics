<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $record;

    public function __construct($ticket,$record = null)
    {
        $this->ticket = $ticket;
        $this->record = $record;

    }

    public function build()
    {
        return $this->view('mail.ticket_created', ['ticket' => $this->ticket , 'record' => $this->record])
                    ->subject(($this->ticket->subject ?? $this->ticket['subject'] ?? $this->record->subject) . ' Ticket Number is ' . ($this->ticket->number ?? $this->ticket['number'] ?? $this->record->number));
    }


}
