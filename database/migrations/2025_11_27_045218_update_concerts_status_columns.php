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
        Schema::table('concerts', function (Blueprint $table) {

            // Rename column status -> selling_status
            if (Schema::hasColumn('concerts', 'status')) {
                $table->renameColumn('status', 'selling_status');
            }

            // Approval status new
            if (!Schema::hasColumn('concerts', 'approval_status')) {
                $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])
                    ->default('draft')
                    ->after('selling_status');
            }

            // Notes if rejected
            if (!Schema::hasColumn('concerts', 'notes')) {
                $table->text('notes')->nullable()->after('approval_status');
            }
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concerts', function (Blueprint $table) {
            //
        });
    }
};
