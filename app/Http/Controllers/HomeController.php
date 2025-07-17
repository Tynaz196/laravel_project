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
        $posts = Post::approved()
            ->where('publish_date', '<=', now())
            ->with(['user:id,first_name,last_name']) // Eager load user info with specific columns
            ->latest('publish_date')
            ->paginate(6); // Display 6 posts per page

        return view('home', compact('posts'));
    }
}
