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
            $table->string('name'); // Nama subject
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade'); // Foreign key untuk kelas
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key untuk dosen
            $table->integer('semester')->default(1); // Semester (1-6)
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
