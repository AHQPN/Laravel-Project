<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHoaDonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_codes' => 'required|array|min:1',
            'ticket_codes.*' => 'exists:ve,mave',
            'phuongthuc_thanhtoan' => 'nullable|string|in:tien_mat,chuyen_khoan,the',
            'sotien' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_codes.required' => 'Vui lòng chọn ít nhất một vé.',
            'ticket_codes.*.exists' => 'Vé không tồn tại.',
            'phuongthuc_thanhtoan.in' => 'Phương thức thanh toán không hợp lệ.',
            'sotien.min' => 'Số tiền phải lớn hơn 0.',
        ];
    }
}
