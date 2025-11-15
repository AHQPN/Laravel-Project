@extends('layouts.PhuXeLayout')

@section('title', 'Chi tiết hành khách')
@section('page-title', 'Hành khách - ' . $trip['machuyendi'])

@section('content')
<div class="container-fluid">
    <!-- Trip Info Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-route me-2"></i>Thông tin chuyến đi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="trip-info-header">
                <h4 class="trip-route-title">{{ $trip['tuyen'] }}</h4>
                <div class="trip-details">
                    <div class="detail-item">
                        <i class="fas fa-clock text-primary"></i>
                        <span>{{ $trip['gio_khoi_hanh'] }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-car text-warning"></i>
                        <span>{{ $trip['bien_so'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Passengers Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>Danh sách hành khách
                <span class="badge bg-white text-success ms-2">{{ $passengers->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-4">
            @if($passengers->isEmpty())
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <h5 class="text-muted mb-2">Chưa có hành khách</h5>
                    <p class="text-muted mb-0">Chưa có hành khách nào đặt vé cho chuyến này.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 passenger-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">Ghế</th>
                                <th>Tên khách hàng</th>
                                <th class="d-none d-md-table-cell">Số điện thoại</th>
                                <th class="text-center" style="width: 200px;">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($passengers as $p)
                                <tr data-mave="{{ $p['mave'] }}" class="passenger-row">
                                    <td class="text-center">
                                        <div class="seat-badge">
                                            <i class="fas fa-chair me-1"></i>{{ $p['so_ghe'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="passenger-name">{{ $p['ten_khach'] }}</div>
                                        <small class="text-muted d-md-none">
                                            <i class="fas fa-phone me-1"></i>{{ $p['sdt'] }}
                                        </small>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <i class="fas fa-phone text-primary me-2"></i>{{ $p['sdt'] }}
                                    </td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-sm btn-toggle-pickup {{ $p['trangthai_don'] === 'da_don' ? 'btn-status-picked' : 'btn-status-pending' }}"
                                            data-mave="{{ $p['mave'] }}"
                                            data-current-status="{{ $p['trangthai_don'] }}"
                                        >
                                            @if($p['trangthai_don'] === 'da_don')
                                                <i class="fas fa-check-circle me-1"></i>
                                                <span class="d-none d-sm-inline">Đã đón</span>
                                                <span class="d-inline d-sm-none">Đón</span>
                                                @if($p['thoidiem_don'])
                                                    <br class="d-none d-sm-block">
                                                    <small class="d-none d-sm-inline status-time">{{ $p['thoidiem_don'] }}</small>
                                                @endif
                                            @else
                                                <i class="fas fa-hourglass-half me-1"></i>
                                                <span class="d-none d-sm-inline">Chưa đón</span>
                                                <span class="d-inline d-sm-none">Chờ</span>
                                            @endif
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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

.trip-info-header {
    padding: 0.5rem 0;
}

.trip-route-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #172B4D;
    margin-bottom: 1rem;
}

.trip-details {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: #6c757d;
}

.detail-item i {
    font-size: 1.2rem;
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

.passenger-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #172B4D;
    font-weight: 600;
    border: none;
    padding: 1rem;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.passenger-row {
    transition: all 0.3s ease;
}

.passenger-row:hover {
    background-color: #f8f9fa;
    transform: translateX(4px);
}

.passenger-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.seat-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 1rem;
    font-weight: 700;
    border-radius: 0.5rem;
    min-width: 60px;
}

.passenger-name {
    font-weight: 600;
    color: #172B4D;
    font-size: 1rem;
}

.btn-toggle-pickup {
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 120px;
}

.btn-status-pending {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: #6c757d;
}

.btn-status-pending:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-status-picked {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
}

.btn-status-picked:hover {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: #6c757d;
    transform: translateY(-2px);
}

.status-time {
    opacity: 0.9;
    font-size: 0.75rem;
}

@media (max-width: 576px) {
    .trip-route-title {
        font-size: 1.25rem;
    }
    
    .trip-details {
        gap: 1rem;
    }
    
    .btn-toggle-pickup {
        min-width: 80px;
        padding: 0.4rem 0.6rem;
        font-size: 0.875rem;
    }
    
    .seat-badge {
        min-width: 50px;
        font-size: 0.875rem;
    }
}

/* Toast notification styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.btn-toggle-pickup').forEach(button => {
    button.addEventListener('click', function() {
        const mave = this.dataset.mave;
        const currentStatus = this.dataset.currentStatus;
        const newStatus = currentStatus === 'da_don' ? 'chua_don' : 'da_don';
        const url = `{{ route('phu-xe.hanh-khach.toggle', ':mave') }}`.replace(':mave', mave);

        // Disable button during request
        this.disabled = true;
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ trangthai_don: newStatus }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                if (typeof showToast !== 'undefined') {
                    showToast(data.message, 'success');
                }
                
                // Update button state
                this.dataset.currentStatus = newStatus;
                if (newStatus === 'da_don') {
                    this.classList.remove('btn-status-pending');
                    this.classList.add('btn-status-picked');
                    this.innerHTML = `<i class="fas fa-check-circle me-1"></i><span class="d-none d-sm-inline">Đã đón</span><span class="d-inline d-sm-none">Đón</span>`;
                    if (data.thoidiem_don) {
                        this.innerHTML += `<br class="d-none d-sm-block"><small class="d-none d-sm-inline status-time">${data.thoidiem_don}</small>`;
                    }
                } else {
                    this.classList.remove('btn-status-picked');
                    this.classList.add('btn-status-pending');
                    this.innerHTML = `<i class="fas fa-hourglass-half me-1"></i><span class="d-none d-sm-inline">Chưa đón</span><span class="d-inline d-sm-none">Chờ</span>`;
                }
            } else {
                // Show error message
                if (typeof showToast !== 'undefined') {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
                this.innerHTML = originalHtml;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showToast !== 'undefined') {
                showToast('Không thể cập nhật trạng thái', 'error');
            } else {
                alert('Không thể cập nhật trạng thái');
            }
            this.innerHTML = originalHtml;
        })
        .finally(() => {
            this.disabled = false;
        });
    });
});
</script>
@endpush
