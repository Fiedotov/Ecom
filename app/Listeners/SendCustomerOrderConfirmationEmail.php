<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\CustomerOrderConfirmation;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCustomerOrderConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;;
    }

    public function handle(OrderCompleted $event): void
    {
        $this->mailer->send(new CustomerOrderConfirmation($event->submission));
    }
}
