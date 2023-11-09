<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\Salesforce\Actions\StoreContract;
use App\Salesforce\Api\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateSalesforceContract
{
    protected Client $salesforce;

    public function __construct(Client $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    public function __invoke(PaymentSubmission $submission, $next)
    {
        $response = $this->salesforce->storeContract(new StoreContract($submission));

        $submission->update(['sf_contract_response' => $response->json()]);

        if (! $response->successful()) {
            $message = 'Error storing Salesforce contract';
            Log::info($message, $response->json());
            throw new Exception($message);
        }

        $next($submission);
    }
}