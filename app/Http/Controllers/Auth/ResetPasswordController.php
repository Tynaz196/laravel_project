<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';


    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', 'Mật khẩu của bạn đã được đặt lại thành công!');
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        $message = match ($response) {
            'passwords.token'     => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.',
            'passwords.user'      => 'Không tìm thấy người dùng với địa chỉ email này.',
            'passwords.throttled' => 'Vui lòng đợi trước khi thử lại.',
            default               => 'Không thể đặt lại mật khẩu. Vui lòng thử lại.',
        };

        return back()->withErrors(['email' => $message]);
    }
}
