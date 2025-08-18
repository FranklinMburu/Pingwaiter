<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampusStore;

class SendDatabaseContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-database-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dd(now());
    }
}
