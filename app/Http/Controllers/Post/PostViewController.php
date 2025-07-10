<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị chi tiết bài viết
     */
    public function show(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập bài viết này.');
        }

        return view('posts.show', compact('post'));
    }
}
