<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

class DailyDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-database-backup';
    protected $description = 'Send daily database backup to email';

    /**
     * The console command description.
     *
     * @var string
     */
    public function handle()
    {
        // Generate backup
        $destination = BackupDestination::create('local');
        $backupJob = BackupJobFactory::createFromArray(config('backup.backup'));

        $backupJob->run($destination);

        // Get the path to the latest backup
        $backupPath = $destination->newestBackup()->path();

        // Send email with backup attachment
        Mail::raw('Daily database backup', function ($message) use ($backupPath) {
            $message->to('mekiermena72@gmail.com')->subject('Daily Database Backup')->attach($backupPath);
        });

        $this->info('Backup sent successfully.');
    }
}
