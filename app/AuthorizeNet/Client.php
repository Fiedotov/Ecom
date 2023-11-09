<?php

namespace App\AuthorizeNet;

use App\Salesforce\Payment;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Client
{
    public function getCustomerProfile(string $profileId): array
    {
        $response = Http::withHeaders($this->getHeaders())->post($this->getEndpoint(), [
            'getCustomerProfileRequest' => [
                'merchantAuthentication' => $this->getMerchantAuthentication(),
                'customerProfileId' => $profileId,
                'includeIssuerInfo' => 'true',
            ],
        ]);

        return $this->decodeResponse($response);
    }

    public function createCustomerProfileFromTransaction(string $transactionId): array
    {
        $response = Http::withHeaders($this->getHeaders())->post($this->getEndpoint(), [
            'createCustomerProfileFromTransactionRequest' => [
                'merchantAuthentication' => $this->getMerchantAuthentication(),
                'transId' => $transactionId
            ],
        ]);

        return $this->decodeResponse($response);
    }

    public function createAchPayment(AchPaymentRequest $request): array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getEndpoint(), [
                'createTransactionRequest' => [
                    'merchantAuthentication' => $this->getMerchantAuthentication(),
                    'transactionRequest' => $request->toPayload()
                ],
            ]);

        return $this->decodeResponse($response);
    }

    public function chargeProfile(Payment $payment, float $amount, string $profileId): array
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->getEndpoint(), [
                'createTransactionRequest' => [
                    'merchantAuthentication' => $this->getMerchantAuthentication(),
                    'transactionRequest' => [
                        'transactionType' => 'authCaptureTransaction',
                        'amount' => $amount,
                        'currencyCode' => 'USD',
                        'profile' => [
                            'customerProfileId' => $payment->user->authorize_net_profile_id,
                            'paymentProfile' => ['paymentProfileId' => $profileId],
                        ],
                        'lineItems' => [
                            'lineItem' => [
                                'itemId' => $payment->payment_id,
                                'name' => 'Contract Payment Installment',
                                'description' => sprintf('Payment installment for contract %s', $payment->contract_id),
                                'quantity' => '1',
                                'unitPrice' => $amount,
                            ]
                        ],
                    ]
                ],
            ]);

        $decode = $this->decodeResponse($response);

        if (Arr::get($decode, 'messages.resultCode') === 'Error') {
            throw new Exception($decode['messages']['message'][0]['text']);
        }

        return $decode;
    }

    private function decodeResponse(Response $response): array
    {
        return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()), true);
    }

    private function getHeaders(): array
    {
        return ['Content-type' => 'application/json'];
    }

    private function getEndpoint(): string
    {
        return config('authorize.net.endpoint');
    }

    private function getMerchantAuthentication(): array
    {
        return [
            'name' => config('authorize.net.api_login_id'),
            'transactionKey' => config('authorize.net.transaction_key'),
        ];
    }
}