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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name'); // Item Name
            $table->decimal('amount', 8, 2); // Item Amount (2 decimal precision)
            $table->unsignedBigInteger('brand_id')->nullable(); // Foreign Key for Brand (should be unsignedBigInteger)
            $table->unsignedBigInteger('model_id')->nullable(); // Foreign Key for Model (nullable, should be unsignedBigInteger)
            $table->timestamps();

            // Define Foreign Key Constraints
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('model_id')->references('id')->on('models')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
