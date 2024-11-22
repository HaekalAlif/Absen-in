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
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('tahun')->nullable(); // Kolom tahun ajaran
        });
    }

    /**
     * Membatalkan perubahan yang telah dilakukan.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
