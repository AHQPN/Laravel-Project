@extends('layouts.NhanVienLayout')

@section('title', 'Đặt vé tại quầy')
@section('page-title', 'Bán vé')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Step 1: Filters --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <span class="step-badge bg-primary text-white me-2">1</span>
                Tìm kiếm chuyến đi
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label for="filter-route" class="form-label fw-bold text-secondary small text-uppercase">
                        Tuyến đường
                    </label>
                    <select id="filter-route" class="form-select" data-trigger name="filter-route">
                        <option value="">Chọn tuyến...</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="filter-time" class="form-label fw-bold text-secondary small text-uppercase">
                        Giờ khởi hành
                    </label>
                    <select id="filter-time" class="form-select" data-trigger name="filter-time" disabled>
                        <option value="">Chọn tuyến trước...</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="filter-vehicle" class="form-label fw-bold text-secondary small text-uppercase">
                        Chọn xe
                    </label>
                    <select id="filter-vehicle" class="form-select" data-trigger name="filter-vehicle" disabled>
                        <option value="">Chọn giờ trước...</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button id="btn-find-seats" class="btn btn-primary px-4 fw-bold" disabled>
                    <i class="fas fa-search me-2"></i>Tìm ghế trống
                </button>
            </div>
        </div>
    </div>

    {{-- Step 2: Vehicle & Trip Info --}}
    <div class="card border shadow-sm mb-4" id="trip-info-card" style="display: none;">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <span class="step-badge bg-primary text-white me-2">2</span>
                Thông tin chuyến đi
            </h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center p-3 border rounded bg-light h-100">
                        <div class="me-3 text-secondary">
                            <i class="fas fa-route fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted small fw-bold">Tuyến đường</div>
                            <div class="fw-bold text-dark" id="trip-route">-</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center p-3 border rounded bg-light h-100">
                        <div class="me-3 text-secondary">
                            <i class="far fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted small fw-bold">Khởi hành</div>
                            <div class="fw-bold text-dark" id="trip-time">-</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center p-3 border rounded bg-light h-100">
                        <div class="me-3 text-secondary">
                            <i class="fas fa-bus fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted small fw-bold">Xe</div>
                            <div class="fw-bold text-dark" id="trip-vehicle">-</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center p-3 border rounded bg-light h-100">
                        <div class="me-3 text-secondary">
                            <i class="fas fa-tag fa-2x"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-muted small fw-bold">Giá vé</div>
                            <div class="fw-bold text-success fs-5" id="trip-price">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Step 3: Seat Map --}}
    <div class="card border shadow-sm mb-4" id="seat-map-card" style="display: none;">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <span class="step-badge bg-primary text-white me-2">3</span>
                Sơ đồ ghế
            </h6>
        </div>
        <div class="card-body p-4">
            {{-- Seat Map Legend --}}
            <div class="d-flex justify-content-center flex-wrap gap-4 mb-4 p-3 bg-light rounded border">
                <div class="d-flex align-items-center">
                    <span class="seat-legend available me-2"></span>
                    <span class="small fw-bold text-secondary">Còn trống</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="seat-legend sold me-2"></span>
                    <span class="small fw-bold text-secondary">Đã bán</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="seat-legend selected me-2"></span>
                    <span class="small fw-bold text-secondary">Đang chọn</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="seat-legend unavailable me-2"></span>
                    <span class="small fw-bold text-secondary">Không bán</span>
                </div>
            </div>
            
            {{-- Seat Map --}}
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="seat-map-container border rounded p-4">
                        <div class="driver-seat mb-4 text-center p-2 bg-light border rounded text-muted fw-bold text-uppercase small">
                            <i class="fas fa-steering-wheel me-2"></i>Tài xế
                        </div>
                        <div id="seat-map-container" class="d-grid gap-2">
                            {{-- JS will render seats here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Step 4: Ticket Summary & Checkout --}}
    <div class="card border shadow-sm mb-4" id="ticket-summary-card" style="display: none;">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <span class="step-badge bg-primary text-white me-2">4</span>
                Thanh toán
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-lg-8 border-end">
                    <div class="p-4">
                        <h6 class="fw-bold mb-3 text-secondary text-uppercase small">Chi tiết vé chọn</h6>
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="text-muted ps-0">Số lượng ghế:</td>
                                    <td class="text-end fw-bold pe-0"><span id="selected-count" class="badge bg-secondary">0</span></td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted ps-0">Vị trí ghế:</td>
                                    <td class="text-end fw-bold pe-0" id="selected-seats-info">-</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted ps-0">Đơn giá:</td>
                                    <td class="text-end fw-bold pe-0" id="unit-price">-</td>
                                </tr>
                                <tr>
                                    <td class="text-dark fw-bold ps-0 fs-5 pt-3">Tổng cộng:</td>
                                    <td class="text-end fw-bold text-success fs-4 pe-0 pt-3" id="total-price-info">0đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-4 bg-light">
                    <div class="p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <i class="fas fa-file-invoice-dollar fa-3x text-success mb-3 opacity-50"></i>
                        <p class="text-muted small mb-4">Vui lòng kiểm tra kỹ thông tin trước khi xuất vé.</p>
                        <button id="btn-show-customer-modal" class="btn btn-success w-100 py-3 fw-bold text-uppercase">
                            <i class="fas fa-check-circle me-2"></i>Tiếp tục thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enterprise UI Styles */
.card {
    border-radius: 6px;
    border-color: #e0e0e0;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.05)!important;
}
.step-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
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
.form-select:focus, .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}
.btn {
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.6rem 1.2rem;
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.btn-success {
    background-color: #198754;
    border-color: #198754;
}

/* Seat Map Styles */
.seat-map-container {
    background-color: #fff;
}
.seat {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #dee2e6;
    background-color: #fff;
    color: #495057;
}
.seat:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    opacity: 0.8;
}
.seat.selected {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: bold;
}
.seat.unavailable {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #adb5bd;
    cursor: not-allowed;
}
.seat-legend {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: inline-block;
    border: 1px solid transparent;
}
.seat-legend.available { border-color: #198754; background-color: #f8fff9; }
.seat-legend.sold { background-color: #dc3545; }
.seat-legend.selected { background-color: #ffc107; }
.seat-legend.unavailable { background-color: #e9ecef; border-color: #dee2e6; }

/* Modal Styles */
.swal2-popup {
    border-radius: 8px;
    font-family: inherit;
}
.swal2-title {
    font-size: 1.25rem;
    color: #333;
}
.swal2-html-container {
    text-align: left !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const choicesConfig = {
        searchEnabled: true,
        itemSelectText: '',
        shouldSort: false,
        allowHTML: true,
        placeholder: true,
        placeholderValue: 'Chọn...'
    };
    const routeSelect = new Choices('#filter-route', choicesConfig);
    const timeSelect = new Choices('#filter-time', choicesConfig);
    const vehicleSelect = new Choices('#filter-vehicle', choicesConfig);
    
    const findSeatsBtn = document.getElementById('btn-find-seats');
    const tripInfoCard = document.getElementById('trip-info-card');
    const seatMapCard = document.getElementById('seat-map-card');
    const seatMapContainer = document.getElementById('seat-map-container');
    const ticketSummaryCard = document.getElementById('ticket-summary-card');
    const selectedSeatsInfo = document.getElementById('selected-seats-info');
    const totalPriceInfo = document.getElementById('total-price-info');
    const showCustomerModalBtn = document.getElementById('btn-show-customer-modal');

    let selectedSeats = [];
    let tripData = {};
    let currentMachuyendi = null;
    let currentRoute = '';
    let currentTimeLabel = '';
    let currentVehicleInfo = '';
    let vehiclesData = [];

    // 1. Fetch initial routes
    fetch("{{ route('nhan-vien-ban-ve.api.chuyen-di') }}")
        .then(response => response.json())
        .then(data => {
            routeSelect.setChoices(data, 'value', 'label', true);
        });

    // 2. Handle route change to fetch time slots
    document.getElementById('filter-route').addEventListener('change', function(event) {
        const route = event.detail.value;
        const selectedRoute = routeSelect.getValue();
        currentRoute = selectedRoute && selectedRoute.label ? selectedRoute.label : '';
        timeSelect.clearStore();
        vehicleSelect.clearStore();
        findSeatsBtn.disabled = true;
        tripInfoCard.style.display = 'none';
        seatMapCard.style.display = 'none';
        ticketSummaryCard.style.display = 'none';
        
        if (!route) {
            timeSelect.disable();
            vehicleSelect.disable();
            timeSelect.setChoices([{value: '', label: 'Chọn tuyến trước...'}], 'value', 'label', true);
            vehicleSelect.setChoices([{value: '', label: 'Chọn giờ trước...'}], 'value', 'label', true);
            return;
        }
        
        timeSelect.enable();
        vehicleSelect.disable();
        timeSelect.setChoices([{value: '', label: 'Đang tải...'}], 'value', 'label', true);
        vehicleSelect.setChoices([{value: '', label: 'Chọn giờ trước...'}], 'value', 'label', true);
        
        fetch(`{{ route('nhan-vien-ban-ve.api.gio-khoi-hanh') }}?route=${route}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    timeSelect.setChoices([{value: '', label: 'Không có chuyến'}], 'value', 'label', true);
                } else {
                    timeSelect.setChoices([{value: '', label: 'Chọn giờ khởi hành...'}], 'value', 'label', true);
                    timeSelect.setChoices(data, 'value', 'label', false);
                }
            })
            .catch(error => {
                showToast('Lỗi khi tải giờ khởi hành', 'error');
                timeSelect.setChoices([{value: '', label: 'Lỗi tải dữ liệu'}], 'value', 'label', true);
            });
    });

    // 3. Handle time selection to fetch vehicles
    document.getElementById('filter-time').addEventListener('change', function(event) {
        const machuyendi = event.detail.value;
        const selectedTime = timeSelect.getValue();
        currentMachuyendi = machuyendi || null;
        currentTimeLabel = selectedTime && selectedTime.label ? selectedTime.label : '';
        vehicleSelect.clearStore();
        findSeatsBtn.disabled = true;
        tripInfoCard.style.display = 'none';
        seatMapCard.style.display = 'none';
        ticketSummaryCard.style.display = 'none';
        
        if (!machuyendi) {
            vehicleSelect.disable();
            vehicleSelect.setChoices([{value: '', label: 'Chọn giờ trước...'}], 'value', 'label', true);
            return;
        }
        
        vehicleSelect.enable();
        vehicleSelect.setChoices([{value: '', label: 'Đang tải...'}], 'value', 'label', true);
        
        fetch(`{{ route('nhan-vien-ban-ve.api.vehicles') }}?machuyendi=${encodeURIComponent(machuyendi)}`)
            .then(response => response.json())
            .then(data => {
                vehiclesData = data;
                if (data.length === 0) {
                    vehicleSelect.setChoices([{value: '', label: 'Không có xe'}], 'value', 'label', true);
                } else {
                    vehicleSelect.setChoices([{value: '', label: 'Chọn xe...'}], 'value', 'label', true);
                    vehicleSelect.setChoices(data, 'value', 'label', false);
                }
            })
            .catch(error => {
                console.error('Error loading vehicles:', error);
                showToast('Lỗi khi tải danh sách xe', 'error');
                vehicleSelect.setChoices([{value: '', label: 'Lỗi tải dữ liệu'}], 'value', 'label', true);
            });
    });

    // 4. Handle vehicle selection
    document.getElementById('filter-vehicle').addEventListener('change', function(event) {
        currentMachuyendi = event.detail.value;
        
        // Find selected vehicle data
        const selectedVehicle = vehiclesData.find(v => v.value === currentMachuyendi);
        if (selectedVehicle) {
            currentVehicleInfo = selectedVehicle.biensoxe + ' - ' + selectedVehicle.loaixe;
            // Store vehicle data for trip info
            tripData.gia_ve = selectedVehicle.gia || 0;
            tripData.tuyen = selectedVehicle.tuyen || '';
            tripData.vehicle_label = currentVehicleInfo;
        }
        
        findSeatsBtn.disabled = !currentMachuyendi;
        tripInfoCard.style.display = 'none';
        seatMapCard.style.display = 'none';
        ticketSummaryCard.style.display = 'none';
    });

    // 5. Handle "Find Seats" button click
    findSeatsBtn.addEventListener('click', function() {
        if (!currentMachuyendi) {
            showToast('Vui lòng chọn giờ khởi hành!', 'error');
            return;
        }

        findSeatsBtn.disabled = true;
        findSeatsBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang tải...`;

        fetch(`{{ route('nhan-vien-ban-ve.api.so-do-ghe') }}?machuyendi=${encodeURIComponent(currentMachuyendi)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Không tìm thấy chuyến đi.');
                }
                return response.json();
            })
            .then(data => {
                tripData = data;
                updateTripInfo(data);
                renderSeatMap(data);
                tripInfoCard.style.display = 'block';
                seatMapCard.style.display = 'block';
                // Scroll to seat map
                seatMapCard.scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                showToast(error.message || 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
            })
            .finally(() => {
                findSeatsBtn.disabled = false;
                findSeatsBtn.innerHTML = `<i class="fas fa-search me-2"></i>Tìm ghế trống`;
            });
    });

    // 6. Update trip info display
    function updateTripInfo(data) {
        document.getElementById('trip-route').innerText = currentRoute;
        document.getElementById('trip-time').innerText = currentTimeLabel;
        document.getElementById('trip-vehicle').innerText = currentVehicleInfo;
        document.getElementById('trip-price').innerText = number_format(data.gia_ve) + 'đ';
        document.getElementById('unit-price').innerText = number_format(data.gia_ve) + 'đ';
    }

    // 7. Render the seat map
    function renderSeatMap(data) {
        seatMapContainer.innerHTML = '';
        selectedSeats = [];
        updateTicketSummary();
        let seats = Array.isArray(data.seats) ? data.seats : [];

        // Dynamic inference giống TheoDoiChuyenDi nếu API không trả danh sách seats chuẩn
        if (!seats.length && data.loaixe && data.loaixe.tong_so_ghe) {
            const totalSeats = parseInt(data.loaixe.tong_so_ghe, 10) || 0;
            const bookedRaw = (Array.isArray(data.booked_seats) ? data.booked_seats : []).map(s => s.toString().toUpperCase());

            // Phân tích pattern
            const meta = bookedRaw.map(code => {
                const match = code.match(/^([A-Z]+)(\d+)$/);
                if (match) return { prefix: match[1], number: parseInt(match[2], 10) };
                const numOnly = code.match(/^(\d+)$/);
                if (numOnly) return { prefix: '', number: parseInt(numOnly[1], 10) };
                return { prefix: null, number: null, raw: code };
            });
            const prefixes = [...new Set(meta.filter(m => m.prefix !== null).map(m => m.prefix))];

            let generatedCodes = [];
            if (prefixes.length === 0) {
                for (let i=1;i<=totalSeats;i++) {
                    generatedCodes.push(i.toString().padStart(2,'0'));
                }
            } else {
                const maxByPrefix = {};
                prefixes.forEach(p => {
                    const nums = meta.filter(m => m.prefix === p && m.number !== null).map(m => m.number);
                    maxByPrefix[p] = nums.length ? Math.max(...nums) : 0;
                });
                const inferredTotal = Object.values(maxByPrefix).reduce((a,b)=>a+b,0);
                let remaining = totalSeats - inferredTotal;
                if (remaining > 0 && prefixes.length) {
                    maxByPrefix[prefixes[0]] += remaining;
                }
                prefixes.forEach(p => {
                    for (let i=1;i<=maxByPrefix[p];i++) {
                        generatedCodes.push(p + i.toString().padStart(2,'0'));
                    }
                });
            }

            seats = generatedCodes.map(code => ({
                code,
                booked: bookedRaw.includes(code),
            }));
        }

        const columns = 5; // tạm chia 5 cột cho mọi loại xe
        seatMapContainer.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;

        seats.forEach(item => {
            const seatCode = String(item.code).toUpperCase();
            const isBooked = !!item.booked;

            const seat = document.createElement('div');
            seat.classList.add('seat');
            seat.dataset.seatCode = seatCode;
            seat.innerText = seatCode;

            seat.classList.add(isBooked ? 'sold' : 'available');

            seatMapContainer.appendChild(seat);
        });
        
        tippy('.seat.available, .seat.sold', {
            content(reference) {
                const seatCode = reference.dataset.seatCode;
                const status = reference.classList.contains('sold') ? 'Đã bán' : 'Còn trống';
                return `<div class="text-center small">Ghế <b>${seatCode}</b><br>${status}</div>`;
            },
            allowHTML: true,
            theme: 'light-border'
        });
    }

    // 8. Handle seat selection
    seatMapContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('seat') && e.target.classList.contains('available')) {
            const seatCode = e.target.dataset.seatCode.toUpperCase();
            e.target.classList.toggle('selected');
            
            if (e.target.classList.contains('selected')) {
                if (!selectedSeats.includes(seatCode)) {
                    selectedSeats.push(seatCode);
                }
            } else {
                selectedSeats = selectedSeats.filter(s => s !== seatCode);
            }
            updateTicketSummary();
        }
    });

    // 9. Update ticket summary panel
    function updateTicketSummary() {
        if (selectedSeats.length > 0) {
            ticketSummaryCard.style.display = 'block';
            selectedSeats.sort();
            document.getElementById('selected-count').innerText = selectedSeats.length;
            selectedSeatsInfo.innerText = selectedSeats.join(', ');
            const unitPrice = tripData && typeof tripData.gia_ve !== 'undefined' ? Number(tripData.gia_ve) : 0;
            totalPriceInfo.innerText = number_format(selectedSeats.length * unitPrice) + 'đ';
        } else {
            ticketSummaryCard.style.display = 'none';
        }
    }

    // 10. Show customer info modal
    showCustomerModalBtn.addEventListener('click', function() {
        Swal.fire({
            title: 'Thông tin khách hàng',
            html: `
                <form id="form-customer-info" method="POST" action="{{ route('nhan-vien-ban-ve.dat-ve.store') }}" class="text-start">
                    @csrf
                    <input type="hidden" name="machuyendi" value="${currentMachuyendi}">
                    <input type="hidden" name="seats" value="${selectedSeats.join(',')}">
                    <input type="hidden" name="gia_ve" value="${tripData.gia_ve}">
                    
                    <div class="mb-3">
                        <label for="kh_hoten" class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" name="kh_hoten" id="kh_hoten" class="form-control" required placeholder="Nhập họ tên khách hàng">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kh_sdt" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="kh_sdt" id="kh_sdt" class="form-control" required pattern="[0-9]{10,11}" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label for="kh_email" class="form-label fw-bold">Email</label>
                            <input type="email" name="kh_email" id="kh_email" class="form-control" placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thanh toán</label>
                        <div class="d-flex gap-2">
                            <div class="form-check border rounded p-2 px-4 flex-fill cursor-pointer">
                                <input class="form-check-input" type="radio" name="phuongthuc_thanhtoan" id="pt_tienmat" value="tien-mat" checked>
                                <label class="form-check-label w-100 cursor-pointer" for="pt_tienmat">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i> Tiền mặt
                                </label>
                            </div>
                            <div class="form-check border rounded p-2 px-4 flex-fill cursor-pointer">
                                <input class="form-check-input" type="radio" name="phuongthuc_thanhtoan" id="pt_chuyenkhoan" value="chuyen-khoan">
                                <label class="form-check-label w-100 cursor-pointer" for="pt_chuyenkhoan">
                                    <i class="fas fa-university text-primary me-1"></i> Chuyển khoản
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label fw-bold">Ghi chú</label>
                        <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="bg-light p-3 rounded border">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-secondary">Tổng thanh toán:</span>
                            <span class="fw-bold text-success fs-5">${number_format(selectedSeats.length * Number(tripData.gia_ve || 0))}đ</span>
                        </div>
                    </div>
                </form>
            `,
            width: '550px',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận đặt vé',
            cancelButtonText: 'Hủy bỏ',
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            focusConfirm: false,
            preConfirm: () => {
                const form = document.getElementById('form-customer-info');
                if (form.checkValidity()) {
                    return true;
                } else {
                    form.reportValidity();
                    return false;
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-customer-info').submit();
            }
        });
    });

    function number_format(number) {
        return new Intl.NumberFormat('vi-VN').format(number);
    }

    window.currentTripIdForRealtime = currentMachuyendi;
});
</script>
@endpush
