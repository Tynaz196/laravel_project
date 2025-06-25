<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email:rfc,dns', 'max:100', 'unique:users,email'], //từ 3 role trở lên sài mảng không sài chuỗi
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // Ít nhất một chữ hoa
                'regex:/[a-z]/',      // Ít nhất một chữ thường
                'regex:/[0-9]/',      // Ít nhất một số
                'regex:/[@$!%*#?&]/', // Ít nhất một ký tự đặc biệt
                'confirmed',
            ],
        ];
    }

  
}
