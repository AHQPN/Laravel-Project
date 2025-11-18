<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khach;
use App\Models\Nhanvien;
use App\Models\Chucvu;

class NguoiDungController extends Controller
{
    // Quản lý khách hàng
    public function khach(Request $request)
    {
        $search = $request->get('search');
        $khachs = Khach::when($search, function($query, $search) {
            return $query->where('makh', 'like', "%{$search}%")
                        ->orWhere('ten', 'like', "%{$search}%")
                        ->orWhere('sdt', 'like', "%{$search}%");
        })->paginate(10);

        // Also load nhanvien for the tabs
        $nhanviens = Nhanvien::with('chucvu')->paginate(10);

        return view('admin.nguoidung.index', compact('khachs', 'nhanviens', 'search'));
    }

    public function khachEdit($id)
    {
        $khach = Khach::findOrFail($id);
        return view('admin.nguoidung.khach.edit', compact('khach'));
    }

    public function khachUpdate(Request $request, $id)
    {
        $request->validate([
            'ten' => 'required|max:100',
            'sdt' => 'required|max:15',
            'diachi' => 'nullable|max:200',
            'ngaysinh' => 'nullable|date',
            'gioitinh' => 'nullable',
        ]);

        $khach = Khach::findOrFail($id);
        $khach->update($request->only(['ten', 'sdt', 'diachi', 'ngaysinh', 'gioitinh']));

        return redirect()->route('admin.nguoidung.khach')
            ->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function khachDestroy($id)
    {
        try {
            $khach = Khach::findOrFail($id);
            $khach->delete();
            return redirect()->route('admin.nguoidung.khach')
                ->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.nguoidung.khach')
                ->with('error', 'Không thể xóa khách hàng này!');
        }
    }

    // Quản lý nhân viên
    public function nhanvien(Request $request)
    {
        $search = $request->get('search');
        $nhanviens = Nhanvien::with('chucvu')
            ->when($search, function($query, $search) {
                return $query->where('manv', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%")
                            ->orWhere('sdt', 'like', "%{$search}%");
            })->paginate(10);

        // Also load khach for the tabs
        $khachs = Khach::paginate(10);

        return view('admin.nguoidung.index', compact('nhanviens', 'khachs', 'search'));
    }

    public function nhanvienCreate()
    {
        $chucvus = Chucvu::all();
        return view('admin.nguoidung.nhanvien.create', compact('chucvus'));
    }

    public function nhanvienStore(Request $request)
    {
        $request->validate([
            'manv' => 'required|max:5|unique:nhanvien,manv',
            'macv' => 'required|exists:chucvu,macv',
            'password' => 'required',
            'ten' => 'required|max:100',
            'sdt' => 'required|max:15',
            'email' => 'required|email',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required',
        ]);

        Nhanvien::create($request->all());

        return redirect()->route('admin.nguoidung.nhanvien')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    public function nhanvienEdit($id)
    {
        $nhanvien = Nhanvien::findOrFail($id);
        $chucvus = Chucvu::all();
        return view('admin.nguoidung.nhanvien.edit', compact('nhanvien', 'chucvus'));
    }

    public function nhanvienUpdate(Request $request, $id)
    {
        $request->validate([
            'macv' => 'required|exists:chucvu,macv',
            'ten' => 'required|max:100',
            'sdt' => 'required|max:15',
            'email' => 'required|email',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required',
            'trangthai' => 'required',
        ]);

        $nhanvien = Nhanvien::findOrFail($id);
        $data = $request->only(['macv', 'ten', 'sdt', 'email', 'ngaysinh', 'gioitinh', 'diachi', 'cccd', 'trangthai']);
        
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $nhanvien->update($data);

        return redirect()->route('admin.nguoidung.nhanvien')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function nhanvienDestroy($id)
    {
        try {
            $nhanvien = Nhanvien::findOrFail($id);
            $nhanvien->delete();
            return redirect()->route('admin.nguoidung.nhanvien')
                ->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.nguoidung.nhanvien')
                ->with('error', 'Không thể xóa nhân viên này!');
        }
    }
}
