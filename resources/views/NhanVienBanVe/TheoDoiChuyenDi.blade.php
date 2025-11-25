@extends('layouts.NhanVienLayout')

@section('title', 'Theo dõi chuyến đi')
@section('page-title', 'Lịch trình Chuyến đi')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ====== FILTER CARD ====== --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="fas fa-filter me-2 text-secondary"></i>
                Bộ lọc tìm kiếm
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" method="GET" id="filter-form">
                <div class="row g-3">
                    <!-- Ngày -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_date" class="form-label fw-bold text-secondary small text-uppercase">
                            Ngày khởi hành
                        </label>
                        <input type="date" 
                               name="date" 
                               id="filter_date" 
                               class="form-control" 
                               value="{{ request('date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                    </div>

                    <!-- Tuyến đường -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_route" class="form-label fw-bold text-secondary small text-uppercase">
                            Tuyến đường
                        </label>
                        <select name="route" id="filter_route" class="form-select">
                            <option value="">Tất cả tuyến</option>
                            @foreach($routes as $route)
                                <option value="{{ $route['value'] }}" @selected(request('route') == $route['value'])>
                                    {{ formatRouteForDropdown($route['label']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-4 col-md-6">
                        <label for="filter_status" class="form-label fw-bold text-secondary small text-uppercase">
                            Trạng thái
                        </label>
                        <select name="status" id="filter_status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="sap-khoi-hanh" @selected(request('status') == 'sap-khoi-hanh')>Sắp khởi hành</option>
                            <option value="dang-chay" @selected(request('status') == 'dang-chay')>Đang chạy</option>
                            <option value="hoan-thanh" @selected(request('status') == 'hoan-thanh')>Hoàn thành</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('nhan-vien-ban-ve.chuyen-di.index') }}" class="btn btn-light border">
                        <i class="fas fa-undo me-2"></i> Đặt lại
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-filter me-2"></i> Lọc dữ liệu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TRIPS TABLE ====== --}}
    <div class="card border shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                Danh sách chuyến đi
            </h6>
            <span class="badge bg-light text-dark border fw-normal">
                Tổng số: <span class="fw-bold">{{ $chuyenDis->count() }}</span> chuyến
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="trips-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bold text-secondary text-uppercase small py-3 ps-4 sortable" data-sort="from" style="cursor: pointer;">
                                Điểm đi <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 sortable" data-sort="to" style="cursor: pointer;">
                                Điểm đến <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 sortable" data-sort="time" style="cursor: pointer;">
                                Giờ khởi hành <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 sortable" data-sort="vehicle" style="cursor: pointer;">
                                Biển số xe <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" data-sort="booked" style="cursor: pointer;">
                                Đã đặt <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" data-sort="available" style="cursor: pointer;">
                                Còn trống <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" data-sort="status" style="cursor: pointer;">
                                Trạng thái <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center pe-4">Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chuyenDis as $chuyen)
                            <tr class="trip-row border-bottom">
                                <!-- Điểm đi -->
                                <td class="ps-4">
                                    @php
                                        $parts = preg_split('/\s*(?:→|->|-)\s*/u', $chuyen->tuyen_duong);
                                        $from = trim($parts[0] ?? '');
                                        $to = trim($parts[1] ?? '');
                                    @endphp
                                    <span class="badge bg-light text-primary border border-primary px-3 py-2">
                                        {{ $from }}
                                    </span>
                                </td>

                                <!-- Điểm đến -->
                                <td>
                                    <span class="badge bg-light text-danger border border-danger px-3 py-2">
                                        {{ $to }}
                                    </span>
                                </td>

                                <!-- Giờ khởi hành -->
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="far fa-clock me-2"></i>
                                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($chuyen->thoigiandi)->format('H:i') }}</span>
                                    </div>
                                </td>

                                <!-- Biển số xe -->
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal">
                                        {{ $chuyen->xe->soxe ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Ghế đã đặt -->
                                <td class="text-center">
                                    <span class="fw-bold text-secondary">
                                        {{ $chuyen->so_ghe_da_dat ?? 0 }} / {{ $chuyen->xe->loaixe->tong_so_ghe ?? 0 }}
                                    </span>
                                </td>

                                <!-- Ghế trống -->
                                <td class="text-center">
                                    @php
                                        $ghetrong = ($chuyen->xe->loaixe->tong_so_ghe ?? 0) - ($chuyen->so_ghe_da_dat ?? 0);
                                        $percent = ($chuyen->xe->loaixe->tong_so_ghe ?? 0) > 0 ? ($ghetrong / $chuyen->xe->loaixe->tong_so_ghe) * 100 : 0;
                                        $colorClass = $percent > 50 ? 'success' : ($percent > 20 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $colorClass }}-subtle text-{{ $colorClass }} border border-{{ $colorClass }}-subtle fw-bold px-3">
                                        {{ $ghetrong }}
                                    </span>
                                </td>

                                <!-- Trạng thái -->
                                <td class="text-center">
                                    @php($status = $chuyen->status_display ?? 'N/A')
                                    @if ($status === 'Sắp khởi hành')
                                        <span class="badge bg-info text-dark bg-opacity-10 border border-info px-2 py-1 fw-normal text-info">
                                            Sắp khởi hành
                                        </span>
                                    @elseif ($status === 'Đang chạy')
                                        <span class="badge bg-warning text-dark bg-opacity-10 border border-warning px-2 py-1 fw-normal text-warning">
                                            Đang chạy
                                        </span>
                                    @elseif ($status === 'Đã hoàn thành')
                                        <span class="badge bg-success text-dark bg-opacity-10 border border-success px-2 py-1 fw-normal text-success">
                                            Hoàn thành
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-dark bg-opacity-10 border border-secondary px-2 py-1 fw-normal text-secondary">
                                            {{ $status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm btn-outline-primary btn-view-details" 
                                            data-id="{{ $chuyen->machuyendi }}"
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-bus fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Không tìm thấy chuyến đi nào phù hợp</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== PAGINATION ====== --}}
        <div class="card-footer bg-white border-top py-3" id="trips-pagination"></div>
    </div>
</div>

{{-- ====== MODAL CHI TIẾT CHUYẾN ĐI ====== --}}
<div class="modal fade" id="tripDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Chi tiết chuyến đi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modal-content-placeholder" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-3 small">Đang tải dữ liệu...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enterprise UI Overrides */
.card {
    border-radius: 6px;
    border-color: #e0e0e0;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.05)!important;
}
.form-label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
.form-select, .form-control {
    border-radius: 4px;
    border-color: #ced4da;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}
.form-select:focus, .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}
.btn {
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.table thead th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

/* Pagination Styles */
.smart-pagination .pagination {
    justify-content: flex-end;
    margin-bottom: 0;
}
.smart-pagination .page-link {
    border-radius: 4px;
    margin: 0 2px;
    color: #495057;
    border: 1px solid #dee2e6;
}
.smart-pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

/* Choices.js Customization */
.choices__inner {
    background-color: #fff;
    border-radius: 4px;
    border: 1px solid #ced4da;
    min-height: auto;
    padding: 0.25rem 0.5rem;
}
.choices__input {
    background-color: transparent;
}

/* Modal Seat Map */
.seat-map-container {
    display: grid;
    gap: 8px;
    max-width: 500px;
    margin: 20px auto;
    padding: 1.5rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
}
.seat-box-modal {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    transition: all 0.2s;
}
.seat-box-modal:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.modal-info-card {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/pagination.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize JS Pagination
    const tripsPagination = new Pagination({
        tableId: 'trips-table',
        paginationId: 'trips-pagination',
        itemsPerPage: 15
    });

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
            headers.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
                const icon = h.querySelector('i');
                if (icon) icon.className = 'fas fa-sort ms-1 text-muted';
            });

            // Toggle sort direction
            if (currentSort.column === sortType) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
            }
            currentSort.column = sortType;

            // Add sort indicator
            this.classList.add(`sort-${currentSort.direction}`);
            const icon = this.querySelector('i');
            if (icon) icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} ms-1 text-dark`;

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
                    <p class="text-muted mt-3 small">Đang tải thông tin chuyến đi...</p>
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
                    const seatStyle = isBooked 
                        ? 'background: #dc3545; border-color: #dc3545; color: white;' 
                        : 'background: #f8fff9; border-color: #198754; color: #198754;';
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
                        <h6 class="mb-3 fw-bold text-dark">
                            <i class="fas fa-users me-2 text-secondary"></i>
                            Danh sách hành khách (${passengers.length})
                        </h6>
                `;
                
                if (passengers.length > 0) {
                    passengerHTML += `
                        <div class="table-responsive border rounded">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-2">Mã vé</th>
                                        <th class="py-2">Ghế</th>
                                        <th class="py-2">Tên khách</th>
                                        <th class="py-2">SĐT</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    passengers.forEach(p => {
                        passengerHTML += `
                            <tr>
                                <td class="py-2"><span class="fw-bold text-primary font-monospace small">${p.mave}</span></td>
                                <td class="py-2"><span class="badge bg-warning text-dark">${p.maghe}</span></td>
                                <td class="py-2 fw-semibold small">${p.ten_khach}</td>
                                <td class="py-2 small text-muted">${p.sdt}</td>
                            </tr>
                        `;
                    });
                    passengerHTML += `
                                </tbody>
                            </table>
                        </div>
                    `;
                } else {
                    passengerHTML += '<p class="text-muted text-center py-4 small border rounded bg-light">Chưa có hành khách đặt vé</p>';
                }
                passengerHTML += '</div>';
                
                modalBody.innerHTML = `
                    <div class="modal-info-card mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-uppercase text-muted small fw-bold mb-1">Tuyến đường</div>
                                <div class="route-display">
                                    ${(() => {
                                        const parts = trip.tuyen.split(/\s*(?:→|->|-)\s*/);
                                        const from = parts[0] || '';
                                        const to = parts[1] || '';
                                        return `
                                            <span class="route-badge route-from">${from}</span>
                                            <i class="fas fa-arrow-right route-arrow"></i>
                                            <span class="route-badge route-to">${to}</span>
                                        `;
                                    })()}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-uppercase text-muted small fw-bold mb-1">Ghế đã đặt</div>
                                <h5 class="fw-bold text-primary mb-0">${booked_seats.length} / ${loaixe.tong_so_ghe}</h5>
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="mb-3 fw-bold text-dark">
                        <i class="fas fa-th me-2 text-secondary"></i>
                        Sơ đồ ghế ngồi
                    </h6>
                    
                    <div class="d-flex justify-content-center gap-4 mb-3 p-2 bg-light rounded border">
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block rounded me-2" style="width: 16px; height: 16px; background: #f8fff9; border: 1px solid #198754;"></span>
                            <span class="small fw-semibold text-secondary">Còn trống</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="d-inline-block rounded me-2" style="width: 16px; height: 16px; background: #dc3545; border: 1px solid #dc3545;"></span>
                            <span class="small fw-semibold text-secondary">Đã đặt</span>
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
                            <h6 class="alert-heading mb-1 fw-bold">Lỗi tải dữ liệu</h6>
                            <p class="mb-0 small">Có lỗi xảy ra khi tải chi tiết chuyến đi. Vui lòng thử lại.</p>
                        </div>
                    </div>
                `;
                console.error('Error:', error);
            });
    }
});
</script>
@endpush