@extends('layouts.TaiXeLayout')

@section('title', 'Danh sách hành khách')
@section('page-title', 'Danh sách hành khách')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
<style>
.trip-header {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 20px 24px;
    margin-bottom: 24px;
}
.passenger-card {
    background: white;
    border: 2px solid #DFE1E6;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 150ms ease;
}
.passenger-card:hover {
    border-color: #0052CC;
}
.passenger-avatar {
    width: 64px;
    height: 64px;
    border-radius: 4px;
    flex-shrink: 0;
}
.passenger-info {
    flex: 1;
}
.passenger-name {
    font-size: 18px;
    font-weight: 700;
    color: #172B4D;
}
.passenger-phone {
    font-size: 16px;
    color: #5E6C84;
    margin-top: 4px;
}
.pickup-toggle {
    width: 48px;
    height: 48px;
    border: 2px solid #DFE1E6;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 150ms ease;
    flex-shrink: 0;
}
.pickup-toggle:checked {
    background: #00875A;
    border-color: #00875A;
}
</style>
@endpush

@section('content')
    <div class="driver-card mb-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <p class="text-muted mb-1">Mã chuyến: {{ $trip['machuyendi'] }}</p>
                <h3>{{ $trip['tuyen'] }}</h3>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark d-block mb-2">
                    <i class="fas fa-clock text-primary me-1"></i>{{ $trip['gio_khoi_hanh'] }}
                </span>
                <span class="badge bg-success text-white">
                    {{ $trip['so_ghe_trong'] }} ghế trống
                </span>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Tìm kiếm khách (theo tên hoặc số ghế)</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="search-passenger" class="form-control" placeholder="Nhập từ khóa bất kỳ">
        </div>
    </div>

    <div id="passenger-list">
        @forelse ($passengers as $passenger)
            <div
                class="driver-card passenger-item"
                data-keywords="{{ mb_strtolower($passenger['ten_khach'] . ' ' . $passenger['so_ghe'], 'UTF-8') }}"
                data-status="{{ $passenger['trangthai_don'] }}"
            >
                <div class="d-flex gap-3 align-items-center">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 d-none d-sm-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($passenger['ten_khach']) }}&background=2dce89&color=fff&size=64&rounded=true" 
                             alt="Avatar" 
                             class="rounded-circle"
                             style="width: 64px; height: 64px;">
                    </div>
                    <div class="flex-shrink-0 d-sm-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($passenger['ten_khach']) }}&background=2dce89&color=fff&size=48&rounded=true" 
                             alt="Avatar" 
                             class="rounded-circle"
                             style="width: 48px; height: 48px;">
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge rounded-pill bg-primary px-3 py-1">Ghế {{ $passenger['so_ghe'] }}</span>
                            <h5 class="mb-0 fw-bold">{{ $passenger['ten_khach'] }}</h5>
                        </div>
                        <p class="meta mb-1 d-none d-sm-block"><i class="fas fa-phone-alt me-2 text-success"></i>{{ $passenger['sdt'] }}</p>
                        @if($passenger['thoidiem_don'])
                            <p class="meta mb-0 text-success d-none d-md-block"><i class="fas fa-check-circle me-2"></i>Đã đón lúc {{ $passenger['thoidiem_don'] }}</p>
                        @else
                            <p class="meta mb-0 text-muted d-none d-md-block"><i class="far fa-clock me-2"></i>Chưa đón</p>
                        @endif
                    </div>

                    <!-- Toggle Switch -->
                    <div class="form-check form-switch">
                        <input
                            class="form-check-input toggle-pickup"
                            type="checkbox"
                            role="switch"
                            data-mave="{{ $passenger['mave'] }}"
                            style="width: 3em; height: 1.5em; cursor: pointer;"
                            {{ $passenger['trangthai_don'] === 'da_don' ? 'checked' : '' }}
                        >
                    </div>
                </div>
            </div>
        @empty
            <div class="driver-card text-center">
                <i class="fas fa-user-slash fa-3x text-secondary mb-3"></i>
                <h3>Chưa có hành khách cho chuyến này</h3>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    const passengers = document.querySelectorAll('.passenger-item');
    const searchField = document.getElementById('search-passenger');

    const filterPassengers = _.debounce(function () {
        const keyword = searchField.value.toLowerCase();
        let visibleCount = 0;

        passengers.forEach(item => {
            const text = item.dataset.keywords;
            if (!keyword || text.includes(keyword)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        const passengerListDiv = document.getElementById('passenger-list');
        let noResultsMsg = passengerListDiv.querySelector('.no-results-message');
        
        if (visibleCount === 0 && keyword) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'driver-card text-center no-results-message';
                noResultsMsg.innerHTML = `
                    <i class="fas fa-search fa-3x text-secondary mb-3"></i>
                    <h3>Không tìm thấy hành khách</h3>
                    <p class="meta mb-0">Thử tìm kiếm với từ khóa khác</p>
                `;
                passengerListDiv.appendChild(noResultsMsg);
            }
        } else {
            if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }
    }, 200);

    searchField.addEventListener('input', filterPassengers);

    document.querySelectorAll('.toggle-pickup').forEach(toggle => {
        tippy(toggle, {
            content: 'Đánh dấu đã đón khách',
            placement: 'left',
        });

        toggle.addEventListener('change', function () {
            const mave = this.dataset.mave;
            const checked = this.checked;

            fetch("{{ route('tai-xe.hanh-khach.toggle', '__MAVE__') }}".replace('__MAVE__', mave), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    trangthai_don: checked ? 'da_don' : 'chua_don'
                })
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) {
                    this.checked = !checked;
                    showToast(data.message || 'Không thể cập nhật.', 'error');
                    return;
                }

                showToast(data.message, 'success');

                const card = this.closest('.passenger-item');
                const statusParagraph = card.querySelector('.meta:last-child');
                if (checked) {
                    statusParagraph.innerHTML = `<i class="fas fa-check-circle me-2"></i>Đã đón lúc ${data.thoidiem_don ?? dayjs().format('HH:mm DD/MM')}`;
                    statusParagraph.classList.remove('text-muted');
                    statusParagraph.classList.add('text-success');
                } else {
                    statusParagraph.innerHTML = `<i class="far fa-clock me-2"></i>Chưa đón`;
                    statusParagraph.classList.remove('text-success');
                    statusParagraph.classList.add('text-muted');
                }
            })
            .catch(() => {
                this.checked = !checked;
                showToast('Có lỗi kết nối máy chủ.', 'error');
            });
        });
    });
</script>
@endpush

