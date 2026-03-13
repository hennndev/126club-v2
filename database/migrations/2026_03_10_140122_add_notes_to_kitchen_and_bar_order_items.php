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
        Schema::table('kitchen_order_items', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_completed');
        });

        Schema::table('bar_order_items', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kitchen_order_items', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('bar_order_items', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
