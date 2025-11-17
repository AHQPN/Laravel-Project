@extends('layouts.khach')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Xác Nhận Thanh Toán</h2>

                @if(session('message'))
                    <div class="alert alert-danger">{{ session('message') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-person-check-fill"></i> Thông tin hành khách
                    </div>
                    <div class="card-body">
                        <p><strong>Họ tên:</strong> {{ $fullname }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $phone }}</p>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-info text-dark">
                        <i class="bi bi-bus-front-fill"></i> Chi tiết chuyến đi
                    </div>
                    <div class="card-body">
                        <p><strong>Chuyến:</strong> {{ $trip->tenchuyen ?? 'N/A' }}</p>
                        <p><strong>Khởi hành:</strong> {{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i d/m/Y') }}</p>
                        <p><strong>Số ghế:</strong> <span class="fw-bold">{{ implode(', ', $seats) }}</span></p>
                        <p><strong>Số lượng:</strong> {{ count($seats) }} vé</p>
                        <h4 class="text-danger">Tổng tiền: {{ number_format($total, 0, ',', '.') }} ₫</h4>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header">
                        <i class="bi bi-credit-card-fill"></i> Hành động
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <form action="{{ route('ticket.paymentConfirm') }}" method="POST" class="me-2">
                            @csrf
                            <input type="hidden" name="tripID" value="{{ $trip->machuyendi }}">
                            <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
                            <input type="hidden" name="fullname" value="{{ $fullname }}">
                            <input type="hidden" name="phone" value="{{ $phone }}">
                            <input type="hidden" name="action" value="confirm">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle-fill"></i> Xác nhận thanh toán
                            </button>
                        </form>

                        <form action="{{ route('ticket.paymentConfirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tripID" value="{{ $trip->machuyendi }}">
                            <input type="hidden" name="seats" value="{{ implode(',', $seats) }}">
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle"></i> Hủy đặt vé
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-muted">
                        Vé sẽ được tự động hủy nếu bạn không xác nhận.
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
