<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Helpers\ValidationHelper;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Log;

class ArticleController extends Controller
{
    // Get all articles
    public function getArticles(Request $request)
    {
        $limit = $request->query('limit', 10);
        $articles = Article::paginate($limit);
        return response()->json($articles);
    }

    // Get article by id
    public function getArticleById($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }
        return response()->json($article);
    }

    // Create article
    public function createArticle(Request $request)
    {
        try {
            $validator = ValidationHelper::article($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            if ($request->hasFile('file')) {
                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/article',
                ]);

                $data['image_url'] = $uploaded['secure_url'];
                $data['public_id'] = $uploaded['public_id'];
            }

            $user = $request->get('user');

            $article = Article::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'writer' => $data['writer'],
                'editor_id' => $user->id,
                'thumbnail' => $data['image_url'],
                'public_id' => $data['public_id'],
            ]);

            return response()->json($article);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat membuat artikel'], 500);
        }
    }

    // Update article
    public function updateArticle(Request $request, $id)
    {
        try {
            $validator = ValidationHelper::article($request->all());

            if ($validator->fails()) {
                return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 400);
            }

            $data = $validator->validated();

            $article = Article::find($id);

            if (!$article) {
                return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
            }
            
            $imageUrl = $article->thumbnail;
            $publicId = $article->public_id;


            if ($request->hasFile('file')) {
                if ($publicId) {
                    Cloudinary::uploadApi()->destroy($publicId);
                }

                $uploaded = Cloudinary::uploadApi()->upload($request->file('file')->getRealPath(), [
                    'folder' => 'images/article',
                ]);

                $imageUrl = $uploaded['secure_url'];
                $publicId = $uploaded['public_id'];
            }

            $user = $request->get('user');

            Log::info('User ID: ' . $user->id);

            $article->update([
                'title' => $data['title'],
                'content' => $data['content'],
                'writer' => $data['writer'],
                'editor_id' => $user->id,
                'thumbnail' => $imageUrl,
                'public_id' => $publicId,
            ]);

            return response()->json(['message' => 'Artikel berhasil diperbarui', 'data' => $article]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui artikel'], 500);
        }
    }

    // Delete article
    public function deleteArticle($id)
    {
        try {
            $article = Article::find($id);
            if (!$article) {
                return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
            }

            if ($article->public_id) {
                Cloudinary::uploadApi()->destroy($article->public_id);
            }

            $article->delete();
            return response()->json(['message' => 'Artikel berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }
}
