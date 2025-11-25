@extends('layouts.TaiXeLayout')

@section('title', 'Danh sách hành khách')
@section('page-title', 'Chi tiết chuyến đi')

@section('content')
    <div class="driver-card mb-3 border-0 text-white shadow-sm" style="background: linear-gradient(135deg, #00796b 0%, #004d40 100%);">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-white text-primary bg-opacity-25 border border-white border-opacity-25 fw-normal">
                        {{ $trip['machuyendi'] }}
                    </span>
                    <span class="badge bg-success text-white border border-success fw-normal">
                        {{ $trip['so_ghe_trong'] }} ghế trống
                    </span>
                </div>
                <h5 class="mb-1 fw-bold text-white">{{ $trip['tuyen'] }}</h5>
                <p class="mb-0 small opacity-75">
                    <i class="fas fa-clock me-1"></i> Khởi hành: {{ $trip['gio_khoi_hanh'] }}
                </p>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0 ps-3">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   id="search-passenger" 
                   class="form-control border-start-0 ps-2" 
                   placeholder="Tìm khách theo tên hoặc số ghế..."
                   style="font-size: 0.95rem;">
        </div>
    </div>

    <div id="passenger-list">
        @forelse ($passengers as $passenger)
            <div class="driver-card passenger-item p-3 mb-3"
                 data-keywords="{{ mb_strtolower($passenger['ten_khach'] . ' ' . $passenger['so_ghe'], 'UTF-8') }}"
                 data-status="{{ $passenger['trangthai_don'] }}">
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Seat Number -->
                    <div class="flex-shrink-0 text-center bg-light rounded-3 d-flex flex-column justify-content-center align-items-center" 
                         style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                        <small class="text-muted" style="font-size: 0.65rem; line-height: 1;">GHẾ</small>
                        <span class="fw-bold text-primary fs-5" style="line-height: 1;">{{ $passenger['so_ghe'] }}</span>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="mb-1 fw-bold text-dark text-truncate">{{ $passenger['ten_khach'] }}</h6>
                        <a href="tel:{{ $passenger['sdt'] }}" class="text-decoration-none text-muted small d-block mb-1">
                            <i class="fas fa-phone-alt me-1 text-secondary" style="font-size: 0.8rem;"></i> {{ $passenger['sdt'] }}
                        </a>
                        
                        <div class="status-text">
                            @if($passenger['thoidiem_don'])
                                <small class="text-success fw-semibold">
                                    <i class="fas fa-check-circle me-1"></i>Đã đón {{ \Carbon\Carbon::parse($passenger['thoidiem_don'])->format('H:i') }}
                                </small>
                            @else
                                <small class="text-muted">
                                    <i class="far fa-circle me-1"></i>Chưa đón
                                </small>
                            @endif
                        </div>
                    </div>

                    <!-- Toggle Switch -->
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input toggle-pickup" 
                               type="checkbox" 
                               role="switch" 
                               data-mave="{{ $passenger['mave'] }}"
                               style="width: 3em; height: 1.5em; cursor: pointer;"
                               {{ $passenger['trangthai_don'] === 'da_don' ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-3 text-muted opacity-25">
                    <i class="fas fa-user-slash fa-3x"></i>
                </div>
                <h6 class="fw-bold text-dark">Chưa có hành khách</h6>
                <p class="text-muted small">Chuyến này hiện chưa có hành khách nào đặt vé.</p>
            </div>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passengers = document.querySelectorAll('.passenger-item');
        const searchField = document.getElementById('search-passenger');

        if (searchField) {
            const filterPassengers = _.debounce(function () {
                const keyword = searchField.value.toLowerCase().trim();
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

                // Handle no results
                const listContainer = document.getElementById('passenger-list');
                let noResults = document.getElementById('no-results-msg');
                
                if (visibleCount === 0 && keyword) {
                    if (!noResults) {
                        noResults = document.createElement('div');
                        noResults.id = 'no-results-msg';
                        noResults.className = 'text-center py-4';
                        noResults.innerHTML = `
                            <div class="text-muted opacity-50 mb-2"><i class="fas fa-search fa-2x"></i></div>
                            <p class="text-muted small mb-0">Không tìm thấy hành khách nào</p>
                        `;
                        listContainer.appendChild(noResults);
                    }
                } else {
                    if (noResults) noResults.remove();
                }
            }, 200);

            searchField.addEventListener('input', filterPassengers);
        }

        // Handle Toggle Pickup
        document.querySelectorAll('.toggle-pickup').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const mave = this.dataset.mave;
                const checked = this.checked;
                const card = this.closest('.passenger-item');
                const statusDiv = card.querySelector('.status-text');

                // Optimistic UI update
                if (checked) {
                    statusDiv.innerHTML = `<small class="text-success fw-semibold"><i class="fas fa-spinner fa-spin me-1"></i>Đang cập nhật...</small>`;
                } else {
                    statusDiv.innerHTML = `<small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Đang cập nhật...</small>`;
                }

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
                        this.checked = !checked; // Revert
                        showToast(data.message || 'Lỗi cập nhật', 'error');
                        // Revert status text
                        if (!checked) {
                             statusDiv.innerHTML = `<small class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i>Đã đón</small>`;
                        } else {
                             statusDiv.innerHTML = `<small class="text-muted"><i class="far fa-circle me-1"></i>Chưa đón</small>`;
                        }
                        return;
                    }

                    showToast(data.message, 'success');

                    if (checked) {
                        const time = data.thoidiem_don ? dayjs(data.thoidiem_don).format('HH:mm') : dayjs().format('HH:mm');
                        statusDiv.innerHTML = `<small class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i>Đã đón ${time}</small>`;
                    } else {
                        statusDiv.innerHTML = `<small class="text-muted"><i class="far fa-circle me-1"></i>Chưa đón</small>`;
                    }
                })
                .catch(() => {
                    this.checked = !checked; // Revert
                    showToast('Lỗi kết nối', 'error');
                });
            });
        });
    });
</script>
@endpush
