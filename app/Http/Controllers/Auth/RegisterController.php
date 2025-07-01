<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendWelcomeEmail;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\DB;

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

        DB::beginTransaction();
        try {
            // Validate the request data
            $request->validated();

            // Create the user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'status'     => UserStatus::PENDING->value,
                'role'       => UserRole::USER->value,
            ]);

            // Commit the transaction
            DB::commit();
            // Gửi email chào mừng qua Mailpit
            SendWelcomeEmail::dispatch($user);

            return to_route('login')
                ->with('success', 'Đăng ký tài khoản thành công! ');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return back()->withErrors(['error' => 'Đăng ký không thành công. Vui lòng thử lại.']);
        }
    }
}
