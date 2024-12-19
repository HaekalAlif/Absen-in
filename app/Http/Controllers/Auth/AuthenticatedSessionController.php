<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // AuthenticatedSessionController.php

    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentikasi dan menghasilkan token JWT
        $request->authenticate();

        // Ambil token yang dihasilkan
        $token = $request->get('token');

        // Pastikan token berhasil dibuat
        if (empty($token)) {
            return response()->json(['error' => 'Token tidak ditemukan'], 400);
        }

        // Simpan token JWT dalam sesi
        session(['jwt_token' => $token]);

        // Regenerasi sesi untuk keamanan
        $request->session()->regenerate();

        // Dapatkan peran pengguna
        $authUserRole = Auth::user()->role;

        // Redirect berdasarkan peran pengguna
        if ($authUserRole == 0) {
            return redirect()->intended(route('admin', absolute: false));
        } elseif ($authUserRole == 1) {
            return redirect()->intended(route('dosen', absolute: false));
        } else {
            return redirect()->intended(route('mahasiswa', absolute: false));
        }
    }



    /**
     * Destroy an authenticated session.
     */
    // AuthenticatedSessionController.php

    public function destroy(Request $request): RedirectResponse
    {
        // Logout pengguna dan hapus sesi
        Auth::guard('web')->logout();

        // Hapus token dari sesi
        $request->session()->forget('jwt_token');

        // Hapus dan regenerasi token sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
