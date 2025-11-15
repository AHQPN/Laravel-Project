@extends('layouts.PhuXeLayout')

@section('title', 'Danh sách hành khách')
@section('page-title', 'Danh sách hành khách')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list-alt me-2"></i>Chọn chuyến đi
            </h5>
        </div>
        <div class="card-body p-4">
            @if($chuyenDis->isEmpty())
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-route"></i>
                    </div>
                    <h5 class="text-muted mb-2">Không có chuyến đi nào</h5>
                    <p class="text-muted mb-0">Hiện tại bạn chưa được phân công chuyến đi nào.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($chuyenDis as $cd)
                        <div class="col-12">
                            <a href="{{ route('phu-xe.hanh-khach.show', $cd['machuyendi']) }}" class="trip-card">
                                <div class="trip-card-header">
                                    <div class="trip-route">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <span class="route-text">{{ $cd['tuyen'] }}</span>
                                    </div>
                                    <div class="trip-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                                <div class="trip-card-body">
                                    <div class="trip-info-item">
                                        <i class="fas fa-clock text-primary"></i>
                                        <span>{{ $cd['thoi_gian_day_du'] }}</span>
                                    </div>
                                    <div class="trip-info-item">
                                        <i class="fas fa-car text-warning"></i>
                                        <span>{{ $cd['bien_so'] }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border-radius: 1rem;
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.empty-state {
    padding: 3rem 1rem;
}

.empty-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 3rem;
    color: white;
}

.trip-card {
    display: block;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.trip-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.trip-card:hover {
    border-color: #667eea;
    transform: translateX(4px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.1);
}

.trip-card:hover::before {
    transform: scaleY(1);
}

.trip-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.trip-route {
    display: flex;
    align-items: center;
    flex-grow: 1;
}

.route-text {
    font-size: 1.25rem;
    font-weight: 700;
    color: #172B4D;
}

.trip-arrow {
    font-size: 1.5rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.trip-card:hover .trip-arrow {
    color: #667eea;
    transform: translateX(4px);
}

.trip-card-body {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.trip-info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.95rem;
}

.trip-info-item i {
    font-size: 1.1rem;
}

@media (max-width: 576px) {
    .route-text {
        font-size: 1rem;
    }
    
    .trip-card-body {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>
@endpush
