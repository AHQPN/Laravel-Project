@extends('layouts.khach')

@section('content')

<div class="layout">
    <aside class="filter-card">
        <div class="filter-header">
            <span class="title">Bộ lọc tìm kiếm</span>
            <div class="reset">
                <span>Bỏ lọc</span>
                <i class="fa-solid fa-trash"></i>
            </div>
        </div>

        <div class="filter-section">
            <span class="label">Giờ đi</span>
            <label><input type="checkbox" value="night"> Sáng sớm 00:00 - 06:00</label>
            <label><input type="checkbox" value="morning"> Buổi sáng 06:00 - 12:00</label>
            <label><input type="checkbox" value="afternoon"> Buổi chiều 12:00 - 18:00</label>
            <label><input type="checkbox" value="evening"> Buổi tối 18:00 - 24:00</label>
        </div>

        <div class="filter-section">
            <span class="label">Loại xe</span>
            <div class="btn-group">
                <button type="button" data-type="Ghế">Ghế</button>
                <button type="button" data-type="Giường nằm">Giường</button>
                <button type="button" data-type="Limousine">Limousine</button>
            </div>
        </div>
    </aside>

    <main class="trip-list">
        @if (isset($results) && $results->isNotEmpty())

            @foreach ($results as $item)
                @php
                    $trip = $item['Trip'];
                    $emptySeats = $item['EmptySeats'];
                    $vehicleType = $item['VehicleType'];
                    $roadMap = implode(' → ', $item['RoadMapCities']);

                    $departure = $trip->thoigiandi;
                   
                    $arrival = $departure->copy()->addMinutes($trip->thoigiandichuyen ?? 240); 
                    $duration = $trip->thoigiandichuyen; 
                    $from = $item['RoadMapCities'][0] ?? 'N/A';
                    $to = end($item['RoadMapCities']) ?? 'N/A';

                    // Chuẩn hóa loại xe để lọc
                    if (stripos($vehicleType, 'ghế') !== false) {
                        $vehicleKey = 'Ghế';
                    } elseif (stripos($vehicleType, 'giường') !== false) {
                        $vehicleKey = 'Giường nằm';
                    } elseif (stripos($vehicleType, 'limousine') !== false) {
                        $vehicleKey = 'Limousine';
                    } else {
                        $vehicleKey = $vehicleType;
                    }
                @endphp

                <div class="trip-card {{ $emptySeats == 0 ? 'disabled' : '' }}"
                     data-departure-hour="{{ $departure->hour }}"
                     data-vehicle="{{ $vehicleKey }}">
                    <div class="trip-times">
                        <span class="time">{{ $departure->format('H:i') }}</span>
                        <span class="duration">{{ $duration }} giờ</span>
                        <span class="time">{{ $arrival->format('H:i') }}</span>
                    </div>

                    <div class="trip-locations">
                        <span>{{ $from }}</span>
                        <span>{{ $to }}</span>
                    </div>

                    <div class="trip-info">
                        <div class="details">
                            <span>• {{ $vehicleType }} </span>
                            <span>• <strong class="{{ $emptySeats == 0 ? 'red' : 'green' }}">
                                {{ $emptySeats > 0 ? $emptySeats : 0 }} chỗ trống
                            </strong></span>
                        </div>
                        <span class="price">{{ number_format($trip->gia, 0, ',', '.') }} đ</span>
                    </div>

                    <div class="trip-actions">
                        <div><strong>Lộ Trình: </strong>{{ $roadMap }}</div>

                        @if ($emptySeats == 0)
                            <a class="select-btn disabled" href="javascript:void(0)">Hết chỗ</a>
                        @else
                            <a class="select-btn" href="{{ route('ticket.book', ['tripID' => $trip->machuyendi]) }}">
                                Chọn chuyến
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        @else
            <p>Không tìm thấy chuyến xe nào.</p>
        @endif
    </main>
</div>

<script>
    const tripCards = document.querySelectorAll(".trip-card");

    function applyFilters() {
        let selectedHours = [];
        document.querySelectorAll(".filter-section input[type=checkbox]:checked").forEach(cb => {
            selectedHours.push(cb.value);
        });

        let selectedVehicle = null;
        const activeBtn = document.querySelector(".btn-group button.active");
        if (activeBtn) selectedVehicle = activeBtn.dataset.type;

        let counts = { morning: 0, afternoon: 0, evening: 0, night: 0 };

        tripCards.forEach(card => {
            const hour = parseInt(card.dataset.departureHour);
            const vehicle = card.dataset.vehicle;
            let show = true;

            if (selectedHours.length > 0) {
                show = selectedHours.some(range => {
                    if (range === "morning") return hour >= 6 && hour < 12;
                    if (range === "afternoon") return hour >= 12 && hour < 18;
                    if (range === "evening") return hour >= 18 && hour < 24;
                    if (range === "night") return hour >= 0 && hour < 6;
                });
            }

            if (selectedVehicle && show) {
                show = (vehicle === selectedVehicle);
            }

            card.style.display = show ? "block" : "none";

            if (hour >= 6 && hour < 12) counts.morning++;
            else if (hour >= 12 && hour < 18) counts.afternoon++;
            else if (hour >= 18 && hour < 24) counts.evening++;
            else counts.night++;
        });

        document.querySelectorAll(".filter-section input[type=checkbox]").forEach(cb => {
            let span = cb.parentElement.querySelector("span.count");
            if (!span) {
                span = document.createElement("span");
                span.classList.add("count");
                cb.parentElement.appendChild(span);
            }
            span.textContent = ` (${counts[cb.value]})`;
        });
    }

    document.querySelectorAll(".filter-section input[type=checkbox]").forEach(cb => {
        cb.addEventListener("change", applyFilters);
    });

    document.querySelectorAll(".btn-group button").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".btn-group button").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            applyFilters();
        });
    });

    document.querySelector(".reset").addEventListener("click", () => {
        document.querySelectorAll(".filter-section input[type=checkbox]").forEach(cb => cb.checked = false);
        document.querySelectorAll(".btn-group button").forEach(b => b.classList.remove("active"));
        applyFilters();
    });

    applyFilters();
</script>

@endsection
