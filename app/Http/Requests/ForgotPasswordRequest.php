<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email'    => 'Địa chỉ email không hợp lệ.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'email',
        ];
    }
}
