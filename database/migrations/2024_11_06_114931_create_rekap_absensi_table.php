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
       Schema::create('rekap_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Relasi ke user
            $table->foreignId('classroom_id')->constrained(); // Relasi ke classroom
            $table->foreignId('subject_id')->constrained(); // Relasi ke subject
            $table->date('date');
            $table->string('attendance_status')->default('alpha'); // Status kehadiran defaultnya 'alpha'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_absensi');
    }
};
