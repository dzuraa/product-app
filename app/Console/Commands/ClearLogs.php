<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all log files in storage/logs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $logFiles = File::glob(storage_path('logs/*.log'));

        foreach ($logFiles as $file) {
            File::delete($file);
        }

        $this->info('Log files cleared!');
    }
}
