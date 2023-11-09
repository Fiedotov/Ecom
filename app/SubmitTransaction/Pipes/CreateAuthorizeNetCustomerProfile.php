<?php

namespace App\SubmitTransaction\Pipes;

use App\AuthorizeNet\Client;
use App\Models\PaymentSubmission;

class CreateAuthorizeNetCustomerProfile
{
    private Client $authorizeNet;

    public function __construct(Client $authorizeNet)
    {
        $this->authorizeNet = $authorizeNet;
    }

    public function __invoke(PaymentSubmission $submission, $next)
    {
        $json = $this->authorizeNet->createCustomerProfileFromTransaction(
            $submission->authorize_net_response->transactionResponse->transId
        );

        $submission->setAttribute('authorize_net_response_profile', $json);
        $submission->save();

        $next($submission);
    }
}