<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->unsignedBigInteger('absen_id')->after('id')->nullable();

            // Set up foreign key constraint if needed
            $table->foreign('absen_id')->references('id')->on('absensi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->dropForeign(['absen_id']);
            $table->dropColumn('absen_id');
        });
    }
};
