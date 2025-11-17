@php
    // Expect $trips as array/collection of objects with Trip, EmptySeats, VehicleType, RoadMapCities
    $items = $trips ?? [];
@endphp

<div class="layout">
    <aside class="filter-card">
        <div class="filter-header">
            <span class="title">Bộ lọc tìm kiếm</span>
            <div class="reset"><span>Bỏ lọc</span><i class="fa-solid fa-trash"></i></div>
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
        @if(!empty($items) && count($items)>0)
            @foreach($items as $tripVm)
                @php
                    $trip = $tripVm->Trip;
                    $departure = 
                        ($trip->thoigiandi ?? null) ? 
                        
                        \Carbon\Carbon::parse($trip->thoigiandi)->setTimezone(config('app.timezone')) : null;
                    $arrival = ($trip->thoigiend?) ?? null;
                    // compute road map
                    $roadMap = is_array($tripVm->RoadMapCities) ? implode(' → ', $tripVm->RoadMapCities) : (is_string($tripVm->RoadMapCities)? $tripVm->RoadMapCities : '');
                    $emptySeats = $tripVm->EmptySeats ?? 0;
                    $vehicleType = $tripVm->VehicleType ?? ($trip->VehicleType ?? '');
                @endphp

                <div class="trip-card {{ $emptySeats == 0 ? 'disabled' : '' }}" data-departure-hour="{{ $departure ? $departure->hour : 0 }}" data-vehicle="{{ $vehicleType }}">
                    <div class="trip-times">
                        <span class="time">{{ $departure ? $departure->format('H:i') : '' }}</span>
                        <span class="duration">@if($trip->thoigiandichuyen) {{ floor($trip->thoigiandichuyen/60) }} giờ @endif</span>
                        <span class="time">{{ $trip->thoigianketthuc ?? '' }}</span>
                    </div>
                    <div class="trip-locations">
                        <span>{{ $trip->lotrinh_from ?? '' }}</span>
                        <span>{{ $trip->lotrinh_to ?? '' }}</span>
                    </div>
                    <div class="trip-info">
                        <div class="details">
                            <span>• {{ $vehicleType }} </span>
                            <span>• <strong class="{{ $emptySeats==0? 'red' : 'green' }}">{{ $emptySeats }} chỗ trống</strong></span>
                        </div>
                        <span class="price">{{ number_format($trip->gia ?? 0) }} đ</span>
                    </div>
                    <div class="trip-actions">
                        <div><strong>Lộ Trình: </strong>{{ $roadMap }}</div>
                        @if($emptySeats==0)
                            <a class="select-btn disabled" href="javascript:void(0)">Hết chỗ</a>
                        @else
                            <a class="select-btn" href="{{ url('/ticket/book/'.$trip->machuyendi) }}">Chọn chuyến</a>
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
    const tripCards = document.querySelectorAll('.trip-card');
    function applyFilters(){
        let selectedHours = [];
        document.querySelectorAll('.filter-section input[type=checkbox]:checked').forEach(cb => selectedHours.push(cb.value));
        let activeBtn = document.querySelector('.btn-group button.active');
        let selectedVehicle = activeBtn ? activeBtn.dataset.type : null;
        let counts = {morning:0, afternoon:0, evening:0, night:0};
        tripCards.forEach(card=>{
            const hour = parseInt(card.dataset.departureHour) || 0;
            const vehicle = card.dataset.vehicle || '';
            let show = true;
            if(selectedHours.length>0){
                show = selectedHours.some(range=>{
                    if(range==='morning') return hour>=6 && hour<12;
                    if(range==='afternoon') return hour>=12 && hour<18;
                    if(range==='evening') return hour>=18 && hour<24;
                    if(range==='night') return hour>=0 && hour<6;
                    return false;
                });
            }
            if(selectedVehicle && show) show = (vehicle===selectedVehicle);
            card.style.display = show ? 'block' : 'none';
            if(hour>=6 && hour<12) counts.morning++; else if(hour>=12 && hour<18) counts.afternoon++; else if(hour>=18 && hour<24) counts.evening++; else counts.night++;
        });
        document.querySelectorAll('.filter-section input[type=checkbox]').forEach(cb=>{
            let span = cb.parentElement.querySelector('span.count');
            if(!span){ span = document.createElement('span'); span.classList.add('count'); cb.parentElement.appendChild(span); }
            span.textContent = ` (${counts[cb.value]})`;
        });
    }
    document.querySelectorAll('.filter-section input[type=checkbox]').forEach(cb=>cb.addEventListener('change', applyFilters));
    document.querySelectorAll('.btn-group button').forEach(btn=>btn.addEventListener('click', function(){ document.querySelectorAll('.btn-group button').forEach(b=>b.classList.remove('active')); this.classList.add('active'); applyFilters(); }));
    document.querySelector('.reset').addEventListener('click', ()=>{ document.querySelectorAll('.filter-section input[type=checkbox]').forEach(cb=>cb.checked=false); document.querySelectorAll('.btn-group button').forEach(b=>b.classList.remove('active')); applyFilters(); });
    applyFilters();
</script>
