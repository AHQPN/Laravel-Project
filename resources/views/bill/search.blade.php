@extends('layouts.khach')

@section('content')
    <style>
        .history-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .history-header {
            background: white;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border-bottom: 3px solid #f97019;
        }

        .history-header h2 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }

        .history-header h2 i {
            color: #f97019;
            margin-right: 0.5rem;
        }

        .search-box {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #dee2e6;
        }

        .search-form {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        .search-input {
            flex: 1;
        }

        .search-input label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .search-input input {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 0.95rem;
        }

        .search-input input:focus {
            border-color: #f97019;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(249, 112, 25, 0.15);
        }

        .btn-search {
            background: #f97019;
            color: white;
            border: none;
            padding: 0.65rem 2rem;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .btn-search:hover {
            background: #e5640f;
            color: white;
        }

        .empty-state {
            background: white;
            padding: 3rem 2rem;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #adb5bd;
            margin-bottom: 1.5rem;
        }

        .ticket-card {
            background: white;
            border: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .ticket-header {
            background: #f97019;
            padding: 1.25rem 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-code {
            font-size: 1rem;
            font-weight: 700;
        }

        .ticket-status {
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-paid {
            background: #28a745;
            color: white;
        }

        .status-cancelled {
            background: #dc3545;
            color: white;
        }

        .ticket-body {
            padding: 1.5rem;
        }

        .route-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .route-point {
            flex: 1;
        }

        .route-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .route-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .route-arrow {
            font-size: 1.5rem;
            color: #f97019;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .seats-section {
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .seats-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .seat-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .seat-tag {
            background: white;
            border: 2px solid #f97019;
            color: #f97019;
            padding: 0.3rem 0.8rem;
            border-radius: 5px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .ticket-footer {
            border-top: 1px solid #dee2e6;
            padding: 1.25rem 1.5rem;
            background: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-info {
            display: flex;
            flex-direction: column;
        }

        .price-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f97019;
        }

        .btn-detail {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 5px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-detail:hover {
            background: #0056b3;
            color: white;
        }
    </style>

    <div class="history-container">
        <div class="container">
            <!-- Header -->
            <div class="history-header">
                <h2>
                    <i class="bi bi-clock-history"></i>
                    Lịch sử mua vé
                </h2>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <form action="{{ route('bill.search') }}" method="POST" class="search-form">
                    @csrf
                    <div class="search-input">
                        <label>Tìm kiếm theo mã hóa đơn</label>
                        <input type="text" name="MaHD" placeholder="Nhập mã hóa đơn..." value="{{ request('MaHD') }}">
                    </div>
                    <button type="submit" class="btn btn-search">
                        <i class="bi bi-search me-2"></i>Tìm kiếm
                    </button>
                </form>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tickets List -->
            @if($bills->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>Chưa có vé nào</h4>
                    <p>Bạn chưa đặt vé nào. Hãy tìm chuyến và đặt vé ngay!</p>
                    <a href="{{ route('home.index') }}" class="btn btn-search mt-3">
                        <i class="bi bi-search me-2"></i>Tìm chuyến xe
                    </a>
                </div>
            @else
                @foreach($bills as $bill)
                    @php
                        $firstTicket = $bill->cthds->first();
                        $trip = $firstTicket ? $firstTicket->ve->chuyendi : null;
                        $seats = $bill->cthds->pluck('ve.maghe');

                        // Extract route from trip name
                        if ($trip) {
                            $routeName = preg_replace('/\s*\([^)]*\)/', '', $trip->tenchuyen);
                            $routeParts = explode(' - ', $routeName);
                            $diemdi = $routeParts[0] ?? 'N/A';
                            $diemden = $routeParts[1] ?? 'N/A';
                            $departTime = \Carbon\Carbon::parse($trip->thoigiandi);
                        }
                    @endphp

                    <div class="ticket-card">
                        <!-- Header -->
                        <div class="ticket-header">
                            <div class="ticket-code">Mã hóa đơn: {{ $bill->mahd }}</div>
                            <div class="ticket-status 
                                                                @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid') status-paid
                                                                @elseif($bill->trangthai == 'Chờ duyệt') status-pending
                                                                @else status-cancelled
                                                                @endif">
                                @if($bill->trangthai == 'Đã duyệt' || $bill->trangthai == 'Paid')
                                    Đã thanh toán
                                @elseif($bill->trangthai == 'Chờ duyệt')
                                    Chờ duyệt
                                @else
                                    Đã hủy
                                @endif
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="ticket-body">
                            @if($trip)
                                <!-- Route Section -->
                                <div class="route-section">
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
                            @endif

                            <!-- Info Grid -->
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Ngày khởi hành</div>
                                    <div class="info-value">
                                        @if($trip)
                                            {{ $departTime->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Giờ khởi hành</div>
                                    <div class="info-value">
                                        @if($trip)
                                            {{ $departTime->format('H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Số lượng ghế</div>
                                    <div class="info-value">{{ $seats->count() }} ghế</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Phương thức thanh toán</div>
                                    <div class="info-value">{{ $bill->thanhtoan->tentt ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <!-- Seats Section -->
                            @if($seats->isNotEmpty())
                                <div class="seats-section">
                                    <div class="seats-label">Ghế đã đặt</div>
                                    <div class="seat-tags">
                                        @foreach($seats as $seat)
                                            <span class="seat-tag">{{ $seat }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="ticket-footer">
                            <div class="price-info">
                                <div class="price-label">Tổng tiền</div>
                                <div class="total-price">{{ number_format($bill->thanhtien, 0, ',', '.') }}đ</div>
                            </div>
                            <a href="{{ route('bill.detail', $bill->mahd) }}" class="btn btn-detail">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection