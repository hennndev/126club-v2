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
        Schema::create('bom_recipe_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_recipe_id')->constrained('bom_recipes')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->string('unit', 20);
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index('bom_recipe_id');
            $table->index('inventory_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_recipe_items');
    }
};
