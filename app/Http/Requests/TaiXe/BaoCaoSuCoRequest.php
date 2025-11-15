<?php

namespace App\Http\Requests\TaiXe;

use Illuminate\Foundation\Http\FormRequest;

class BaoCaoSuCoRequest extends FormRequest
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
            'loaisuco' => 'required|string|max:50',
            'mota' => 'required|string|max:500',
            'vitri' => 'nullable|string|max:200',
            'thoigian' => 'required|date_format:Y-m-d H:i',
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
            'loaisuco.required' => 'Vui lòng nhập loại sự cố',
            'mota.required' => 'Vui lòng mô tả sự cố',
            'mota.max' => 'Mô tả không được vượt quá :max ký tự',
            'thoigian.required' => 'Vui lòng chọn thời gian xảy ra sự cố',
            'thoigian.date_format' => 'Thời gian không đúng định dạng',
        ];
    }
}
