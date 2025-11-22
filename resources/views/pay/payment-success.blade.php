@extends('layouts.khach')

@section('content')
    <style>
        .success-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 700px;
            margin: 0 auto;
        }

        .success-header {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .success-icon i {
            font-size: 2.5rem;
            color: #00b894;
        }

        .success-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .invoice-section {
            padding: 2rem;
        }

        .invoice-header {
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 1.5rem;
        }

        .invoice-header h5 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }

        .invoice-code {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .invoice-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .invoice-row:last-child {
            border-bottom: none;
        }

        .invoice-label {
            color: #6c757d;
            font-weight: 500;
        }

        .invoice-value {
            font-weight: 600;
            color: #2c3e50;
            text-align: right;
        }

        .seat-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            justify-content: flex-end;
        }

        .seat-item {
            background: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .total-section {
            background: #f8f9fa;
            padding: 1.25rem;
            border-radius: 12px;
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .total-amount {
            font-size: 1.75rem;
            font-weight: 700;
            color: #f97019;
        }

        .status-badge {
            background: #d4edda;
            color: #155724;
            padding: 0.4rem 1rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .action-section {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-home {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-home:hover {
            background: #667eea;
            color: white;
        }

        .btn-tickets {
            background: #00b894;
            color: white;
        }

        .btn-tickets:hover {
            background: #00a885;
            color: white;
        }
    </style>

    <div class="success-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-xl-8">
                    <div class="success-card">
                        <div class="success-header">
                            <div class="success-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h2 class="success-title">Thanh toán thành công!</h2>
                        </div>

                        @php
                            $firstVe = $bill->cthds->first()->ve;
                            $tripInfo = $firstVe->chuyendi;
                            $seatList = $bill->cthds->map(fn($ct) => $ct->ve->maghe);
                        @endphp

                        <div class="invoice-section">
                            <div class="invoice-header">
                                <h5>Thông tin đặt vé</h5>
                                <p class="invoice-code mb-0">Mã hóa đơn: <strong>{{ $bill->mahd }}</strong></p>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Khách hàng</span>
                                <span class="invoice-value">{{ $bill->khach->ten ?? 'Khách vãng lai' }}</span>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Số điện thoại</span>
                                <span class="invoice-value">{{ $bill->khach->sdt ?? 'N/A' }}</span>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Tuyến xe</span>
                                <span class="invoice-value">{{ $tripInfo->tenchuyen ?? 'Chuyến đi' }}</span>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Giờ khởi hành</span>
                                <span
                                    class="invoice-value">{{ \Carbon\Carbon::parse($tripInfo->thoigiandi)->format('H:i - d/m/Y') }}</span>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Ghế đã mua</span>
                                <div class="seat-list">
                                    @foreach($seatList as $seat)
                                        <span class="seat-item">{{ $seat }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Ngày đặt vé</span>
                                <span
                                    class="invoice-value">{{ \Carbon\Carbon::parse($bill->thoigian)->format('d/m/Y H:i') }}</span>
                            </div>

                            <div class="invoice-row">
                                <span class="invoice-label">Trạng thái</span>
                                <span class="status-badge">✓ {{ $bill->trangthai }}</span>
                            </div>

                            <div class="total-section">
                                <span class="total-label">Tổng tiền</span>
                                <span class="total-amount">{{ number_format($bill->thanhtien, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('home.index') }}" class="btn btn-action btn-home">
                                <i class="bi bi-house-door me-1"></i> Trang chủ
                            </a>
                            <a href="{{ route('bill.index') }}" class="btn btn-action btn-tickets">
                                <i class="bi bi-ticket-perforated me-1"></i> Vé của tôi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thanh toán thành công!',
            text: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
            timer: 2500,
            showConfirmButton: false,
            backdrop: 'rgba(102, 126, 234, 0.4)'
        });
    </script>
@endsection