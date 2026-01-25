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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique(); 
            $table->text('description')->nullable();
            $table->date('start_date'); 
            $table->date('end_date'); 
            $table->time('start_time')->nullable(); 
            $table->time('end_time')->nullable(); 
            $table->boolean('is_active')->default(false);
            
            $table->enum('price_adjustment_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('price_adjustment_value', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
