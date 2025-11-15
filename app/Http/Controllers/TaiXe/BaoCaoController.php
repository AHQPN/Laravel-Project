<?php

namespace App\Http\Controllers\TaiXe;

use App\Http\Controllers\Controller;
use App\Models\BaocaoSuco;
use App\Models\Chuyendi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaoCaoController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        $taixe = session('taixe');

        $trips = Chuyendi::with('lotrinhs.tinhthanh')
            ->whereHas('xe', function ($query) use ($taixe) {
                $query->where('manv', $taixe->manv);
            })
            ->whereBetween('thoigiandi', [now()->subDays(2), now()->addDays(2)])
            ->orderBy('thoigiandi', 'desc')
            ->get()
            ->map(function (Chuyendi $trip) {
                $routePoints = $trip->lotrinhs->sortBy('trinhtu');
                $start = optional(optional($routePoints->first())->tinhthanh)->ten ?? '---';
                $end = optional(optional($routePoints->last())->tinhthanh)->ten ?? '---';

                return [
                    'machuyendi' => $trip->machuyendi,
                    'label' => $trip->machuyendi . ' • ' . $start . ' → ' . $end . ' • ' . optional($trip->thoigiandi)->format('d/m H:i'),
                ];
            });

        return view('TaiXe.BaoCaoSuCo', [
            'trips' => $trips,
        ]);
    }

    public function store(Request $request)
    {
        $taixe = session('taixe');

        $validated = $request->validate(
            [
                'machuyendi' => 'required|exists:Chuyendi,machuyendi',
                'loai_suco' => 'required|string|max:100',
                'mota' => 'nullable|string',
                'anh' => 'nullable|image|max:4096',
            ],
            [
                'machuyendi.required' => 'Vui lòng chọn chuyến gặp sự cố.',
                'machuyendi.exists' => 'Chuyến không hợp lệ.',
                'loai_suco.required' => 'Vui lòng chọn loại sự cố.',
                'anh.image' => 'Tệp tải lên phải là hình ảnh.',
                'anh.max' => 'Ảnh tối đa 4MB.',
            ]
        );

        $data = [
            'machuyendi' => $validated['machuyendi'],
            'manv' => $taixe->manv,
            'loai_suco' => $validated['loai_suco'],
            'mota' => $validated['mota'] ?? null,
            'trangthai' => 'moi_tao',
        ];

        if ($request->hasFile('anh')) {
            $data['duongdan_anh'] = $request->file('anh')->store('suco', 'public');
        }

        BaocaoSuco::create($data);

        return redirect()->route('tai-xe.chuyen-hom-nay')->with('success', 'Đã gửi báo cáo sự cố tới bộ phận quản lý.');
    }
}

