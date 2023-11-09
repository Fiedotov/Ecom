<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->string('email');
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('notification_id')->references('id')->on('notifications');
            $table->unique(['notification_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
