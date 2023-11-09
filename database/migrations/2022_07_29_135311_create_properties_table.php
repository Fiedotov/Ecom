<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_id')->unique();
            $table->string('name');
            $table->string('status')->index();
            $table->string('apn');

            $table->string('street')->nullable()->default(null);
            $table->string('city')->nullable();
            $table->string('county')->nullable()->index();
            $table->string('state')->nullable()->index();
            $table->string('zip_code')->index()->nullable();

            $table->float('payment_1')->nullable();
            $table->float('payment_2')->nullable();
            $table->float('payment_3')->nullable();
            $table->unsignedInteger('term_1')->nullable();
            $table->unsignedInteger('term_2')->nullable();
            $table->unsignedInteger('term_3')->nullable();

            $table->text('property_description')->nullable()->default(null);
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->json('corner_coordinates')->nullable()->default(null);

            $table->float('acreage');
            $table->string('zoning');
            $table->float('annual_property_taxes');
            $table->float('hoa_poa_annual_fee');
            $table->string('water_connection')->nullable();
            $table->string('sewer_connection')->nullable();
            $table->string('power_connection')->nullable();
            $table->boolean('road_access');
            $table->string('elevation')->nullable()->default(null);
            $table->string('terrain')->nullable()->default(null);
            $table->string('time_limit')->nullable()->default(null);

            $table->float('asking_price')->nullable()->default(null);
            $table->float('cash_only_deal_price')->nullable()->default(null);
            $table->float('cash_price_current')->nullable()->default(null);
            $table->float('cash_sale_price')->nullable()->default(null);
            $table->float('change_price')->nullable()->default(null);
            $table->string('comp_agent_price')->nullable()->default(null);
            $table->float('current_listing_price')->nullable()->default(null);
            $table->float('original_cash_price')->nullable()->default(null);
            $table->float('property_list_price');
            $table->float('reduced_cash_price')->nullable()->default(null);
            $table->float('ted_max_price')->nullable()->default(null);
            $table->float('updated_price')->nullable()->default(null);
            $table->float('wholesale_price')->nullable()->default(null);

            $table->float('down_payment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
