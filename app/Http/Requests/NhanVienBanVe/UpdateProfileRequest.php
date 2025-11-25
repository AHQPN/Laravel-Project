<?php

namespace App\Http\Requests\NhanVienBanVe;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
