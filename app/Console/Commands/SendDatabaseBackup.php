<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class SendDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database backup and send it via email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*$filename = 'backup_' . strtotime(now()) . '.sql';
        $command = 'mysqldump --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD') . ' --host=' . env('DB_HOST') . ' ' . env('DB_DATABASE') . ' > ' . storage_path() . '/app/backup/' . 'hhh';
        exec($command);*/
        // Create database backup
        $backup = BackupJobFactory::createFromArray(config('backup.backup'));
        $backup->run();

        // Send email with backup attachment
        Mail::raw('Database backup is attached.', function ($message) {
            $message->to('mekiermena60@gmail.com')->subject('Database Backup');
            $message->attach(storage_path('app/backup/' . date('Y-m-d') . '/backup.zip'));
        });

        $this->info('Database backup sent successfully.');
    }
}
