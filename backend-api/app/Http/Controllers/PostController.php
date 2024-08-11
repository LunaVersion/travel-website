<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with('category')->get();
            return response()->json($posts);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }  
    }

    public function show($id)
    {
        try {
            $post = Post::with('category')->find($id);
            if (!$post) {
                return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
            }
            return response()->json($post);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        } 
    }

    // Thêm bài viết 
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts',
                'content' => 'required|string',
                'status' => 'required|tinyInteger',
                'image' => 'nullable|string',
                'draft' => 'required|json',
            ]);
    
            $post = Post::create([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'status' => $request->status,
                'image' => $request->image,
            ]);
    
            return response()->json($post, 201);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        } 
        
    }

    // Thêm bài viết nháp mới 
    public function storeDraft(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts',
                'content' => 'required|string',
                'status' => 'required|tinyInteger',
                'image' => 'nullable|string',
                'draft' => 'required|json',
            ]);
    
            $draftData = [
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'status' => 0, 
                'image' => $request->image,
            ];

            if ($request->has('id')) {
                // Cập nhật nháp từ bài viết chính hiện có
                $post = Post::findOrFail($request->id);
                $post->draft = json_encode($draftData);
                $post->save();
            } else {
                // Tạo nháp mới
                $post = Post::create([
                    'draft' => json_encode($draftData),
                ]);
            }
    
            return response()->json($post, 201);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        } 
        
    }

    // Cập nhật bài viết + lưu thành bản nháp
    // public function updatePostToDraft(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'category_id' => 'required|exists:categories,id',
    //             'title' => 'required|string|max:255',
    //             'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
    //             'content' => 'nullable|string',
    //             'status' => 'required|tinyInteger',
    //             'image' => 'nullable|string',
    //             'draft' => 'required|json',
    //         ]);
    
    //         $post = Post::findOrFail($id);
    
    //         $draft = [
    //             'category_id' => $request->category_id,
    //             'title' => $request->title,
    //             'slug' => $request->slug,
    //             'content' => $request->content,
    //             'status' => 0, 
    //             'image' => $request->image, 
    //         ];
    
    //         $post->draft = json_encode($draft);
    
    //         $post->save();
    
    //         return response()->json($post, 200);
    //     } catch (\Exception $exception) {
    //         \Log::error('Login error: ' . $exception->getMessage());
    //         return response()->json(['error' => 'Có lỗi xảy ra'], 500);
    //     } 
        
    // }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
                'content' => 'nullable|string',
                'status' => 'required|integer',
                'image' => 'nullable|string',
                'draft' => 'nullable|json',
            ]);
    
            $post = Post::find($id);
            if (!$post) {
                return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
            }
            
            $post->update([
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'status' => $request->status,
                'image' => $request->image,
                'draft' => null, 
            ]);
        
            return response()->json($post);
        } catch (\Exception $exception) {
            \Log::error('Login error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }  
        
    }

    public function updateDraft(Request $request, $id)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
                'content' => 'nullable|string',
                'status' => 'required|integer',
                'image' => 'nullable|string',
            ]);

            $post = Post::find($id);
            if (!$post) {
                return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
            }

            $post->update([
                'draft' => json_encode([
                    'category_id' => $request->category_id,
                    'title' => $request->title,
                    'slug' => Str::slug($request->title),
                    'content' => $request->content,
                    'status' => 0, 
                    'image' => $request->image,
                ]),
            ]);

            return response()->json($post);
        } catch (\Exception $exception) {
            \Log::error('Draft update error: ' . $exception->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $post = Post::find($id);
        
            if (!$post) {
                return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
            }

            $post->delete();
            
            return response()->json(['message' => 'Bài viết đã được xóa thành công']);

        } catch (\Exception $exception) {
            return $this->errorResponse(null, 'Data not found.', 404);
        }
        
    }
}
