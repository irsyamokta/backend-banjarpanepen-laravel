<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use App\Helpers\JwtHelper;
use App\Models\User;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        \Log::info('refreshToken dari cookie:', [$request->cookie('refreshToken')]);

        $refreshToken = $request->cookie('refreshToken');

        if (!$refreshToken) {
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Tidak ada refresh token di cookie',
            ], 401);
        }

        try {
            $decoded = JwtHelper::decode($refreshToken, env('REFRESH_TOKEN_SECRET'));
            \Log::info('Decoded payload:', (array) $decoded);
            $userId = $decoded->userId ?? null;

            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'status' => 'forbidden',
                    'message' => 'User tidak ditemukan atau tidak valid',
                ], 403);
            }

            // Role check
            if (count($roles) && !in_array($user->role, $roles)) {
                return response()->json([
                    'status' => 'forbidden',
                    'message' => 'Akses ditolak. Peran tidak sesuai.',
                ], 403);
            }

            $request->merge(['user' => $user]);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Refresh Token Verification Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'forbidden',
                'message' => 'Refresh token tidak valid',
            ], 403);
        }
    }
}
