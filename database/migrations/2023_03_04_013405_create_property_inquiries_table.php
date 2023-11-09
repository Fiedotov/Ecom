<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->json('buy_reasons');
            $table->text('question');
            $table->boolean('spanish');
            $table->boolean('contact_allowed');
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_inquiries');
    }
};
