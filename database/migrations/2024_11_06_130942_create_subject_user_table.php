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
        Schema::create('subject_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Referensi ke User
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Referensi ke Subject
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_user');
    }

};
