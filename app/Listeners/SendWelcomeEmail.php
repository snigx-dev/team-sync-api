<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail implements ShouldQueue
{

    public $tries = 3;
    public $queue = 'notifications';


    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        // Mail::to($event->user->email)->send(new WelcomeEmail($event->user));

        \Log::info('Sending welcome email to: ' . $event->user->email);
    }
}
