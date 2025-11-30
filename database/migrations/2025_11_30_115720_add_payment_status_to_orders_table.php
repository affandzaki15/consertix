<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan kolom hanya jika belum ada
            if (!Schema::hasColumn('orders', 'buyer_name')) {
                $table->string('buyer_name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'buyer_email')) {
                $table->string('buyer_email')->nullable();
            }
            if (!Schema::hasColumn('orders', 'identity_type')) {
                $table->string('identity_type')->nullable();
            }
            if (!Schema::hasColumn('orders', 'identity_number')) {
                $table->string('identity_number')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending');
            }
            if (!Schema::hasColumn('orders', 'tickets_generated')) {
                $table->boolean('tickets_generated')->default(false);
            }
            if (!Schema::hasColumn('orders', 'tickets_generated_at')) {
                $table->timestamp('tickets_generated_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya drop kolom yang benar-benar ingin dihapus (hati-hati!)
            // Lebih aman: jangan drop jika data penting
        });
    }
};