<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryEloquent;

use Illuminate\Support\Str;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPosts()
    {
        return $this->postRepository->all();
    }

    public function getPostById($id)
    {
        return $this->postRepository->findById($id);
    }

    public function createPost(array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        return $this->postRepository->createPost($data);
    }

    public function createOrUpdateDraft($id, array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        return $this->postRepository->createOrUpdateDraft($id, $data);
    }

    public function updatePost($id, array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        return $this->postRepository->updatePost($id, $data);
    }

    public function deletePost($id)
    {
        // return $this->postRepository->deletePost($id);
    }
}
