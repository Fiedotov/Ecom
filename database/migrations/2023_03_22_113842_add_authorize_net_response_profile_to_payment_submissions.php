<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            $table->json('authorize_net_response_profile')->nullable()->after('authorize_net_response');
        });
    }

    public function down(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            $table->dropColumn('authorize_net_response_profile');
        });
    }
};
