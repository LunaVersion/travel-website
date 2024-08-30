<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface PostRepository extends RepositoryInterface
{
    public function getPostById(int $id);
    public function createPost(array $data);
    public function updatePost(int $id, array $data);
    public function deletePost(int $id);
    public function createOrUpdateDraft(int $id, array $data);
    public function getAllPosts();
}
