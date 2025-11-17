<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loaixe;

class LoaixeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $loaixe = Loaixe::when($search, function($query, $search) {
            return $query->where('maloai', 'like', "%{$search}%")
                        ->orWhere('tenloai', 'like', "%{$search}%");
        })->get();

        return view('admin.LoaiXe.Index', compact('loaixe', 'search'));
    }

    public function create()
    {
        return view('admin.LoaiXe.Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'maloai' => 'required|max:3|unique:loaixe,maloai',
            'tenloai' => 'required|max:100',
            'soghe' => 'required|integer|min:1',
        ], [
            'maloai.required' => 'Vui lòng nhập mã loại xe',
            'maloai.unique' => 'Mã loại xe đã tồn tại',
            'tenloai.required' => 'Vui lòng nhập tên loại xe',
            'soghe.required' => 'Vui lòng nhập số ghế',
            'soghe.min' => 'Số ghế phải lớn hơn 0',
        ]);

        Loaixe::create($request->all());

        return redirect()->route('quan-ly.loaixe.index')
            ->with('success', 'Thêm loại xe thành công!');
    }

    public function edit($loaixe)
    {
        $loaixe = Loaixe::findOrFail($loaixe);
        return view('admin.LoaiXe.Edit', compact('loaixe'));
    }

    public function update(Request $request, $loaixe)
    {
        $request->validate([
            'tenloai' => 'required|max:100',
            'soghe' => 'required|integer|min:1',
        ], [
            'tenloai.required' => 'Vui lòng nhập tên loại xe',
            'soghe.required' => 'Vui lòng nhập số ghế',
            'soghe.min' => 'Số ghế phải lớn hơn 0',
        ]);

        $loaixe = Loaixe::findOrFail($loaixe);
        $loaixe->update($request->only(['tenloai', 'soghe']));

        return redirect()->route('quan-ly.loaixe.index')
            ->with('success', 'Cập nhật loại xe thành công!');
    }

    public function destroy($loaixe)
    {
        try {
            $loaixe = Loaixe::findOrFail($loaixe);
            $loaixe->delete();
            return redirect()->route('quan-ly.loaixe.index')
                ->with('success', 'Xóa loại xe thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.loaixe.index')
                ->with('error', 'Không thể xóa loại xe này!');
        }
    }
}
