<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class GalleryController extends Controller
{
    // Get all galleries
    public function getGalleries (Request $request)
    {
        $limit = $request->query('limit', 10);
        $gallery = Gallery::paginate($limit);
        return response()->json($gallery);
    }

    // Get gallery by id
    public function getGalleryById($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
        }
        return response()->json($gallery);
    }

    // Create gallery
    public function createGallery(Request $request)
    {
        try {
            $validator = ValidationHelper::gallery($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/gallery',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $gallery = Gallery::create([
                'title' => $data['title'],
                'caption' => $data['caption'],
                'image' => $data['image_url'],
                'public_id' => $data['public_id'],
            ]);

            return response()->json($gallery);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat membuat gallery'], 500);
        }
    }

    // Update gallery
    public function updateGallery(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::gallery($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $gallery = Gallery::find($id);
            if (!$gallery) {
                return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
            }

            $imageUrl = $gallery->image;
            $publicId = $gallery->public_id;

            if ($request->hasFile('file')) {
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/gallery',
                ]);

                $imageUrl= $uploaded['secure_url'];
                $publicId = $uploaded['public_id'];
            }

            $gallery->update([
                'title' => $data['title'],
                'caption' => $data['caption'],
                'image' => $imageUrl,
                'public_id' => $publicId,
            ]);

            return response()->json(['message' => 'Gallery berhasil diperbarui', 'data' => $gallery]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui gallery'], 500);
        }
    }

    // Delete gallery
    public function deleteGallery($id)
    {
        try {
            $gallery = Gallery::find($id);
            if (!$gallery) {
                return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
            }
            $publicId = $gallery->public_id;
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
            $gallery->delete();
            return response()->json(['message' => 'Gallery berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus gallery'], 500);
        }
    }
}
