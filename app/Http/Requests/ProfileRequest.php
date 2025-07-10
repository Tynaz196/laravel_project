<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'address'    => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Bạn chưa nhập tên.',
            'first_name.string' => 'Tên phải là chuỗi ký tự.',
            'first_name.max' => 'Tên không được vượt quá :max ký tự.',

            'last_name.required' => 'Bạn chưa nhập họ.',
            'last_name.string' => 'Họ phải là chuỗi ký tự.',
            'last_name.max' => 'Họ không được vượt quá :max ký tự.',

            'address.required' => 'Bạn chưa nhập địa chỉ.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',
        ];
    }
}
