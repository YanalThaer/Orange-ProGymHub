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

class CoachProfileUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $coach;
    public $club;
    public $oldData;
    public $changedFields;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Coach  $coach
     * @param  array  $oldData
     * @param  array  $changedFields
     * @param  \App\Models\Club|null  $club
     * @return void
     */
    public function __construct(Coach $coach, array $oldData, array $changedFields, ?Club $club = null)
    {
        $this->coach = $coach;
        $this->club = $club;
        $this->oldData = $oldData;
        $this->changedFields = $changedFields;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Coach Profile Updated: ' . $this->coach->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coach-profile-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
