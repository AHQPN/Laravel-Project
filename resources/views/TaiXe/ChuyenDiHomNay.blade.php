@extends('layouts.TaiXeLayout')

@section('title', 'Chuyến hôm nay')
@section('page-title', 'Lịch trình hôm nay')

@section('content')
    <div class="driver-card mb-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #00796b 0%, #004d40 100%);">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 fw-bold text-white">Xin chào, {{ $driver->ten ?? 'Tài xế' }}</h5>
                <p class="mb-0 small opacity-75">Chúc bạn một ngày làm việc hiệu quả!</p>
            </div>
            <a href="{{ route('tai-xe.su-co.create') }}" class="btn btn-light btn-sm text-danger fw-bold shadow-sm">
                <i class="fas fa-exclamation-triangle me-1"></i> Báo sự cố
            </a>
        </div>
    </div>

    @if($trips->isEmpty())
        <div class="driver-card text-center py-5">
            <div class="mb-3 text-muted opacity-50">
                <i class="fas fa-clipboard-list fa-4x"></i>
            </div>
            <h5 class="fw-bold text-dark">Chưa có chuyến nào</h5>
            <p class="text-muted small mb-0">Hôm nay bạn chưa được phân công chuyến đi nào.</p>
        </div>
    @else
        <h6 class="text-muted small fw-bold text-uppercase mb-3 ps-1">Danh sách chuyến đi ({{ $trips->count() }})</h6>
        
        @foreach($trips as $trip)
            <div class="driver-card position-relative overflow-hidden" data-trip="{{ $trip['machuyendi'] }}">
                {{-- Status Strip --}}
                <div class="position-absolute top-0 start-0 bottom-0" style="width: 4px; background-color: {{ $trip['raw_status'] === 'dang_chay' ? '#198754' : ($trip['raw_status'] === 'hoan_thanh' ? '#6c757d' : '#0d6efd') }};"></div>
                
                <div class="d-flex justify-content-between align-items-start mb-3 ps-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge {{ $trip['raw_status'] === 'dang_chay' ? 'bg-success' : ($trip['raw_status'] === 'hoan_thanh' ? 'bg-secondary' : 'bg-primary') }} bg-opacity-10 {{ $trip['raw_status'] === 'dang_chay' ? 'text-success' : ($trip['raw_status'] === 'hoan_thanh' ? 'text-secondary' : 'text-primary') }} border {{ $trip['raw_status'] === 'dang_chay' ? 'border-success' : ($trip['raw_status'] === 'hoan_thanh' ? 'border-secondary' : 'border-primary') }} px-2 py-1 rounded-2 fw-normal" style="font-size: 0.75rem;">
                                {{ $trip['trang_thai'] }}
                            </span>
                            <span class="text-muted small">
                                <i class="fas fa-clock me-1"></i>{{ $trip['gio_xuat_phat'] }}
                            </span>
                        </div>
                        <div class="mt-1">
                            <x-route-badge :route="$trip['tuyen']" />
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="d-block fw-bold text-dark fs-5">{{ $trip['bien_so'] }}</span>
                        <small class="text-muted" style="font-size: 0.75rem;">Biển số xe</small>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-3 mb-3 mx-2">
                    <div class="text-center">
                        <small class="d-block text-muted" style="font-size: 0.7rem; text-transform: uppercase;">Ghế trống</small>
                        <span class="fw-bold {{ $trip['so_ghe_trong'] > 0 ? 'text-success' : 'text-danger' }} fs-5">
                            {{ $trip['so_ghe_trong'] }}
                        </span>
                    </div>
                    <div style="width: 1px; height: 24px; background-color: #dee2e6;"></div>
                    <div class="text-center">
                        <small class="d-block text-muted" style="font-size: 0.7rem; text-transform: uppercase;">Tổng khách</small>
                        <span class="fw-bold text-dark fs-5">
                            {{ $trip['tong_khach'] ?? '--' }}
                        </span>
                    </div>
                    <div style="width: 1px; height: 24px; background-color: #dee2e6;"></div>
                    <div class="text-center">
                        <a href="{{ route('tai-xe.hanh-khach.show', $trip['machuyendi']) }}" class="text-decoration-none">
                            <small class="d-block text-primary" style="font-size: 0.7rem; text-transform: uppercase;">Chi tiết <i class="fas fa-chevron-right ms-1"></i></small>
                            <i class="fas fa-users text-primary fs-5 mt-1"></i>
                        </a>
                    </div>
                </div>

                <div class="d-grid gap-2 px-2">
                    @if($trip['raw_status'] === 'sap_chay')
                        <button class="btn btn-primary btn-lg btn-start-trip fw-bold" 
                                data-start-url="{{ route('tai-xe.chuyen-di.bat-dau', $trip['machuyendi']) }}">
                            <i class="fas fa-play me-2"></i> Bắt đầu chuyến đi
                        </button>
                    @elseif($trip['raw_status'] === 'dang_chay')
                        <button class="btn btn-success btn-lg btn-end-trip fw-bold" 
                                data-end-url="{{ route('tai-xe.chuyen-di.ket-thuc', $trip['machuyendi']) }}">
                            <i class="fas fa-check-circle me-2"></i> Hoàn thành chuyến đi
                        </button>
                    @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            <i class="fas fa-lock me-2"></i> Đã hoàn thành
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Start Trip
        document.querySelectorAll('.btn-start-trip').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.dataset.startUrl;
                
                Swal.fire({
                    title: 'Bắt đầu chuyến đi?',
                    text: 'Xác nhận xe đã xuất bến và bắt đầu hành trình.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Bắt đầu ngay',
                    cancelButtonText: 'Hủy bỏ'
                }).then(result => {
                    if (result.isConfirmed) {
                        handleTripAction(url, 'start');
                    }
                });
            });
        });

        // Handle End Trip
        document.querySelectorAll('.btn-end-trip').forEach(button => {
            button.addEventListener('click', function () {
                const url = this.dataset.endUrl;
                
                Swal.fire({
                    title: 'Kết thúc chuyến đi?',
                    text: 'Xác nhận xe đã về bến và trả hết khách.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xác nhận hoàn thành',
                    cancelButtonText: 'Hủy bỏ'
                }).then(result => {
                    if (result.isConfirmed) {
                        handleTripAction(url, 'end');
                    }
                });
            });
        });

        function handleTripAction(url, action) {
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng đợi trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Có lỗi xảy ra',
                        confirmButtonColor: '#0d6efd'
                    });
                    return;
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối',
                    text: 'Không thể kết nối đến máy chủ',
                    confirmButtonColor: '#0d6efd'
                });
            });
        }
    });
</script>
@endpush
