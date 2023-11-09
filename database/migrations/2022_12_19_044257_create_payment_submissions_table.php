<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->json('payload');
            $table->unsignedInteger('amount')->nullable();
            $table->json('authorize_net_response')->nullable();
            $table->json('sf_account_response')->nullable();
            $table->json('sf_contract_response')->nullable();
            $table->json('sf_property_response')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable()->default(null);

            $table->foreign('property_id')->references('id')->on('properties');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_submissions');
    }
};
