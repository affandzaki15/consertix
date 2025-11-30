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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizer_id');
            $table->string('code')->unique(); // e.g., "SAVE50"
            $table->string('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed']); // percentage atau fixed amount
            $table->integer('discount_value'); // 50 untuk 50% atau 100000 untuk Rp 100,000
            $table->integer('usage_limit')->nullable(); // max berapa kali bisa dipakai total
            $table->integer('usage_count')->default(0); // berapa kali sudah dipakai
            $table->integer('max_per_user')->default(1); // max berapa kali per user
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
