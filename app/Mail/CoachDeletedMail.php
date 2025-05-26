<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Coach;

class CoachDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coach;
    public $action;

    /**
     * Create a new message instance.
     *
     * @param Coach $coach
     * @param string $action
     * @return void
     */
    public function __construct(Coach $coach, string $action = 'deleted')
    {
        $this->coach = $coach;
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
            subject: "Coach Account {$actionText} - ProGymHub",
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
            view: 'emails.coach-account-action',
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
