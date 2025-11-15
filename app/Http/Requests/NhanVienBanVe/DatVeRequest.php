<?php

namespace App\Http\Requests\NhanVienBanVe;

use Illuminate\Foundation\Http\FormRequest;

class DatVeRequest extends FormRequest
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
            'machuyendi' => 'required|exists:chuyendi,machuyendi',
            'sdt_khach' => 'required|string|max:15',
            'ten_khach' => 'required|string|max:100',
            'email_khach' => 'nullable|email|max:100',
            'soghe' => 'required|array|min:1',
            'soghe.*' => 'required|string',
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
            'machuyendi.required' => 'Vui lòng chọn chuyến đi',
            'machuyendi.exists' => 'Chuyến đi không hợp lệ',
            'sdt_khach.required' => 'Vui lòng nhập số điện thoại khách hàng',
            'ten_khach.required' => 'Vui lòng nhập tên khách hàng',
            'email_khach.email' => 'Email không đúng định dạng',
            'soghe.required' => 'Vui lòng chọn ghế',
            'soghe.min' => 'Vui lòng chọn ít nhất 1 ghế',
        ];
    }
}
