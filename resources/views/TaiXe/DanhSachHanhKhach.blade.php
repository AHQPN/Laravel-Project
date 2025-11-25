@extends('layouts.TaiXeLayout')

@section('title', 'Danh sách hành khách')
@section('page-title', 'Chọn chuyến đi')

@section('content')
    <div class="mb-4">
        <div class="input-group input-group-lg shadow-sm">
            <span class="input-group-text bg-white border-end-0 ps-3">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   id="search-trip" 
                   class="form-control border-start-0 ps-2" 
                   placeholder="Tìm theo mã chuyến, tuyến hoặc biển số..."
                   style="font-size: 0.95rem;">
        </div>
    </div>

    <div id="trip-list">
        @forelse ($trips as $trip)
            <div class="driver-card trip-item cursor-pointer hover-shadow transition-all" 
                 data-keywords="{{ strtolower($trip['tuyen'] . ' ' . $trip['bien_so'] . ' ' . $trip['machuyendi']) }}"
                 onclick="window.location.href='{{ route('tai-xe.hanh-khach.show', $trip['machuyendi']) }}'">
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="badge bg-light text-secondary border fw-normal mb-2">
                            {{ $trip['machuyendi'] }}
                        </span>
                        <div class="mb-1">
                            <x-route-badge :route="$trip['tuyen']" />
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-dark">{{ $trip['bien_so'] }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center text-muted small mb-3">
                    <i class="far fa-clock me-2"></i>
                    {{ $trip['thoi_gian_day_du'] }}
                </div>

                <div class="d-grid">
                    <button class="btn btn-outline-primary btn-sm fw-semibold">
                        <i class="fas fa-users me-2"></i> Xem danh sách hành khách
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-3 text-muted opacity-25">
                    <i class="fas fa-search fa-3x"></i>
                </div>
                <h6 class="fw-bold text-dark">Không tìm thấy chuyến nào</h6>
                <p class="text-muted small">Vui lòng thử lại với từ khóa khác.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        border-color: #0d6efd !important;
        transform: translateY(-2px);
    }
    .transition-all {
        transition: all 0.2s ease;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-trip');
        const tripItems = document.querySelectorAll('.trip-item');

        if (searchInput) {
            const filterTrips = _.debounce(function () {
                const keyword = searchInput.value.toLowerCase().trim();

                tripItems.forEach(item => {
                    const text = item.dataset.keywords;
                    if (!keyword || text.includes(keyword)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }, 300);

            searchInput.addEventListener('input', filterTrips);
        }
    });
</script>
@endpush
