<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'draft',
        'status'
    ];

    protected $casts = [
        'draft' => 'array', // Chuyển đổi trường draft thành mảng
    ];



    // Bạn có thể thêm các quan hệ và phương thức khác tại đây

    /**
     * Tìm một bài viết theo ID.
     */
    public static function findById($id)
    {
        return self::find($id);
    }

    /**
     * Tạo mới một bài viết.
     */
    public static function createPost(array $data)
    {
        return self::create($data);
    }

    /**
     * Cập nhật một bài viết theo ID.
     */
    public function updatePost(array $data)
    {
        return $this->update($data);
    }

    /**
     * Xóa một bài viết theo ID.
     */
    public static function deletePost($id)
    {
        // $post = self::find($id);
        // if ($post) {
        //     return $post->delete();
        // }
        // return false;
    }

    /**
     * Tạo hoặc cập nhật bản nháp.
     */
    public static function createOrUpdateDraft($id, array $data)
    {
        $post = self::find($id);
        if ($post) {
            $post->draft = $data;
            $post->save();
        } else {
            $post = self::create([
                'id' => $id,
                'draft' => $data,
                'status' => 'draft'
            ]);
        }
        return $post;
    }
}

