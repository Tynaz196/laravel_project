<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Bạn chưa nhập địa chỉ email.',
            'email.email'       => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Bạn chưa nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
        ];
    }

    /**
     * Gán tên hiển thị tiếng Việt cho các trường.
     */
    public function attributes(): array
    {
        return [
            'email'    => 'địa chỉ email',
            'password' => 'mật khẩu',
        ];
    }
}
