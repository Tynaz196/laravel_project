<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PostListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị trang danh sách bài viết
     */
    public function index()
    {
        return view('posts.index');
    }

    /**
     * Xử lý yêu cầu AJAX từ DataTable với Yajra
     */
    public function data()
    {
        $posts = Auth::user()->posts()->latest();

        return DataTables::of($posts)
            ->addColumn('thumbnail', function ($post) {
                return $post->thumbnail_url;
            })
            ->addColumn('status_badge', function ($post) {
                return $post->status->value;
            })
            ->addColumn('actions', function ($post) {
                return $post->slug;
            })
            ->editColumn('publish_date', function ($post) {
                return $post->publish_date?->format('H:i d/m/Y');
            })
            ->editColumn('description', function ($post) {
                return $post->description ?? '';
            })
            ->rawColumns(['thumbnail', 'status_badge', 'actions'])
            ->make(true);
    }
}
