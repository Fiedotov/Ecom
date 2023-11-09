<?php

namespace App\Jobs;

use App\AuthorizeNet\Client;
use App\AuthorizeNet\CustomerProfile;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAuthorizeProfile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(Client $authorizeDotNet): void
    {
        $profile = $authorizeDotNet->getCustomerProfile($this->user->authorize_net_profile_id);

        CustomerProfile::query()->updateOrCreate(
            ['user_id' => $this->user->id],
            ['data' => $profile['profile']]
        );
    }
}
