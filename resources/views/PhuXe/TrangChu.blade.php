@extends('layouts.PhuXeLayout')

@section('title', 'Tổng quan')
@section('page-title', 'Tổng quan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
<style>
.profile-card {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 32px;
    text-align: center;
    margin-bottom: 24px;
}
.profile-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #0052CC, #00875A);
    color: white;
    font-size: 32px;
    font-weight: 700;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.trip-list-item {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 150ms ease;
}
.trip-list-item:hover {
    border-color: #0052CC;
}
</style>
@endpush

@section('content')
    <div class="phuxe-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Xin chào, {{ session('phuxe')->ten ?? 'Phụ xe' }}</h3>
                <p class="meta mb-0">Chúc bạn một ngày làm việc tốt lành!</p>
            </div>
        </div>
    </div>

    @if($chuyenDiHomNay->isEmpty())
        <div class="phuxe-card text-center">
            <i class="fas fa-calendar-times fa-3x text-secondary mb-3"></i>
            <h3>Hôm nay không có chuyến đi nào</h3>
            <p class="meta mb-0">Vui lòng liên hệ điều hành nếu cần hỗ trợ.</p>
        </div>
    @else
        <div class="phuxe-card mb-3">
            <h3 class="mb-3">
                <i class="fas fa-route me-2 text-danger"></i>Chuyến đi hôm nay
            </h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Tuyến</th>
                            <th class="text-nowrap">Giờ</th>
                            <th class="text-nowrap d-none d-md-table-cell">Biển số</th>
                            <th class="text-center text-nowrap">Hành khách</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chuyenDiHomNay as $cd)
                            <tr>
                                <td>
                                    <strong class="d-block">{{ $cd['tuyen'] }}</strong>
                                    <small class="text-muted d-md-none">{{ $cd['bien_so'] }}</small>
                                </td>
                                <td class="text-nowrap">
                                    <span class="badge bg-primary">{{ $cd['thoigian'] }}</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-secondary">{{ $cd['bien_so'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $cd['ghe_da_don'] }}/{{ $cd['tong_ghe'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="phuxe-card">
            <h5 class="mb-3">
                <i class="fas fa-info-circle me-2 text-info"></i>Thông tin hữu ích
            </h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Hỗ trợ hành khách lên/xuống xe an toàn
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Kiểm tra vé và hành lý của hành khách
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Đảm bảo sạch sẽ trên xe
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    Hỗ trợ tài xế trong mọi tình huống
                </li>
            </ul>
        </div>
    @endif
@endsection
