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
        Schema::create('kitchen_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_user_id')->constrained('customer_users')->onDelete('cascade');
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'credit-card', 'debit-card', 'e-wallet'])->default('cash');
            $table->enum('status', ['baru', 'proses', 'selesai'])->default('baru');
            $table->integer('progress')->default(0); // 0-100
            $table->timestamps();
            
            $table->index('status');
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen_orders');
    }
};
