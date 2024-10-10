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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama subject
            $table->foreignId('classroom_id')->constrained('classrooms'); // Relasi ke tabel classrooms
            $table->foreignId('user_id')->constrained('users'); // Relasi ke tabel users (dosen)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
