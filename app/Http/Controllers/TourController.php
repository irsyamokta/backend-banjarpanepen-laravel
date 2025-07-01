<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TourController extends Controller
{
    // Get all tours
    public function getTours(Request $request)
    {
        $limit = $request->query('limit', 10);
        $tours = Tour::paginate($limit);
        return response()->json($tours);
    }

    // Get tour by id
    public function getTourById($id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour tidak ditemukan'], 404);
        }
        return response()->json($tour);
    }

    // Create tour
    public function createTour(Request $request)
    {
        try {
            $validator = ValidationHelper::tour($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/tour',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $tour = Tour::create([
            'title' => $data['title'],
            'about' => $data['about'],
            'operational' => $data['operational'],
            'location' => $data['location'],
            'start' => $data['start'],
            'end' => $data['end'],
            'facility' => $data['facility'],
            'maps' => $data['maps'],
            'price' => intval($data['price']),
            'thumbnail' => $data['image_url'],
            'public_id' => $data['public_id']
        ]);

            return response()->json(['message' => 'Tour berhasil dibuat', 'data' => $tour]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Update tour
    public function updateTour(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::tour($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $tour = Tour::find($id);

            $imageUrl = $tour->thumbnail;
            $publicId = $tour->public_id;

            if (!$tour) {
                return response()->json(['message' => 'Tour tidak ditemukan'], 404);
            }

            if ($request->hasFile('file')) {
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/tour',
                ]);

                $imageUrl = $uploaded['secure_url'];
                $publicId = $uploaded['public_id'];
            }

            $tour->update([
                'title' => $data['title'],
                'about' => $data['about'],
                'operational' => $data['operational'],
                'location' => $data['location'],
                'start' => $data['start'],
                'end' => $data['end'],
                'facility' => $data['facility'],
                'maps' => $data['maps'],
                'price' => intval($data['price']),
                'thumbnail' => $imageUrl,
                'public_id' => $publicId
            ]);

            return response()->json(['message' => 'Tour berhasil diperbarui', 'data' => $tour]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }

    // Delete tour
    public function deleteTour($id)
    {
        try {
            $tour = Tour::find($id);

            if (!$tour) {
                return response()->json(['message' => 'Tour tidak ditemukan'], 404);
            }

            if ($tour->public_id) {
                Cloudinary::uploadApi()->destroy($tour->public_id);
            }

            $tour->delete();

            return response()->json(['message' => 'Tour berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus tour'], 500);
        }
    }
}
