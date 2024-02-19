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
        Schema::create('popular_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("operator_id")->constrained("operators")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("title");
            $table->text("image")->nullable();
            $table->string("link", 200)->nullable();
            $table->text("description");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_products');
    }
};
