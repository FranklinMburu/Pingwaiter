<?php

namespace App\Listeners;

use App\Notifications\CustomBackupWasSuccessfulNotification;
use Spatie\Backup\Events\BackupWasSuccessful;

class SendCustomBackupNotification
{
    /**
     * Handle the event.
     *
     * @param  \Spatie\Backup\Events\BackupWasSuccessful  $event
     * @return void
     */
    public function handle(BackupWasSuccessful $event)
    {
        $notifiable = app(\Spatie\Backup\Notifications\Notifiable::class);
        $notifiable->notify(new CustomBackupWasSuccessfulNotification($event));
    }
}
