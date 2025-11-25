<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Hoadon;
use App\Models\CTHD;

class HoadonController extends Controller
{
    public function index(Request $request)
    {
        // Kiểm tra quyền xem danh sách hóa đơn
        $admin = $request->session()->get('admin');
        Gate::forUser($admin)->authorize('viewAny', Hoadon::class);

        $search = $request->get('search');
        $status = $request->get('status');

        $query = Hoadon::with(['khach', 'nhanvien', 'thanhtoan', 'cthds.ve.chuyendi'])
            ->when($search, function ($query, $search) {
                return $query->where('mahd', 'like', "%{$search}%")
                    ->orWhereHas('khach', function ($q) use ($search) {
                        $q->where('ten', 'like', "%{$search}%");
                    });
            })
            ->when($status, function ($query, $status) {
                return $query->where('trangthai', $status);
            });

        $hoadons = $query->orderBy('thoigian', 'desc')->get();

        return view('admin.HoaDon.Index', compact('hoadons', 'search', 'status'));
    }

    public function show($id)
    {
        $hoadon = Hoadon::with(['khach', 'nhanvien', 'thanhtoan', 'cthds.ve.chuyendi'])
            ->findOrFail($id);

        return view('admin.HoaDon.Show', compact('hoadon'));
    }

    public function approve($id, Request $request)
    {
        $hoadon = Hoadon::findOrFail($id);

        $oldStatus = $hoadon->trangthai;
        $hoadon->update(['trangthai' => 'Đã duyệt']);

        return redirect()->route('quan-ly.hoadon.index')
            ->with('success', 'Duyệt đơn hàng thành công!');
    }

    public function cancel($id, Request $request)
    {
        $hoadon = Hoadon::findOrFail($id);

        $oldStatus = $hoadon->trangthai;
        $hoadon->update(['trangthai' => 'Đã hủy']);

        // Cập nhật lại số ghế trống
        foreach ($hoadon->cthds as $cthd) {
            $chuyendi = $cthd->ve->chuyendi;
            if ($chuyendi) {
                $chuyendi->increment('SLgheconlai');
            }
        }

        return redirect()->route('quan-ly.hoadon.index')
            ->with('success', 'Hủy đơn hàng thành công!');
    }

    public function destroy($id)
    {
        try {
            $hoadon = Hoadon::findOrFail($id);

            // Only allow deletion of cancelled invoices
            if ($hoadon->trangthai !== 'Đã hủy') {
                return redirect()->route('quan-ly.hoadon.index')
                    ->with('error', 'Chỉ có thể xóa hóa đơn đã hủy!');
            }

            CTHD::where('mahd', $id)->delete();
            $hoadon->delete();
            return redirect()->route('quan-ly.hoadon.index')
                ->with('success', 'Xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->route('quan-ly.hoadon.index')
                ->with('error', 'Không thể xóa đơn hàng này!');
        }
    }
}
