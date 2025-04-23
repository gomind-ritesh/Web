<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use carbon\carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('bill_id');
            $table->timestamp('bill_date')->useCurrent();
            $table->unsignedBigInteger('customer_id');
            $table->decimal('bill_discount', 10, 2);
            $table->enum('status', ['active', 'completed', 'cancel'])->default('active');
            $table->enum('reviewed', ['0','1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
