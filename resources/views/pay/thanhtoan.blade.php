@extends('layouts.khach')

@section('content')
    <style>
        .payment-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .payment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 650px;
            margin: 0 auto;
        }

        .payment-header {
            background: linear-gradient(135deg, #f97019 0%, #ff8c42 100%);
            padding: 1.5rem;
            text-align: center;
            color: white;
        }

        .payment-header h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .info-section {
            padding: 1.5rem;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 600;
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
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px solid #e9ecef;
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

        .action-section {
            padding: 1.5rem;
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

        .btn-confirm {
            background: #00b894;
            color: white;
        }

        .btn-confirm:hover {
            background: #00a885;
            color: white;
        }

        .btn-cancel {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
        }

        .btn-cancel:hover {
            background: #dc3545;
            color: white;
        }

        .alert-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>

    <div class="payment-container">
        <div class="container">
            <div class="payment-card">
                <div class="payment-header">
                    <h3><i class="bi bi-check-circle me-2"></i>Xác Nhận Thanh Toán</h3>
                </div>

                @if(session('message'))
                    <div class="alert alert-danger m-3 mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('message') }}
                    </div>
                @endif

                <div class="info-section">
                    <div class="section-title">Thông tin hành khách</div>
                    <div class="info-row">
                        <span class="info-label">Họ và tên</span>
                        <span class="info-value">{{ $fullname }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số điện thoại</span>
                        <span class="info-value">{{ $phone }}</span>
                    </div>
                </div>

                <div class="info-section">
                    <div class="section-title">Chi tiết chuyến đi</div>
                    <div class="info-row">
                        <span class="info-label">Tuyến xe</span>
                        <span class="info-value">{{ $trip->tenchuyen ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Thời gian khởi hành</span>
                        <span
                            class="info-value">{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i - d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Số ghế</span>
                        <div class="seat-list">
                            @foreach($seats as $seat)
                                <span class="seat-item">{{ $seat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="alert-note">
                    <i class="bi bi-info-circle me-2"></i>
                    Vui lòng có mặt tại bến xuất phát trước <strong>30 phút</strong> giờ xe khởi hành.
                </div>

                <div class="total-section">
                    <span class="total-label">Tổng thanh toán</span>
                    <span class="total-amount">{{ number_format($total, 0, ',', '.') }}đ</span>
                </div>

                <div class="action-section">
                    <form action="{{ route('ticket.paymentConfirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tripID" value="{{ $trip->machuyendi }}">
                        <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
                        <input type="hidden" name="fullname" value="{{ $fullname }}">
                        <input type="hidden" name="phone" value="{{ $phone }}">
                        <input type="hidden" name="action" value="confirm">
                        <button type="submit" class="btn btn-action btn-confirm">
                            <i class="bi bi-check-circle me-1"></i>Xác nhận
                        </button>
                    </form>

                    <form action="{{ route('ticket.paymentConfirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tripID" value="{{ $trip->machuyendi }}">
                        <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" class="btn btn-action btn-cancel">
                            <i class="bi bi-x-circle me-1"></i>Hủy bỏ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection