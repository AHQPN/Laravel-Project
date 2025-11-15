<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChuyenDiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $machuyendi = $this->route('machuyendi');
        
        return [
            'tenchuyen' => 'sometimes|required|string|max:100',
            'maxe' => 'sometimes|required|exists:xe,maxe',
            'thoigiandi' => 'sometimes|required|date',
            'thoigiandichuyen' => 'sometimes|required|integer|min:1',
            'gia' => 'sometimes|required|numeric|min:0',
            'lotrinh' => 'sometimes|required|array|min:2',
            'lotrinh.*' => 'exists:tinhthanh,matinh',
            'trangthai' => 'sometimes|in:sap_chay,dang_chay,da_chay,da_huy',
        ];
    }

    public function messages(): array
    {
        return [
            'tenchuyen.required' => 'Vui lòng nhập tên chuyến.',
            'maxe.exists' => 'Xe không tồn tại.',
            'gia.min' => 'Giá vé phải lớn hơn 0.',
            'lotrinh.min' => 'Lộ trình phải có ít nhất 2 điểm.',
        ];
    }
}
