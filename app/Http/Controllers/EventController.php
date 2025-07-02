<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EventController extends Controller
{
    // Get all events
    public function getEvents(Request $request)
    {
        $limit = $request->query('limit', 10);
        $events = Event::paginate($limit);
        return response()->json($events);
    }

    // Get event by id
    public function getEventById($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }
        return response()->json($event);
    }

    // Create event
    public function createEvent(Request $request)
    {
        try {
            $validator = ValidationHelper::event($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/event',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $event = Event::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'date' => $data['date'],
                'time' => $data['time'],
                'place' => $data['place'],
                'price' => intval($data['price']),
                'thumbnail' => $data['image_url'],
                'public_id' => $data['public_id'],
            ]);

            return response()->json($event);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan' . $e->getMessage()], 500);
        }
    }

    // Update event
    public function updateEvent(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::event($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $event = Event::find($id);

            $imageUrl = $event->thumbnail;
            $publicId = $event->public_id;

            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], 404);
            }

            if ($request->hasFile('file')) {
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/event',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $event->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'date' => $data['date'],
                'time' => $data['time'],
                'place' => $data['place'],
                'price' => intval($data['price']),
                'thumbnail' => $data['image_url'],
                'public_id' => $data['public_id'],
            ]);

            return response()->json(['message' => 'Event berhasil diperbarui', 'data' => $event]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui event'], 500);
        }
    }

    // Delete event
    public function deleteEvent($id)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], 404);
            }
            $publicId = $event->public_id;
            if ($publicId) {
                Cloudinary::uploadApi()->destroy($publicId);
            }
            $event->delete();
            return response()->json(['message' => 'Event berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus event'], 500);
        }
    }
}
