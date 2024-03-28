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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string("id", 255);
            $table->string("user_id")->nullable();
            $table->string("mobile_number");
            $table->unsignedBigInteger("product_id");
            $table->string("product_name", 100);
            $table->string("destination", 100);
            $table->string("server_id", 100)->nullable();
            $table->string("payment_method", 50);
            $table->unsignedBigInteger("total");
            $table->string("status", 50)->default("PENDING");
            $table->string("payment_channel_status", 50)->default("PENDING");
            $table->string("failure_code", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
