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

    public function showLoginForm()
    {
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-cache, no-store')
            ->header('Expires', '0');
    }

    public function login(LoginRequest $request)
    {
        $credentials = array_merge(
            $request->only('email', 'password'),
            ['status' => UserStatus::APPROVED]
        );

        if (Auth::attempt($credentials)) {
            return redirect()->intended($this->redirectTo);
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng hoặc tài khoản chưa được phê duyệt.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
