<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('table_session_id')->constrained('table_sessions')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict'); // Staff yang input order
            
            // Order info
            $table->string('order_number')->unique(); // e.g., ORD-20260125-0001
            $table->enum('status', [
                'pending',      // Baru dibuat, ada items yang masih pending
                'preparing',    // Ada items yang sedang diproses
                'ready',        // Semua items sudah siap
                'served',       // Semua items sudah diantar
                'completed',    // Order selesai
                'cancelled'     // Order dibatalkan
            ])->default('pending');
            
            // Totals
            $table->decimal('items_total', 15, 2)->default(0); // Total dari semua order items
            $table->decimal('discount_amount', 15, 2)->default(0); // Discount level order (bukan item)
            $table->decimal('total', 15, 2)->default(0); // items_total - discount_amount
            
            // Timestamps
            $table->timestamp('ordered_at')->useCurrent(); // Kapan order dibuat
            $table->timestamp('completed_at')->nullable(); // Kapan semua items served
            $table->timestamp('cancelled_at')->nullable();
            
            // Additional
            $table->text('notes')->nullable(); // Catatan order
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index('table_session_id');
            $table->index('status');
            $table->index('order_number');
            $table->index('ordered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
