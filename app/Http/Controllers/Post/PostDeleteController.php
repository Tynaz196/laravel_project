<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostDeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Xóa một bài viết (soft delete)
     */
    public function destroy(Post $post)
    {
        // Kiểm tra quyền sở hữu bài viết
        if ($post->user_id !== Auth::id()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Bạn không có quyền xóa bài viết này.'], 403);
            }
            return back()->with('error', 'Bạn không có quyền xóa bài viết này.');
        }

        try {
            // Soft delete 
            $post->delete();

            if (request()->ajax()) {
                return response()->json(['success' => 'Bài viết đã được xóa thành công!']);
            }
            return back()->with('success', 'Bài viết đã được xóa thành công!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Đã xảy ra lỗi khi xóa bài viết. Vui lòng thử lại.'], 500);
            }
            return back()->with('error', 'Đã xảy ra lỗi khi xóa bài viết. Vui lòng thử lại.');
        }
    }

    /**
     * Xóa tất cả bài viết của user hiện tại
     */
    public function destroyAll()
    {
        try {
            $user = Auth::user();

            // Lấy số lượng bài viết của user sẽ bị xóa
            $postsCount = $user->posts()->count();

            if ($postsCount === 0) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Bạn không có bài viết nào để xóa.'], 400);
                }
                return back()->with('error', 'Bạn không có bài viết nào để xóa.');
            }

            // Soft delete tất cả bài viết của user
            $user->posts()->delete();

            if (request()->ajax()) {
                return response()->json(['success' => "Đã xóa thành công {$postsCount} bài viết!"]);
            }
            return back()->with('success', "Đã xóa thành công {$postsCount} bài viết!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Đã xảy ra lỗi khi xóa tất cả bài viết. Vui lòng thử lại.'], 500);
            }
            return back()->with('error', 'Đã xảy ra lỗi khi xóa tất cả bài viết. Vui lòng thử lại.');
        }
    }
}
