<?php

namespace App\Console\Commands;

use Spatie\Backup\Commands\BackupCommand as BaseBackupCommand;

class BackupCommand extends BaseBackupCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom backup command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
}
