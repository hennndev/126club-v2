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
        Schema::table('table_reservations', function (Blueprint $table) {
            $table->string('check_in_qr_code')->nullable()->after('status');
            $table->timestamp('check_in_qr_expires_at')->nullable()->after('check_in_qr_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_reservations', function (Blueprint $table) {
            $table->dropColumn(['check_in_qr_code', 'check_in_qr_expires_at']);
        });
    }
};
