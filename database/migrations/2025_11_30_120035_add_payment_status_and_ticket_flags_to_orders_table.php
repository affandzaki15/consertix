<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahkan hanya kolom yang diperlukan dan mungkin belum ada
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'tickets_generated')) {
                $table->boolean('tickets_generated')->default(false)->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'tickets_generated_at')) {
                $table->timestamp('tickets_generated_at')->nullable()->after('tickets_generated');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya hapus jika benar-benar ingin rollback (opsional)
            // $table->dropColumn(['payment_status', 'tickets_generated', 'tickets_generated_at']);
        });
    }
};