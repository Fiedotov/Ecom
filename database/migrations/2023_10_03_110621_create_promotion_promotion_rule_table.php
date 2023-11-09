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
        Schema::create('promotion_promotion_rule', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignUuid('promotion_id')
                ->constrained('promotions')
                ->onDelete('cascade');
            $table
                ->foreignUuid('promotion_rule_id')
                ->constrained('promotion_rules')
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
        Schema::dropIfExists('promotion_promotion_rule');
    }
};
