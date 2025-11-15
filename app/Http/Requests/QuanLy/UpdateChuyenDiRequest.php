<?php

namespace App\Http\Requests\QuanLy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChuyenDiRequest extends FormRequest
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
            'malotrinh' => 'required|exists:lotrinh,malotrinh',
            'maxe' => 'required|exists:xe,maxe',
            'ngaydi' => 'required|date',
            'giodi' => 'required|date_format:H:i',
            'gioden' => 'required|date_format:H:i|after:giodi',
            'giave' => 'required|numeric|min:0',
            'trangthai' => 'nullable|in:Chưa khởi hành,Đang di chuyển,Đã hoàn thành,Đã hủy',
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
            'malotrinh.required' => 'Vui lòng chọn lộ trình',
            'malotrinh.exists' => 'Lộ trình không hợp lệ',
            'maxe.required' => 'Vui lòng chọn xe',
            'maxe.exists' => 'Xe không hợp lệ',
            'ngaydi.required' => 'Vui lòng chọn ngày đi',
            'giodi.required' => 'Vui lòng nhập giờ đi',
            'giodi.date_format' => 'Giờ đi không đúng định dạng',
            'gioden.required' => 'Vui lòng nhập giờ đến',
            'gioden.date_format' => 'Giờ đến không đúng định dạng',
            'gioden.after' => 'Giờ đến phải sau giờ đi',
            'giave.required' => 'Vui lòng nhập giá vé',
            'giave.min' => 'Giá vé phải lớn hơn 0',
            'trangthai.in' => 'Trạng thái không hợp lệ',
        ];
    }
}
