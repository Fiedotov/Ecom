<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('zoning_headline', 2000)->nullable()->after('legal_description');
            $table->string('zone_item_1', 500)->nullable()->after('zoning_headline');
            $table->string('zone_item_2', 500)->nullable()->after('zone_item_1');
            $table->string('zone_item_3', 500)->nullable()->after('zone_item_2');
            $table->string('zone_item_4', 500)->nullable()->after('zone_item_3');
            $table->string('after_zoning_text', 500)->nullable()->after('zone_item_4');
            $table->string('cta_text', 1000)->nullable()->after('after_zoning_text');;
            $table->string('video_tour_url')->nullable()->after('cta_text');
            $table->string('title')->nullable()->after('video_tour_url');
            $table->text('description', 500)->nullable()->after('title');
            $table->text('usage')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('usage');
            $table->dropColumn('description');
            $table->dropColumn('title');
            $table->dropColumn('video_tour_url');
            $table->dropColumn('cta_text');
            $table->dropColumn('after_zoning_text');
            $table->dropColumn('zone_item_4');
            $table->dropColumn('zone_item_3');
            $table->dropColumn('zone_item_2');
            $table->dropColumn('zone_item_1');
            $table->dropColumn('zoning_headline');
        });
    }
};
