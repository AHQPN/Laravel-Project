
@extends('layouts.NhanVienLayout')

@section('title', 'Theo dõi chuyến đi')
@section('page-title', 'Theo dõi chuyến đi')

@section('content')
<div class="container-fluid">
    
    {{-- ====== FILTER CARD ====== --}}
    <div class="card shadow-sm border-0 mb-4 animate-card">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc tìm kiếm
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" method="GET" id="filter-form">
                <div class="row g-3 mb-3">
                    <!-- Ngày -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_date" class="form-label fw-semibold text-secondary mb-2">
                            <i class="far fa-calendar-alt me-1"></i> Ngày
                        </label>
                        <input type="date" 
                               name="date" 
                               id="filter_date" 
                               class="form-control shadow-sm" 
                               value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                    </div>

                    <!-- Tuyến đường -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_route" class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-route me-1"></i> Tuyến đường
                        </label>
                        <select name="route" id="filter_route" class="form-select shadow-sm">
                            <option value="">Tất cả tuyến</option>
                            @foreach($routes as $route)
                                <option value="{{ $route['value'] }}" @selected(request('route') == $route['value'])>
                                    {{ $route['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_status" class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-info-circle me-1"></i> Trạng thái
                        </label>
                        <select name="status" id="filter_status" class="form-select shadow-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="sap-khoi-hanh" @selected(request('status') == 'sap-khoi-hanh')>
                                🕐 Sắp khởi hành
                            </option>
                            <option value="dang-chay" @selected(request('status') == 'dang-chay')>
                                🚌 Đang chạy
                            </option>
                            <option value="hoan-thanh" @selected(request('status') == 'hoan-thanh')>
                                ✓ Hoàn thành
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-redo-alt me-2"></i> Đặt lại
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-search me-2"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TRIPS TABLE ====== --}}
    <div class="card shadow-sm border-0 animate-card">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-bus me-2 text-primary"></i>
                    Danh sách chuyến đi
                </h5>
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fas fa-list me-1"></i> Tổng: {{ $chuyenDis->count() }} chuyến
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="trips-table">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable fw-semibold text-secondary" data-sort="route">
                                Tuyến đường <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable fw-semibold text-secondary" data-sort="time">
                                Giờ khởi hành <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable fw-semibold text-secondary" data-sort="vehicle">
                                Biển số xe <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable fw-semibold text-secondary text-center" data-sort="booked">
                                Ghế đã đặt <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable fw-semibold text-secondary text-center" data-sort="available">
                                Ghế trống <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="sortable fw-semibold text-secondary text-center" data-sort="status">
                                Trạng thái <i class="fas fa-sort ms-1"></i>
                            </th>
                            <th class="fw-semibold text-secondary text-center">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chuyenDis as $chuyen)
                            <tr class="trip-row">
                                <!-- Tuyến đường -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="trip-icon-box me-2">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <span class="fw-semibold">{{ $chuyen->tuyen_duong }}</span>
                                    </div>
                                </td>

                                <!-- Giờ khởi hành -->
                                <td>
                                    <i class="far fa-clock me-1 text-muted"></i>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($chuyen->thoigiandi)->format('H:i') }}</span>
                                </td>

                                <!-- Biển số xe -->
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1">
                                        <i class="fas fa-bus me-1"></i>
                                        {{ $chuyen->xe->soxe ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Ghế đã đặt -->
                                <td class="text-center">
                                    <div class="seat-info">
                                        <span class="badge bg-info-subtle text-info px-3 py-2">
                                            {{ $chuyen->so_ghe_da_dat ?? 0 }} / {{ $chuyen->xe->loaixe->tong_so_ghe ?? 0 }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Ghế trống -->
                                <td class="text-center">
                                    @php
                                        $ghetrong = ($chuyen->xe->loaixe->tong_so_ghe ?? 0) - ($chuyen->so_ghe_da_dat ?? 0);
                                    @endphp
                                    <span class="badge bg-success-subtle text-success px-3 py-2 fw-bold">
                                        <i class="fas fa-chair me-1"></i>{{ $ghetrong }}
                                    </span>
                                </td>

                                <!-- Trạng thái -->
                                <td class="text-center">
                                    @php($status = $chuyen->status_display ?? 'N/A')
                                    @if ($status === 'Sắp khởi hành')
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="far fa-clock me-1"></i>{{ $status }}
                                        </span>
                                    @elseif ($status === 'Đang chạy')
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="fas fa-bus me-1"></i>{{ $status }}
                                        </span>
                                    @elseif ($status === 'Đã hoàn thành')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>{{ $status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">{{ $status }}</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view-details shadow-sm" 
                                            data-id="{{ $chuyen->machuyendi }}"
                                            title="Xem chi tiết chuyến đi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-bus fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                        <p class="mb-0">Không có chuyến đi nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ====== MODAL CHI TIẾT CHUYẾN ĐI ====== --}}
<div class="modal fade" id="tripDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-bus me-2"></i>
                    Chi tiết chuyến đi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modal-content-placeholder" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-3">Đang tải thông tin chuyến đi...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ====== Gradient Background ====== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* ====== Card Animation ====== */
.animate-card {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ====== Form Controls ====== */
.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control,
.form-select {
    border-radius: 0.5rem;
    padding: 0.625rem 0.875rem;
    transition: all 0.2s ease;
}

.form-control:hover,
.form-select:hover {
    border-color: #667eea;
}

/* ====== Table Styling ====== */
#trips-table {
    font-size: 0.9rem;
}

#trips-table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
}

#trips-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

/* ====== Sortable Headers ====== */
.sortable {
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
}

.sortable:hover {
    background-color: #f8f9fa !important;
}

.sortable i {
    opacity: 0.3;
    transition: opacity 0.2s ease;
}

.sortable.sort-asc i::before {
    content: "\f160";
    opacity: 1;
    color: #667eea;
}

.sortable.sort-desc i::before {
    content: "\f161";
    opacity: 1;
    color: #667eea;
}

/* ====== Row Hover Effect ====== */
.trip-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.trip-row:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* ====== Trip Icon Box ====== */
.trip-icon-box {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

/* ====== Badge Styling ====== */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    border-radius: 0.375rem;
    letter-spacing: 0.3px;
}

/* ====== Button Styling ====== */
.btn-view-details {
    transition: all 0.2s ease;
    border-radius: 0.375rem;
}

.btn-view-details:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* ====== Modal Styling ====== */
.modal-content {
    border-radius: 0.75rem;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

/* ====== Seat Map in Modal ====== */
.seat-map-container {
    display: grid;
    gap: 10px;
    max-width: 600px;
    margin: 20px auto;
    padding: 2rem;
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
}

.seat-box-modal {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    border-radius: 0.5rem;
    border: 2px solid;
    transition: all 0.2s ease;
}

.seat-box-modal:hover {
    transform: scale(1.05);
}

/* ====== Info Card in Modal ====== */
.modal-info-card {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border-left: 4px solid #667eea;
    margin-bottom: 1.5rem;
}

/* ====== Legend in Modal ====== */
.legend-box-modal {
    width: 24px;
    height: 24px;
    border-radius: 0.375rem;
    display: inline-block;
    border: 2px solid;
    margin-right: 0.5rem;
}

/* ====== Responsive ====== */
@media (max-width: 992px) {
    #trips-table {
        font-size: 0.85rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.6rem !important;
    }
    
    .trip-icon-box {
        width: 30px;
        height: 30px;
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem !important;
    }
    
    #trips-table thead th {
        font-size: 0.7rem;
        padding: 0.75rem 0.5rem;
    }
    
    #trips-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* ====== Empty State ====== */
.fa-bus.fa-3x {
    opacity: 0.3;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Table sorting functionality
    const table = document.getElementById('trips-table');
    const headers = table.querySelectorAll('th.sortable');
    let currentSort = { column: null, direction: 'asc' };

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr.trip-row'));

            if (rows.length === 0) return;

            // Remove previous sort indicators
            headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));

            // Toggle sort direction
            if (currentSort.column === sortType) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
            }
            currentSort.column = sortType;

            // Add sort indicator
            this.classList.add(`sort-${currentSort.direction}`);

            // Sort rows
            rows.sort((a, b) => {
                let aValue, bValue;
                const cells = {
                    a: a.querySelectorAll('td'),
                    b: b.querySelectorAll('td')
                };

                switch(sortType) {
                    case 'route':
                        aValue = a.querySelector('td').textContent.trim();
                        bValue = b.querySelector('td').textContent.trim();
                        break;
                    case 'time':
                        aValue = cells.a[0].textContent.trim();
                        bValue = cells.b[0].textContent.trim();
                        break;
                    case 'vehicle':
                        aValue = cells.a[1].textContent.trim();
                        bValue = cells.b[1].textContent.trim();
                        break;
                    case 'booked':
                        aValue = parseInt(cells.a[2].textContent.split('/')[0]);
                        bValue = parseInt(cells.b[2].textContent.split('/')[0]);
                        break;
                    case 'available':
                        aValue = parseInt(cells.a[3].textContent.trim());
                        bValue = parseInt(cells.b[3].textContent.trim());
                        break;
                    case 'status':
                        aValue = cells.a[4].textContent.trim();
                        bValue = cells.b[4].textContent.trim();
                        break;
                }

                if (typeof aValue === 'number') {
                    return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
                } else {
                    return currentSort.direction === 'asc' 
                        ? aValue.localeCompare(bValue, 'vi')
                        : bValue.localeCompare(aValue, 'vi');
                }
            });

            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Modal functionality
    const viewButtons = document.querySelectorAll('.btn-view-details');
    const modal = new bootstrap.Modal(document.getElementById('tripDetailsModal'));
    const modalBody = document.getElementById('modal-content-placeholder');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const tripId = this.getAttribute('data-id');
            
            // Show loading spinner
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-3">Đang tải thông tin chuyến đi...</p>
                </div>
            `;
            modal.show();

            // Fetch trip details
            setTimeout(() => {
                fetchTripDetails(tripId);
            }, 300);
        });
    });

    function fetchTripDetails(tripId) {
        fetch(`{{ url('/nhan-vien-ban-ve/chuyen-di') }}/${tripId}`)
            .then(response => response.json())
            .then(data => {
                const { trip, loaixe, booked_seats, passengers } = data;
                const totalSeats = loaixe.tong_so_ghe || 0;
                const columns = 5;
                
                // Process booked seats
                const rawBooked = (booked_seats || []).map(s => s.toString().toUpperCase());
                const seatMeta = rawBooked.map(code => {
                    const match = code.match(/^([A-Z]+)(\d+)$/);
                    if (match) return { prefix: match[1], number: parseInt(match[2], 10) };
                    const numOnly = code.match(/^(\d+)$/);
                    if (numOnly) return { prefix: '', number: parseInt(numOnly[1], 10) };
                    return { prefix: null, number: null, raw: code };
                });

                const prefixes = [...new Set(seatMeta.filter(m => m.prefix !== null).map(m => m.prefix))];

                // Generate all seat codes
                let allSeatCodes = [];
                if (prefixes.length === 0) {
                    for (let i = 1; i <= totalSeats; i++) {
                        allSeatCodes.push(i.toString().padStart(2, '0'));
                    }
                } else {
                    const maxByPrefix = {};
                    prefixes.forEach(p => {
                        const nums = seatMeta.filter(m => m.prefix === p && m.number !== null).map(m => m.number);
                        maxByPrefix[p] = nums.length ? Math.max(...nums) : 0;
                    });
                    const inferredTotal = Object.values(maxByPrefix).reduce((a,b)=>a+b,0);
                    let remaining = totalSeats - inferredTotal;
                    if (remaining > 0 && prefixes.length) {
                        maxByPrefix[prefixes[0]] += remaining;
                    }
                    prefixes.forEach(p => {
                        for (let i=1; i<=maxByPrefix[p]; i++) {
                            allSeatCodes.push(p + i.toString().padStart(2,'0'));
                        }
                    });
                }

                const normalizedBooked = new Set(rawBooked);

                // Render seat map
                let seatMapHTML = `<div class="seat-map-container" style="grid-template-columns: repeat(${columns}, 1fr);">`;
                allSeatCodes.forEach(seatCode => {
                    const isBooked = normalizedBooked.has(seatCode);
                    const seatClass = isBooked ? 'booked' : 'available';
                    const seatStyle = isBooked 
                        ? 'background: #dc3545; border-color: #dc3545; color: white;' 
                        : 'background: white; border-color: #28a745; color: #28a745;';
                    const seatText = isBooked ? 'Đã đặt' : 'Trống';
                    seatMapHTML += `
                        <div class="seat-box-modal" 
                             title="Ghế ${seatCode} - ${seatText}" 
                             style="${seatStyle}">
                            ${seatCode}
                        </div>
                    `;
                });
                seatMapHTML += '</div>';
                
                // Render passenger list
                let passengerHTML = `
                    <div class="mt-4">
                        <h5 class="mb-3">
                            <i class="fas fa-users me-2 text-primary"></i>
                            Danh sách hành khách (${passengers.length})
                        </h5>
                `;
                
                if (passengers.length > 0) {
                    passengerHTML += `
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã vé</th>
                                        <th>Ghế</th>
                                        <th>Tên khách</th>
                                        <th>SĐT</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    passengers.forEach(p => {
                        passengerHTML += `
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary font-monospace">${p.mave}</span></td>
                                <td><span class="badge bg-info px-3 py-2">${p.maghe}</span></td>
                                <td class="fw-semibold">${p.ten_khach}</td>
                                <td><i class="fas fa-phone-alt me-1 text-muted"></i>${p.sdt}</td>
                            </tr>
                        `;
                    });
                    passengerHTML += `
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    passengerHTML += '<p class="text-muted text-center py-4">Chưa có hành khách đặt vé</p>';
                }
                passengerHTML += '</div>';
                
                modalBody.innerHTML = `
                    <div class="modal-info-card">
                        <h4 class="mb-4 fw-bold">
                            <i class="fas fa-route me-2 text-primary"></i>
                            ${trip.tuyen}
                        </h4>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-chair fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small">Ghế đã đặt</p>
                                        <p class="fw-bold mb-0">${booked_seats.length} / ${loaixe.tong_so_ghe}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-th me-2 text-primary"></i>
                        Sơ đồ ghế ngồi
                    </h5>
                    
                    <div class="d-flex justify-content-center gap-4 mb-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <span class="legend-box-modal" style="background: white; border-color: #28a745;"></span>
                            <span class="small fw-semibold">Còn trống</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="legend-box-modal" style="background: #dc3545; border-color: #dc3545;"></span>
                            <span class="small fw-semibold">Đã đặt</span>
                        </div>
                    </div>
                    
                    ${seatMapHTML}
                    ${passengerHTML}
                `;
            })
            .catch(error => {
                modalBody.innerHTML = `
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Lỗi tải dữ liệu</h5>
                            <p class="mb-0">Có lỗi xảy ra khi tải chi tiết chuyến đi. Vui lòng thử lại.</p>
                        </div>
                    </div>
                `;
                console.error('Error:', error);
            });
    }
});
</script>
@endpush