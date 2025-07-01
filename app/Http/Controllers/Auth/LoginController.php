<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Enums\UserStatus;

class LoginController extends Controller
{
    /**
     * Redirect path after login.
     */
    protected $redirectTo = '/';

    /**
     * Apply middleware.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store')
            ->header('Expires', '0');
    }

    /**
     * Xử lý đăng nhập với LoginRequest.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status === UserStatus::APPROVED) {
                return redirect()->intended($this->redirectTo);
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Tài khoản không hợp lệ'])->withInput();
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ])->withInput();
    }

    /**
     * Xử lý đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
