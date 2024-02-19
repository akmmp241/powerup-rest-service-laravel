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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained("products")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("title", 200)->nullable();
            $table->integer("percentage");
            $table->text("description")->nullable();
            $table->string("product_url")->nullable();
            $table->text("image_url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
