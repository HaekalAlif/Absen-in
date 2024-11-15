<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Nama mata kuliah
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade'); // Relasi ke classrooms
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users (dosen)
            $table->integer('semester')->default(1); // Semester, default 1
            $table->timestamps(); // Timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
