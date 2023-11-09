<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyInquiry as Request;
use App\Models\Property;
use App\Salesforce\Api\Client;
use Illuminate\Http\JsonResponse;

class StorePropertyInquiry
{
    private Client $salesforce;

    public function __construct(Client $salesforce)
    {
        $this->salesforce = $salesforce;
    }

    public function __invoke(Request $request, Property $property): JsonResponse
    {
        $inquiry = $request->inquiry();

        $this->salesforce->createLead($inquiry);

        return response()->json($inquiry);
    }
}