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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ref_id')->unsigned()->nullable();
            $table->foreignId("category_id")->constrained("categories")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("name", 200);
            $table->string("slug")->nullable();
            $table->text("image")->nullable();
            $table->text("banner")->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
