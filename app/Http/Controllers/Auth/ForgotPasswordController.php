<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use \App\Jobs\SendResetPasswordEmail;
use \App\Models\User;

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
    public function sendResetLinkEmailJob(Request $request)
    {
        $this->validateEmail($request);

        $credentials = $this->credentials($request);
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            $token = app('auth.password.broker')->createToken($user);
            SendResetPasswordEmail::dispatch($user->email, $token);

            return back()->with('status', trans('passwords.sent'));
        }

        return back()->withErrors(['email' => trans('passwords.user')]);
    }
    use SendsPasswordResetEmails;
}
