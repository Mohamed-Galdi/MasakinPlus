<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerif extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $email;
    protected $actionUrl;


    public function __construct()
    {
        $this->name = 'Mohamed';
        $this->email = 'contact.galdi@gmail.com';
        $this->actionUrl = 'https://masakinplus.com/test-email';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأكيد التسجيل',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.otp',
            with: [
                'name' => $this->name,
                'otp' => '123456',
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
