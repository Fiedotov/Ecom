<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\Salesforce\Actions\StoreAccount;
use App\Salesforce\Api\Account;
use App\Salesforce\Api\Client;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CreateSalesforceAccount
{
    protected Client $salesforce;

    public function __construct(Client $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    public function __invoke(PaymentSubmission $submission, $next): void
    {
        $existing = $this->getExistingAccount($submission);

        if (! $existing) {
            $response = $this->salesforce->storeAccount($this->storeAccount($submission));
            $submission->update(['sf_account_response' => $response->json()]);

            if (!$response->successful()) {
                $json = $response->json();

                if (data_get($json, '0.duplicateResult')) {
                    $submission->update([
                        'sf_account_response' => [
                            'id' => data_get($json, '0.duplicateResult.matchResults.0.matchRecords.0.record.Id')]
                        ]
                    );
                } else {
                    $message = 'Error creating Salesforce account';
                    Log::info($message, $response->json());
                    throw new Exception($message);
                }
            }
        }

        $next($submission);
    }

    private function getExistingAccount(PaymentSubmission $submission): ?Account
    {
        $account = $this->salesforce
            ->getAccountByAuthorizeNetPaymentProfileId($submission->getAuthorizeNetPaymentProfileId());

        if ($account) {
            $submission->update(['sf_account_response' => ['id' => $account->getId()]]);
        }

        return $account;
    }

    private function storeAccount(PaymentSubmission $submission): StoreAccount
    {
        return new StoreAccount(
            $submission->billingAddress(),
            $submission->payload->customer->phone,
            $submission->payload->customer->email,
            $submission->payload->customer->full_legal_name,
            $submission->payload->customer->referrer_name,
            object_get($submission->authorize_net_response_profile, 'customerProfileId', ''),
            $submission->getAuthorizeNetPaymentProfileId(),
        );
    }
}
