<?php
// FILE PATH: app/Mail/OtpMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $userName;

    public function __construct(string $otp, string $userName)
    {
        $this->otp      = $otp;
        $this->userName = $userName;
    }

    public function build(): self
    {
        return $this
            ->subject('SWF Portal — Email Verification Code')
            ->view('emails.otp');
    }
}