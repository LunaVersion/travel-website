<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\PostRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class PostRepositoryEloquent extends BaseRepository implements PostRepository
{
    protected $model;

    public function model()
    {
        return \App\Models\Post::class;
    }

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function getAllPosts()
    {
        return $this->model->all();
    }

    public function getPostById($id)
    {
        return $this->model->find($id);
    }

    public function createPost(array $data)
    {
        return $this->model->create($data);
    }

    public function createOrUpdateDraft($id, array $data)
    {
        $post = $this->model->find($id);
        if ($post) {
            $post->update($data);
            return $post;
        }
        return $this->createPost($data);
    }

    public function updatePost($id, array $data)
    {
        $post = $this->model->find($id);
        if ($post) {
            $post->update($data);
            return $post;
        }
        return null;
    }

    public function deletePost($id)
    {
        // $post = $this->model->find($id);
        // if ($post) {
        //     return $post->delete();
        // }
        // return false;
    }
}
