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
        Schema::create('models', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name'); // Model Name
            $table->unsignedBigInteger('brand_id')->nullable(); // Foreign Key for Brand (should be unsignedBigInteger)
            $table->timestamps();

            // Define Foreign Key Constraint
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
