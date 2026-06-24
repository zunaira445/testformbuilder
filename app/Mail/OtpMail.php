<?php
// FILE PATH: app/Mail/OtpMail.php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OtpMail extends Mailable
{
    // NOTE: No Queueable trait — sends IMMEDIATELY (within seconds)
    public string $otp;
    public string $userName;

    public function __construct(string $otp, string $userName)
    {
        $this->otp      = $otp;
        $this->userName = $userName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SWF Portal — Email Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otp'      => $this->otp,
                'userName' => $this->userName,
            ],
        );
    }
}