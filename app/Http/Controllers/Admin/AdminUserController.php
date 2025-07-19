<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AdminUserRequest;

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
        try {
            $users = User::select(['id', 'first_name', 'last_name', 'email', 'address', 'status', 'role', 'created_at']);

            // Server-side search
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = $request->search['value'];
                $users->where(function ($query) use ($searchValue) {
                    $query->where('first_name', 'like', "%{$searchValue}%")
                        ->orWhere('last_name', 'like', "%{$searchValue}%")
                        ->orWhere('email', 'like', "%{$searchValue}%")
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$searchValue}%"]);
                });
            }

            // Server-side ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];

                // Map column index to database column
                $columns = ['', 'last_name', 'email', 'address', 'status', ''];

                if (isset($columns[$orderColumnIndex]) && !empty($columns[$orderColumnIndex])) {
                    $users->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                $users->orderBy('created_at', 'desc');
            }

            // Get total count before pagination
            $totalRecords = User::count();
            $filteredRecords = (clone $users)->count();

            // Server-side pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $users = $users->skip($start)->take($length)->get();

            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name, // Sử dụng accessor để get full name
                    'email' => $user->email,
                    'address' => $user->address ?? 'Chưa cập nhật',
                    'status' => $user->status->value,
                    'status_label' => $user->status->label(),
                    'status_badge_class' => $user->status->badgeClass(),
                    'role' => $user->role->value,
                    'is_admin' => $user->role === UserRole::ADMIN,
                    'actions' => ''
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('AdminUserController data method error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Ngăn chặn chỉnh sửa tài khoản admin
        if ($user->role === UserRole::ADMIN) {
            return to_route('admin.users.index')
                ->with('error', 'Không thể chỉnh sửa tài khoản Admin!');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(AdminUserRequest $request, User $user)
    {
        // Ngăn chặn cập nhật tài khoản admin
        if ($user->role === UserRole::ADMIN) {
            return to_route('admin.users.index')
                ->with('error', 'Không thể cập nhật tài khoản Admin!');
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'status' => UserStatus::from($request->status)
        ]);

        return to_route('admin.users.index')
            ->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
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
