<div class="card border-0 mb-0">
    <div class="card-body">
        <h5 class="mb-3">Vé {{ $ve->mave }}</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="text-muted mb-1">Chuyến đi</p>
                <div class="mb-2">
                    <x-route-badge :route="$tuyen" />
                </div>
                <p class="mb-0"><i class="fas fa-clock me-2 text-primary"></i>{{ \Carbon\Carbon::parse($ve->chuyendi->thoigiandi)->format('H:i d/m/Y') }}</p>
            </div>
            <div class="col-md-6">
                <p class="text-muted mb-1">Xe &amp; ghế</p>
                <p class="mb-0"><i class="fas fa-bus me-2 text-primary"></i>{{ $ve->chuyendi->xe->soxe ?? 'N/A' }}</p>
                <p class="mb-0"><i class="fas fa-chair me-2 text-primary"></i>Ghế {{ $ve->maghe }}</p>
            </div>
        </div>

        <hr>

        <div class="row mb-3">
            <div class="col-md-6">
                <p class="text-muted mb-1">Khách hàng</p>
                @if($ve->hoadon?->khach)
                    <p class="mb-0 fw-semibold">{{ $ve->hoadon->khach->hoten }}</p>
                    <p class="mb-0"><i class="fas fa-phone me-2 text-primary"></i>{{ $ve->hoadon->khach->sdt }}</p>
                    @if($ve->hoadon->khach->email)
                        <p class="mb-0"><i class="fas fa-envelope me-2 text-primary"></i>{{ $ve->hoadon->khach->email }}</p>
                    @endif
                @else
                    <p class="mb-0 text-muted fst-italic">Chưa có thông tin khách hàng</p>
                @endif
            </div>
            <div class="col-md-6">
                <p class="text-muted mb-1">Thanh toán</p>
                <p class="mb-0">Giá vé: <span class="fw-semibold text-success">{{ number_format($ve->chuyendi->gia, 0, ',', '.') }}đ</span></p>
                @if($ve->hoadon)
                    <p class="mb-0">Mã hóa đơn: <code>{{ $ve->hoadon->mahd }}</code></p>
                    <p class="mb-0">Trạng thái: 
                        @if($ve->hoadon->trangthai == 'Đã duyệt')
                            <span class="badge bg-success-subtle text-success">Đã duyệt</span>
                        @elseif($ve->hoadon->trangthai == 'Đã hủy')
                            <span class="badge bg-danger-subtle text-danger">Đã hủy</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning">{{ $ve->hoadon->trangthai }}</span>
                        @endif
                    </p>
                @else
                    <p class="mb-0 text-muted fst-italic">Chưa tạo hóa đơn</p>
                @endif
            </div>
        </div>
    </div>
</div>
