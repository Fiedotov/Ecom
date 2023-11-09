<?php

namespace App\Http\Controllers;

use App\Events\OrderCompleted;
use App\Http\Requests\SubmitTransaction as Request;
use App\Jobs\SalesforceCheckout;
use App\Jobs\SyncAuthorizeProfile;
use App\Models\PaymentSubmission;
use App\Models\Property;
use App\SubmitTransaction\Pipes\CreateAuthorizeNetCustomerProfile;
use App\SubmitTransaction\Pipes\CreateDiscountLotsAccount;
use App\SubmitTransaction\Pipes\SubmitToAuthorizeNet;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;

class SubmitTransaction
{
    public function __invoke(Request $request, Pipeline $pipeline): JsonResponse
    {
        $submission = $request->paymentSubmission();

        try {
            $pipeline
                ->send($submission)
                ->through([
                    SubmitToAuthorizeNet::class,
                    CreateAuthorizeNetCustomerProfile::class,
                    CreateDiscountLotsAccount::class,
                ])
                ->thenReturn();
            $submission->property->update(['status' => Property::STATUS_SOLD]);
            $submission->refresh();
            dispatch(new SalesforceCheckout($submission));
            dispatch(new SyncAuthorizeProfile($submission->getUser()));
            event(new OrderCompleted($submission));
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json([
            'transaction_id' => $submission->authorize_net_response->transactionResponse->transId,
            'customer' => $submission->payload->customer,
            'property' => $request->property(),
        ]);
    }
}