<?php

namespace App\Jobs;

use App\Models\User;
use App\Salesforce\Api\Client;
use App\Salesforce\Api\Contract as ApiContract;
use App\Salesforce\Contract;
use App\Salesforce\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncSalesforceContracts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(Client $salesforce): void
    {
        $contracts = $salesforce->getContractsByEmail($this->user->email);

        $contracts->each(function (ApiContract $contract) use ($salesforce) {
            Contract::query()->updateOrCreate(
                ['contract_id' => $contract->getId()],
                ['user_id' => $this->user->getKey(), 'data' => $contract->toArray()]
            );

            $salesforce->getPaymentsByContract($contract->getId())
                ->each(fn(array $payment) => Payment::query()->updateOrCreate(
                    ['payment_id' => $payment['Id']],
                    ['contract_id' => $contract->getId(), 'data' => $payment]
                ));
        });
    }
}
