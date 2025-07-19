<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
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
        $rules = [
            'title'       => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:200'],
            'content'     => ['required', 'string'],
        ];

        // Admin có thể chọn status, nhưng chỉ khi dùng admin routes
        if (Auth::check() && Auth::user()->role->value === 'admin' && $this->route()->getName() && str_contains($this->route()->getName(), 'admin.')) {
            $rules['status'] = ['required', 'in:0,1,2'];
        }

        // Thumbnail chỉ bắt buộc khi tạo mới (POST)
        if ($this->isMethod('POST')) {
            $rules['thumbnail'] = ['required', 'image', 'max:2048'];
            $rules['publish_date'] = ['nullable', 'date', 'after:now'];
        } else {
            $rules['thumbnail'] = ['nullable', 'image', 'max:2048'];
            $rules['publish_date'] = ['nullable', 'date', 'after:now'];
        }

        return $rules;
    }

    /**
     * Get the custom messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Bạn chưa nhập tiêu đề bài viết.',
            'title.max' => 'Tiêu đề không được vượt quá :max ký tự.',

            'description.max' => 'Mô tả không được vượt quá :max ký tự.',

            'content.required' => 'Bạn chưa nhập nội dung bài viết.',

            'status.required' => 'Bạn chưa chọn trạng thái bài viết.',
            'status.in' => 'Trạng thái bài viết không hợp lệ.',

            'thumbnail.required' => 'Bạn chưa chọn ảnh thumbnail.',
            'thumbnail.image' => 'File phải là hình ảnh.',
            'thumbnail.max' => 'Kích thước ảnh không được vượt quá :max KB.',

            'publish_date.required' => 'Bạn chưa chọn ngày đăng.',
            'publish_date.date'     => 'Ngày đăng không hợp lệ.',
            'publish_date.after'    => 'Ngày đăng phải lớn hơn thời điểm hiện tại.',
        ];
    }
}
