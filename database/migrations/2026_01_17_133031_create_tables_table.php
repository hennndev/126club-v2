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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('restrict');
            $table->string('table_number', 50);
            $table->string('qr_code', 255)->unique()->comment('QR code unique identifier');
            $table->integer('capacity')->comment('Jumlah orang yang bisa duduk');
            $table->decimal('minimum_charge', 10, 2)->nullable()->comment('Minimum charge untuk reservasi');
            $table->enum('status', ['available', 'reserved', 'occupied', 'maintenance'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes untuk performa query
            $table->index('area_id');
            $table->index('status');
            $table->index('is_active');
            $table->unique(['area_id', 'table_number'], 'unique_table_number_per_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};