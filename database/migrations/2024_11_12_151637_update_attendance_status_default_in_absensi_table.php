<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Mengubah nilai default kolom attendance_status menjadi 'dibuka'
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('attendance_status')->default('dibuka')->change();
        });
    }

    public function down()
    {
        // Jika migrasi dibatalkan, kembalikan nilai default ke 'pending'
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('attendance_status')->default('pending')->change();
        });
    }
};
