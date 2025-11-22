@extends('layouts.khach')

@section('content')

@php
    $allTickets = $trip->ves;

    function getSeatLabel($prefix, $i) {
        return $prefix . $i;
    }

    function isBooked($seat, $allTickets) {
        foreach ($allTickets as $ticket) {
            if ($ticket->maghe == $seat && $ticket->trangthai == 'Booked') {
                return true;
            }
        }
        return false;
    }

    function isPending($seat, $allTickets) {
        foreach ($allTickets as $ticket) {
            if ($ticket->maghe == $seat && $ticket->trangthai == 'Pending') {
                return true;
            }
        }
        return false;
    }
@endphp

<style>
    .booking-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .seat-map-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .seat-map-card:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }
    
    .ghe {
        cursor: pointer;
        text-align: center;
        width: 55px;
        height: 55px;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .ghe:hover:not([data-status="booked"]) {
        transform: translateY(-5px) scale(1.1);
    }
    
    .ghe img {
        width: 45px;
        height: 45px;
        transition: all 0.3s ease;
    }
    
    .seat-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 700;
        font-size: 11px;
        color: #2c3e50;
        text-shadow: 0 1px 2px rgba(255,255,255,0.8);
        pointer-events: none;
    }
    
    .floor-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem;
        border-radius: 10px;
        text-align: center;
        font-weight: 700;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .legend-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    
    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        border-color: #f97019;
        box-shadow: 0 12px 40px rgba(249, 112, 25, 0.2);
    }
    
    .info-card h5 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #f97019;
    }
    
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .form-control-modern:focus {
        border-color: #f97019;
        box-shadow: 0 0 0 0.2rem rgba(249, 112, 25, 0.15);
        transform: translateY(-2px);
    }
    
    .form-label-modern {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .terms-box {
        background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
        border-radius: 12px;
        padding: 1rem;
        border-left: 4px solid #f97019;
    }
    
    .terms-box p {
        margin: 0;
        font-size: 0.85rem;
        color: #2d3436;
        line-height: 1.6;
    }
    
    .payment-section {
        background: linear-gradient(135deg, #00b894 0%, #00613D 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 97, 61, 0.3);
    }
    
    .payment-badge {
        background: white;
        color: #00613D;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .total-price {
        font-size: 2.5rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        margin: 0;
    }
    
    .btn-book {
        background: linear-gradient(135deg, #f97019 0%, #ff8c42 100%);
        border: none;
        color: white;
        padding: 1rem 3rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 8px 25px rgba(249, 112, 25, 0.4);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .btn-book:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(249, 112, 25, 0.6);
        background: linear-gradient(135deg, #ff8c42 0%, #f97019 100%);
    }
    
    .trip-info-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }
    
    .trip-info-table tr {
        border-bottom: 1px solid #f1f3f5;
    }
    
    .trip-info-table tr:last-child {
        border-bottom: none;
    }
    
    .trip-info-table td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .trip-info-table td:first-child {
        font-weight: 600;
        color: #6c757d;
        width: 40%;
    }
    
    .trip-info-table td:last-child {
        color: #2c3e50;
        font-weight: 500;
    }
    
    .price-detail-card {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 8px 25px rgba(9, 132, 227, 0.3);
    }
    
    .price-detail-card h5 {
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .price-row:last-child {
        border-bottom: none;
        font-size: 1.3rem;
        font-weight: 700;
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .toast-custom {
        min-width: 400px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideInDown 0.5s ease;
    }
    
    @keyframes slideInDown {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .custom-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #f97019;
    }
    
    .checkbox-label {
        cursor: pointer;
        user-select: none;
        font-weight: 500;
    }
</style>

<div class="booking-container">
    <div class="container">
        <div id="mes" class="d-none position-fixed top-0 start-50 translate-middle-x mt-3 p-4 toast-custom"
             role="alert" aria-live="assertive" aria-atomic="true"
             style="z-index: 9999;">
            <div class="toast-body text-white fw-bold fs-5"></div>
        </div>

        <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="seat-map-card">
                @php
                    $capacity = $trip->xe->loaixe->soghe ?? 0;
                @endphp

                @if ($capacity == 40)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="floor-title">
                                <i class="bi bi-arrow-down-circle me-2"></i>Tầng Dưới
                            </div>
                        <div class="row gap-3 justify-content-between">
                            @for ($i = 1; $i <= 20; $i++)
                                @php
                                    $seat = getSeatLabel("A", $i);
                                    $booked = isBooked($seat, $allTickets);
                                    $pending = isPending($seat, $allTickets);
                                    $isDisabled = $booked || $pending;
                                @endphp
                                <div class="ghe col-3" data-seat="{{ $seat }}" data-status="{{ $isDisabled ? 'booked' : 'available' }}">
                                    <img src="{{ asset('images/' . ($isDisabled ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                         alt="{{ $isDisabled ? 'seat_disabled' : 'seat_active' }}" />
                                    <span class="seat-label">{{ $seat }}</span>
                                </div>
                            @endfor
                        </div>
                        </div>

                        <div class="col-md-6">
                            <div class="floor-title">
                                <i class="bi bi-arrow-up-circle me-2"></i>Tầng Trên
                            </div>
                        <div class="row gap-3 justify-content-between">
                            @for ($i = 1; $i <= 20; $i++)
                                @php
                                    $seat = getSeatLabel("B", $i);
                                    $booked = isBooked($seat, $allTickets);
                                    $pending = isPending($seat, $allTickets);
                                    $isDisabled = $booked || $pending;
                                @endphp
                                <div class="ghe col-3" data-seat="{{ $seat }}" data-status="{{ $isDisabled ? 'booked' : 'available' }}">
                                    <img src="{{ asset('images/' . ($isDisabled ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                         alt="{{ $isDisabled ? 'seat_disabled' : 'seat_active' }}" />
                                    <span class="seat-label">{{ $seat }}</span>
                                </div>
                            @endfor
                        </div>
                        </div>
                    </div>
                @else
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="floor-title">
                                <i class="bi bi-grid-3x3 me-2"></i>Sơ đồ ghế
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center gap-3">
                            @for ($row = 0; $row < $capacity / 4; $row++)
                                <div class="d-flex justify-content-center gap-4">
                                    <div class="d-flex gap-3">
                                        @php
                                            $seat1 = getSeatLabel("A", $row * 4 + 1);
                                            $seat2 = getSeatLabel("A", $row * 4 + 2);
                                            $booked1 = isBooked($seat1, $allTickets);
                                            $pending1 = isPending($seat1, $allTickets);
                                            $booked2 = isBooked($seat2, $allTickets);
                                            $pending2 = isPending($seat2, $allTickets);
                                            $isDisabled1 = $booked1 || $pending1;
                                            $isDisabled2 = $booked2 || $pending2;
                                        @endphp
                                        <div class="ghe" data-seat="{{ $seat1 }}" data-status="{{ $isDisabled1 ? 'booked' : 'available' }}">
                                            <img src="{{ asset('images/' . ($isDisabled1 ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                                 alt="{{ $isDisabled1 ? 'seat_disabled' : 'seat_active' }}" />
                                            <span class="seat-label">{{ $seat1 }}</span>
                                        </div>
                                        <div class="ghe" data-seat="{{ $seat2 }}" data-status="{{ $isDisabled2 ? 'booked' : 'available' }}">
                                            <img src="{{ asset('images/' . ($isDisabled2 ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                                 alt="{{ $isDisabled2 ? 'seat_disabled' : 'seat_active' }}" />
                                            <span class="seat-label">{{ $seat2 }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3">
                                        @php
                                            $seat3 = getSeatLabel("A", $row * 4 + 3);
                                            $seat4 = getSeatLabel("A", $row * 4 + 4);
                                            $booked3 = isBooked($seat3, $allTickets);
                                            $pending3 = isPending($seat3, $allTickets);
                                            $booked4 = isBooked($seat4, $allTickets);
                                            $pending4 = isPending($seat4, $allTickets);
                                            $isDisabled3 = $booked3 || $pending3;
                                            $isDisabled4 = $booked4 || $pending4;
                                        @endphp
                                        <div class="ghe" data-seat="{{ $seat3 }}" data-status="{{ $isDisabled3 ? 'booked' : 'available' }}">
                                            <img src="{{ asset('images/' . ($isDisabled3 ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                                 alt="{{ $isDisabled3 ? 'seat_disabled' : 'seat_active' }}" />
                                            <span class="seat-label">{{ $seat3 }}</span>
                                        </div>
                                        <div class="ghe" data-seat="{{ $seat4 }}" data-status="{{ $isDisabled4 ? 'booked' : 'available' }}">
                                            <img src="{{ asset('images/' . ($isDisabled4 ? 'seat_disabled.svg' : 'seat_active.svg')) }}"
                                                 alt="{{ $isDisabled4 ? 'seat_disabled' : 'seat_active' }}" />
                                            <span class="seat-label">{{ $seat4 }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #D5D9DD;"></div>
                                <span class="fw-semibold"><i class="bi bi-x-circle text-danger me-1"></i>Đã bán</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #DEF3FF;"></div>
                                <span class="fw-semibold"><i class="bi bi-check-circle text-success me-1"></i>Còn trống</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background-color: #FDEDE8;"></div>
                                <span class="fw-semibold"><i class="bi bi-hand-index text-primary me-1"></i>Đang chọn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h5><i class="bi bi-person-fill me-2"></i>Thông tin khách hàng</h5>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fullname" class="form-label-modern">
                                <i class="bi bi-person-badge me-1"></i>Họ và tên *
                            </label>
                            <input type="text" class="form-control form-control-modern" id="fullname"
                                   placeholder="Nhập họ và tên đầy đủ"
                                   value="{{ $userInfo->ten ?? '' }}"
                                   {{ $userInfo ? 'readonly' : 'required' }} />
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label-modern">
                                <i class="bi bi-telephone-fill me-1"></i>Số điện thoại *
                            </label>
                            <input type="text" class="form-control form-control-modern" id="phone"
                                   placeholder="Nhập số điện thoại"
                                   value="{{ $userInfo->sdt ?? '' }}"
                                   {{ $userInfo ? 'readonly' : 'required' }} />
                        </div>
                        <div class="col-12">
                            <div class="terms-box">
                                <h6 class="mb-2"><i class="bi bi-info-circle-fill me-2"></i>Điều khoản & lưu ý</h6>
                                <p><strong>(*)</strong> Quý khách vui lòng có mặt tại bến xuất phát trước ít nhất <strong>30 phút</strong> giờ xe khởi hành. Mang theo giấy tờ tùy thân và mã đặt vé để làm thủ tục lên xe.</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-2">
                                <input class="custom-checkbox" type="checkbox" id="acceptPolicy" required>
                                <label for="acceptPolicy" class="checkbox-label mb-0">
                                    Tôi đồng ý với <strong class="text-primary">Điều khoản đặt vé</strong> & <strong class="text-primary">Chính sách bảo mật</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="payment-section">
                <div class="text-center mb-3">
                    <span class="payment-badge">
                        <i class="bi bi-wallet2 me-2"></i>FUTAPAY
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <p class="mb-1 opacity-75 fs-6">Tổng thanh toán</p>
                        <h2 class="total-price tongtien">0đ</h2>
                    </div>
                    <div>
                        <a class="btn btn-book" id="booking" href="#">
                            <i class="bi bi-cart-check me-2"></i>Đặt vé ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-card">
                <h5><i class="bi bi-bus-front-fill me-2"></i>Thông tin lượt đi</h5>
                <table class="trip-info-table">
                    <tbody>
                        <tr>
                            <td><i class="bi bi-geo-alt-fill text-danger me-2"></i>Tuyến xe</td>
                            <td><strong>{{ $trip->tenchuyen }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-clock-fill text-primary me-2"></i>Giờ xuất bến</td>
                            <td><strong>{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i - d/m/Y') }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-ticket-perforated-fill text-success me-2"></i>Số ghế</td>
                            <td id="soghe" class="text-primary fw-bold">Chưa chọn</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-pin-map-fill text-info me-2"></i>Điểm trả khách</td>
                            <td>Theo lộ trình</td>
                        </tr>
                        <tr style="background: #f8f9fa;">
                            <td><strong><i class="bi bi-cash-stack text-success me-2"></i>Tổng tiền</strong></td>
                            <td class="tongtien text-danger fw-bold fs-5">0đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="price-detail-card">
                <h5><i class="bi bi-receipt me-2"></i>Chi tiết giá</h5>
                <div class="price-row">
                    <span>Giá vé lượt đi</span>
                    <span class="fw-bold">{{ number_format($trip->gia, 0, ',', '.') }} đ</span>
                </div>
                <div class="price-row">
                    <span>Phí thanh toán</span>
                    <span class="fw-bold">0đ</span>
                </div>
                <div class="price-row">
                    <span>Tổng cộng</span>
                    <span class="tongtien">0đ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let selectedSeats = [];
    let seatCount = 0;
    let ticketPrice = {{ $trip->gia }};
    const bookingBtn = document.querySelector("#booking");

    document.querySelector('.col-lg-7').addEventListener("click", function (e) {
        let el = e.target.closest(".ghe");
        if (!el) return;

        let img = el.querySelector("img");
        let seat = el.querySelector("span").innerText;

        if (img.alt === "seat_active" && seatCount < 5) {
            img.src = '{{ asset("images/seat_selecting.svg") }}';
            img.alt = 'seat_selecting';
            selectedSeats.push(seat);
            seatCount++;
        } else if (img.alt === "seat_selecting") {
            selectedSeats = selectedSeats.filter(s => s !== seat);
            seatCount--;
            img.src = '{{ asset("images/seat_active.svg") }}';
            img.alt = 'seat_active';
        } else if (img.alt === 'seat_active' && seatCount >= 5) {
             showToast('Bạn chỉ được chọn tối đa 5 ghế.', 'danger');
        }

        document.querySelector("#soghe").innerText = selectedSeats.join(',');

        document.querySelectorAll(".tongtien").forEach(el => {
            el.innerText = new Intl.NumberFormat('vi-VN').format(ticketPrice * seatCount) + "đ";
        });
    });

    bookingBtn.addEventListener("click", function (e) {
        e.preventDefault(); 
        
        let fullname = document.querySelector("#fullname").value.trim();
        let phone = document.querySelector("#phone").value.trim();

        if (selectedSeats.length === 0) {
            showToast('Vui lòng chọn ít nhất 1 ghế.', 'danger');
            return;
        }

        if (!fullname || !phone) {
            showToast('Vui lòng điền đầy đủ họ tên và email/SĐT.', 'danger');
            return;
        }

        if (!document.querySelector("#acceptPolicy").checked) {
            showToast('Bạn cần chấp nhận điều khoản trước khi đặt vé.', 'danger');
            return;
        }

        const total = ticketPrice * selectedSeats.length;

        // Create a hidden form and submit via POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("ticket.handleBooking") }}';

        const fields = {
            '_token': '{{ csrf_token() }}',
            'tripID': '{{ $trip->machuyendi }}',
            'seats': selectedSeats.join(','),
            'fullname': fullname,
            'phone': phone,
            'total': total
        };

        for (const [key, value] of Object.entries(fields)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    });

    function showToast(message, type = 'danger') {
        const mes = document.querySelector('#mes');
        mes.classList.remove('d-none');
        mes.classList.add('d-flex', `bg-${type}`);
        document.querySelector('.toast-body').innerHTML = message;
        setTimeout(() => {
            mes.classList.remove('d-flex', `bg-${type}`);
            mes.classList.add('d-none');
        }, 2000);
    }

    @if (session('message'))
        (function() {
            showToast('{!! session("message") !!}', '{{ session("messageType", "danger") }}');
        })();
    @endif
});
</script>

{{-- Real-time seat updates --}}
<script src="{{ asset('js/real-time-seat-updates.js') }}"></script>
<script>
    @if(isset($trip))
        initSeatUpdates('{{ $trip->machuyendi }}');
    @endif
</script>

@endsection