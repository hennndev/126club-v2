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
        Schema::create('pos_category_settings', function (Blueprint $table) {
            $table->id();
            $table->string('category_type')->unique();
            $table->boolean('show_in_pos')->default(false);
            $table->enum('source', ['bom', 'inventory', 'both'])->default('bom');
            $table->enum('preparation_location', ['kitchen', 'bar', 'direct'])->default('bar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_category_settings');
    }
};
