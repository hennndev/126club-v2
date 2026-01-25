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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->unsignedBigInteger('accurate_id')->unique();
            $table->string('name');
            $table->string('category_type'); // 'beverage', 'spices', 'condiments', 'spirits', 'dairy'
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('threshold')->default(10);
            $table->string('unit')->default('unit'); // unit, bottle, kg, liter, etc
            $table->boolean('is_active')->default(true);
            $table->boolean('item_produced')->default(false);
            $table->boolean('material_produced')->default(false);
            $table->timestamps();
            
            $table->index('category_type');
            $table->index('stock_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
