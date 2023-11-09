<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->json('tracking')->nullable()->after('contact_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('property_inquiries', function (Blueprint $table) {
            $table->dropColumn('tracking');
        });
    }
};
