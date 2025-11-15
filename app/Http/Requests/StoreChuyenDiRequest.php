<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChuyenDiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'machuyendi' => 'required|string|max:15|unique:chuyendi,machuyendi',
            'tenchuyen' => 'required|string|max:100',
            'maxe' => 'required|exists:xe,maxe',
            'thoigiandi' => 'required|date|after:now',
            'thoigiandichuyen' => 'required|integer|min:1',
            'gia' => 'required|numeric|min:0',
            'lotrinh' => 'required|array|min:2',
            'lotrinh.*' => 'required|exists:tinhthanh,matinh',
        ];
    }

    public function messages(): array
    {
        return [
            'machuyendi.required' => 'Vui lòng nhập mã chuyến đi.',
            'machuyendi.unique' => 'Mã chuyến đi đã tồn tại.',
            'tenchuyen.required' => 'Vui lòng nhập tên chuyến.',
            'maxe.required' => 'Vui lòng chọn xe.',
            'maxe.exists' => 'Xe không tồn tại.',
            'thoigiandi.required' => 'Vui lòng chọn thời gian đi.',
            'thoigiandi.after' => 'Thời gian đi phải sau thời điểm hiện tại.',
            'thoigiandichuyen.required' => 'Vui lòng nhập thời gian di chuyển.',
            'thoigiandichuyen.min' => 'Thời gian di chuyển phải lớn hơn 0.',
            'gia.required' => 'Vui lòng nhập giá vé.',
            'gia.min' => 'Giá vé phải lớn hơn 0.',
            'lotrinh.required' => 'Vui lòng chọn lộ trình.',
            'lotrinh.min' => 'Lộ trình phải có ít nhất 2 điểm.',
        ];
    }
}
