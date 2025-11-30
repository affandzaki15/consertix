<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
