@extends('layouts.admin')

@section('title', 'Quản lý Đơn đặt vé')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Quản lý Đơn đặt vé</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form action="{{ route('admin.hoadon.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã hóa đơn..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Chờ duyệt" {{ request('status') == 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="Đã duyệt" {{ request('status') == 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Khách hàng</th>
                            <th>Nhân viên</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hoadons as $item)
                        <tr>
                            <td>{{ $item->mahd }}</td>
                            <td>{{ $item->khach->ten ?? 'N/A' }}</td>
                            <td>{{ $item->nhanvien->ten ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->thoigian)->format('d/m/Y') }}</td>
                            <td>{{ number_format($item->thanhtien, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($item->trangthai == 'Chờ duyệt')
                                    <span class="badge bg-warning text-dark">{{ $item->trangthai }}</span>
                                @elseif($item->trangthai == 'Đã duyệt')
                                    <span class="badge bg-success">{{ $item->trangthai }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $item->trangthai }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->trangthai == 'Chờ duyệt')
                                    <form action="{{ route('admin.hoadon.approve', $item->mahd) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận duyệt đơn này?')">
                                            <i class="fas fa-check"></i> Duyệt
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.hoadon.cancel', $item->mahd) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận hủy đơn này?')">
                                            <i class="fas fa-times"></i> Hủy
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $hoadons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
