<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Helpers\ValidationHelper;
use App\Models\User;

class UserController extends Controller
{
    // Get all users
    public function getUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    // Get user by contact
    public function getUserByContact()
    {
        $users = User::select('id', 'name', 'phone', 'instagram')->get();
        return response()->json(['user' => $users]);
    }

    // Update user
    public function updateUser(Request $request)
    {
        try {
            $user = $request->get('user');
            $validator = ValidationHelper::updateUser($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                if ($user->public_id) {
                    Cloudinary::uploadApi()->destroy($user->public_id);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/profile',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $message = 'Profil berhasil diperbarui';

            if ($data['email'] !== $user->email) {
                if (User::where('email', $data['email'])->exists()) {
                    return response()->json(['message' => 'Email sudah terdaftar'], 400);
                }

                $data['is_verified'] = false;
                $data['verification_token'] = Str::random(64);
                $message = 'Email verifikasi telah dikirim';
            }

            $user->update($data);

            return response()->json(['message' => $message, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui profil'], 500);
        }
    }

    // Delete user
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Akun tidak ditemukan'], 404);
            }

            if ($user->role === 'ADMIN') {
                return response()->json(['message' => 'Tidak dapat menghapus akun admin'], 403);
            }

            if ($user->public_id) {
                Cloudinary::uploadApi()->destroy($user->public_id);
            }

            $user->delete();

            return response()->json(['message' => 'Akun berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus akun'], 500);
        }
    }
}
