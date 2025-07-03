<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\User;
use App\Jobs\SendResetPasswordEmail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function sendResetLinkEmailJob(ForgotPasswordRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            SendResetPasswordEmail::dispatch($user->email, $token);

            return back()->with('status', 'Chúng tôi đã gửi email hướng dẫn đặt lại mật khẩu!');
        }

        return back()->withErrors([
            'email' => 'Không tìm thấy người dùng với địa chỉ email này.',
        ]);
    }
    use SendsPasswordResetEmails;
}
