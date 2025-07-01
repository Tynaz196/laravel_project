<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:100',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Bạn chưa nhập tên.',
            'first_name.max' => 'Tên không được vượt quá :max ký tự.',

            'last_name.required' => 'Bạn chưa nhập họ.',
            'last_name.max' => 'Họ không được vượt quá :max ký tự.',

            'email.required' => 'Bạn chưa nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',

            'password.required' => 'Bạn chưa nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.mixed' => 'Mật khẩu phải có chữ hoa và chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
        ];
    }

    /**
     * Gán tên dễ hiểu cho các trường.
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'tên',
            'last_name' => 'họ',
            'email' => 'địa chỉ email',
            'password' => 'mật khẩu',
        ];
    }
}
