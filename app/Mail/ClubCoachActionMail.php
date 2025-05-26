<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Coach;
use App\Models\Club;

class ClubCoachActionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coach;
    public $club;
    public $action;

    /**
     * Create a new message instance.
     *
     * @param Coach $coach
     * @param Club $club
     * @param string $action
     * @return void
     */
    public function __construct(Coach $coach, Club $club, string $action = 'deleted')
    {
        $this->coach = $coach;
        $this->club = $club;
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
            subject: "Club Coach {$actionText} - ProGymHub",
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
            view: 'emails.club-coach-action',
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
