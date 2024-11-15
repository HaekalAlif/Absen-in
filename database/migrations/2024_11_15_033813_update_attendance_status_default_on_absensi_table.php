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
        // Ubah default value kolom 'attendance_status' menjadi 'open'
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('attendance_status')->default('open')->change();
        });
    }

    public function down()
    {
        // Kembalikan default value kolom 'attendance_status' ke 'pending'
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('attendance_status')->default('pending')->change();
        });
    }
};
