<?php
// FILE PATH: app/Mail/PaymentApprovedMail.php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentApprovedMail extends Mailable
{
    // NOTE: No Queueable trait — sends IMMEDIATELY
    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SWF Portal — Your Payment Has Been Approved! 🎉',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-approved',
            with: [
                'payment' => $this->payment,
            ],
        );
    }
}