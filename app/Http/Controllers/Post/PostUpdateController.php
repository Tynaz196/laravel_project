<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;

class PostUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị form chỉnh sửa bài viết
     */
    public function edit(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(StorePostRequest $request, Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();

            $post->update([
                'title'       => $data['title'],
                'content'     => $data['content'],
                'description' => $data['description'] ?? null,
            ]);

            // Upload ảnh thumbnail mới nếu có
            if ($request->hasFile('thumbnail')) {
                // Xóa ảnh cũ
                $post->clearMediaCollection('thumbnails');

                // Thêm ảnh mới
                $post->addMediaFromRequest('thumbnail')
                    ->toMediaCollection('thumbnails');
            }

            DB::commit();

            return to_route('posts.index')
                ->with('success', 'Bài viết đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật bài viết. Vui lòng thử lại.'])
                ->withInput();
        }
    }
}
