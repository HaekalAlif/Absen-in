<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat pengguna dengan role admin
        $this->admin = User::factory()->create(['role' => 0]);
    }

    /**
 * @testdox Kasus Positif: Admin berhasil menambah mahasiswa baru dan QR code dihasilkan
 */
public function test_admin_can_create_student_and_generate_qr_code()
{
    $classroom = Classroom::factory()->create();

    // Membuat mahasiswa baru dan memeriksa QR code
    $response = $this->actingAs($this->admin)->post(route('user.store'), [
        'name' => 'Student QR Code Test',
        'email' => 'student@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 2, 
        'class_id' => $classroom->id,
    ]);

    $response->assertRedirect(route('user.manage'));

    // Pastikan mahasiswa baru telah disimpan di database
    $this->assertDatabaseHas('users', ['email' => 'student@example.com']);

    // Ambil mahasiswa baru dari database
    $student = User::where('email', 'student@example.com')->first();

    // Pastikan QR code telah dibuat dan tersimpan di path yang benar
    $this->assertNotNull($student->qr_code);
    $this->assertFileExists(public_path($student->qr_code)); // Memastikan file QR code ada
}


    /**
     * @testdox Kasus Positif: Admin berhasil memperbarui data user
     */
    public function test_admin_can_update_user()
    {
        $classroom = Classroom::factory()->create();
        $user = User::factory()->create(['role' => 2, 'class_id' => $classroom->id]);

        $response = $this->actingAs($this->admin)->put(route('user.update', $user->id), [
            'name' => 'Jane Doe',
            'email' => $user->email,
            'role' => 1, // dosen
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'class_id' => $classroom->id,
        ]);

        $response->assertRedirect(route('user.manage'));
        $this->assertDatabaseHas('users', ['name' => 'Jane Doe', 'role' => 1]);
    }

    /**
     * @testdox Kasus Positif: Admin berhasil menghapus user dari sistem
     */
    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('user.destroy', $user->id));

        $response->assertRedirect(route('user.manage'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * @testdox Kasus Negatif: Admin gagal menambah user dengan field kosong atau tidak valid
     */
    public function test_admin_cannot_create_user_with_invalid_data()
    {
        $response = $this->actingAs($this->admin)->post(route('user.store'), [
            'name' => '', // nama kosong
            'email' => 'not-an-email', // email tidak valid
            'password' => 'short', // password kurang dari 8 karakter
            'password_confirmation' => 'notmatch', // konfirmasi tidak sesuai
            'role' => 3, // peran tidak valid
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

     /**
     * @testdox Kasus Negatif: Admin gagal mengubah data user yang tidak ada
     */
    public function test_admin_cannot_update_nonexistent_user()
    {
        $response = $this->actingAs($this->admin)->put(route('user.update', 9999), [
            'name' => 'Nonexistent User',
            'email' => 'nonexistent@example.com',
            'role' => 2,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertNotFound(); // Pastikan respon 404 jika user tidak ada
    }
}
