<?php

namespace App\Http\Controllers;

use App\AuthorizeNet\Client;
use App\Http\Requests\SubmitAchPayment as Request;
use App\Models\AchPayment;
use Illuminate\Http\JsonResponse;

class SubmitAchPayment
{
    public function __invoke(Request $request, Client $authorizeNet): JsonResponse
    {
        $paymentRequest = $request->request();
        $payment = AchPayment::query()->create([
            'user_id' => $request->user()->id,
            'request_payload' => $request->validated(),
            'authorize_request' => $paymentRequest->toPayload(),
        ]);
        $response = $authorizeNet->createAchPayment($paymentRequest);
        $payment->update(['authorize_response' => $response]);

        return new JsonResponse([
            'payload' => $request->validated(),
            'message' => 'ACH Payment submitted successfully.',
        ]);
    }
}