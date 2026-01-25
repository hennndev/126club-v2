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
    Schema::create('internal_users', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger("accurate_id")->nullable()->unique();
      $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
      $table->foreignId('user_profile_id')->constrained('user_profiles')->onDelete('cascade');
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('internal_users');
  }
};
