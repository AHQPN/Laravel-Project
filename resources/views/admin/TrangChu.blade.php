@extends('layouts.admin.app')

@section('title', 'Trang chủ')
@section('page-title', 'Trang chủ')

@section('content')
<div class="row">
    <!-- Welcome Message -->
    <div class="col-12 mb-4">
        <div class="card border-0 bg-gradient-primary text-white">
            <div class="card-body">
                <h3><i class="fas fa-hand-paper me-2"></i>Chào mừng trở lại, {{ session('admin')->ten }}!</h3>
                <p class="mb-0">Hôm nay là {{ \Carbon\Carbon::now()->locale('vi')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Nhân viên</div>
                    <div class="stat-value">{{ $totalNhanvien }}</div>
                    <small><i class="fas fa-check-circle me-1"></i>Đang hoạt động</small>
                </div>
                <div>
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Khách hàng</div>
                    <div class="stat-value">{{ $totalKhach }}</div>
                    <small><i class="fas fa-user-plus me-1"></i>Tổng số khách</small>
                </div>
                <div>
                    <i class="fas fa-user-friends"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Chuyến đi</div>
                    <div class="stat-value">{{ $totalChuyendi }}</div>
                    <small><i class="fas fa-calendar-day me-1"></i>Sắp tới</small>
                </div>
                <div>
                    <i class="fas fa-bus"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-gradient-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Đơn chờ duyệt</div>
                    <div class="stat-value">{{ $donChoXuly }}</div>
                    <small><i class="fas fa-clock me-1"></i>Cần xử lý</small>
                </div>
                <div>
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Doanh thu -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i>Doanh thu tháng {{ \Carbon\Carbon::now()->month }}/{{ \Carbon\Carbon::now()->year }}
            </div>
            <div class="card-body text-center">
                <h2 class="text-primary mb-0">{{ number_format($doanhThuThang) }} VNĐ</h2>
                <p class="text-muted mb-0">Từ {{ $totalDonHang }} đơn hàng đã hoàn thành</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chuyến đi hôm nay -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-day me-2"></i>Chuyến đi hôm nay ({{ $chuyenDiHomNay->count() }})
            </div>
            <div class="card-body">
                @if($chuyenDiHomNay->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã chuyến</th>
                                    <th>Tuyến đường</th>
                                    <th>Giờ khởi hành</th>
                                    <th>Xe</th>
                                    <th>Ghế còn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chuyenDiHomNay as $chuyen)
                                <tr>
                                    <td><strong>{{ $chuyen->machuyendi }}</strong></td>
                                    <td>{{ $chuyen->tenchuyen }}</td>
                                    <td>
                                        <i class="fas fa-clock text-primary me-1"></i>
                                        {{ $chuyen->thoigiandi->format('H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $chuyen->xe->loaixe->tenloai ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $chuyen->SLgheconlai }} chỗ</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                        <p>Không có chuyến đi nào hôm nay</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-receipt me-2"></i>Đơn đặt vé gần đây
            </div>
            <div class="card-body">
                @if($donHangGanDay->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã HD</th>
                                    <th>Khách hàng</th>
                                    <th>Thành tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donHangGanDay as $hoadon)
                                <tr>
                                    <td><strong>{{ $hoadon->mahd }}</strong></td>
                                    <td>{{ $hoadon->khach->ten ?? 'N/A' }}</td>
                                    <td class="text-success fw-bold">{{ number_format($hoadon->thanhtien) }}đ</td>
                                    <td>
                                        @if($hoadon->trangthai == 'Đã duyệt')
                                            <span class="badge bg-success">{{ $hoadon->trangthai }}</span>
                                        @elseif($hoadon->trangthai == 'Chờ duyệt')
                                            <span class="badge bg-warning">{{ $hoadon->trangthai }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $hoadon->trangthai }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
