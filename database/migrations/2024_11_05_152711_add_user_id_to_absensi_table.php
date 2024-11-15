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
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->after('end_time'); // Atau sesuai urutan yang Anda inginkan
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Hapus constraint
            $table->dropColumn('user_id'); // Hapus kolom
        });
    }
};
