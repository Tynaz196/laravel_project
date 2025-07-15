<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;

class PublicPostController extends Controller
{
    /**
     * Hiển thị chi tiết bài viết công khai (đã được duyệt)
     */
    public function show(Post $post)
    {
        // Kiểm tra bài viết đã được duyệt chưa
        if ($post->status !== PostStatus::APPROVED) {
            abort(404, 'Bài viết không tồn tại hoặc chưa được duyệt');
        }

        // Eager load user information nếu chưa có
        if (!$post->relationLoaded('user')) {
            $post->load(['user:id,first_name,last_name']);
        }

        return view('public.post-detail', compact('post'));
    }
}
