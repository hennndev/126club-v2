<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->string('pos_name')->nullable()->after('name');
        });

        DB::statement('UPDATE inventory_items SET pos_name = name WHERE pos_name IS NULL');
    }

    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn('pos_name');
        });
    }
};
