<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Enums\PostStatus;

class HomeController extends Controller
{
    /**
     * Show the application homepage with approved posts
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Lấy các bài viết đã được duyệt, sắp xếp theo ngày tạo mới nhất
        $posts = Post::where('status', PostStatus::APPROVED)
            ->with(['user:id,first_name,last_name']) // Eager load user info với các cột thực tế
            ->latest('publish_date')
            ->paginate(6); // Hiển thị 6 bài viết mỗi trang

        return view('home', compact('posts'));
    }
}
