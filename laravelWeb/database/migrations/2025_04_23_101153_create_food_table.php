<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id('food_id');
            $table->string('food_name', 200);
            $table->decimal('food_price', 10, 2);
            $table->decimal('food_discount', 10, 2);
            $table->enum('food_category', ['Appetizer', 'Main Course', 'Dessert']);
            $table->enum('food_type', ['Veg', 'Non-veg']);
            $table->string('food_source');
            $table->enum('available', ['0','1'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
