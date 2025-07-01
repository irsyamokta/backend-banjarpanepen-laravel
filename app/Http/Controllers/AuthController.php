<?php

namespace App\Http\Controllers;

use App\Helpers\JwtHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\ValidationHelper;
use App\Helpers\CookieHelper;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        try {
            $validator = ValidationHelper::register($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $verificationToken = Str::random(64);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'verification_token' => $verificationToken,
            ]);

            return response()->json(['message' => 'Akun berhasil dibuat. Silakan verifikasi email Anda']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan' . $e->getMessage()], 500);
        }
    }

    // Login
    public function login(Request $request)
    {
        try {
            $validator = ValidationHelper::login($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Kredensial tidak valid'], 401);
            }

            if (!$user->is_verified) {
                return response()->json(['message' => 'Email belum diverifikasi'], 401);
            }

            $payload = [
                'userId' => $user->id,
                'exp' => now()->addDays(7)->timestamp,
            ];

            $refreshToken = JwtHelper::generate($payload, env('REFRESH_TOKEN_SECRET'));

            return response()
                ->json([
                    'message' => 'Login berhasil',
                    'data' => [
                        'id' => $user->id,
                        'role' => $user->role,
                    ],
                ])
                ->withCookie(CookieHelper::makeRefreshToken($refreshToken));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan' . $e->getMessage()], 500);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        return response()
            ->json(['message' => 'Berhasil logout'])
            ->withCookie(CookieHelper::forgetRefreshToken());
    }

    // Me
    public function me(Request $request)
    {
        try {
            $user = $request->get('user');

            if (!$user) {
                return response()->json(['message' => 'Tidak ada token yang diberikan'], 401);
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'image_url' => $user->image_url,
                'instagram' => $user->instagram,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan' . $e->getMessage()], 500);
        }
    }
}
