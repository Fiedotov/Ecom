<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->unsignedBigInteger('size')->nullable();
            $table->string('salesforce_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropColumn('size');
            $table->dropColumn('salesforce_url');
            $table->dropTimestamps();
        });
    }
};
