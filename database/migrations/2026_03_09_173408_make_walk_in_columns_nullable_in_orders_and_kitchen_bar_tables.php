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
        // orders: allow walk-in orders with no table session
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['table_session_id']);
            $table->foreignId('table_session_id')->nullable()->change();
            $table->foreign('table_session_id')->references('id')->on('table_sessions')->onDelete('cascade');
        });

        // kitchen_orders: table_id and customer_user_id optional for walk-in
        Schema::table('kitchen_orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['customer_user_id']);
            $table->unsignedBigInteger('table_id')->nullable()->change();
            $table->unsignedBigInteger('customer_user_id')->nullable()->change();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('customer_user_id')->references('id')->on('customer_users')->onDelete('cascade');
        });

        // bar_orders: table_id and customer_user_id optional for walk-in
        Schema::table('bar_orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['customer_user_id']);
            $table->unsignedBigInteger('table_id')->nullable()->change();
            $table->unsignedBigInteger('customer_user_id')->nullable()->change();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('customer_user_id')->references('id')->on('customer_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['table_session_id']);
            $table->foreignId('table_session_id')->nullable(false)->change();
            $table->foreign('table_session_id')->references('id')->on('table_sessions')->onDelete('cascade');
        });

        Schema::table('kitchen_orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['customer_user_id']);
            $table->unsignedBigInteger('table_id')->nullable(false)->change();
            $table->unsignedBigInteger('customer_user_id')->nullable(false)->change();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('customer_user_id')->references('id')->on('customer_users')->onDelete('cascade');
        });

        Schema::table('bar_orders', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['customer_user_id']);
            $table->unsignedBigInteger('table_id')->nullable(false)->change();
            $table->unsignedBigInteger('customer_user_id')->nullable(false)->change();
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('customer_user_id')->references('id')->on('customer_users')->onDelete('cascade');
        });
    }
};
