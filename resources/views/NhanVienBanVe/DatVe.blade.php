@extends('layouts.NhanVienLayout')

@section('title', 'Đặt vé')
@section('page-title', 'Đặt vé')

@section('content')
<div class="container-fluid px-4 py-3">
    
    {{-- Filter Section --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0">
            <button class="btn btn-link text-decoration-none text-dark fw-bold p-0 w-100 text-start d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                <span><i class="fas fa-filter me-2 text-primary"></i> Bộ lọc tìm kiếm</span>
                <i class="fas fa-chevron-down small"></i>
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <div class="row g-3">
                    {{-- Tuyến đường --}}
                    <div class="col-md-4">
                        <label for="filter-route" class="form-label small text-uppercase fw-bold text-secondary">Tuyến đường</label>
                        <select id="filter-route" class="form-select">
                            <option value="">Tất cả tuyến</option>
                        </select>
                    </div>

                    {{-- Thời gian --}}
                    <div class="col-md-4">
                        <label for="filter-time" class="form-label small text-uppercase fw-bold text-secondary">Thời gian</label>
                        <select id="filter-time" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="morning">Sáng (6h - 12h)</option>
                            <option value="afternoon">Chiều (12h - 18h)</option>
                            <option value="evening">Tối (18h - 24h)</option>
                        </select>
                    </div>

                    {{-- Tình trạng ghế --}}
                    <div class="col-md-4">
                        <label for="filter-status" class="form-label small text-uppercase fw-bold text-secondary">Tình trạng</label>
                        <select id="filter-status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="available">Còn ghế trống</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" id="btn-apply-filter" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Áp dụng
                    </button>
                    <button type="button" id="btn-reset-filter" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Đặt lại
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Trip List --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="fas fa-bus me-2 text-primary"></i> Danh sách chuyến đi
            </h6>
            <span class="badge bg-light text-dark border" id="trip-count">0 chuyến</span>
        </div>
        <div class="card-body">
            <div class="row g-3" id="trip-list-container">
                {{-- Trip cards will be rendered here by JavaScript --}}
            </div>
        </div>
    </div>

    {{-- Seat Selection Modal --}}
    <div class="modal fade" id="seatModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="modal-trip-title">Chọn ghế ngồi</h5>
                        <p class="text-muted small mb-0" id="modal-trip-subtitle"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-th me-2 text-secondary"></i> Sơ đồ ghế ngồi
                            </h6>
                            <div class="d-flex justify-content-center gap-4 mb-3 p-2 bg-light rounded border">
                                <div class="d-flex align-items-center">
                                    <span class="seat-legend seat-legend-available me-2"></span>
                                    <span class="small fw-semibold text-secondary">Còn trống</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="seat-legend seat-legend-selected me-2"></span>
                                    <span class="small fw-semibold text-secondary">Đang chọn</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="seat-legend seat-legend-sold me-2"></span>
                                    <span class="small fw-semibold text-secondary">Đã bán</span>
                                </div>
                            </div>
                            <div id="seat-map-container" class="seat-map-container"></div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-bold">Chi tiết đặt vé</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="small text-uppercase fw-bold text-secondary">Ghế đã chọn</label>
                                        <p class="mb-0 fw-semibold" id="selected-seats-display">Chưa chọn ghế</p>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="small text-uppercase fw-bold text-secondary">Số lượng</label>
                                            <p class="mb-0 fw-semibold" id="seat-quantity">0</p>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-uppercase fw-bold text-secondary">Đơn giá</label>
                                            <p class="mb-0 fw-semibold" id="seat-price">0₫</p>
                                        </div>
                                    </div>
                                    <div class="border-top pt-3">
                                        <label class="small text-uppercase fw-bold text-secondary">Tổng tiền</label>
                                        <p class="mb-0 fs-4 fw-bold text-primary" id="total-price">0₫</p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="btn-proceed-checkout" class="btn btn-primary w-100 mt-3" disabled>
                                <i class="fas fa-arrow-right me-2"></i> Tiếp tục thanh toán
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Checkout Modal --}}
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-check-circle text-success me-2"></i> Xác nhận đặt vé
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="checkout-form" method="POST">
                        @csrf
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Thông tin chuyến đi</h6>
                                <div class="mb-2">
                                    <span class="small text-secondary">Tuyến:</span>
                                    <span class="fw-semibold" id="checkout-trip"></span>
                                </div>
                                <div class="mb-2">
                                    <span class="small text-secondary">Ghế:</span>
                                    <span class="fw-semibold" id="checkout-seats"></span>
                                </div>
                                <div class="border-top pt-2 mt-2">
                                    <span class="small text-secondary">Tổng tiền:</span>
                                    <span class="fw-bold text-primary fs-5" id="checkout-total"></span>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3">Thông tin khách hàng</h6>
                        <div class="mb-3">
                            <label for="ten_khach" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ten_khach" name="ten_khach" required>
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="sdt" name="sdt" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="2"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Xác nhận đặt vé
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Quay lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 6px;
    border-color: #e0e0e0;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.05)!important;
}
.cursor-pointer {
    cursor: pointer;
}

/* Trip Card Styles */
.trip-card {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.25rem;
    transition: all 0.2s ease;
    cursor: pointer;
    background: white;
    height: 100%;
}
.trip-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    transform: translateY(-2px);
}
.trip-card.selected {
    border-color: #0d6efd;
    background-color: #f0f7ff;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
}
.trip-card .seat-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.6rem;
    font-weight: 600;
}

/* Seat Map Styles */
.seat-map-container {
    display: grid;
    gap: 8px;
    max-width: 100%;
    margin: 0 auto;
}
.seat {
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid #dee2e6;
    background-color: #fff;
    color: #495057;
    user-select: none;
}
.seat:hover:not(.sold) {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.seat.available {
    border-color: #198754;
    color: #198754;
    background-color: #f8fff9;
}
.seat.available:hover {
    background-color: #198754;
    color: #fff;
}
.seat.sold {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
    cursor: not-allowed;
    opacity: 0.7;
}
.seat.selected {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 800;
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.3);
}

/* Seat Legend */
.seat-legend {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: inline-block;
    border: 2px solid;
}
.seat-legend-available {
    background-color: #f8fff9;
    border-color: #198754;
}
.seat-legend-selected {
    background-color: #ffc107;
    border-color: #ffc107;
}
.seat-legend-sold {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Form Styles */
.form-label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
.form-select, .form-control {
    border-radius: 4px;
    border-color: #ced4da;
    font-size: 0.9rem;
    padding: 0.6rem 0.75rem;
}
.btn {
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.6rem 1.2rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let allTrips = [];
    let selectedTrip = null;
    let selectedSeats = [];
    let seatData = {};

    // City name mapping for shortening
    const cityMap = {
        'TP. Ho Chi Minh': 'HCM',
        'TP. Hồ Chí Minh': 'HCM',
        'Ho Chi Minh': 'HCM',
        'Hồ Chí Minh': 'HCM',
        'Can Tho': 'Cần Thơ',
        'Hai Phong': 'Hải Phòng',
        'Da Nang': 'Đà Nẵng',
        'Da Lat': 'Đà Lạt',
        'Vung Tau': 'Vũng Tàu',
        'Nha Trang': 'Nha Trang',
        'Ha Noi': 'Hà Nội'
    };

    function shortenCityName(name) {
        return cityMap[name.trim()] || name;
    }

    // Initialize Choices.js for filters
    const routeSelect = new Choices('#filter-route', {
        searchEnabled: true,
        itemSelectText: '',
        shouldSort: false
    });

    // Load initial data
    loadTrips();

    // Filter handlers
    document.getElementById('btn-apply-filter').addEventListener('click', applyFilters);
    document.getElementById('btn-reset-filter').addEventListener('click', resetFilters);

    // Checkout handlers
    document.getElementById('btn-proceed-checkout').addEventListener('click', openCheckout);
    document.getElementById('checkout-form').addEventListener('submit', handleCheckout);

    function loadTrips() {
        fetch('{{ route("nhan-vien-ban-ve.api.chuyen-di") }}')
            .then(res => res.json())
            .then(data => {
                allTrips = data;
                renderTrips(allTrips);
                populateFilters(allTrips);
            })
            .catch(err => {
                console.error('Error loading trips:', err);
                showError('Không thể tải danh sách chuyến đi');
            });
    }

    function populateFilters(trips) {
        const routes = [...new Set(trips.map(t => t.tuyen))];
        routeSelect.setChoices(
            routes.map(r => ({ value: r, label: r })),
            'value',
            'label',
            true
        );
    }

    function renderTrips(trips) {
        const container = document.getElementById('trip-list-container');
        document.getElementById('trip-count').textContent = `${trips.length} chuyến`;

        if (trips.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Không tìm thấy chuyến đi phù hợp</p>
                </div>
            `;
            return;
        }

        container.innerHTML = trips.map(trip => {
            // Split route for badges
            const parts = trip.tuyen.split(/\s*(?:→|->|-)\s*/);
            const fromOriginal = parts[0] || '';
            const toOriginal = parts[1] || '';
            const fromShort = shortenCityName(fromOriginal);
            const toShort = shortenCityName(toOriginal);
            
            return `
            <div class="col-lg-4 col-md-6">
                <div class="trip-card" data-trip-id="${trip.machuyendi}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="route-display">
                            <span class="route-badge route-from" title="${fromOriginal}">${fromShort}</span>
                            <i class="fas fa-arrow-right route-arrow"></i>
                            <span class="route-badge route-to" title="${toOriginal}">${toShort}</span>
                        </div>
                        <span class="badge seat-badge ${trip.ghe_trong > 0 ? 'bg-success' : 'bg-danger'}">
                            ${trip.ghe_trong} ghế
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <span><i class="far fa-clock me-1"></i> ${trip.gio_khoi_hanh}</span>
                        <span><i class="fas fa-bus me-1"></i> ${trip.bien_so}</span>
                    </div>
                    <div class="mt-2 pt-2 border-top">
                        <span class="fw-bold text-primary">${formatCurrency(trip.gia_ve)}</span>
                    </div>
                </div>
            </div>
            `;
        }).join('');
        
        // Add click handlers using event delegation
        container.querySelectorAll('.trip-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                container.querySelectorAll('.trip-card').forEach(c => c.classList.remove('selected'));
                // Add selected class to clicked card
                this.classList.add('selected');
                // Handle selection
                const tripId = this.getAttribute('data-trip-id');
                selectTrip(tripId);
            });
        });
    }

    function selectTrip(tripId) {
        selectedTrip = allTrips.find(t => t.machuyendi === tripId);
        if (!selectedTrip) return;

        // Load seat data
        fetch(`{{ url('nhan-vien-ban-ve/api/chuyen-di') }}/${tripId}/ghe`)
            .then(res => res.json())
            .then(data => {
                seatData = data;
                selectedSeats = []; // Reset selected seats
                openSeatModal();
            })
            .catch(err => {
                console.error('Error loading seats:', err);
                showError('Không thể tải sơ đồ ghế');
            });
    }

    function openSeatModal() {
        document.getElementById('modal-trip-title').textContent = selectedTrip.tuyen;
        document.getElementById('modal-trip-subtitle').textContent = 
            `${selectedTrip.gio_khoi_hanh} • ${selectedTrip.bien_so}`;
        
        renderSeatMap();
        updateBookingSummary();
        
        const modal = new bootstrap.Modal(document.getElementById('seatModal'));
        modal.show();
    }

    function renderSeatMap() {
        const container = document.getElementById('seat-map-container');
        const { layout, seats } = seatData;
        
        container.style.gridTemplateColumns = `repeat(${layout.cols}, 1fr)`;
        
        container.innerHTML = seats.map(seat => {
            const status = seat.trang_thai === 'Đã bán' ? 'sold' : 'available';
            return `
                <div class="seat ${status}" 
                     data-seat-code="${seat.ma_ghe}"
                     data-price="${seat.gia}">
                    ${seat.ma_ghe}
                </div>
            `;
        }).join('');
        
        // Add click handlers to seats
        container.querySelectorAll('.seat.available').forEach(seat => {
            seat.addEventListener('click', function() {
                toggleSeat(this.getAttribute('data-seat-code'));
            });
        });
    }

    function toggleSeat(seatCode) {
        const seatEl = document.querySelector(`[data-seat-code="${seatCode}"]`);
        if (!seatEl || seatEl.classList.contains('sold')) return;

        const index = selectedSeats.indexOf(seatCode);
        if (index > -1) {
            selectedSeats.splice(index, 1);
            seatEl.classList.remove('selected');
        } else {
            selectedSeats.push(seatCode);
            seatEl.classList.add('selected');
        }

        updateBookingSummary();
    }

    function updateBookingSummary() {
        const quantity = selectedSeats.length;
        const price = selectedTrip.gia_ve || 0;
        const total = quantity * price;

        document.getElementById('selected-seats-display').textContent = 
            selectedSeats.length > 0 ? selectedSeats.join(', ') : 'Chưa chọn ghế';
        document.getElementById('seat-quantity').textContent = quantity;
        document.getElementById('seat-price').textContent = formatCurrency(price);
        document.getElementById('total-price').textContent = formatCurrency(total);

        document.getElementById('btn-proceed-checkout').disabled = quantity === 0;
    }

    function openCheckout() {
        document.getElementById('checkout-trip').textContent = selectedTrip.tuyen;
        document.getElementById('checkout-seats').textContent = selectedSeats.join(', ');
        document.getElementById('checkout-total').textContent = 
            document.getElementById('total-price').textContent;

        bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        modal.show();
    }

    function handleCheckout(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        
        const payload = new FormData();
        payload.append('_token', '{{ csrf_token() }}');
        payload.append('machuyendi', selectedTrip.machuyendi);
        payload.append('seats', selectedSeats.join(','));
        payload.append('gia_ve', selectedTrip.gia_ve);
        payload.append('kh_hoten', formData.get('ten_khach'));
        payload.append('kh_sdt', formData.get('sdt'));
        payload.append('kh_email', formData.get('email') || '');
        payload.append('phuongthuc_thanhtoan', 'tien-mat');
        payload.append('ghi_chu', formData.get('ghi_chu') || '');

        fetch('{{ route("nhan-vien-ban-ve.dat-ve.store") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: payload
        })
        .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, data })))
        .then(({ status, ok, data }) => {
            if (!ok) {
                throw new Error(data.message || 'Có lỗi xảy ra khi đặt vé');
            }
            
            // Close the modal first
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Đặt vé thành công!',
                text: data.message || 'Vé đã được tạo và lưu vào hệ thống',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                window.location.href = data.redirect_url || '{{ route("nhan-vien-ban-ve.ve.index") }}';
            });
        })
        .catch(err => {
            console.error('Checkout error:', err);
            showError(err.message || 'Có lỗi xảy ra khi đặt vé');
        });
    }

    function applyFilters() {
        const route = document.getElementById('filter-route').value;
        const time = document.getElementById('filter-time').value;
        const status = document.getElementById('filter-status').value;

        let filtered = allTrips;

        if (route) {
            filtered = filtered.filter(t => t.tuyen === route);
        }

        if (time) {
            filtered = filtered.filter(t => {
                const hour = parseInt(t.gio_khoi_hanh.split(':')[0]);
                if (time === 'morning') return hour >= 6 && hour < 12;
                if (time === 'afternoon') return hour >= 12 && hour < 18;
                if (time === 'evening') return hour >= 18;
                return true;
            });
        }

        if (status === 'available') {
            filtered = filtered.filter(t => t.ghe_trong > 0);
        }

        renderTrips(filtered);
    }

    function resetFilters() {
        routeSelect.setChoiceByValue('');
        document.getElementById('filter-time').value = '';
        document.getElementById('filter-status').value = '';
        renderTrips(allTrips);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: message,
            confirmButtonColor: '#dc3545'
        });
    }
});
</script>
@endpush
