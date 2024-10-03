<?php


namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewEmailEvent
{
    use Dispatchable, SerializesModels;

    /**
     * The email message instance.
     *
     * @var \Webklex\PHPIMAP\Message
     */
    public $emailMessage;

    /**
     * Create a new event instance.
     *
     * @param \Webklex\PHPIMAP\Message $emailMessage
     */
    public function __construct($emailMessage)
    {
        $this->emailMessage = $emailMessage;
    }
}
