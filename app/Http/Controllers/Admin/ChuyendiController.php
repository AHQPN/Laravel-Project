<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chuyendi;
use App\Models\Xe;
use App\Models\TinhThanh;
use App\Models\Lotrinh;

class ChuyendiController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh'])
            ->when($search, function($query, $search) {
                return $query->where('machuyendi', 'like', "%{$search}%")
                            ->orWhere('tenchuyen', 'like', "%{$search}%");
            });

        $chuyendis = $query->orderBy('thoigiandi', 'desc')->paginate(10);

        return view('admin.ChuyenDi.Index', compact('chuyendis', 'search'));
    }

    public function create()
    {
        $xes = Xe::with('loaixe')->get();
        $tinhThanhs = TinhThanh::all();
        return view('admin.ChuyenDi.Create', compact('xes', 'tinhThanhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'machuyendi' => 'required|max:15|unique:chuyendi,machuyendi',
            'tenchuyen' => 'required|max:100',
            'maxe' => 'required|exists:xe,maxe',
            'thoigiandi' => 'required|date',
            'thoigiandichuyen' => 'required|integer|min:1',
            'gia' => 'required|integer|min:0',
            'lotrinh' => 'required|array|min:2',
        ], [
            'machuyendi.required' => 'Vui lòng nhập mã chuyến đi',
            'machuyendi.unique' => 'Mã chuyến đi đã tồn tại',
            'tenchuyen.required' => 'Vui lòng nhập tên chuyến',
            'maxe.required' => 'Vui lòng chọn xe',
            'thoigiandi.required' => 'Vui lòng chọn thời gian đi',
            'lotrinh.required' => 'Vui lòng chọn ít nhất 2 điểm trong lộ trình',
        ]);

        // Lấy số ghế từ loại xe
        $xe = Xe::with('loaixe')->findOrFail($request->maxe);
        
        $chuyendi = Chuyendi::create([
            'machuyendi' => $request->machuyendi,
            'tenchuyen' => $request->tenchuyen,
            'maxe' => $request->maxe,
            'SLgheconlai' => $xe->loaixe->soghe,
            'thoigiandi' => $request->thoigiandi,
            'thoigiandichuyen' => $request->thoigiandichuyen,
            'gia' => $request->gia,
        ]);

        // Thêm lộ trình
        foreach ($request->lotrinh as $index => $matinh) {
            Lotrinh::create([
                'machuyendi' => $chuyendi->machuyendi,
                'matinh' => $matinh,
                'trinhtu' => $index + 1,
            ]);
        }

        return redirect()->route('quan-ly.chuyendi.index')
            ->with('success', 'Thêm chuyến đi thành công!');
    }

    public function edit($id)
    {
        $chuyendi = Chuyendi::with('lotrinhs')->findOrFail($id);
        $xes = Xe::with('loaixe')->get();
        $tinhThanhs = TinhThanh::all();
        return view('admin.ChuyenDi.Edit', compact('chuyendi', 'xes', 'tinhThanhs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tenchuyen' => 'required|max:100',
            'maxe' => 'required|exists:xe,maxe',
            'thoigiandi' => 'required|date',
            'thoigiandichuyen' => 'required|integer|min:1',
            'gia' => 'required|integer|min:0',
            'lotrinh' => 'required|array|min:2',
        ]);

        $chuyendi = Chuyendi::findOrFail($id);
        $chuyendi->update($request->only(['tenchuyen', 'maxe', 'thoigiandi', 'thoigiandichuyen', 'gia']));

        // Cập nhật lộ trình
        Lotrinh::where('machuyendi', $id)->delete();
        foreach ($request->lotrinh as $index => $matinh) {
            Lotrinh::create([
                'machuyendi' => $chuyendi->machuyendi,
                'matinh' => $matinh,
                'trinhtu' => $index + 1,
            ]);
        }

        return redirect()->route('quan-ly.chuyendi.index')
            ->with('success', 'Cập nhật chuyến đi thành công!');
    }

    public function destroy($id)
    {
        try {
            $chuyendi = Chuyendi::findOrFail($id);
            Lotrinh::where('machuyendi', $id)->delete();
            $chuyendi->delete();
            return redirect()->route('quan-ly.chuyendi.index')
                ->with('success', 'Xóa chuyến đi thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.chuyendi.index')
                ->with('error', 'Không thể xóa chuyến đi này!');
        }
    }
}
