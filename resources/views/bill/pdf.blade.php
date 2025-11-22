<!DOCTYPE html>
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ve xe - {{ $bill->mahd }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A5 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }

        .ticket-container {
            border: 1.5px solid #f97019;
        }

        .ticket-header {
            background-color: #f97019;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .ticket-header h1 {
            font-size: 14px;
            margin-bottom: 4px;
        }

        .ticket-header .invoice-code {
            font-size: 10px;
        }

        .ticket-body {
            padding: 10px;
        }

        .section {
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 0.5px solid #ddd;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .section-title {
            color: #f97019;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info-row {
            margin-bottom: 3px;
        }

        .info-label {
            display: inline-block;
            width: 32%;
            color: #666;
            font-size: 9px;
        }

        .info-value {
            display: inline-block;
            width: 66%;
            color: #000;
            font-weight: 600;
            font-size: 9px;
        }

        .route-section {
            background: #f5f5f5;
            padding: 6px;
            margin-bottom: 6px;
            text-align: center;
        }

        .route-text {
            font-size: 11px;
            font-weight: bold;
            color: #000;
        }

        .route-arrow {
            color: #f97019;
            margin: 0 6px;
        }

        .seats-section {
            background: #f5f5f5;
            padding: 6px;
            margin-top: 5px;
        }

        .seats-label {
            color: #666;
            margin-bottom: 3px;
            font-size: 9px;
        }

        .seats-list {
            font-size: 10px;
            font-weight: bold;
            color: #f97019;
        }

        .total-section {
            background: #f5f5f5;
            padding: 8px;
            text-align: center;
            margin-top: 6px;
        }

        .total-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #f97019;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 9px;
            margin-top: 4px;
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

        .qr-section {
            text-align: center;
            padding: 8px;
            border-top: 1px dashed #ddd;
            margin-top: 6px;
        }

        .qr-code {
            margin: 6px auto;
        }

        .qr-text {
            font-size: 8px;
            color: #666;
            margin-top: 4px;
        }

        .footer-note {
            text-align: center;
            font-size: 7px;
            color: #999;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 0.5px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="ticket-header">
            <h1>VE XE KHACH</h1>
            <div class="invoice-code">Ma hoa don: {{ $bill->mahd }}</div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            @php
                $firstTicket = $bill->cthds->first();
                $trip = $firstTicket ? $firstTicket->ve->chuyendi : null;
                $vehicle = $trip ? $trip->xe : null;
                $seats = $bill->cthds->pluck('ve.maghe');

                $diemdi = 'N/A';
                $diemden = 'N/A';
                if ($trip) {
                    $routeName = preg_replace('/\s*\([^)]*\)/', '', $trip->tenchuyen);
                    $routeParts = explode(' - ', $routeName);
                    $diemdi = $routeParts[0] ?? 'N/A';
                    $diemden = $routeParts[1] ?? 'N/A';
                }
            @endphp

            <!-- Thong tin khach hang -->
            <div class="section">
                <div class="section-title">THONG TIN HANH KHACH</div>
                <div class="info-row">
                    <span class="info-label">Ho va ten:</span>
                    <span class="info-value">{{ $bill->khach->ten ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">So dien thoai:</span>
                    <span class="info-value">{{ $bill->khach->sdt ?? 'N/A' }}</span>
                </div>
                @if($bill->khach && $bill->khach->email)
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $bill->khach->email }}</span>
                    </div>
                @endif
            </div>

            <!-- Thong tin chuyen di -->
            @if($trip)
                <div class="section">
                    <div class="section-title">THONG TIN CHUYEN DI</div>

                    <div class="route-section">
                        <span class="route-text">{{ $diemdi }}</span>
                        <span class="route-arrow">---></span>
                        <span class="route-text">{{ $diemden }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Ngay khoi hanh:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gio khoi hanh:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i') }}</span>
                    </div>
                    @if($vehicle)
                        <div class="info-row">
                            <span class="info-label">Loai xe:</span>
                            <span class="info-value">{{ $vehicle->loaixe->tenloaixe ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Bien so xe:</span>
                            <span class="info-value">{{ $vehicle->bienso }}</span>
                        </div>
                    @endif

                    @if($seats->isNotEmpty())
                        <div class="seats-section">
                            <div class="seats-label">Ghe da dat:</div>
                            <div class="seats-list">{{ $seats->implode(', ') }}</div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Thong tin ve -->
            <div class="section">
                <div class="section-title">THONG TIN THANH TOAN</div>
                <div class="info-row">
                    <span class="info-label">Ngay dat ve:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($bill->thoigian)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">So luong ghe:</span>
                    <span class="info-value">{{ $seats->count() }} ghe</span>
                </div>
                @if($trip)
                    <div class="info-row">
                        <span class="info-label">Gia ve/ghe:</span>
                        <span class="info-value">{{ number_format($trip->gia, 0, ',', '.') }}d</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Phuong thuc TT:</span>
                    <span class="info-value">{{ $bill->thanhtoan->tentt ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Tong tien -->
            <div class="total-section">
                <div class="total-label">TONG THANH TOAN</div>
                <div class="total-amount">{{ number_format($bill->thanhtien, 0, ',', '.') }}d</div>
                <div class="status-badge 
                    @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid') status-paid
                    @elseif($bill->trangthai == 'Chờ duyệt') status-pending
                    @else status-cancelled
                    @endif">
                    @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid')
                        Da thanh toan
                    @elseif($bill->trangthai == 'Chờ duyệt')
                        Cho duyet
                    @else
                        Da huy
                    @endif
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-section">
                <div class="qr-code">
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" width="120">
                </div>
                <div class="qr-text">Quet ma QR de kiem tra thong tin ve</div>
            </div>

            <!-- Footer Note -->
            <div class="footer-note">
                Cam on quy khach da su dung dich vu cua chung toi.<br>
                Vui long xuat trinh ve nay khi len xe. Chuc quy khach co chuyen di vui ve!
            </div>
        </div>
    </div>
</body>

</html>