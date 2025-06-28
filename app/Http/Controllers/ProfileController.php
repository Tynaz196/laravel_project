<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(ProfileRequest $request)
    {
        Auth::user()->update($request->validated());

        return redirect()->route('profile.edit')->with('status', 'Cập nhật hồ sơ thành công!');
    }
}
