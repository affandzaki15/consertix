<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya tambahkan jika belum ada
            if (!Schema::hasColumn('orders', 'tickets_generated')) {
                $table->boolean('tickets_generated')->default(false)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'tickets_generated_at')) {
                $table->timestamp('tickets_generated_at')->nullable()->after('tickets_generated');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('payment_status');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya drop jika ada
            if (Schema::hasColumn('orders', 'tickets_generated')) {
                $table->dropColumn('tickets_generated');
            }
            if (Schema::hasColumn('orders', 'tickets_generated_at')) {
                $table->dropColumn('tickets_generated_at');
            }
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};