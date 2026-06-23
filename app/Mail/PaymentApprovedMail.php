<?php
// FILE PATH: app/Mail/PaymentApprovedMail.php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function build(): self
    {
        return $this
            ->subject('SWF Portal — Payment Approved & Subscription Activated!')
            ->view('emails.payment-approved');
    }
}