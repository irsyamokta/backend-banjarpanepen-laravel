<?php

namespace App\Http\Controllers;

use App\Models\SelectOption;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;

class SettingController extends Controller
{
    // Get all select options
    public function getSettings(Request $request)
    {
        $selectOptions = SelectOption::all();
        return response()->json($selectOptions);
    }

    // Get select option by id
    public function getSettingById($id)
    {
        $selectOption = SelectOption::find($id);
        if (!$selectOption) {
            return response()->json(['message' => 'Select option tidak ditemukan'], 404);
        }
        return response()->json($selectOption);
    }

    // Create select option
    public function createSetting(Request $request)
    {
        try {
            $validator = ValidationHelper::setting($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $selectOption = SelectOption::where('name', $data['name'])->first();
            if ($selectOption) {
                return response()->json(['message' => 'Select option sudah ada'], 400);
            }

            $selectOption = SelectOption::create([
                'name' => $data['name'],
                'category' => $data['category'],
            ]);

            return response()->json($selectOption);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat membuat select option'], 500);
        }
    }

    // Update select option
    public function updateSetting(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::setting($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $selectOption = SelectOption::find($id);
            if (!$selectOption) {
                return response()->json(['message' => 'Select option tidak ditemukan'], 404);
            }

            $ExistingSelectOption = SelectOption::where('name', $data['name'])->where('id', '!=', $id)->first();
            if ($ExistingSelectOption) {
                return response()->json(['message' => 'Select option sudah ada'], 400);
            }

            $selectOption->update([
                'name' => $data['name'],
                'category' => $data['category'],
            ]);

            return response()->json(['message' => 'Select option berhasil diperbarui', 'data' => $selectOption]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui select option'], 500);
        }
    }

    // Delete select option
    public function deleteSetting($id)
    {
        try {
            $selectOption = SelectOption::find($id);
            if (!$selectOption) {
                return response()->json(['message' => 'Select option tidak ditemukan'], 404);
            }
            $selectOption->delete();
            return response()->json(['message' => 'Select option berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus select option'], 500);
        }
    }
}
