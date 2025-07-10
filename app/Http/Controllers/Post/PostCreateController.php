<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Enums\PostStatus;

class PostCreateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị form tạo bài viết mới
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Lưu bài viết mới vào database
     */
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $post = Post::create([
                'title'       => $data['title'],
                'content'     => $data['content'],
                'description' => $data['description'] ?? null,
                'publish_date' => now(),
                'user_id'     => Auth::id(),
                'status'      => PostStatus::PENDING,
            ]);

            // Upload ảnh thumbnail nếu có
            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')
                    ->toMediaCollection('thumbnails');
            }

            DB::commit();

            return to_route('posts.index')
                ->with('success', 'Bài viết đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Đã xảy ra lỗi khi tạo bài viết. Vui lòng thử lại.'])
                ->withInput();
        }
    }
}
