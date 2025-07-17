<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of all users for admin
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * AJAX data endpoint for DataTables
     */
    public function data(Request $request)
    {
        $users = User::select(['id', 'name', 'email', 'role', 'status', 'created_at']);

        // Server-side search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $users->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%");
            });
        }

        // Server-side ordering
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];

            // Map column index to database column
            $columns = ['', 'name', 'email', 'role', 'status', 'created_at', ''];

            if (isset($columns[$orderColumnIndex]) && !empty($columns[$orderColumnIndex])) {
                $users->orderBy($columns[$orderColumnIndex], $orderDirection);
            }
        } else {
            $users->orderBy('created_at', 'desc');
        }

        // Get total count before pagination
        $totalRecords = User::count();
        $filteredRecords = $users->count();

        // Server-side pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $users = $users->skip($start)->take($length)->get();

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->value,
                'role_label' => $user->role->value == 1 ? 'Admin' : 'User',
                'status' => $user->status->value,
                'status_label' => $user->status->value == 1 ? 'Hoạt động' : 'Không hoạt động',
                'created_at' => $user->created_at->format('d/m/Y H:i'),
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
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:0,1',
            'status' => 'required|in:0,1'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => UserRole::from($request->role),
            'status' => UserStatus::from($request->status)
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Tài khoản đã được cập nhật thành công!');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion
            if ($user->id === Auth::user()->id) {
                return response()->json([
                    'error' => 'Bạn không thể xóa tài khoản của chính mình!'
                ], 400);
            }

            // Delete user's posts and media
            $user->posts()->each(function ($post) {
                $post->clearMediaCollection('thumbnails');
                $post->delete();
            });

            // Delete the user
            $user->delete();

            return response()->json([
                'success' => 'Tài khoản đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra khi xóa tài khoản: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified user
     */
    public function show() {}
}
