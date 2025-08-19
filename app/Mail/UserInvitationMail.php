<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;
    public $acceptUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($invitation)
    {
        // Accept either an Invitation model or array (for legacy, but prefer model)
        $this->invite = is_array($invitation) ? $invitation : $invitation->toArray();
        $token = is_array($invitation) ? $invitation['token'] ?? $invitation['invitation_token'] : $invitation->token;
        $this->acceptUrl = \URL::temporarySignedRoute(
            'invitations.accept',
            now()->addHours(48),
            ['token' => $token]
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('You are invited to join ' . config('app.name'))
            ->markdown('emails.invite-user')
            ->with([
                'invite' => $this->invite,
                'acceptUrl' => $this->acceptUrl,
            ]);
    }
}
