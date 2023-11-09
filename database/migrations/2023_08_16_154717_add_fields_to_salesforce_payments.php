<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salesforce_payments', function (Blueprint $table) {
            $table->json('authorize_response')->nullable()->after('data');
            $table->json('salesforce_response')->nullable()->after('authorize_response');
        });
    }

    public function down(): void
    {
        Schema::table('salesforce_payments', function (Blueprint $table) {
            $table->dropColumn('authorize_response');
            $table->dropColumn('salesforce_response');
        });
    }
};
