<?php

namespace App\Http\Middleware;
use App\Enums;
use App\Enums\UserStatus;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
        switch ($user->status) {
            case UserStatus::PENDING:
                Auth::logout();
                return to_route('login')->with('error', 'Tài khoản của bạn cần được phê duyệt trước khi đăng nhập.');
            case UserStatus::REJECTED:
                Auth::logout();
                return to_route('login')->with('error', 'Tài khoản của bạn đã bị từ chối.');
            case UserStatus::BLOCKED:
                Auth::logout();
                return to_route('login')->with('error', 'Tài khoản của bạn đã bị khóa.');
        }
    }
        return $next($request);
    }
}
