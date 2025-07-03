<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Enums\PostStatus;

class PostController extends Controller
{
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
        // Get all posts for DataTable to handle pagination
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(StorePostRequest $request, Post $post)
    {
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
        try {
            // Xóa tất cả media liên quan
            $post->clearMediaCollection('thumbnails');

            $post->delete();

            return to_route('posts.index')
                ->with('success', 'Bài viết đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Đã xảy ra lỗi khi xóa bài viết. Vui lòng thử lại.']);
        }
    }
}
