<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Subscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'انقضای اشتراک - کارت‌اکس',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expiry',
        );
    }
}
