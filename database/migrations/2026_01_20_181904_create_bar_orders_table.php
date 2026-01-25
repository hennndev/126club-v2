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
        Schema::create('bar_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_user_id')->constrained('customer_users')->cascadeOnDelete();
            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'credit-card', 'debit-card', 'e-wallet']);
            $table->enum('status', ['baru', 'proses', 'selesai'])->default('baru');
            $table->integer('progress')->default(0);
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bar_orders');
    }
};
