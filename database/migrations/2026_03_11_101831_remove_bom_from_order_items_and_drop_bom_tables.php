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
        // 1. Drop bom_recipe_id FK + column from kitchen_order_items; add inventory_item_id
        Schema::table('kitchen_order_items', function (Blueprint $table) {
            $table->dropForeign(['bom_recipe_id']);
            $table->dropColumn('bom_recipe_id');
            $table->foreignId('inventory_item_id')->nullable()->after('kitchen_order_id')->constrained('inventory_items')->nullOnDelete();
        });

        // 2. Drop bom_recipe_id FK + column from bar_order_items (inventory_item_id already exists)
        Schema::table('bar_order_items', function (Blueprint $table) {
            $table->dropForeign(['bom_recipe_id']);
            $table->dropColumn('bom_recipe_id');
        });

        // 3. Drop BOM tables (items first due to FK)
        Schema::dropIfExists('bom_recipe_items');
        Schema::dropIfExists('bom_recipes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('bom_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accurate_id')->unique();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('type');
            $table->text('description')->nullable();
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->index('type');
        });

        Schema::create('bom_recipe_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_recipe_id')->constrained('bom_recipes')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity', 8, 2);
            $table->string('unit', 20);
            $table->decimal('cost', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::table('kitchen_order_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_item_id']);
            $table->dropColumn('inventory_item_id');
            $table->foreignId('bom_recipe_id')->after('kitchen_order_id')->constrained('bom_recipes')->onDelete('cascade');
        });

        Schema::table('bar_order_items', function (Blueprint $table) {
            $table->foreignId('bom_recipe_id')->nullable()->after('bar_order_id')->constrained('bom_recipes')->cascadeOnDelete();
        });
    }
};
