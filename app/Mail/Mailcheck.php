<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mailcheck extends Mailable
{
    use Queueable, SerializesModels;

    // public $ticket;

    public function __construct()
    {
        // $this->ticket = $ticket;
    }

    public function build()
    {
        return $this->view('mail.mail-check')
                    ->subject('Mail Test');
    }
}
