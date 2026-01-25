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
    Schema::create('customer_users', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger("accurate_id")->unique();  
      $table->string('customer_code')->unique();
      $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
      $table->foreignId('user_profile_id')->constrained('user_profiles')->onDelete('cascade');
      $table->integer('total_visits')->default(0);
      $table->decimal('lifetime_spending', 12, 2)->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customer_users');
  }
};
