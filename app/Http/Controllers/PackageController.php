<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PackageController extends Controller
{
    // Get all packages
    public function getPackages(Request $request)
    {
        $limit = $request->query('limit', 10);
        $packages = TourPackage::paginate($limit);
        return response()->json($packages);
    }

    // Get package by id
    public function getPackageById($id)
    {
        $package = TourPackage::find($id);
        if (!$package) {
            return response()->json(['message' => 'Package tidak ditemukan'], 404);
        }
        return response()->json($package);
    }

    // Create package
    public function createPackage(Request $request)
    {
        try {
            $validator = ValidationHelper::package($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/package',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $package = TourPackage::create([
                'title' => $data['title'],
                'price' => intval($data['price']),
                'benefit' => $data['benefit'],
                'thumbnail' => $data['image_url'],
                'public_id' => $data['public_id'],
            ]);

            return response()->json($package);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat membuat package'], 500);
        }
    }

    // Update package
    public function updatePackage(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::package($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $package = TourPackage::find($id);

            if (!$package) {
                return response()->json(['message' => 'Package tidak ditemukan'], 404);
            }

            $imageUrl = $package->thumbnail;
            $publicId = $package->public_id;

            if ($request->hasFile('file')) {
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/package',
                ]);

                $imageUrl = $uploaded['secure_url'];
                $publicId = $uploaded['public_id'];
            }

            $package->update([
                'title' => $data['title'],
                'price' => intval($data['price']),
                'benefit' => $data['benefit'],
                'thumbnail' => $imageUrl,
                'public_id' => $publicId,
            ]);

            return response()->json(['message' => 'Package berhasil diperbarui', 'data' => $package]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui package'], 500);
        }
    }

    // Delete package
    public function deletePackage($id)
    {
        try {
            $package = TourPackage::find($id);
            if (!$package) {
                return response()->json(['message' => 'Package tidak ditemukan'], 404);
            }
            $publicId = $package->public_id;
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
            $package->delete();
            return response()->json(['message' => 'Package berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus package'], 500);
        }
    }
}
