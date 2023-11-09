<?php

namespace App\Http\Controllers;

use App\AuthorizeNet\Client as AuthorizeClient;
use App\Http\Requests\StoreOneTimePayment as Request;
use App\Jobs\SyncSalesforceContracts;
use App\Salesforce\Api\Client;
use App\Salesforce\PaymentInstallment;
use Exception;
use Illuminate\Http\JsonResponse;

class StoreOneTimePayment
{
    private AuthorizeClient $authorizeNet;
    private Client $salesforce;

    public function __construct(AuthorizeClient $authorizeNet, Client $salesforce)
    {
        $this->authorizeNet = $authorizeNet;
        $this->salesforce = $salesforce;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $installment = new PaymentInstallment(['amount' => $request->amount()]);

        try {
            $authorizeResponse = $this->authorizeNet->chargeProfile(
                $request->payment(),
                $request->amount(),
                $request->input('profile_id')
            );
            $installment->fill(['authorize_response' => $authorizeResponse]);
            $installment = $request->payment()->installments()->save($installment);
        } catch (Exception $e) {
            $installment->update(['authorize_response' => ['error' => 'Authorize.net error - ' . $e->getMessage()]]);

            return new JsonResponse('Error processing payment', 500);
        }

        try {
            $salesforceResponse = $this->salesforce->updatePayment(
                $request->input('payment_id'),
                $request->amount()
            );
            $installment->update(['salesforce_response' => $salesforceResponse]);
        } catch (Exception $e) {
            $installment->update(['salesforce_response' => ['error' => $e->getMessage()]]);

            return new JsonResponse('Error processing payment', 500);
        }

        dispatch(new SyncSalesforceContracts($request->user()));

        return new JsonResponse();
    }
}