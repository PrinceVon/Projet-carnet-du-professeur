<?php

namespace App\Console\Commands;

use App\Jobs\UpdateColorsJob;
use Illuminate\Console\Command;

class UpdateColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update colors for events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        UpdateColorsJob::dispatch();
        $this->info('Colors updated.');
    }
}
