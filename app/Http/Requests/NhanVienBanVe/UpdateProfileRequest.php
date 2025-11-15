<?php

namespace App\Http\Requests\NhanVienBanVe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $userId = session('nhanvien')->manv ?? null;

        return [
            'hoten' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('nhanvien', 'email')->ignore($userId, 'manv'),
            ],
            'sdt' => [
                'required',
                'string',
                'max:15',
                Rule::unique('nhanvien', 'sdt')->ignore($userId, 'manv'),
            ],
            'diachi' => 'nullable|string|max:200',
            'ngaysinh' => 'nullable|date|before:today',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'hoten.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'sdt.required' => 'Vui lòng nhập số điện thoại',
            'sdt.unique' => 'Số điện thoại đã được sử dụng',
            'ngaysinh.before' => 'Ngày sinh phải trước ngày hiện tại',
        ];
    }
}
