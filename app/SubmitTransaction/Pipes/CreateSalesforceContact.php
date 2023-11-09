<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\Salesforce\Actions\StoreContact;
use App\Salesforce\Api\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class CreateSalesforceContact
{
    protected Client $salesforce;

    public function __construct(Client $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    public function __invoke(PaymentSubmission $submission, $next): void
    {
        $response = $this->salesforce->storeContact($this->storeContact($submission));
        $contact = $this->salesforce->getContactByEmail($submission->payload->customer->email);
        $submission->update(['sf_contact_response' => array_merge([$response->json()], ['Id' => $contact?->getId()])]);

        if (! $response->successful()) {
            $message = 'Error creating Salesforce contact';
            Log::info($message, $response->json());
            throw new Exception($message);
        }

        $next($submission);
    }

    private function storeContact(PaymentSubmission $submission): StoreContact
    {
        return new StoreContact(
            $submission->getSalesforceAccountId(),
            $submission->payload->customer->phone,
            $submission->payload->customer->email,
            $submission->payload->customer->first_name,
            $submission->payload->customer->last_name,
        );
    }
}