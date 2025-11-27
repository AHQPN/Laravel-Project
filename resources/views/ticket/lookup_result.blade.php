@extends('layouts.khach')

@section('content')
    <style>
        .result-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .back-button {
            margin-bottom: 1.5rem;
        }

        .btn-back {
            background: white;
            border: 2px solid #dee2e6;
            color: #495057;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            border-color: #f97019;
            color: #f97019;
            transform: translateX(-3px);
        }

        .error-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 3rem 2rem;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .error-box i {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .error-box h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .error-box p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .ticket-result {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }

        .ticket-header {
            background: linear-gradient(135deg, #f97019 0%, #e5640f 100%);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .ticket-header h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .ticket-code-display {
            font-size: 1.5rem;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: inline-block;
            margin-top: 1rem;
            letter-spacing: 2px;
        }

        .ticket-status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        .status-available {
            background: #6c757d;
            color: white;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-booked {
            background: #28a745;
            color: white;
        }

        .status-approved {
            background: #198754;
            color: white;
        }

        .ticket-body {
            padding: 2rem;
        }

        .section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #f97019;
            font-size: 1.25rem;
        }

        .route-display {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .route-point {
            flex: 1;
            text-align: center;
        }

        .route-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .route-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .route-arrow {
            font-size: 2rem;
            color: #f97019;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            color: #f97019;
        }

        .info-value {
            font-size: 1.05rem;
            font-weight: 600;
            color: #2c3e50;
            padding-left: 1.75rem;
        }

        .seat-display {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }

        .seat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #f97019;
            margin-bottom: 0.5rem;
        }

        .seat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .invoice-box {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 1.5rem;
            border-radius: 5px;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .invoice-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .invoice-label {
            font-size: 0.85rem;
            color: #0c5aa6;
            font-weight: 600;
        }

        .invoice-value {
            font-size: 0.95rem;
            color: #0d6efd;
            font-weight: 700;
        }

        .price-display {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
        }

        .price-label {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .price-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #f97019;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.85rem;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #f97019;
            color: white;
        }

        .btn-primary:hover {
            background: #e5640f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 112, 25, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem 1.25rem;
            border-radius: 5px;
            margin-top: 1rem;
        }

        .warning-box i {
            color: #856404;
            margin-right: 0.5rem;
        }

        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .route-display {
                flex-direction: column;
                gap: 1rem;
            }

            .route-arrow {
                transform: rotate(90deg);
            }

            .info-grid,
            .invoice-info {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <div class="result-container">
        <div class="container">
            <!-- Back Button -->
            <div class="back-button">
                <a href="{{ route('ticket.lookupForm') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Tra cứu vé khác
                </a>
            </div>

            @if($message)
                <!-- Error State -->
                <div class="error-box">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h3>Không tìm thấy vé</h3>
                    <p>{{ $message }}</p>
                    <a href="{{ route('ticket.lookupForm') }}" class="btn-action btn-primary"
                        style="display: inline-block; max-width: 300px;">
                        <i class="bi bi-search me-2"></i>
                        Thử lại
                    </a>
                </div>
            @elseif($ticket)
                <!-- Ticket Found -->
                <div class="ticket-result">
                    <!-- Header -->
                    <div class="ticket-header">
                        <h2>
                            <i class="bi bi-ticket-perforated-fill me-2"></i>
                            Thông tin vé
                        </h2>
                        <div class="ticket-code-display">{{ $ticket->mave }}</div>

                        <div class="ticket-status-badge 
                                    @if($ticket->trangthai == 'Available') status-available
                                    @elseif($ticket->trangthai == 'Pending') status-pending
                                    @elseif($ticket->trangthai == 'Booked') status-booked
                                    @elseif($ticket->trangthai == 'approved') status-approved
                                    @endif">
                            @if($ticket->trangthai == 'Available') Còn trống
                            @elseif($ticket->trangthai == 'Pending') Đang giữ chỗ
                            @elseif($ticket->trangthai == 'Booked') Đã đặt
                            @elseif($ticket->trangthai == 'approved') Đã thanh toán
                            @else {{ $ticket->trangthai }}
                            @endif
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="ticket-body">
                        @if($ticket->chuyendi)
                            @php
                                $trip = $ticket->chuyendi;
                                $routeName = preg_replace('/\s*\([^)]*\)/', '', $trip->tenchuyen);
                                $routeParts = explode(' - ', $routeName);
                                $diemdi = $routeParts[0] ?? 'N/A';
                                $diemden = $routeParts[1] ?? 'N/A';
                                $departTime = \Carbon\Carbon::parse($trip->thoigiandi);
                            @endphp

                            <!-- Route Section -->
                            <div class="section">
                                <div class="section-title">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    Thông tin chuyến đi
                                </div>
                                <div class="route-display">
                                    <div class="route-point">
                                        <div class="route-label">Điểm đi</div>
                                        <div class="route-value">{{ $diemdi }}</div>
                                    </div>
                                    <div class="route-arrow">
                                        <i class="bi bi-arrow-right-circle-fill"></i>
                                    </div>
                                    <div class="route-point">
                                        <div class="route-label">Điểm đến</div>
                                        <div class="route-value">{{ $diemden }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trip Details -->
                            <div class="section">
                                <div class="section-title">
                                    <i class="bi bi-calendar-event"></i>
                                    Chi tiết chuyến
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-calendar3"></i>
                                            Ngày khởi hành
                                        </div>
                                        <div class="info-value">{{ $departTime->format('d/m/Y') }}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-clock"></i>
                                            Giờ khởi hành
                                        </div>
                                        <div class="info-value">{{ $departTime->format('H:i') }}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-hourglass-split"></i>
                                            Thời gian di chuyển
                                        </div>
                                        <div class="info-value">{{ $trip->thoigiandichuyen }} phút</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-bus-front"></i>
                                            Mã chuyến
                                        </div>
                                        <div class="info-value">{{ $trip->machuyendi }}</div>
                                    </div>

                                    @if($trip->xe)
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-truck"></i>
                                                Loại xe
                                            </div>
                                            <div class="info-value">{{ $trip->xe->loaixe->tenloai ?? 'N/A' }}</div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-card-text"></i>
                                                Biển số xe
                                            </div>
                                            <div class="info-value">{{ $trip->xe->soxe ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Seat Information -->
                            <div class="section">
                                <div class="section-title">
                                    <i class="bi bi-seat"></i>
                                    Thông tin ghế
                                </div>
                                <div class="seat-display">
                                    <div class="seat-number">{{ $ticket->maghe }}</div>
                                    <div class="seat-label">Số ghế</div>
                                </div>

                                @if($ticket->trangthai_don)
                                    <div class="info-item mt-3">
                                        <div class="info-label">
                                            <i class="bi bi-person-check"></i>
                                            Trạng thái đón khách
                                        </div>
                                        <div class="info-value">
                                            @if($ticket->trangthai_don == 'da_don')
                                                <span class="badge bg-success">Đã đón</span>
                                                @if($ticket->thoidiem_don)
                                                    <small class="text-muted d-block mt-1">
                                                        {{ \Carbon\Carbon::parse($ticket->thoidiem_don)->format('d/m/Y H:i') }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Chưa đón</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($ticket->pending_expires_at && $ticket->trangthai == 'Pending')
                                    <div class="warning-box">
                                        <i class="bi bi-clock-history"></i>
                                        <p>
                                            <strong>Lưu ý:</strong> Ghế đang được giữ cho đến
                                            {{ \Carbon\Carbon::parse($ticket->pending_expires_at)->format('H:i d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Invoice Information -->
                            @if(isset($hoadon) && $hoadon)
                                <div class="section">
                                    <div class="section-title">
                                        <i class="bi bi-receipt"></i>
                                        Thông tin hóa đơn
                                    </div>
                                    <div class="invoice-box">
                                        <div class="invoice-info">
                                            <div class="invoice-item">
                                                <div class="invoice-label">Mã hóa đơn</div>
                                                <div class="invoice-value">{{ $hoadon->mahd }}</div>
                                            </div>

                                            <div class="invoice-item">
                                                <div class="invoice-label">Thời gian đặt</div>
                                                <div class="invoice-value">
                                                    {{ \Carbon\Carbon::parse($hoadon->thoigian)->format('H:i d/m/Y') }}
                                                </div>
                                            </div>

                                            @if($hoadon->khach)
                                                <div class="invoice-item">
                                                    <div class="invoice-label">Khách hàng</div>
                                                    <div class="invoice-value">{{ $hoadon->khach->ten }}</div>
                                                </div>

                                                <div class="invoice-item">
                                                    <div class="invoice-label">Số điện thoại</div>
                                                    <div class="invoice-value">{{ $hoadon->khach->sdt }}</div>
                                                </div>
                                            @endif

                                            @if($hoadon->thanhtoan)
                                                <div class="invoice-item">
                                                    <div class="invoice-label">Phương thức thanh toán</div>
                                                    <div class="invoice-value">{{ $hoadon->thanhtoan->ptthanhtoan }}</div>
                                                </div>
                                            @endif

                                            <div class="invoice-item">
                                                <div class="invoice-label">Trạng thái thanh toán</div>
                                                <div class="invoice-value">
                                                    @if($hoadon->trangthai == 'Paid' || $hoadon->trangthai == 'Đã duyệt')
                                                        <span class="badge bg-success">Đã thanh toán</span>
                                                    @elseif($hoadon->trangthai == 'Chờ duyệt')
                                                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $hoadon->trangthai }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="section">
                                <div class="price-display">
                                    <div class="price-label">Giá vé</div>
                                    <div class="price-value">{{ number_format($trip->gia, 0, ',', '.') }}đ</div>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if(isset($hoadon) && $hoadon)
                                <div class="action-buttons">
                                    <a href="{{ route('bill.detail', $hoadon->mahd) }}" class="btn-action btn-primary">
                                        <i class="bi bi-file-text me-2"></i>
                                        Xem hóa đơn chi tiết
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Không tìm thấy thông tin chuyến đi cho vé này.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection