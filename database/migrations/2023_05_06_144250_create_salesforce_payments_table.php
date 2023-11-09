<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salesforce_payments', function (Blueprint $table) {
            $table->id();
            $table->string('contract_id');
            $table->string('payment_id')->unique();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')->references('contract_id')->on('salesforce_contracts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesforce_payments');
    }
};
