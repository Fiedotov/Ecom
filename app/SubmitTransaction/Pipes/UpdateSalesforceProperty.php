<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\Models\Property;
use App\Salesforce\Actions\UpdateProperty;
use App\Salesforce\Api\Client;
use Exception;

class UpdateSalesforceProperty
{
    protected Client $salesforce;

    public function __construct(Client $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    public function __invoke(PaymentSubmission $submission, $next): void
    {
        $response = $this->salesforce->updateProperty(
            $this->updateProperty($submission),
            $submission->property->property_id,
        );

        $submission->update(['sf_property_response' => $response->json()]);

        if (!$response->successful()) {
            throw new Exception('Error updating Salesforce property');
        }

        $next($submission);
    }

    private function updateProperty(PaymentSubmission $submission): UpdateProperty
    {
        return new UpdateProperty(
            $submission->getSalesforceAccountId(),
            Property::STATUS_SOLD,
            $submission->getCustomerEmail(),
            $submission->created_at
        );
    }
}