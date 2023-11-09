<?php

namespace App\Http\Controllers;

use App\Models\PaymentSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Confirmation
{
    public function __invoke(Request $request): Response
    {
        /** @var PaymentSubmission $submission */
        $submission = PaymentSubmission::query()
            ->with(['property'])
            ->where('authorize_net_response->transactionResponse->transId', $request->route('transactionId'))
            ->first();

        return Inertia::render('Checkout/Confirmation', [
            'submission' => $submission,
            'user' => $submission->getUser(),
        ]);
    }
}
