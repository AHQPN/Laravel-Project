@extends('layouts.TaiXeLayout')

@section('title', 'Danh sách hành khách')
@section('page-title', 'Chọn chuyến cần xem')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
<style>
.search-bar {
    height: 56px;
    padding: 0 24px;
    font-size: 18px;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    margin-bottom: 24px;
}
.search-bar:focus {
    border-color: #0052CC;
    box-shadow: 0 0 0 4px rgba(0,82,204,0.1);
}
.trip-card {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 24px;
    margin-bottom: 16px;
    transition: all 150ms ease;
}
.trip-card:hover {
    border-color: #0052CC;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.trip-route {
    font-size: 24px;
    font-weight: 700;
    color: #172B4D;
}
.trip-meta {
    font-size: 16px;
    color: #5E6C84;
    margin: 8px 0;
}
.trip-action {
    height: 48px;
    min-width: 160px;
}
</style>
@endpush

@section('content')
    <div class="mb-3">
        <label class="form-label">Tìm kiếm chuyến</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="search-trip" class="form-control" placeholder="Nhập mã chuyến, tuyến hoặc biển số">
        </div>
    </div>

    <div id="trip-list">
        @forelse ($trips as $trip)
            <div class="driver-card trip-item" data-keywords="{{ strtolower($trip['tuyen'] . ' ' . $trip['bien_so'] . ' ' . $trip['machuyendi']) }}">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">{{ $trip['machuyendi'] }}</p>
                        <h3>{{ $trip['tuyen'] }}</h3>
                    </div>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                        <i class="fas fa-clock me-1 text-primary"></i>{{ $trip['thoi_gian_day_du'] }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="meta">
                        <i class="fas fa-bus me-2 text-success"></i>Xe: {{ $trip['bien_so'] }}
                    </div>
                    <a href="{{ route('tai-xe.hanh-khach.show', $trip['machuyendi']) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-users me-2"></i>Xem hành khách
                    </a>
                </div>
            </div>
        @empty
            <div class="driver-card text-center">
                <i class="fas fa-folder-open fa-3x text-secondary mb-3"></i>
                <h3>Không tìm thấy chuyến phù hợp</h3>
                <p class="meta mb-0">Thử lại với thời gian khác hoặc liên hệ điều hành.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('search-trip').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        const tripItems = document.querySelectorAll('.trip-item');
        
        tripItems.forEach(item => {
            const keywords = item.getAttribute('data-keywords');
            if (keywords.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endpush

@push('scripts')
<script>
    const searchInput = document.getElementById('search-trip');
    const tripItems = document.querySelectorAll('.trip-item');

    const filterTrips = _.debounce(function () {
        const keyword = searchInput.value.toLowerCase();

        tripItems.forEach(item => {
            const text = item.dataset.keywords.toLowerCase();
            if (!keyword || text.includes(keyword)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }, 200);

    searchInput.addEventListener('input', filterTrips);
</script>
@endpush

