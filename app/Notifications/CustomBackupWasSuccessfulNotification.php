<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Support\Facades\Storage;

class CustomBackupWasSuccessfulNotification extends Notification
{
    protected $event;

    /**
     * Create a new notification instance.
     *
     * @param  \Spatie\Backup\Events\BackupWasSuccessful  $event
     * @return void
     */
    public function __construct(BackupWasSuccessful $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $backupPath = $this->event->backupDestination->newestBackup()->path();
        $backupDisk = $this->event->backupDestination->diskName();
        $fullBackupPath = Storage::disk($backupDisk)->path($backupPath);

        return (new MailMessage)
            ->subject('Database Backup Successful')
            ->line('Great news, a new backup of Student clinic management system database was successfully created.')
            ->line('Attached is the latest database backup.')
            ->attach($fullBackupPath);
    }
}

/*
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomBackupWasSuccessfulNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     *
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     *
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}*/
