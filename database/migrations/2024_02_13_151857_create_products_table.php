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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer("ref_id")->nullable();
            $table->foreignId("icon_id")->constrained("icons")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("type_id")->constrained("types")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("code", 25);
            $table->string("name", 200);
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
