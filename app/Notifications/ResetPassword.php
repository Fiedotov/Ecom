<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends BaseResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Create Password'))
            ->line(
                Lang::get('You are receiving this email because we received a request to create a password for your account.')
            )
            ->action(Lang::get('Create Password'), $url)
            ->line(
                Lang::get(
                    'This password create link will expire in :count minutes.',
                    ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]
                )
            )
            ->line(Lang::get('If you did not make a request to create a password, no further action is required.'));
    }
}
