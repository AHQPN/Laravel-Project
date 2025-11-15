@extends('layouts.admin.app')

@section('title', 'Thống kê doanh thu')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Thống kê doanh thu</h2>

    <div class="card mb-4">
        <div class="card-header">
            <form action="{{ route('quan-ly.thongke.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', date('Y-m-01')) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-chart-bar"></i> Xem thống kê
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-money-bill-wave"></i> Tổng doanh thu
                    </h5>
                    <h3 class="mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-ticket-alt"></i> Số vé đã bán
                    </h5>
                    <h3 class="mb-0">{{ $totalTickets }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-receipt"></i> Số hóa đơn
                    </h5>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-calculator"></i> Giá trị TB
                    </h5>
                    <h3 class="mb-0">{{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 0, ',', '.') }} VNĐ</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Biểu đồ doanh thu theo ngày</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Routes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top 10 Tuyến đường có doanh thu cao nhất</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mã chuyến</th>
                                    <th>Xe</th>
                                    <th>Thời gian</th>
                                    <th>Số vé đã bán</th>
                                    <th>Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topRoutes as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tenchuyen }}</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>{{ $item->tickets_sold }}</td>
                                    <td class="fw-bold text-success">{{ number_format($item->revenue, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: {!! json_encode($dailyRevenue->pluck('total')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', { 
                            notation: 'compact',
                            compactDisplay: 'short'
                        }).format(value);
                    }
                }
            }
        }
    }
});

// Payment Method Chart
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
const paymentChart = new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($paymentMethods->pluck('ptthanhtoan')) !!},
        datasets: [{
            data: {!! json_encode($paymentMethods->pluck('total')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + ' VNĐ';
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection
