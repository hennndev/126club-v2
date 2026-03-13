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
        Schema::table('billings', function (Blueprint $table) {
            $table->unsignedTinyInteger('service_charge_percentage')->default(0)->after('tax_percentage');
            $table->decimal('service_charge', 15, 2)->default(0)->after('service_charge_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['service_charge_percentage', 'service_charge']);
        });
    }
};
