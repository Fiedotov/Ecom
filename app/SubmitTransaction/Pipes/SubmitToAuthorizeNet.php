<?php

namespace App\SubmitTransaction\Pipes;

use App\Models\PaymentSubmission;
use App\SubmitTransaction\Exceptions\AuthorizeNetSubmissionException;
use Illuminate\Support\Facades\Log;
use Omnipay\AuthorizeNet\AIMGateway;
use Omnipay\AuthorizeNet\Message\AIMResponse;
use Omnipay\Common\CreditCard;

class SubmitToAuthorizeNet
{
    private AIMGateway $gateway;

    public function __construct(AIMGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function __invoke(PaymentSubmission $submission, $next): void
    {
        $authorizeRequest = $this->gateway->purchase([
            'amount' => $submission->getPaymentAmount(),
            'opaqueDataDescriptor' => $submission->payload->dataDescriptor,
            'opaqueDataValue' => $submission->payload->dataValue,
            'card' => new CreditCard([
                'name' => $submission->payload->customer->full_legal_name,
                'firstName' => $submission->payload->customer->first_name,
                'lastName' => $submission->payload->customer->last_name,
                'address' => $submission->payload->customer->address,
                'city' => $submission->payload->customer->city,
                'state' => $submission->payload->customer->state,
                'zip' => $submission->payload->customer->postal_code,
                'country' => 'US',
                'phone' => $submission->payload->customer->phone,
                'email' => $submission->payload->customer->email,
            ]),
            'order' => [
                'description' => $submission->property->apn,
            ],
        ]);

        /** @var AIMResponse $response */
        $response = $authorizeRequest->send();

        Log::info('Transaction response from Authorize.net', [
            'type' => gettype($response->getData())
        ]);

        $submission->update([
            'amount' => $submission->getPaymentAmount(),
            'authorize_net_response' => (array) $response->getData(),
        ]);

        throw_unless($response->isSuccessful(), new AuthorizeNetSubmissionException($response->getMessage()));

        $next($submission);
    }
}