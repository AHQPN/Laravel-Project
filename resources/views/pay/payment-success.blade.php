@extends('layouts.khach')

@section('content')

    @if (session('message'))
        @php $type = session('messageType') ?? 'success'; @endphp
        <div id="mes" class="d-none position-fixed top-0 start-50 translate-middle-x mt-3 p-3 rounded-3 shadow"
             role="alert" aria-live="assertive" aria-atomic="true"
             style="z-index: 9999; min-width: 350px; text-align:center;">
            <div class="d-flex">
                <div class="toast-body">
                    {!! session('message') !!}
                </div>
            </div>
        </div>
    @endif

    <div class="container mt-5 text-center">
        <h2 class="text-success mb-4">
            <i class="fa fa-check-circle"></i> Thanh toán thành công!
        </h2>

        <div class="card shadow-lg p-4 mx-auto" style="max-width: 600px;">
            <h4 class="mb-3 text-primary">Hóa đơn thanh toán</h4>

            @php
                // ✅ SỬA: cthd → cthds (số nhiều)
                $firstVe = $bill->cthds->first()->ve;
                $tripInfo = $firstVe->chuyendi;
                $seatList = $bill->cthds->map(fn($ct) => $ct->ve->maghe)->join(', ');
            @endphp

            <table class="table table-borderless text-start">
                <tr>
                    <th>Mã hóa đơn:</th>
                    <td>{{ $bill->mahd }}</td>
                </tr>
                <tr>
                    <th>Khách hàng:</th>
                    <td>{{ $bill->khach->ten ?? 'Khách vãng lai' }} ({{ $bill->khach->sdt ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <th>Chuyến đi:</th>
                    <td>{{ $tripInfo->tenchuyen ?? 'Chuyến đi' }}</td>
                </tr>
                <tr>
                    <th>Thời gian khởi hành:</th>
                    <td>{{ \Carbon\Carbon::parse($tripInfo->thoigiandi)->format('H:i d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Ghế đã mua:</th>
                    <td>{{ $seatList }}</td>
                </tr>
                <tr>
                    <th>Số lượng vé:</th>
                    <td>{{ $bill->soluong }}</td>
                </tr>
                <tr>
                    <th>Tổng tiền:</th>
                    <td><strong>{{ number_format($bill->thanhtien, 0, ',', '.') }} ₫</strong></td>
                </tr>
                <tr>
                    <th>Trạng thái thanh toán:</th>
                    <td><span class="badge bg-success">{{ $bill->trangthai }}</span></td>
                </tr>
                <tr>
                    <th>Ngày tạo hóa đơn:</th>
                    <td>{{ \Carbon\Carbon::parse($bill->thoigian)->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('home.index') }}" class="btn btn-outline-primary me-2">
                <i class="fa fa-home"></i> Về trang chủ
            </a>
            <a href="{{ route('bill.index') }}" class="btn btn-success">
                <i class="fa fa-ticket"></i> Xem vé của tôi
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Thanh toán thành công!',
            text: 'Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
@endsection