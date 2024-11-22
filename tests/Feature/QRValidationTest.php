<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QRValidationTest extends TestCase
{
    /**
     * @testdox Kasus Positif: QR Code valid sesuai dengan email pengguna yang sedang login
     */
    public function testQrValidationSesuaiDenganEmailPengguna()
    {
        $user = User::factory()->create(); 
        $this->actingAs($user); 

        // Membuat data QR code yang valid (sesuai dengan email user yang login)
        $qrCode = $user->email;

        $response = $this->postJson(route('absensi.qr.validate'), [
            'qr_code' => $qrCode,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'QR Code valid'
                ]);
    }

    /**
     * @testdox Kasus Negatif: QR Code tidak valid untuk pengguna yang berbeda
     */
    public function testQrValidationTidakValidUntukPengguna()
    {
        $user = User::factory()->create(); 
        $this->actingAs($user); 

        // Membuat QR code yang tidak valid (tidak sama dengan email user)
        $qrCode = 'wrong-email@example.com';

        $response = $this->postJson(route('absensi.qr.validate'), [
            'qr_code' => $qrCode,
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'error' => 'QR Code tidak valid untuk pengguna ini'
                ]);
    }

    /**
     * @testdox Kasus Negatif: QR Code dengan format yang tidak valid
     */
    public function testQrValidationFormatTidakValid()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Mengirim QR Code dengan format yang salah (misalnya bukan email)
        $qrCode = 'invalid-format';

        $response = $this->postJson(route('absensi.qr.validate'), [
            'qr_code' => $qrCode,
        ]);

        // Memastikan status dan response sesuai error yang diharapkan
        $response->assertStatus(400)
                ->assertJson([
                    'error' => 'Format QR Code tidak valid'
                ]);
    }
}
