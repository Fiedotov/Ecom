<?php

namespace App\Http\Controllers;

use App\Jobs\SyncAuthorizeProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Omnipay\AuthorizeNet\CIMGateway;

class DeletePaymentMethod
{
    public function __invoke(Request $request, CIMGateway $gateway): JsonResponse
    {
        $response = $gateway->deleteCard([
            'customerProfileId' => request()->user()->authorize_net_profile_id,
            'customerPaymentProfileId' => $request->input('id')
        ])->send();

        dispatch_sync(new SyncAuthorizeProfile($request->user()));

        return new JsonResponse($response->getMessage(), status: 204);
    }
}