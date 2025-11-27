<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Xe;
use App\Models\Loaixe;
use App\Models\Nhanvien;

class XeController extends Controller
{
    private function generateMaXe(): string
    {
        $lastXe = Xe::orderBy('maxe', 'desc')->first();
        
        if (!$lastXe) {
            return 'X1';
        }
        
        $lastNumber = (int) substr($lastXe->maxe, 1);
        $newNumber = $lastNumber + 1;
        
        return 'X' . $newNumber;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Xe::with(['loaixe', 'taixe'])
            ->when($search, function($query, $search) {
                return $query->where('maxe', 'like', "%{$search}%")
                            ->orWhere('soxe', 'like', "%{$search}%");
            });

        $xes = $query->orderBy('maxe', 'asc')->get();

        return view('admin.Xe.Index', compact('xes', 'search'));
    }

    public function create()
    {
        $loaixes = Loaixe::all();
        $taixe = Nhanvien::where('macv', 'TX')->where('trangthai', 1)->get();
        return view('admin.Xe.Create', compact('loaixes', 'taixe'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'maloai' => 'required|exists:loaixe,maloai',
            'soxe' => 'required|max:10',
            'manv' => 'required|exists:nhanvien,manv',
        ], [
            'maloai.required' => 'Vui lòng chọn loại xe',
            'soxe.required' => 'Vui lòng nhập biển số xe',
            'manv.required' => 'Vui lòng chọn tài xế',
        ]);

        Xe::create([
            'maxe' => $this->generateMaXe(),
            'maloai' => $request->maloai,
            'soxe' => $request->soxe,
            'manv' => $request->manv,
        ]);

        return redirect()->route('quan-ly.xe.index')
            ->with('success', 'Thêm xe thành công!');
    }

    public function edit($id)
    {
        $xe = Xe::findOrFail($id);
        $loaixes = Loaixe::all();
        $taixe = Nhanvien::where('macv', 'TX')->where('trangthai', 1)->get();
        return view('admin.Xe.Edit', compact('xe', 'loaixes', 'taixe'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'maloai' => 'required|exists:loaixe,maloai',
            'soxe' => 'required|max:10',
            'manv' => 'required|exists:nhanvien,manv',
        ], [
            'maloai.required' => 'Vui lòng chọn loại xe',
            'soxe.required' => 'Vui lòng nhập biển số xe',
            'manv.required' => 'Vui lòng chọn tài xế',
        ]);

        $xe = Xe::findOrFail($id);
        $xe->update($request->only(['maloai', 'soxe', 'manv']));

        return redirect()->route('quan-ly.xe.index')
            ->with('success', 'Cập nhật xe thành công!');
    }

    public function destroy($id)
    {
        try {
            $xe = Xe::findOrFail($id);
            $xe->delete();
            return redirect()->route('quan-ly.xe.index')
                ->with('success', 'Xóa xe thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.xe.index')
                ->with('error', 'Không thể xóa xe này!');
        }
    }
}
