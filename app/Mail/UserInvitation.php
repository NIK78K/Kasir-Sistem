<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $temporaryPassword;
    public $activationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $temporaryPassword, $activationUrl)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->activationUrl = $activationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Aktivasi Akun - Sistem Kasir',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user_invitation',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'activationUrl' => $this->activationUrl,
            ],
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
