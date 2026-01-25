<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            
            // ONE-TO-ONE dengan table_session
            $table->foreignId('table_session_id')
                  ->unique()
                  ->constrained('table_sessions')
                  ->onDelete('cascade');
            
            // Breakdown charges
            $table->decimal('minimum_charge', 15, 2)->default(0);
            $table->decimal('orders_total', 15, 2)->default(0);            
            // Calculations
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(10.00);
            $table->decimal('discount_amount', 15, 2)->default(0);
            
            // Totals
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            
            // Status
            $table->enum('billing_status', [
                'draft',           // Masih aktif, customer belum minta bill
                'finalized',       // Customer minta bill, tidak bisa tambah order
                'paid',            // Sudah lunas
                'partially_paid'   // Dibayar sebagian (split payment)
            ])->default('draft');            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('billing_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};