<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RoleManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @testdox Kasus Positif: Admin dapat mengakses dashboard admin
     */
    public function test_admin_can_access_admin_dashboard()
    {
        // Membuat user dengan peran 'admin'
        $admin = User::factory()->create(['role' => 0]);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard'); // Akses halaman admin
        
        $response->assertStatus(200); // Pastikan akses berhasil
    }

    /**
     * @testdox Kasus Positif: Dosen dapat mengakses dashboard dosen
     */
    public function test_dosen_can_access_dosen_dashboard()
    {
        // Membuat user dengan peran 'dosen'
        $dosen = User::factory()->create(['role' => 1]);

        $response = $this->actingAs($dosen)->get('/dosen/dashboard'); // Akses halaman dosen
        
        $response->assertStatus(200); // Pastikan akses berhasil
    }

    /**
     * @testdox Kasus Positif: Mahasiswa dapat mengakses dashboard mahasiswa
     */
    public function test_mahasiswa_can_access_mahasiswa_dashboard()
    {
        // Membuat user dengan peran 'mahasiswa'
        $mahasiswa = User::factory()->create(['role' => 2]);

        $response = $this->actingAs($mahasiswa)->get('/mahasiswa/dashboard'); // Akses halaman mahasiswa
        
        $response->assertStatus(200); // Pastikan akses berhasil
    }

    /**
     * @testdox Kasus Negatif: Pengguna yang belum login diarahkan ke halaman login
     */
    public function test_guest_is_redirected_to_login()
    {
        // Uji jika pengguna tidak login
        $response = $this->get('/admin/dashboard'); 
        
        $response->assertRedirect(route('login')); 
    }

    /**
     * @testdox Kasus Negatif: Dosen tidak dapat mengakses dashboard admin
     */
    public function test_dosen_cannot_access_admin_dashboard()
    {
        // Membuat user dengan peran 'dosen'
        $dosen = User::factory()->create(['role' => 1]);

        $response = $this->actingAs($dosen)->get('/admin/dashboard'); 
        
        $response->assertRedirect(route('dosen')); 
    }

    /**
     * @testdox Kasus Negatif: Mahasiswa tidak dapat mengakses dashboard dosen
     */
    public function test_mahasiswa_cannot_access_dosen_dashboard()
    {
        // Membuat user dengan peran 'mahasiswa'
        $mahasiswa = User::factory()->create(['role' => 2]);

        $response = $this->actingAs($mahasiswa)->get('/dosen/dashboard');
        
        $response->assertRedirect(route('mahasiswa')); 
    }

    /**
     * @testdox Kasus Negatif: Login gagal jika kredensial kosong
     */
    public function test_login_fails_with_empty_credentials()
    {
        // Mencoba login tanpa kredensial
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['email', 'password']); 
    }

    /**
     * @testdox Kasus Negatif: Login gagal jika kredensial tidak valid
     */
    public function test_login_fails_with_invalid_credentials()
    {
        // Mencoba login dengan kredensial yang salah
        $response = $this->post(route('login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');
    }
}
