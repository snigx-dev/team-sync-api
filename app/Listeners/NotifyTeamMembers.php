<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTeamMembers implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        $team = $event->task->team;
        foreach ($team->members as $user) {
            // Mail::to($user->email)->send(new TaskCreatedNotification($event->task));
            \Log::info('Notifying user: ' . $user->email);
        }
    }
}
