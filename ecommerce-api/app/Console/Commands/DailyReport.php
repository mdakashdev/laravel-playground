<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Daily report generated!');

        return Command::SUCCESS;
    }
}
