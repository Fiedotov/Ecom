<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('sf_contact')->nullable();
            $table->json('sf_account')->nullable();
            $table->json('sf_contracts')->nullable();
            $table->json('sf_properties')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sf_contact');
            $table->dropColumn('sf_account');
            $table->dropColumn('sf_contracts');
            $table->dropColumn('sf_properties');
        });
    }
};
