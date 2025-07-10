<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;

class PublicPostController extends Controller
{
    /**
     * Hiển thị chi tiết bài viết công khai (đã được duyệt)
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', PostStatus::APPROVED)
            ->with(['user:id,first_name,last_name']) // Sử dụng các cột thực tế
            ->firstOrFail();

        return view('public.post-detail', compact('post'));
    }
}
