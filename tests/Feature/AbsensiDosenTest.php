<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiDosenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Pengujian agar dosen bisa melihat absensi.
     *
     * @return void
     */
    public function testDosenCanViewAbsensi()
    {
        // Menyiapkan data pengguna (Dosen)
        $dosen = User::create([
            'name' => 'Dosen Test',
            'email' => 'dosen@test.com',
            'password' => bcrypt('password'),
            'role' => 1,  // Pastikan role dosen
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login sebagai dosen
        $this->actingAs($dosen);

        // Menyiapkan data absensi
        $absensi = Absensi::create([
            'user_id' => $dosen->id,
            'status' => 'hadir',
            'tanggal' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengirimkan request untuk melihat absensi
        $response = $this->get(route('absensi.index'));

        // Memastikan response berhasil dan data absensi ada
        $response->assertStatus(200);
        $response->assertSee($absensi->status);
    }

    /**
     * Pengujian agar dosen bisa membuat absensi.
     *
     * @return void
     */
    public function testDosenCanCreateAbsensi()
    {
        // Menyiapkan data pengguna (Dosen)
        $dosen = User::create([
            'name' => 'Dosen Create',
            'email' => 'dosencreate@test.com',
            'password' => bcrypt('password'),
            'role' => 1, // Role dosen
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login sebagai dosen
        $this->actingAs($dosen);

        // Menyiapkan data absensi baru
        $dataAbsensi = [
            'user_id' => $dosen->id,
            'status' => 'hadir',
            'tanggal' => now(),
        ];

        // Mengirimkan request untuk membuat absensi
        $response = $this->post(route('absensi.store'), $dataAbsensi);

        // Memastikan absensi berhasil dibuat dan database terupdate
        $this->assertDatabaseHas('absensis', $dataAbsensi);

        // Memastikan response mengarah ke halaman yang tepat
        $response->assertRedirect(route('absensi.index'));
    }

    /**
     * Pengujian agar dosen bisa memperbarui absensi.
     *
     * @return void
     */
    public function testDosenCanUpdateAbsensi()
    {
        // Menyiapkan data pengguna (Dosen)
        $dosen = User::create([
            'name' => 'Dosen Update',
            'email' => 'dosenupdate@test.com',
            'password' => bcrypt('password'),
            'role' => 1, // Role dosen
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login sebagai dosen
        $this->actingAs($dosen);

        // Menyiapkan data absensi yang sudah ada
        $absensi = Absensi::create([
            'user_id' => $dosen->id,
            'status' => 'hadir',
            'tanggal' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Menyiapkan data untuk memperbarui absensi
        $dataUpdate = [
            'status' => 'izin',
        ];

        // Mengirimkan request untuk memperbarui absensi
        $response = $this->put(route('absensi.update', $absensi->id), $dataUpdate);

        // Memastikan absensi berhasil diperbarui di database
        $this->assertDatabaseHas('absensis', [
            'id' => $absensi->id,
            'status' => 'izin',
        ]);

        // Memastikan response mengarah ke halaman yang tepat
        $response->assertRedirect(route('absensi.index'));
    }
}
