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
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->string('qr_code')->nullable();  // Menambahkan kolom qr_code
        });
    }

    public function down()
    {
        Schema::table('rekap_absensi', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });
    }

};
