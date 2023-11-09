<?php

namespace App\Jobs;

use App\Models\User;
use App\Salesforce\Api\Client;
use App\Salesforce\Api\Contract as SalesforceContract;
use App\Salesforce\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SyncSalesforceData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(Client $salesforce): void
    {
        $this->user->update([
            'sf_account' => optional($salesforce->getAccountByEmail($this->user->email))->toArray(),
            'sf_contact' => optional($salesforce->getContactByEmail($this->user->email))->toArray(),
            'sf_properties' => $salesforce->getPropertiesByEmail($this->user->email)->map->toArray(),
        ]);

        dispatch(new SyncSalesforceContracts($this->user));
    }

    private function storeContracts(Collection $contracts): void
    {
        $contracts->each(
            fn(SalesforceContract $contract) => Contract::query()->updateOrCreate(
                ['contract_id' => $contract->getId()],
                ['user_id' => $this->user->getKey(), 'data' => $contract->toArray()]
            )
        );
    }
}
