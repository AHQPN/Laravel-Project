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
    .ghe {
        cursor: pointer;
        text-align: center;
        width: 50px;
        height: 50px;
        position: relative;
    }
    .ghe img {
        width: 40px;
        height: 40px;
    }
    .seat-label {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        font-weight: bold;
        font-size: 12px;
        color: #0d6efd;
    }
</style>

<div class="container mb-4 position-relative">
    <div id="mes" class="d-none position-fixed top-0 start-50 translate-middle-x mt-3 p-3 rounded-3 shadow"
         role="alert" aria-live="assertive" aria-atomic="true"
         style="z-index: 9999; min-width: 350px; text-align:center;">
        <div class="toast-body text-white fw-bold"></div>
    </div>

    <div class="row justify-content-around ">
        <div class="col-lg-7 border-2">
            <div class="bg-white rounded-4 gap-2 d-md-flex row p-3">
                @php
                    $capacity = $trip->xe->loaixe->soghe ?? 0;
                @endphp

                @if ($capacity == 40)
                    <div class="col-md-6 row">
                        <h6 class="text-center">Tầng Dưới</h6>
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

                    <div class="col-md-6 row">
                        <h6 class="text-center">Tầng Trên</h6>
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
                @else
                    <div class="col-md-10">
                        <h6 class="text-center">Sơ đồ ghế</h6>
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

                <div class="col-md-2 mt-4">
                    <div class="d-flex flex-md-column gap-2 justify-content-between">
                        <div class="d-flex align-items-center">
                            <div style="background-color: #D5D9DD; width: 16px; height: 16px;"></div>
                            <span style="font-size: 12px; margin-left: 5px;">Đã bán</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="background-color: #DEF3FF; width: 16px; height: 16px;"></div>
                            <span style="font-size: 12px; margin-left: 5px;">Còn trống</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div style="background-color: #FDEDE8; width: 16px; height: 16px;"></div>
                            <span style="font-size: 12px; margin-left: 5px;">Đang chọn</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white row mt-2">
                <div class="col-md-5 m-2">
                    <h5>Thông tin khách hàng</h5>
                    <form>
                        <div class="form-group mb-2">
                            <label for="fullname">Họ và tên *</label>
                            <input type="text" class="form-control" id="fullname"
                                   placeholder="Vui lòng nhập tên"
                                   value="{{ $userInfo->ten ?? '' }}"
                                   {{ $userInfo ? 'readonly' : 'required' }} />
                        </div>
                        <div class="form-group mb-2">
                            <label for="phone">Số điện thoại *</label>
                            <input type="text" class="form-control" id="phone"
                                   placeholder="Vui lòng nhập số điện thoại"
                                   value="{{ $userInfo->sdt ?? '' }}"
                                   {{ $userInfo ? 'readonly' : 'required' }} />
                        </div>
                    </form>
                </div>
                <div class="col-md-6 m-2">
                    <h6 class="text-center">Điều khoản & lưu ý</h6>
                    <p>(*) Quý khách vui lòng có mặt tại bến xuất phát trước ít nhất 30 phút giờ xe khởi hành...</p>
                </div>
                <div class="row">
                    <div class="form-check">
                        <input class="form-check-input position-static" type="checkbox" id="acceptPolicy" required>
                        <p>Chấp nhận điều khoản đặt vé & chính sách bảo mật thông tin</p>
                    </div>
                </div>
            </div>

            <div class="row bg-white mt-2 mb-2">
                <div class="row m-2 ">
                    <h6 class="text-white rounded text-center"
                        style="background-color: #00613D;width: 100px;">
                        FUTAPAY
                    </h6>
                </div>
                <div class="d-flex justify-content-between p-2">
                    <h2 class="tongtien">0đ</h2>
                    <div class="d-flex gap-2">
                        <a class="btn" style="color: white;background-color: #f97019;width: 150px;"
                           id="booking" href="#">
                            Đặt vé
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-2">
            <div class="row bg-white rounded-4">
                <h5 class="row m-1">Thông tin lượt đi</h5>
                <div class="row">
                    <table class="table table-borderless">
                        <tr>
                            <td>Tuyến xe</td>
                            <td>{{ $trip->tenchuyen }}</td>
                        </tr>
                        <tr>
                            <td>Thời gian xuất bến</td>
                            <td>{{ \Carbon\Carbon::parse($trip->thoigiandi)->format('H:i d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Số ghế</td>
                            <td id="soghe"></td>
                        </tr>
                        <tr>
                            <td>Điểm trả khách</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Tổng tiền lượt đi</td>
                            <td class="tongtien">0đ</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row bg-white rounded-4 mt-2">
                <h5 class="row m-1">Chi tiết giá</h5>
                <table class="table">
                    <tr>
                        <td>Giá vé lượt đi</td>
                        <td>{{ number_format($trip->gia, 0, ',', '.') }} đ</td>
                    </tr>
                    <tr>
                        <td>Phí thanh toán</td>
                        <td>0đ</td>
                    </tr>
                    <tr>
                        <td>Tổng tiền</td>
                        <td class="tongtien">0đ</td>
                    </tr>
                </table>
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