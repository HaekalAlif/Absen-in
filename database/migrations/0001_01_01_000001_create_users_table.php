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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Nama user
            $table->string('email')->unique(); // Email unik
            $table->tinyInteger('role')->default(2); // Peran user, default 2 (misalnya mahasiswa)
            $table->timestamp('email_verified_at')->nullable(); // Verifikasi email
            $table->string('password'); // Password
            $table->foreignId('class_id')->nullable()->constrained('classrooms')->onDelete('set null'); // Relasi ke classrooms
            $table->string('batch_year')->nullable(); // Tahun angkatan
            $table->string('status')->default('active'); // Status user, default aktif
            $table->string('qr_code')->nullable(); // Path QR code
            $table->rememberToken(); // Token remember
            $table->timestamps(); // Timestamps
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email untuk reset
            $table->string('token'); // Token reset
            $table->timestamp('created_at')->nullable(); // Timestamp dibuat
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID sesi
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Relasi ke users
            $table->string('ip_address', 45)->nullable(); // Alamat IP
            $table->text('user_agent')->nullable(); // User agent
            $table->longText('payload'); // Payload data
            $table->integer('last_activity')->index(); // Aktivitas terakhir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
