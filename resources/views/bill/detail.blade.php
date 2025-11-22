@extends('layouts.khach')

@section('content')
    <style>
        .detail-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .detail-card {
            background: white;
            border: 1px solid #dee2e6;
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        .detail-header {
            background: #f97019;
            padding: 2rem 2.5rem;
            color: white;
        }

        .company-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .detail-header h3 {
            margin: 0.5rem 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .invoice-code {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .detail-section {
            padding: 1.5rem 2.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-section:last-of-type {
            border-bottom: none;
        }

        .section-title {
            color: #f97019;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            font-size: 1rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .detail-row {
            padding-left: 0;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
        }

        .status-badge {
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-paid {
            background: #28a745;
            color: white;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-cancelled {
            background: #dc3545;
            color: white;
        }

        .seat-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .seat-item {
            background: white;
            border: 2px solid #f97019;
            color: #f97019;
            padding: 0.3rem 0.8rem;
            border-radius: 5px;
            font-weight: 700;
            font-size: 0.9rem;
        }



        .total-section {
            background: #f8f9fa;
            padding: 2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #dee2e6;
        }

        .total-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 700;
            color: #f97019;
        }

        .qr-section {
            padding: 2rem 2.5rem;
            background: white;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .qr-code-container {
            display: inline-block;
            padding: 1rem;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin: 0 auto 1rem;
        }

        .qr-code-container svg {
            display: block;
        }

        .qr-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .qr-hint {
            font-size: 0.85rem;
            color: #495057;
            margin-top: 0.75rem;
        }

        .qr-hint strong {
            color: #f97019;
            font-weight: 700;
        }

        .btn-download-qr {
            margin-top: 1rem;
            padding: 0.5rem 1.25rem;
            background: #f97019;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-download-qr:hover {
            background: #e06010;
        }

        .action-section {
            padding: 1.5rem 2.5rem;
            background: #f8f9fa;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-action {
            padding: 0.6rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            border: none;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
            color: white;
        }

        .btn-print {
            background: #28a745;
            color: white;
        }

        .btn-print:hover {
            background: #218838;
            color: white;
        }
    </style>

    <div class="detail-container">
        <div class="container">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="company-logo">Chi Tiết Hóa Đơn</div>
                    <div class="invoice-code">Mã hóa đơn: {{ $bill->mahd }}</div>
                </div>

                @php
                    $firstTicket = $bill->cthds->first();
                    $trip = $firstTicket ? $firstTicket->ve->chuyendi : null;
                    $vehicle = $trip ? $trip->xe : null;
                    $seats = $bill->cthds->pluck('ve.maghe');
                @endphp

                <!-- Thông tin khách hàng -->
                <div class="detail-section">
                    <div class="section-title">
                        <i class="bi bi-person-fill"></i>
                        Thông tin hành khách
                    </div>
                    <div class="detail-grid">
                        <div class="detail-row">
                            <div class="detail-label">Họ và tên</div>
                            <div class="detail-value">{{ $bill->khach->ten ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Số điện thoại</div>
                            <div class="detail-value">{{ $bill->khach->sdt ?? 'N/A' }}</div>
                        </div>
                        @if($bill->khach && $bill->khach->email)
                            <div class="detail-row">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">{{ $bill->khach->email }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thông tin chuyến đi -->
                @if($trip)
                    <div class="detail-section">
                        <div class="section-title">
                            <i class="bi bi-bus-front-fill"></i>
                            Thông tin chuyến đi
                        </div>

                        @php
                            // Extract route from trip name (e.g., "Đà Lạt - Sài Gòn (Sáng)")
                            $routeName = preg_replace('/\s*\([^)]*\)/', '', $trip->tenchuyen);
                            $routeParts = explode(' - ', $routeName);
                            $diemdi = $routeParts[0] ?? 'N/A';
                            $diemden = $routeParts[1] ?? 'N/A';
                        @endphp

                        <div class="detail-grid">
                            <div class="detail-row">
                                <div class="detail-label">Điểm đi</div>
                                <div class="detail-value">{{ $diemdi }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Điểm đến</div>
                                <div class="detail-value">{{ $diemden }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Ngày khởi hành</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('d/m/Y') }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Giờ khởi hành</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i') }}</div>
                            </div>
                            @if($vehicle)
                                <div class="detail-row">
                                    <div class="detail-label">Loại xe</div>
                                    <div class="detail-value">{{ $vehicle->loaixe->tenloaixe ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Biển số</div>
                                    <div class="detail-value">{{ $vehicle->bienso }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Thông tin vé -->
                <div class="detail-section">
                    <div class="section-title">
                        <i class="bi bi-ticket-perforated-fill"></i>
                        Thông tin vé
                    </div>
                    <div class="detail-grid">
                        <div class="detail-row">
                            <div class="detail-label">Ngày đặt vé</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($bill->thoigian)->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Số lượng ghế</div>
                            <div class="detail-value">{{ $seats->count() }} ghế</div>
                        </div>
                        @if($trip)
                            <div class="detail-row">
                                <div class="detail-label">Giá vé/ghế</div>
                                <div class="detail-value">{{ number_format($trip->gia, 0, ',', '.') }}đ</div>
                            </div>
                        @endif
                        <div class="detail-row">
                            <div class="detail-label">Phương thức thanh toán</div>
                            <div class="detail-value">{{ $bill->thanhtoan->tentt ?? 'N/A' }}</div>
                        </div>
                    </div>

                    @if($seats->isNotEmpty())
                        <div style="margin-top: 1.5rem; padding: 1rem; background: #f8f9fa; border-radius: 10px;">
                            <div class="detail-label" style="margin-bottom: 0.75rem;">Số ghế đã đặt</div>
                            <div class="seat-list">
                                @foreach($seats as $seat)
                                    <span class="seat-item">{{ $seat }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Tổng tiền -->
                <div class="total-section">
                    <div>
                        <div class="total-label">Tổng thanh toán</div>
                        <div class="total-amount">{{ number_format($bill->thanhtien, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="status-badge 
                                @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid') status-paid
                                @elseif($bill->trangthai == 'Chờ duyệt') status-pending
                                @else status-cancelled
                                @endif">
                        @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid')
                            ✓ Đã thanh toán
                        @elseif($bill->trangthai == 'Chờ duyệt')
                            ⏱ Chờ duyệt
                        @else
                            ✗ Đã hủy
                        @endif
                    </div>
                </div>

                <!-- QR Code -->
                <div class="qr-section">
                    <div class="qr-code-container" id="qr-code">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->margin(1)->encoding('UTF-8')->generate($qrContent) !!}
                    </div>
                    <div class="qr-text">Quét mã QR để kiểm tra thông tin vé</div>
                    <div class="qr-hint">Mã hóa đơn: <strong>{{ $bill->mahd }}</strong></div>
                    <button onclick="downloadQRCode()" class="btn btn-download-qr">
                        <i class="bi bi-download me-1"></i> Tải xuống QR Code
                    </button>
                </div>

                <!-- Actions -->
                <div class="action-section">
                    <a href="{{ route('bill.index') }}" class="btn btn-action btn-back">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                    <button onclick="window.print()" class="btn btn-action btn-print">
                        <i class="bi bi-printer me-1"></i> In vé
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style media="print">
        .detail-container {
            background: white !important;
            padding: 0 !important;
        }

        .action-section,
        header,
        footer,
        .copyright-section {
            display: none !important;
        }

        .detail-card {
            box-shadow: none !important;
            max-width: 100% !important;
        }
    </style>

    <script>
        function downloadQRCode() {
            const qrCode = document.getElementById('qr-code');
            const svg = qrCode.querySelector('svg');

            if (!svg) {
                alert('Không tìm thấy QR code để tải xuống!');
                return;
            }

            // Tạo canvas từ SVG
            const svgData = new XMLSerializer().serializeToString(svg);
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = function () {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0);

                // Tải xuống
                const link = document.createElement('a');
                link.download = 'QR-Code-{{ $bill->mahd }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            };

            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
        }
    </script>
@endsection