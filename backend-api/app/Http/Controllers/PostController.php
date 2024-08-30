<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;
use App\Repositories\PostRepositoryEloquent; // Import đúng namespace


class PostController extends Controller
{
    protected $postRepository;
    protected $postService; 

    public function __construct(PostRepositoryEloquent $postRepository, PostService $postService)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }

    public function index()
    {
        return $this->postService->getAllPosts();
    }

    public function show($id)
    {
        // try {
        //     $post = $this->postService->getPostById($id);
        //     if (!$post) {
        //         return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
        //     }
        //     return response()->json($post);
        // } catch (\Exception $exception) {
        //     \Log::error('Error fetching post: ' . $exception->getMessage());
        //     return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        // }
        try {
            $post = $this->postService->getPostById($id);
            if (!$post) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Post not found'
                ]);
            }
            return response()->json([
                'status_code' => 200,
                'data' => $post
            ]);
        } catch (\Exception $exception) {
            \Log::error('Error fetching post: ' . $exception->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred while fetching the post: ' . $exception->getMessage()
            ]);
        }  
    }

    public function store(Request $request)
    {
        // try {
        //     $request->validate([
        //         'category_id' => 'required|exists:categories,id',
        //         'title' => 'required|string|max:255',
        //         'slug' => 'required|string|max:255|unique:posts',
        //         'content' => 'required|string',
        //         'status' => 'required|tinyInteger',
        //         'image' => 'nullable|image|mimes:jpg,jpeg,png',
        //         'draft' => 'nullable|json',
        //     ]);

        //     // \Log::info('Validated data:', $request->all());  // Log validated data

        //         // Handle image upload
        //     if ($request->hasFile('image')) {
        //         $imagePath = $request->file('image')->store('images', 'public');
        //         $request->merge(['image' => $imagePath]);
        //     }

        //     $postData = $request->all();

        //     // Save post with draft data if available
        //     $postData['draft'] = $request->draft ?? null;

        //     $post = $this->postService->createPost($postData);

        //     return response()->json($post, 201);

        // } catch (\Exception $exception) {
        //     \Log::error('Error creating post: ' . $exception->getMessage());
        //     return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        // } 
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts',
                'content' => 'required|string',
                'status' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
                'draft' => 'nullable|json',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $request->merge(['image' => $imagePath]);
            }

            $postData = $request->all();
            $postData['draft'] = $request->draft ?? null;

            $post = $this->postService->createPost($postData);

            return response()->json([
                'status_code' => 201,
                'data' => $post
            ], 201);

        } catch (\Exception $exception) {
            \Log::error('Error creating post: ' . $exception->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred while creating the post: ' . $exception->getMessage()
            ]);
        } 
    }


    public function storeDraft(Request $request)
    {
        // try {
        //     $request->validate([
        //         'category_id' => 'required|exists:categories,id',
        //         'title' => 'required|string|max:255',
        //         'slug' => 'required|string|max:255|unique:posts',
        //         'content' => 'required|string',
        //         'status' => 'required|tinyInteger',
        //         'image' => 'nullable|image|mimes:jpg,jpeg,png',
        //         'draft' => 'nullable|json',
        //     ]);
    
        //     if ($request->hasFile('image')) {
        //         $imagePath = $request->file('image')->store('images', 'public');
        //         $request->merge(['image' => $imagePath]);
        //     }
    
        //     $draftData = $request->all();
    
        //     // Save draft data
        //     $draftData['status'] = 0; // Ensure it's a draft
        //     $draftData['draft'] = json_encode($draftData); // Save entire data as JSON
    
        //     if ($request->has('id')) {
        //         $post = $this->postService->createOrUpdateDraft($request->id, $draftData);
        //     } else {
        //         $post = $this->postService->createPost(['draft' => $draftData['draft']]);
        //     }
    
        //     return response()->json($post, 201);
        // } catch (\Exception $exception) {
        //     \Log::error('Error storing draft: ' . $exception->getMessage());
        //     return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        // } 
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts',
                'content' => 'required|string',
                'status' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
                'draft' => 'nullable|json',
            ]);
    
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $request->merge(['image' => $imagePath]);
            }
    
            $draftData = $request->all();
            $draftData['status'] = 0; // Ensure it's a draft
            $draftData['draft'] = json_encode($draftData); // Save entire data as JSON
    
            if ($request->has('id')) {
                $post = $this->postService->createOrUpdateDraft($request->id, $draftData);
            } else {
                $post = $this->postService->createPost(['draft' => $draftData['draft']]);
            }
    
            return response()->json([
                'status_code' => 201,
                'data' => $post
            ], 201);
        } catch (\Exception $exception) {
            \Log::error('Error storing draft: ' . $exception->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred while storing the draft: ' . $exception->getMessage()
            ]);
        } 
    }

    public function update(Request $request, $id)
    {
        // try {
        //     $request->validate([
        //         'category_id' => 'required|exists:categories,id',
        //         'title' => 'required|string|max:255',
        //         'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
        //         'content' => 'nullable|string',
        //         'status' => 'required|tinyInteger',
        //         'image' => 'nullable|string',
        //         'draft' => 'nullable|json',
        //     ]);
    
        //     $post = $this->postService->updatePost($id, $request->all());
    
        //     if (!$post) {
        //         return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
        //     }
    
        //     return response()->json($post);
        // } catch (\Exception $exception) {
        //     \Log::error('Error updating post: ' . $exception->getMessage());
        //     return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        // }
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
    
            $post = $this->postService->updatePost($id, $request->all());
    
            if (!$post) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Post not found'
                ]);
            }
    
            return response()->json([
                'status_code' => 200,
                'data' => $post
            ]);
        } catch (\Exception $exception) {
            \Log::error('Error updating post: ' . $exception->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred while updating the post: ' . $exception->getMessage()
            ]);
        }    
    }

    public function updateDraft(Request $request, $id)
    {
        // try {
        //     $request->validate([
        //         'category_id' => 'required|exists:categories,id',
        //         'title' => 'required|string|max:255',
        //         'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
        //         'content' => 'nullable|string',
        //         'status' => 'required|tinyInteger',
        //         'image' => 'nullable|image|mimes:jpg,jpeg,png',
        //         'draft' => 'nullable|json',
        //     ]);
    
        //     $post = $this->postService->getPostById($id);
        //     if (!$post) {
        //         return response()->json(['error' => 'Bài viết không tìm thấy'], 404);
        //     }
    
        //     // Handle image upload
        //     if ($request->hasFile('image')) {
        //         $imagePath = $request->file('image')->store('images', 'public');
        //         $request->merge(['image' => $imagePath]);
        //     }
    
        //     $draftData = $request->all();
    
        //     // Save draft data
        //     $draftData['status'] = 0; // Ensure it's a draft
        //     $draftData['draft'] = json_encode($draftData); // Save entire data as JSON
    
        //     $post->update($draftData);
    
        //     return response()->json($post);
        // } catch (\Exception $exception) {
        //     \Log::error('Error updating draft: ' . $exception->getMessage());
        //     return response()->json(['error' => 'Có lỗi xảy ra'], 500);
        // }
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts,slug,' . $id,
                'content' => 'nullable|string',
                'status' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
                'draft' => 'nullable|json',
            ]);
    
            $post = $this->postService->getPostById($id);
            if (!$post) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Post not found'
                ]);
            }
    
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $request->merge(['image' => $imagePath]);
            }
    
            $draftData = $request->all();
            $draftData['status'] = 0; // Ensure it's a draft
            $draftData['draft'] = json_encode($draftData); // Save entire data as JSON
    
            $post->update($draftData);
    
            return response()->json([
                'status_code' => 200,
                'data' => $post
            ]);
        } catch (\Exception $exception) {
            \Log::error('Error updating draft: ' . $exception->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'An error occurred while updating the draft: ' . $exception->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        
    }
}

