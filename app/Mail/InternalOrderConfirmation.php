<?php

namespace App\Mail;

use App\Models\Notification;
use App\Models\PaymentSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InternalOrderConfirmation extends Mailable
{
    use Queueable;
    use SerializesModels;

    public PaymentSubmission $order;
    public User $user;

    public function __construct(PaymentSubmission $order)
    {
        $this->order = $order;
        $this->user = $order->getUser();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            to: $this->notification()->recipients->pluck('email')->toArray(),
            subject: 'Order Created',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.internal-order-confirmation');
    }

    public function attachments(): array
    {
        return [];
    }

    protected function notification(): ?Notification
    {
        return Notification::getByName(Notification::INTERNAL_ORDER_CONFIRMATION);
    }
}
