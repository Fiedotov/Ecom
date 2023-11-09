<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\InternalOrderConfirmation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInternalOrderConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;;
    }

    public function handle(OrderCompleted $event): void
    {
        $this->mailer->send(new InternalOrderConfirmation($event->submission));
    }
}
