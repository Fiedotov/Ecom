<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowPayments
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Dashboard/Payments', [
            'user' => $request->user()->load(['customerProfile']),
            'showAch' => in_array($request->user()->email, config('discount.test_users'))
        ]);
    }
}