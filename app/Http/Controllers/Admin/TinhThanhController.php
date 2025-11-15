<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TinhThanh;

class TinhThanhController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $tinhThanhs = TinhThanh::when($search, function($query, $search) {
            return $query->where('matinh', 'like', "%{$search}%")
                        ->orWhere('ten', 'like', "%{$search}%");
        })->paginate(10);

        return view('admin.TinhThanh.Index', compact('tinhThanhs', 'search'));
    }

    public function create()
    {
        return view('admin.TinhThanh.Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'matinh' => 'required|max:4|unique:tinhthanh,matinh',
            'ten' => 'required|max:100',
        ], [
            'matinh.required' => 'Vui lòng nhập mã tỉnh thành',
            'matinh.unique' => 'Mã tỉnh thành đã tồn tại',
            'ten.required' => 'Vui lòng nhập tên tỉnh thành',
        ]);

        TinhThanh::create($request->all());

        return redirect()->route('quan-ly.tinhthanh.index')
            ->with('success', 'Thêm tỉnh thành thành công!');
    }

    public function edit($id)
    {
        $tinhThanh = TinhThanh::findOrFail($id);
        return view('admin.TinhThanh.Edit', compact('tinhThanh'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ten' => 'required|max:100',
        ], [
            'ten.required' => 'Vui lòng nhập tên tỉnh thành',
        ]);

        $tinhThanh = TinhThanh::findOrFail($id);
        $tinhThanh->update($request->only('ten'));

        return redirect()->route('quan-ly.tinhthanh.index')
            ->with('success', 'Cập nhật tỉnh thành thành công!');
    }

    public function destroy($id)
    {
        try {
            $tinhThanh = TinhThanh::findOrFail($id);
            $tinhThanh->delete();
            return redirect()->route('quan-ly.tinhthanh.index')
                ->with('success', 'Xóa tỉnh thành thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.tinhthanh.index')
                ->with('error', 'Không thể xóa tỉnh thành này!');
        }
    }
}
