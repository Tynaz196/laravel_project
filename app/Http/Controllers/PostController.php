<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Enums\PostStatus;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('posts.create');
    }

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
                'status'      => PostStatus::PENDING->value,
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

    public function index()
    {
        // Get posts của user hiện tại
        $posts = Auth::user()->posts()->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền truy cập bài viết này.');
        }

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        return view('posts.edit', compact('post'));
    }

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
    public function destroy(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            return back()->with('error', 'Bạn không có quyền xóa bài viết này.');
        }

        try {
            // Soft delete 
            $post->delete();

            return back()->with('success', 'Bài viết đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa bài viết. Vui lòng thử lại.');
        }
    }

    public function destroyAll()
    {
        try {
            $user = Auth::user();

            // Lấy số lượng bài viết của user sẽ bị xóa
            $postsCount = $user->posts()->count();

            if ($postsCount === 0) {
                return back()->with('error', 'Bạn không có bài viết nào để xóa.');
            }

            // Soft delete tất cả bài viết của user
            $user->posts()->delete();

            return back()->with('success', "Đã xóa thành công {$postsCount} bài viết!");
        } catch (\Exception $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tất cả bài viết. Vui lòng thử lại.');
        }
    }
}
