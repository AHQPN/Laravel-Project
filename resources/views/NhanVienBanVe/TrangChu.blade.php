@extends('layouts.NhanVienLayout')

@section('title', 'Trang chủ')
@section('page-title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid">
    
    {{-- ====== STATISTICS CARDS ====== --}}
    <div class="row g-4 mb-4">
        <!-- Vé bán trong tháng -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="stat-card stat-primary animate-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label text-primary">Vé bán trong tháng</div>
                        <div class="stat-value">{{ number_format($tongVeBan) }}</div>
                        <div class="stat-subtitle">vé đã bán</div>
                    </div>
                    <div class="stat-icon bg-primary-subtle">
                        <i class="fas fa-ticket-alt text-primary"></i>
                    </div>
                </div>
                <div class="stat-progress mt-3">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu tháng này -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="stat-card stat-success animate-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label text-success">Doanh thu tháng này</div>
                        <div class="stat-value">{{ number_format($tongDoanhThu) }}</div>
                        <div class="stat-subtitle">VNĐ</div>
                    </div>
                    <div class="stat-icon bg-success-subtle">
                        <i class="fas fa-dollar-sign text-success"></i>
                    </div>
                </div>
                <div class="stat-progress mt-3">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vé bán hôm nay -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="stat-card stat-info animate-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label text-info">Vé bán hôm nay</div>
                        <div class="stat-value">{{ number_format($veHomNay) }}</div>
                        <div class="stat-subtitle">vé trong ngày</div>
                    </div>
                    <div class="stat-icon bg-info-subtle">
                        <i class="fas fa-calendar-day text-info"></i>
                    </div>
                </div>
                <div class="stat-progress mt-3">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== CHARTS & UPCOMING TRIPS ====== --}}
    <div class="row g-4 mb-4">
        <!-- Biểu đồ vé bán theo ngày -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 animate-card">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>
                            Vé bán 7 ngày gần nhất
                        </h5>
                        <span class="badge bg-primary-subtle text-primary">Tuần này</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <canvas id="chartVeTheoNgay" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Chuyến đi sắp tới -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 animate-card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-bus me-2 text-primary"></i>
                        Chuyến đi sắp tới
                    </h5>
                </div>
                <div class="card-body p-3" style="max-height: 420px; overflow-y: auto;">
                    @forelse($chuyenDiSapToi as $chuyen)
                        <div class="trip-item mb-3">
                            <div class="d-flex align-items-start">
                                <div class="trip-icon">
                                    <i class="fas fa-route text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ $chuyen['tuyen'] }}</h6>
                                    <p class="text-muted small mb-2">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $chuyen['thoigian'] }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-chair me-1"></i>{{ $chuyen['ghe_trong'] }} ghế trống
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bus fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <p class="text-muted">Không có chuyến đi sắp tới</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ====== QUICK ACTIONS ====== --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 animate-card">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt me-2"></i>
                        Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Đặt vé mới -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <a href="{{ route('nhan-vien-ban-ve.dat-ve.create') }}" class="quick-action">
                                <div class="quick-action-icon text-primary">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div class="quick-action-label">Đặt vé mới</div>
                                <div class="quick-action-desc">Tạo đơn đặt vé</div>
                            </a>
                        </div>

                        <!-- Quản lý vé -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <a href="{{ route('nhan-vien-ban-ve.ve.index') }}" class="quick-action">
                                <div class="quick-action-icon text-success">
                                    <i class="fas fa-list-alt"></i>
                                </div>
                                <div class="quick-action-label">Quản lý vé</div>
                                <div class="quick-action-desc">Xem danh sách vé</div>
                            </a>
                        </div>

                        <!-- Theo dõi chuyến đi -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <a href="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" class="quick-action">
                                <div class="quick-action-icon text-info">
                                    <i class="fas fa-bus-alt"></i>
                                </div>
                                <div class="quick-action-label">Chuyến đi</div>
                                <div class="quick-action-desc">Theo dõi chuyến đi</div>
                            </a>
                        </div>

                        <!-- Thông tin cá nhân -->
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="quick-action">
                                <div class="quick-action-icon text-warning">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="quick-action-label">Hồ sơ</div>
                                <div class="quick-action-desc">Thông tin cá nhân</div>
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
/* ====== Gradient Background ====== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* ====== Card Animation ====== */
.animate-card {
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

/* ====== Statistics Cards ====== */
.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-color: transparent;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    margin: 0.5rem 0;
    color: #172B4D;
}

.stat-subtitle {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    font-size: 1.75rem;
}

.stat-progress .progress {
    background-color: #e9ecef;
    border-radius: 10px;
}

/* ====== Quick Actions ====== */
.quick-action {
    background: white;
    padding: 2rem 1.5rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    text-align: center;
    text-decoration: none;
    display: block;
    height: 100%;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    transition: left 0.5s ease;
}

.quick-action:hover::before {
    left: 100%;
}

.quick-action:hover {
    border-color: #667eea;
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
    text-decoration: none;
}

.quick-action-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.quick-action:hover .quick-action-icon {
    transform: scale(1.1);
}

.quick-action-label {
    font-size: 1rem;
    font-weight: 600;
    color: #172B4D;
    margin-bottom: 0.5rem;
}

.quick-action-desc {
    font-size: 0.875rem;
    color: #6c757d;
}

/* ====== Trip Items ====== */
.trip-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
    border-left: 4px solid #667eea;
    transition: all 0.2s ease;
}

.trip-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.trip-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 0.5rem;
    margin-right: 1rem;
    font-size: 1.25rem;
    flex-shrink: 0;
}

/* ====== Chart Container ====== */
.card-body canvas {
    max-height: 350px;
}

/* ====== Custom Scrollbar ====== */
.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}

/* ====== Responsive ====== */
@media (max-width: 992px) {
    .stat-value {
        font-size: 1.75rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .quick-action {
        padding: 1.5rem 1rem;
    }
    
    .quick-action-icon {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
}

/* ====== Badge Improvements ====== */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
}

/* ====== Card Enhancements ====== */
.card {
    border-radius: 0.75rem;
}

.card-header {
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const canvas = document.getElementById('chartVeTheoNgay');
        if (!canvas) {
            console.error('Chart canvas not found');
            return;
        }
        
        const ctx = canvas.getContext('2d');
        const veData = @json($veTheoNgay ?? []);
        
        if (!veData || veData.length === 0) {
            console.warn('No chart data available');
            ctx.font = '16px Arial';
            ctx.fillStyle = '#6c757d';
            ctx.textAlign = 'center';
            ctx.fillText('Không có dữ liệu', canvas.width / 2, canvas.height / 2);
            return;
        }
        
        // Create gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.4)');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: veData.map(d => d.ngay || ''),
                datasets: [{
                    label: 'Số vé bán',
                    data: veData.map(d => parseInt(d.so_ve) || 0),
                    backgroundColor: gradient,
                    borderColor: '#667eea',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(102, 126, 234, 1)',
                    hoverBorderColor: '#5568d3',
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Số vé: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: {
                                size: 12
                            },
                            color: '#6c757d'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6c757d'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
});
</script>
@endpush