<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:0,1,2,3']
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Hãy nhập tên của bạn.',
            'first_name.string' => 'Tên phải là chuỗi ký tự.',
            'first_name.max' => 'Tên không được vượt quá :max ký tự.',

            'last_name.required' => 'Hãy nhập họ của bạn.',
            'last_name.string' => 'Họ phải là chuỗi ký tự.',
            'last_name.max' => 'Họ không được vượt quá :max ký tự.',

            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',

            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái được chọn không hợp lệ.',
        ];
    }
}
