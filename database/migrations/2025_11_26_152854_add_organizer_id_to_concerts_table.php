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
            if (!Schema::hasColumn('concerts', 'organizer_id')) {
                $table->unsignedBigInteger('organizer_id')->nullable()->after('date');
            }
            $table->foreign('organizer_id')->references('id')->on('organizers')->onDelete('cascade');
        });
    }

    public function down()
    {
       Schema::table('concerts', function (Blueprint $table) {
    $table->dropForeign(['organizer_id']);
});

    }
};
