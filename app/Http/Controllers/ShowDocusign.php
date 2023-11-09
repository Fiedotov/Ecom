<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowDocusign as Request;
use App\Models\PaymentSubmission;
use Illuminate\Http\JsonResponse;

class ShowDocusign
{
    public function __invoke(Request $request, PaymentSubmission $submission): JsonResponse
    {
        return new JsonResponse($request->docusignUrl(), $request->responseCode());
    }
}