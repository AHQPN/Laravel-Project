<?php

namespace App\Http\Requests\QuanLy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNhanVienRequest extends FormRequest
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
        $nhanvienId = $this->route('id');

        return [
            'macv' => 'required|exists:chucvu,macv',
            'password' => 'nullable|string|min:6',
            'ten' => 'required|string|max:100',
            'sdt' => [
                'required',
                'string',
                'max:15',
                Rule::unique('nhanvien', 'sdt')->ignore($nhanvienId, 'manv'),
            ],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('nhanvien', 'email')->ignore($nhanvienId, 'manv'),
            ],
            'ngaysinh' => 'required|date|before:today',
            'gioitinh' => 'required|in:Nam,Nữ',
            'diachi' => 'nullable|string|max:200',
            'cccd' => [
                'nullable',
                'string',
                'max:12',
                Rule::unique('nhanvien', 'cccd')->ignore($nhanvienId, 'manv'),
            ],
            'hinhanh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trangthai' => 'required|boolean',
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
            'macv.required' => 'Vui lòng chọn chức vụ',
            'macv.exists' => 'Chức vụ không hợp lệ',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'ten.required' => 'Vui lòng nhập tên nhân viên',
            'sdt.required' => 'Vui lòng nhập số điện thoại',
            'sdt.unique' => 'Số điện thoại đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'ngaysinh.required' => 'Vui lòng chọn ngày sinh',
            'ngaysinh.before' => 'Ngày sinh phải trước ngày hiện tại',
            'gioitinh.required' => 'Vui lòng chọn giới tính',
            'cccd.unique' => 'Số CCCD đã được sử dụng',
            'hinhanh.image' => 'File phải là ảnh',
            'hinhanh.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'trangthai.required' => 'Vui lòng chọn trạng thái',
        ];
    }
}
