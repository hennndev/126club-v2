<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('restrict');
            
            // Item details (historical snapshot untuk accuracy)
            $table->string('item_name'); // Snapshot nama item saat order
            $table->string('item_code'); // Snapshot code item
            $table->integer('quantity'); // Jumlah yang di-order
            $table->decimal('price', 15, 2); // Harga per item saat order
            $table->decimal('subtotal', 15, 2); // quantity * price
            
            // Discount (optional, item-level)
            $table->decimal('discount_amount', 15, 2)->default(0);
            
            // Production tracking
            $table->enum('preparation_location', ['kitchen', 'bar'])->nullable();
            $table->enum('status', [
                'pending',      // Baru di-order, belum mulai diproses
                'preparing',    // Sedang diproses (kitchen/bar)
                'ready',        // Sudah siap, siap diantar
                'served',       // Sudah diantar ke customer
                'cancelled'     // Di-cancel
            ])->default('pending');
            
            // Timestamps untuk tracking
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Additional info
            $table->text('notes')->nullable(); // Special request
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes untuk performa query
            $table->index('order_id');
            $table->index('status');
            $table->index(['preparation_location', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};