<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('current_listing_price');
            $table->dropColumn('asking_price');
            $table->dropColumn('cash_only_deal_price');
            $table->dropColumn('comp_agent_price');
            $table->dropColumn('change_price');
            $table->dropColumn('reduced_cash_price');
            $table->dropColumn('ted_max_price');
            $table->dropColumn('updated_price');
            $table->dropColumn('wholesale_price');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->float('wholesale_price')->nullable()->default(null);
            $table->float('updated_price')->nullable()->default(null);
            $table->float('ted_max_price')->nullable()->default(null);
            $table->float('reduced_cash_price')->nullable()->default(null);
            $table->float('change_price')->nullable()->default(null);
            $table->string('comp_agent_price')->nullable()->default(null);
            $table->float('cash_only_deal_price')->nullable()->default(null);
            $table->float('asking_price')->nullable()->default(null);
            $table->float('current_listing_price')->nullable()->default(null);
        });
    }
};
