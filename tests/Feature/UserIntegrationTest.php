<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class UserIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_user_with_qr_code()
    {
        // Membuat pengguna untuk mengotentikasi
        $user = User::factory()->create();
        
        // Jika menggunakan token API, pastikan token tersedia
        $token = $user->createToken('TestToken')->plainTextToken;

        // Data pengguna baru yang akan dibuat
        $userData = [
            'name' => 'Mr. Lindsey Bartell Sr.',
            'email' => 'admin_' . Str::random(10) . '@example.com', // Email unik
            'password' => 'password123',
            'role' => 0,  // Role misalnya untuk admin
            'class_id' => 1, // ID kelas yang digunakan dalam sistem
        ];

        // Mengirimkan permintaan POST untuk membuat pengguna dengan otentikasi Bearer Token
        $response = $this->postJson(route('user.store'), $userData, [
            'Authorization' => 'Bearer ' . $token,  // Menambahkan token pada header
        ]);

        // Memastikan respons yang diterima adalah status 201 (Created)
        $response->assertStatus(201);

        // Memastikan bahwa QR Code terbuat setelah pengguna dibuat
        $response->assertJsonStructure([
            'data' => [
                'qr_code',  // Pastikan QR Code ada dalam respons
            ]
        ]);
    }
}
