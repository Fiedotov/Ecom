<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            $table->json('sf_contact_response')->nullable()->after('sf_account_response');
        });
    }

    public function down(): void
    {
        Schema::table('payment_submissions', function (Blueprint $table) {
            $table->dropColumn('sf_contact_response');
        });
    }
};
