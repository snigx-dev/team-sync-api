<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
class GenerateTaskPdf implements ShouldQueue
{
    public $queue = 'pdf_tasks';
    public $tries = 3;

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        \Log::info('Generating PDF for task: ' . $event->task->title);
        sleep(10);
    }
}
