<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupOldSoftDeletedRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cleanup-old-soft-deleted-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old soft deleted records';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Dispatching cleanup job for soft-deleted records...');

        \App\Jobs\CleanupOldSoftDeletedRecords::dispatch();

        $this->comment('Job successfully dispatched to the queue.');
    }
}
