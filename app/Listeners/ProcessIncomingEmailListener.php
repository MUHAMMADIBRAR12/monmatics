<?php

namespace App\Listeners;

use App\Events\NewEmailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\ProcessIncomingEmail;

class ProcessIncomingEmailListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewEmailEvent $event)
    {
        // Dispatch the ProcessIncomingEmail job
        ProcessIncomingEmail::dispatch($event->emailMessage);
    }
}
