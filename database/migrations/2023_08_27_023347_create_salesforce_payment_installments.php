<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salesforce_payments', function (Blueprint $table) {
            $table->dropColumn('authorize_response');
            $table->dropColumn('salesforce_response');
        });

        Schema::create('salesforce_payment_installments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->json('authorize_response')->nullable();
            $table->json('salesforce_response')->nullable();
            $table->float('amount')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('payment_id')->on('salesforce_payments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesforce_payment_installments');

        Schema::table('salesforce_payments', function (Blueprint $table) {
            $table->json('authorize_response')->nullable()->after('data');
            $table->json('salesforce_response')->nullable()->after('authorize_response');
        });
    }
};
