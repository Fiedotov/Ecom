<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_group_property', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('product_group_id')
                ->constrained('product_groups')
                ->onDelete('cascade');
            $table
                ->foreignId('property_id')
                ->constrained('properties')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_group_property');
    }
};
