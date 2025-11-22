<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khach;
use App\Models\Nhanvien;
use App\Models\Chucvu;
use App\Models\ActivityLog;

class NguoiDungController extends Controller
{

    // Quản lý khách hàng
    public function khach(Request $request)
    {
        $search = $request->get('search');
        
        $query = Khach::query()
            ->when($search, function($query, $search) {
                return $query->where('makh', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%")
                            ->orWhere('sdt', 'like', "%{$search}%");
            });

        $khachs = $query->orderBy('makh', 'desc')->get();
        $nhanviens = Nhanvien::with('chucvu')->orderBy('manv')->get();

        return view('admin.NguoiDung.Index', compact('khachs', 'nhanviens', 'search'));
    }

    public function khachEdit($id)
    {
        $khach = Khach::findOrFail($id);
        return view('admin.NguoiDung.Khach.Edit', compact('khach'));
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

        return redirect()->route('quan-ly.nguoidung.khach')
            ->with('success', 'Cập nhật khách hàng thành công!');
    }

    public function khachDestroy($id)
    {
        try {
            $khach = Khach::findOrFail($id);
            $khach->delete();
            return redirect()->route('quan-ly.nguoidung.khach')
                ->with('success', 'Xóa khách hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.nguoidung.khach')
                ->with('error', 'Không thể xóa khách hàng này!');
        }
    }

    // Quản lý nhân viên
    public function nhanvien(Request $request)
    {
        // Kiểm tra quyền xem danh sách nhân viên (chỉ Quản lý)
        $this->authorize('viewAny', Nhanvien::class);
        
        $search = $request->get('search');
        
        $query = Nhanvien::with('chucvu')
            ->when($search, function($query, $search) {
                return $query->where('manv', 'like', "%{$search}%")
                            ->orWhere('ten', 'like', "%{$search}%")
                            ->orWhere('sdt', 'like', "%{$search}%");
            });

        $nhanviens = $query->orderBy('manv', 'desc')->get();
        $khachs = Khach::paginate(10);

        return view('admin.NguoiDung.Index', compact('nhanviens', 'khachs', 'search'));
    }

    public function nhanvienCreate()
    {
        // Kiểm tra quyền tạo nhân viên (chỉ Quản lý)
        $this->authorize('create', Nhanvien::class);
        
        $chucvus = Chucvu::all();
        return view('admin.NguoiDung.NhanVien.Create', compact('chucvus'));
    }

    public function nhanvienStore(Request $request)
    {
        // Kiểm tra quyền tạo nhân viên
        $this->authorize('create', Nhanvien::class);
        
        $request->validate([
            'manv' => 'required|max:5|unique:Nhanvien,manv',
            'macv' => 'required|exists:Chucvu,macv',
            'password' => 'required',
            'ten' => 'required|max:100',
            'sdt' => 'required|max:15',
            'email' => 'required|email',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required',
        ]);

        $nhanvien = Nhanvien::create($request->all());
        
        // Log activity
        ActivityLog::log(
            'created',
            $nhanvien,
            $nhanvien->manv,
            null,
            $nhanvien->toArray(),
            "Tạo nhân viên mới: {$nhanvien->ten} (#{$nhanvien->manv})"
        );

        return redirect()->route('quan-ly.nguoidung.nhanvien')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    public function nhanvienEdit($id)
    {
        $nhanvien = Nhanvien::findOrFail($id);
        
        // Kiểm tra quyền sửa nhân viên (chỉ Quản lý)
        $this->authorize('update', $nhanvien);
        $chucvus = Chucvu::all();
        return view('admin.NguoiDung.NhanVien.Edit', compact('nhanvien', 'chucvus'));
    }

    public function nhanvienUpdate(Request $request, $id)
    {
        $nhanvien = Nhanvien::findOrFail($id);
        
        // Kiểm tra quyền cập nhật nhân viên
        $this->authorize('update', $nhanvien);
        
        $request->validate([
            'macv' => 'required|exists:chucvu,macv',
            'ten' => 'required|max:100',
            'sdt' => 'required|max:15',
            'email' => 'required|email',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required',
            'trangthai' => 'required',
        ]);

        $oldData = $nhanvien->toArray();
        $data = $request->only(['macv', 'ten', 'sdt', 'email', 'ngaysinh', 'gioitinh', 'diachi', 'cccd', 'trangthai']);
        
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $nhanvien->update($data);
        
        // Log activity
        ActivityLog::log(
            'updated',
            $nhanvien,
            $nhanvien->manv,
            $oldData,
            $nhanvien->fresh()->toArray(),
            "Cập nhật thông tin nhân viên: {$nhanvien->ten} (#{$nhanvien->manv})"
        );

        return redirect()->route('quan-ly.nguoidung.nhanvien')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function nhanvienDestroy($id)
    {
        try {
            $nhanvien = Nhanvien::findOrFail($id);
            
            // Kiểm tra quyền xóa (Quản lý và không được xóa chính mình)
            $this->authorize('delete', $nhanvien);
            
            // Log before delete
            ActivityLog::log(
                'deleted',
                $nhanvien,
                $nhanvien->manv,
                $nhanvien->toArray(),
                null,
                "Xóa nhân viên: {$nhanvien->ten} (#{$nhanvien->manv})"
            );
            
            $nhanvien->delete();
            return redirect()->route('quan-ly.nguoidung.nhanvien')
                ->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.nguoidung.nhanvien')
                ->with('error', 'Không thể xóa nhân viên này!');
        }
    }
}
