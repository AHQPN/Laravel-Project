@extends('layouts.TaiXeLayout')

@section('title', 'Chuyến hôm nay')
@section('page-title', 'Chuyến hôm nay')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
<style>
.driver-header {
    background: white;
    padding: 24px;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    margin-bottom: 24px;
}
.driver-time {
    font-size: 48px;
    font-weight: 700;
    color: #0052CC;
    line-height: 1;
    font-family: var(--font-mono);
}
.driver-route {
    font-size: 24px;
    font-weight: 700;
    color: #172B4D;
    margin-top: 8px;
}
.action-tile {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 32px;
    text-align: center;
    text-decoration: none;
    color: #172B4D;
    display: block;
    transition: all 150ms ease;
    min-height: 160px;
}
.action-tile:hover {
    border-color: #0052CC;
    background: #F4F5F7;
    transform: translateY(-4px);
    text-decoration: none;
}
.action-tile-icon {
    font-size: 56px;
    margin-bottom: 16px;
}
.action-tile-label {
    font-size: 20px;
    font-weight: 700;
}
</style>
@endpush

@section('content')
    <div class="driver-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Xin chào, {{ $driver->ten ?? 'Tài xế' }}</h3>
                <p class="meta mb-0">Chúc bạn một chuyến đi an toàn!</p>
            </div>
            <a href="{{ route('tai-xe.su-co.create') }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>Báo cáo sự cố
            </a>
        </div>
    </div>

    @if($trips->isEmpty())
        <div class="driver-card text-center">
            <i class="fas fa-bus-alt fa-3x text-secondary mb-3"></i>
            <h3>Hôm nay bạn chưa có chuyến nào</h3>
            <p class="meta mb-0">Vui lòng liên hệ điều hành nếu cần hỗ trợ.</p>
        </div>
    @else
        @foreach($trips as $trip)
            <div class="driver-card" data-trip="{{ $trip['machuyendi'] }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4">{{ $trip['badge'] }}</span>
                            <span class="fw-semibold text-uppercase text-muted small">{{ $trip['trang_thai'] }}</span>
                        </div>
                        <h3 class="mt-2">{{ $trip['tuyen'] }}</h3>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                        <i class="fas fa-ticket-alt me-1 text-success"></i>
                        {{ $trip['so_ghe_trong'] }} ghế trống
                    </span>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="meta">
                            <i class="fas fa-clock me-2 text-primary"></i> Giờ xuất phát
                        </div>
                        <p class="fw-semibold fs-5 mb-0">{{ $trip['gio_xuat_phat'] }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <div class="meta">
                            <i class="fas fa-id-card me-2 text-primary"></i> Biển số
                        </div>
                        <p class="fw-semibold fs-5 mb-0">{{ $trip['bien_so'] }}</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('tai-xe.hanh-khach.show', $trip['machuyendi']) }}" class="btn btn-outline-primary flex-grow-1">
                        <i class="fas fa-users me-2 d-none d-sm-inline"></i><i class="fas fa-users d-sm-none"></i><span class="d-none d-sm-inline">Danh sách hành khách</span><span class="d-sm-none">Hành khách</span>
                    </a>
                    <button
                        class="btn btn-primary flex-grow-1 btn-start-trip"
                        data-start-url="{{ route('tai-xe.chuyen-di.bat-dau', $trip['machuyendi']) }}"
                        {{ $trip['raw_status'] !== 'sap_chay' ? 'disabled' : '' }}
                    >
                        <i class="fas fa-play me-2 d-none d-sm-inline"></i><i class="fas fa-play d-sm-none"></i><span class="d-none d-sm-inline">Bắt đầu chuyến</span><span class="d-sm-none">Bắt đầu</span>
                    </button>
                    <button
                        class="btn btn-success flex-grow-1 btn-end-trip"
                        data-end-url="{{ route('tai-xe.chuyen-di.ket-thuc', $trip['machuyendi']) }}"
                        {{ $trip['raw_status'] !== 'dang_chay' ? 'disabled' : '' }}
                    >
                        <i class="fas fa-stop me-2 d-none d-sm-inline"></i><i class="fas fa-stop d-sm-none"></i><span class="d-none d-sm-inline">Kết thúc chuyến</span><span class="d-sm-none">Kết thúc</span>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-start-trip').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.startUrl;
            const card = this.closest('.driver-card');
            Swal.fire({
                title: 'Bắt đầu chuyến?',
                text: 'Xác nhận để chuyển chuyến sang trạng thái đang chạy.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Bắt đầu',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) {
                            showToast(data.message || 'Không thể bắt đầu chuyến.', 'error');
                            return;
                        }
                        showToast(data.message, 'success');
                        card.querySelector('.btn-start-trip').setAttribute('disabled', true);
                        card.querySelector('.btn-end-trip').removeAttribute('disabled');
                        const statusBadge = card.querySelector('.fw-semibold.text-uppercase');
                        if (statusBadge) {
                            statusBadge.textContent = 'Đang chạy';
                        }
                    })
                    .catch(() => {
                        showToast('Có lỗi khi kết nối máy chủ.', 'error');
                    });
                }
            });
        });
    });

    document.querySelectorAll('.btn-end-trip').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.endUrl;
            const card = this.closest('.driver-card');
            Swal.fire({
                title: 'Kết thúc chuyến?',
                text: 'Xác nhận để chuyển chuyến sang trạng thái hoàn thành.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Kết thúc',
                cancelButtonText: 'Hủy'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        if (!ok) {
                            showToast(data.message || 'Không thể kết thúc chuyến.', 'error');
                            return;
                        }
                        showToast(data.message, 'success');
                        card.querySelector('.btn-start-trip').removeAttribute('disabled');
                        card.querySelector('.btn-end-trip').setAttribute('disabled', true);
                        const statusBadge = card.querySelector('.fw-semibold.text-uppercase');
                        if (statusBadge) {
                            statusBadge.textContent = 'Hoàn thành';
                        }
                    })
                    .catch(() => {
                        showToast('Có lỗi khi kết nối máy chủ.', 'error');
                    });
                }
            });
        });
    });
</script>
@endpush

