<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtTokenMiddleware
{
    // JwtTokenMiddleware.php

    public function handle($request, Closure $next)
    {
        // Mengambil token dari sesi
        $token = session('jwt_token');

        if (!$token) {
            return response()->json(['error' => 'Token tidak ditemukan'], 401);
        }

        try {
            // Verifikasi token
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Token tidak valid'], 401);
            }

            return $next($request);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        }
    }

}
