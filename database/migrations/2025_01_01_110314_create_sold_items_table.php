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
        Schema::create('sold_items', function (Blueprint $table) {
            $table->id();
            $table->string('coustomer_name');
            $table->string('coustomer_email');
            $table->unsignedBigInteger('item_id'); 
            $table->integer('quantity'); 
            $table->string('brand_id'); 
            $table->decimal('original_price', 10, 2); 
            $table->decimal('discount_price', 10, 2); 
            $table->decimal('total_amount', 10, 2); 
            $table->string('status')->nullable();
            $table->timestamps(); 

            $table->foreign('item_id')->references('id')->on('items')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_items');
    }
};