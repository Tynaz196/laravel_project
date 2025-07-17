<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePostRequest;
use App\Enums\PostStatus;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('posts.index');
    }

    /**
     * Get data for DataTable via AJAX.
     */
    public function data(Request $request)
    {
        $columns = ['thumbnail', 'title', 'publish_date', 'description', 'status_badge'];
        $query = Auth::user()->posts();

        // tìm kiếm
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // sắp xếp
        if (!is_null($order = $request->input('order.0'))) {
            $idx = (int) $order['column'];
            $dir = $order['dir'] ?? 'asc';
            if (isset($columns[$idx]) && in_array($columns[$idx], ['title', 'publish_date', 'description'])) {
                $query->orderBy($columns[$idx], $dir);
            }
        }

        $total = $query->count();

        // Phân Trang
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $posts = $query->skip($start)->take($length)->get();

        $data = $posts->map(function ($post) {
            return [
                'id'            => $post->id,
                'thumbnail'     => $post->thumbnail_url,
                'title'         => $post->title,
                'slug'          => $post->slug,
                'publish_date'  => $post->publish_date?->format('H:i d/m/Y'),
                'description'   => $post->description ?? '',
                'status_badge'  => $post->status->value,
                'actions'       => '',
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw'),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $post = Post::create([
                'user_id'      => Auth::id(),
                'title'        => $data['title'],
                'content'      => $data['content'],
                'description'  => $data['description'] ?? null,
                'publish_date' => $data['publish_date'],
                'status'       => PostStatus::PENDING,
            ]);
            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }
            DB::commit();
            return to_route('posts.index')->with('success', 'Bài viết đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi tạo bài viết.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        abort_unless($post->user_id === Auth::id(), 403);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        abort_unless($post->user_id === Auth::id(), 403);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, Post $post)
    {
        abort_unless($post->user_id === Auth::id(), 403);
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $post->update([
                'title'       => $data['title'],
                'content'     => $data['content'],
                'description' => $data['description'] ?? null,
                'publish_date' => $data['publish_date'],
            ]);
            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnails');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
            }
            DB::commit();
            return to_route('posts.index')->with('success', 'Bài viết đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi cập nhật bài viết.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_unless($post->user_id === Auth::id(), request()->ajax() ? 403 : abort(403));
        try {
            $post->delete();
            if (request()->ajax()) {
                return response()->json(['success' => 'Bài viết đã được xóa thành công!']);
            }
            return back()->with('success', 'Bài viết đã được xóa thành công!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Đã xảy ra lỗi khi xóa bài viết.'], 500);
            }
            return back()->with('error', 'Đã xảy ra lỗi khi xóa bài viết.');
        }
    }

    /**
     * Remove all posts of current user.
     */
    public function destroyAll()
    {
        try {
            $count = Auth::user()->posts()->count();
            if ($count === 0) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Bạn không có bài viết nào để xóa.'], 400);
                }
                return back()->with('error', 'Bạn không có bài viết nào để xóa.');
            }
            Auth::user()->posts()->delete();
            if (request()->ajax()) {
                return response()->json(['success' => "Đã xóa {$count} bài viết!"]);
            }
            return back()->with('success', "Đã xóa {$count} bài viết!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Đã xảy ra lỗi khi xóa tất cả bài viết.'], 500);
            }
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tất cả bài viết.');
        }
    }
}
