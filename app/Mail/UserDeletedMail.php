<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $action;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $action
     * @return void
     */
    public function __construct(User $user, string $action = 'deleted')
    {
        $this->user = $user;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $actionText = ucfirst($this->action);
        return new Envelope(
            subject: "User Account {$actionText} - ProGymHub",
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.user-account-action',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
