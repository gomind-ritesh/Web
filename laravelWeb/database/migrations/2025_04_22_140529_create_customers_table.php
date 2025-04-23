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
        Schema::create('customers', function (Blueprint $table) {
            $table->id("customer_id");
            $table->string('customer_name', 200);
            $table->string('customer_email',200);
            $table->string('customer_pwd', 200);
            $table->string('customer_firstname', 200);
            $table->string('customer_lastname', 200);
            $table->string('phone', 200);
            $table->unsignedBigInteger('ban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
