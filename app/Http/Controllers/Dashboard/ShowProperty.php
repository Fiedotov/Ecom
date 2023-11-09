<?php

namespace App\Http\Controllers\Dashboard;

use App\Salesforce\Contract;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowProperty
{
    public function __invoke(Contract $contract, Request $request): Response
    {
        return Inertia::render('ShowProperty', [
            'contract' => $contract,
            'user' => $request->user()->load(['customerProfile']),
        ]);
    }
}