<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StorePostRequest;
use App\Jobs\SendPostApprovedEmail;

class AdminPostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of all posts for admin
     */
    public function index()
    {
        return view('admin.posts.index');
    }

    /**
     * AJAX data endpoint for DataTables
     */
    public function data(Request $request)
    {
        $posts = Post::with(['user', 'media'])
            ->select([
                'id',
                'title',
                'description',
                'status',
                'publish_date',
                'slug',
                'user_id',
                'created_at'
            ]);

        // Server-side search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $posts->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', "%{$searchValue}%")
                    ->orWhere('description', 'like', "%{$searchValue}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchValue) {
                        $userQuery->where('email', 'like', "%{$searchValue}%")
                            ->orWhere('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Server-side ordering
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];

            // Map column index to database column
            $columns = [
                '',
                '',
                'title',
                'user_id',
                'publish_date',
                'description',
                'status',
                ''
            ];

            if (isset($columns[$orderColumnIndex]) && !empty($columns[$orderColumnIndex])) {
                if ($columns[$orderColumnIndex] == 'user_id') {
                    $posts->join('users', 'posts.user_id', '=', 'users.id')
                        ->orderBy('users.name', $orderDirection);
                } else {
                    $posts->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            }
        } else {
            $posts->orderBy('created_at', 'desc');
        }

        // Get total count before pagination
        $totalRecords = Post::count();
        $filteredRecords = $posts->count();

        // Server-side pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $posts = $posts->skip($start)->take($length)->get();

        $data = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'thumbnail' => $post->thumbnail_url,
                'title' => $post->title,
                'author' => $post->user->name ?? 'Unknown',
                'author_email' => $post->user->email ?? 'Unknown',
                'publish_date' => $post->publish_date
                    ? $post->publish_date->format('d/m/Y H:i')
                    : 'Chưa đặt',
                'description' => $post->description,
                'status' => $post->status->value,
                'status_badge' => $post->status->value,
                'slug' => $post->slug,
                'actions' => ''
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified post
     */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $data = $request->validated();

        // Lưu trạng thái cũ để so sánh
        $oldStatus = $post->status;
        $newStatus = PostStatus::from($data['status']);

        $post->update([
            'title'       => $data['title'],
            'content'     => $data['content'],
            'description' => $data['description'] ?? null,
            'status'      => $newStatus,
            'publish_date' => $data['publish_date'],
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($post->getFirstMedia('thumbnails')) {
                $post->getFirstMedia('thumbnails')->delete();
            }

            // Add new thumbnail
            $post->addMediaFromRequest('thumbnail')
                ->toMediaCollection('thumbnails');
        }

        // Gửi email thông báo nếu bài viết được duyệt (từ pending hoặc rejected thành approved)
        if ($oldStatus !== PostStatus::APPROVED && $newStatus === PostStatus::APPROVED) {
            try {
                // Dispatch job để gửi email qua queue
                SendPostApprovedEmail::dispatch($post);

                Log::info('Post approval email job dispatched', [
                    'post_id' => $post->id,
                    'user_email' => $post->user->email
                ]);
            } catch (\Exception $e) {
                // Log lỗi nhưng không làm fail toàn bộ request
                Log::error('Failed to dispatch post approval email job: ' . $e->getMessage());
            }
        }

        return to_route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!' .
                ($oldStatus !== PostStatus::APPROVED && $newStatus === PostStatus::APPROVED ? ' Email thông báo đã được gửi cho tác giả.' : ''));
    }

    /**
     * Remove the specified post from storage
     */
    public function destroy(Post $post)
    {
        try {
            // Delete associated media
            $post->clearMediaCollection('thumbnails');

            // Delete the post
            $post->delete();

            return response()->json([
                'success' => 'Bài viết đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xóa bài viết: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified post
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Remove all posts (Admin can delete all posts in system)
     */
    public function destroyAll()
    {
        try {
            $count = Post::count();
            if ($count === 0) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Không có bài viết nào để xóa.'], 400);
                }
                return back()->with('error', 'Không có bài viết nào để xóa.');
            }

            // Delete all posts and their media
            Post::chunk(100, function ($posts) {
                foreach ($posts as $post) {
                    $post->clearMediaCollection('thumbnails');
                    $post->delete();
                }
            });

            if (request()->ajax()) {
                return response()->json(['success' => "Đã xóa tất cả {$count} bài viết trong hệ thống!"]);
            }
            return back()->with('success', "Đã xóa tất cả {$count} bài viết trong hệ thống!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Có lỗi xảy ra khi xóa tất cả bài viết: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi xóa tất cả bài viết.');
        }
    }
}
