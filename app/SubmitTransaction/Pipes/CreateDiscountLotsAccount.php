<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\Models\User;
use App\UserTracker;
use Illuminate\Support\Facades\Password;

class CreateDiscountLotsAccount
{
    public function __invoke(PaymentSubmission $submission, $next)
    {
        $email = $submission->getCustomerEmail();
        $user = $this->getAccount($email, $submission);
        $user->update([
            'authorize_net_profile_id' => object_get($submission->authorize_net_response_profile, 'customerProfileId')
        ]);

        if ($user->email_verified_at !== null) {
            return $next($submission);
        }

        Password::sendResetLink(['email' => $user->email]);

        $next($submission);
    }

    private function getAccount(string $email, PaymentSubmission $submission): User
    {
        /** @var User $user */
        $user = User::query()->where('email', $email)->first();

        if ($user) {
            return $user;
        }

        /** @var User */
        return User::query()->create([
            'email' => $email,
            'password' => '*',
            'name' => sprintf(
                '%s %s',
                $submission->payload->customer->first_name,
                $submission->payload->customer->last_name,
            ),
            'tracking' => [
                'params' => UserTracker::getParams(),
                'original_landing_page' => UserTracker::getFirstPage(),
                'user_agent' => UserTracker::getUserAgent(),
                'ip_address' => UserTracker::getIpAddress(),
                'referer' => UserTracker::getReferer(),
            ],
        ]);
    }
}