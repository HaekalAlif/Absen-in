<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained(); // Mengacu pada tabel classrooms
            $table->foreignId("subject_id")->constrained();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('attendance_status')->default('pending'); // Status kehadiran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi');
    }
};
