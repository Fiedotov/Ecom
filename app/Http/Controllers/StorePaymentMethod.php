<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethod as Request;
use App\Jobs\SyncAuthorizeProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Omnipay\AuthorizeNet\CIMGateway;
use Omnipay\AuthorizeNet\Message\CIMResponse;

class StorePaymentMethod
{
    public function __invoke(Request $request, CIMGateway $gateway): JsonResponse
    {
        /** @var CIMResponse $response */
        $response = $gateway->createAdditionalCard($request->card())->send();

        Log::info('Authorize.net add card response', (array) $response->getData());

        if ($response->isSuccessful()) {
            dispatch_sync(new SyncAuthorizeProfile($request->user()));

            return new JsonResponse($response->getData(), status: 201);
        }

        return new JsonResponse($response->getMessage(), status: 400);
    }
}