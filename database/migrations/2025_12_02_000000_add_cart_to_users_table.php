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
        if (! Schema::hasColumn('users', 'cart')) {
            Schema::table('users', function (Blueprint $table) {
                // Add JSON cart column; don't rely on `after()` because some
                // installations may not have `remember_token` column.
                $table->json('cart')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'cart')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('cart');
            });
        }
    }
};
