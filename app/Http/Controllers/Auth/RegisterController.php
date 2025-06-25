<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendWelcomeEmail;
use App\Enums\UserStatus;

class RegisterController extends Controller
{
    use RegistersUsers;

    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
   public function register(RegisterRequest $request)
{
    
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'status'     => UserStatus::PENDING->value,
        'role'       => 'user',
    ]);

   

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
       

        // Gửi email chào mừng qua Mailpit
        SendWelcomeEmail::dispatch($user);

        return to_route('login')
            ->with('success', 'Đăng ký tài khoản thành công! ');
    
}

}