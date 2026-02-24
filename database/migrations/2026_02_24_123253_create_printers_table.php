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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->enum('connection_type', ['network', 'file', 'windows'])->default('network');
            $table->string('ip')->nullable();
            $table->integer('port')->default(9100);
            $table->string('path')->nullable();
            $table->integer('timeout')->default(30);
            $table->string('header')->default('126 Club');
            $table->string('footer')->default('Thank you for your visit!');
            $table->boolean('show_qr_code')->default(true);
            $table->integer('width')->default(42);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
