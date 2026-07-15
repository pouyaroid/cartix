<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Payment $payment) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تأیید پرداخت - کارت‌اکس',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmation',
        );
    }
}
