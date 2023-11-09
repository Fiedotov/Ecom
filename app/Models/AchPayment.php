<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchPayment extends Model
{
    use HasFactory;

    protected $table = 'ach_payments';
    protected $guarded = [];
    protected $casts = [
        'authorize_request' => 'object',
        'authorize_response' => 'object',
        'request_payload' => 'object',
    ];
}
