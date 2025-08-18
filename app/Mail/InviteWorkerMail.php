<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteWorkerMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $designation;

    public string $link;

    public string $restaurantName;

    public function __construct(string $link, string $designation, string $restaurantName)
    {
        $this->link = $link;
        $this->designation = $designation;
        $this->restaurantName = $restaurantName;

    }

    public function build()
    {
        return $this->subject('You have been invited to PingWaiter')
            ->markdown('emails.invite-worker')
            ->with([
                'link' => $this->link,
                'designation' => $this->designation,
                'restaurantName' => $this->restaurantName,
            ]);
    }
}
