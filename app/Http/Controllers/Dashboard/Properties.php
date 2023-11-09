<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Properties
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Properties', ['contracts' => $request->user()->contracts()->with('payments')->get()]);
    }
}