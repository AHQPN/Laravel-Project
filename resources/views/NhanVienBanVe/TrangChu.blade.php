@extends('layouts.NhanVienLayout')

@section('title', 'Trang chủ')
@section('page-title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ====== STATISTICS CARDS ====== --}}
    <div class="row g-3 mb-4">
        <!-- Vé bán trong tháng -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="card h-100 border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="text-uppercase fw-bold text-secondary small mb-1">Vé bán tháng này</div>
                            <h2 class="mb-0 fw-bold text-dark">{{ number_format($tongVeBan) }}</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fas fa-ticket-alt fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <span class="text-success fw-bold me-1"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span>so với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu tháng này -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="card h-100 border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="text-uppercase fw-bold text-secondary small mb-1">Doanh thu tháng này</div>
                            <h2 class="mb-0 fw-bold text-dark">{{ number_format($tongDoanhThu) }} <span class="fs-6 text-muted fw-normal">VNĐ</span></h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <span class="text-success fw-bold me-1"><i class="fas fa-arrow-up"></i> 8%</span>
                        <span>so với tháng trước</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vé bán hôm nay -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="card h-100 border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="text-uppercase fw-bold text-secondary small mb-1">Vé bán hôm nay</div>
                            <h2 class="mb-0 fw-bold text-dark">{{ number_format($veHomNay) }}</h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 p-3">
                            <i class="fas fa-calendar-day fa-lg"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center text-muted small">
                        <span>Ghi nhận đến hiện tại</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== CHARTS & UPCOMING TRIPS ====== --}}
    <div class="row g-3 mb-4">
        <!-- Thống kê vé bán theo ngày -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold text-dark">Thống kê vé bán 7 ngày qua</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4 text-secondary small fw-bold text-uppercase">Ngày</th>
                                    <th class="border-0 py-3 px-4 text-secondary small fw-bold text-uppercase">Số vé bán</th>
                                    <th class="border-0 py-3 px-4 text-secondary small fw-bold text-uppercase">Biểu đồ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxVe = max(array_column($veTheoNgay, 'so_ve')) ?: 1;
                                @endphp
                                @foreach($veTheoNgay as $item)
                                    <tr>
                                        <td class="px-4 py-3 fw-semibold text-dark">{{ $item['ngay'] }}</td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $item['so_ve'] }} vé</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-primary" 
                                                     role="progressbar" 
                                                     style="width: {{ $maxVe > 0 ? ($item['so_ve'] / $maxVe * 100) : 0 }}%"
                                                     aria-valuenow="{{ $item['so_ve'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="{{ $maxVe }}">
                                                    @if($item['so_ve'] > 0)
                                                        <span class="fw-bold">{{ $item['so_ve'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light border-top">
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-dark">Tổng cộng</td>
                                    <td class="px-4 py-3" colspan="2">
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            {{ array_sum(array_column($veTheoNgay, 'so_ve')) }} vé
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chuyến đi sắp tới -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold text-dark">Chuyến đi sắp tới</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($chuyenDiSapToi as $chuyen)
                            <div class="list-group-item px-3 py-3 border-bottom-0">
                                <div class="mb-2">
                                    <x-route-badge :route="$chuyen['tuyen']" />
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>{{ $chuyen['thoigian'] }}
                                    </small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark border fw-normal small">
                                        <i class="fas fa-bus me-1 text-secondary"></i> Xe thường
                                    </span>
                                    <span class="text-success small fw-bold">
                                        <i class="fas fa-chair me-1"></i>{{ $chuyen['ghe_trong'] }} ghế trống
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-2x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">Không có chuyến đi sắp tới</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white border-top text-center py-2">
                    <a href="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" class="text-decoration-none small fw-bold">Xem tất cả chuyến đi <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== QUICK ACTIONS ====== --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="m-0 fw-bold text-dark">Thao tác nhanh</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Đặt vé mới -->
                        <div class="col-md-3 col-6">
                            <a href="{{ route('nhan-vien-ban-ve.dat-ve.create') }}" class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2 border-2">
                                <i class="fas fa-plus-circle fa-2x mb-1"></i>
                                <span class="fw-bold">Đặt vé mới</span>
                            </a>
                        </div>

                        <!-- Quản lý vé -->
                        <div class="col-md-3 col-6">
                            <a href="{{ route('nhan-vien-ban-ve.ve.index') }}" class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2 border-2">
                                <i class="fas fa-ticket-alt fa-2x mb-1"></i>
                                <span class="fw-bold">Quản lý vé</span>
                            </a>
                        </div>

                        <!-- Theo dõi chuyến đi -->
                        <div class="col-md-3 col-6">
                            <a href="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2 border-2">
                                <i class="fas fa-route fa-2x mb-1"></i>
                                <span class="fw-bold">Lịch trình</span>
                            </a>
                        </div>

                        <!-- Thông tin cá nhân -->
                        <div class="col-md-3 col-6">
                            <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="btn btn-outline-secondary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center gap-2 border-2">
                                <i class="fas fa-user-cog fa-2x mb-1"></i>
                                <span class="fw-bold">Tài khoản</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Enterprise Style Overrides */
    .card {
        border-radius: 6px;
        border-color: #e0e0e0;
    }
    .shadow-sm {
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.05)!important;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    .btn-outline-primary, .btn-outline-secondary {
        border-style: dashed;
        transition: all 0.2s;
    }
    .btn-outline-primary:hover, .btn-outline-secondary:hover {
        border-style: solid;
        transform: translateY(-2px);
    }
    .text-secondary {
        color: #6c757d !important;
    }
    .bg-primary {
        background-color: #0d6efd !important;
    }
    .text-primary {
        color: #0d6efd !important;
    }
    .progress {
        background-color: #e9ecef;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush