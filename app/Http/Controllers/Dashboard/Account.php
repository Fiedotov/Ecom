<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Account
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Account', [
            'account' => $request->user()->sf_account,
            'contact' => $request->user()->sf_contact,
        ]);
    }
}