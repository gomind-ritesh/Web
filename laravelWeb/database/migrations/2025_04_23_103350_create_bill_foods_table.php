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
        Schema::create('bill_foods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Order::class,'bill_id');
            $table->foreignIdFor(\App\Models\Food::class);
            $table->integer('item_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_foods');
    }
};
