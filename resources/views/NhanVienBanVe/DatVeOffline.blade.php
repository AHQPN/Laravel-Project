@extends('layouts.NhanVienLayout')

@section('title', 'Đặt vé')
@section('page-title', 'Đặt vé')

@section('content')
<div class="container-fluid">
    
    {{-- Step 1: Filters --}}
    <div class="card shadow-sm border-0 mb-4 animate-card">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <span class="step-number me-3">1</span>
                <i class="fas fa-search me-2"></i>
                Tìm kiếm chuyến đi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label for="filter-route" class="form-label fw-semibold text-secondary mb-2">
                        <i class="fas fa-route me-1"></i> Chọn tuyến đường
                    </label>
                    <select id="filter-route" class="form-select shadow-sm" data-trigger name="filter-route">
                        <option value="">Chọn tuyến...</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="filter-time" class="form-label fw-semibold text-secondary mb-2">
                        <i class="far fa-clock me-1"></i> Chọn giờ khởi hành
                    </label>
                    <select id="filter-time" class="form-select shadow-sm" data-trigger name="filter-time" disabled>
                        <option value="">Chọn tuyến trước...</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="filter-vehicle" class="form-label fw-semibold text-secondary mb-2">
                        <i class="fas fa-bus me-1"></i> Chọn xe
                    </label>
                    <select id="filter-vehicle" class="form-select shadow-sm" data-trigger name="filter-vehicle" disabled>
                        <option value="">Chọn giờ trước...</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button id="btn-find-seats" class="btn btn-primary btn-lg px-5 shadow-sm" disabled>
                    <i class="fas fa-search me-2"></i>Tìm ghế trống
                </button>
            </div>
        </div>
    </div>

    {{-- Step 2: Vehicle & Trip Info --}}
    <div class="card shadow-sm border-0 mb-4 animate-card" id="trip-info-card" style="display: none;">
        <div class="card-header bg-gradient-info text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <span class="step-number me-3">2</span>
                <i class="fas fa-info-circle me-2"></i>
                Thông tin chuyến đi
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="info-box">
                        <div class="info-icon bg-primary-subtle">
                            <i class="fas fa-route text-primary"></i>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Tuyến đường</p>
                            <p class="info-value" id="trip-route">-</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="info-box">
                        <div class="info-icon bg-success-subtle">
                            <i class="far fa-clock text-success"></i>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Thời gian khởi hành</p>
                            <p class="info-value" id="trip-time">-</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="info-box">
                        <div class="info-icon bg-info-subtle">
                            <i class="fas fa-bus text-info"></i>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Xe</p>
                            <p class="info-value" id="trip-vehicle">-</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="info-box">
                        <div class="info-icon bg-warning-subtle">
                            <i class="fas fa-tag text-warning"></i>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Giá vé</p>
                            <p class="info-value text-success" id="trip-price">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Step 3: Seat Map --}}
    <div class="card shadow-sm border-0 mb-4 animate-card" id="seat-map-card" style="display: none;">
        <div class="card-header bg-gradient-success text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <span class="step-number me-3">3</span>
                <i class="fas fa-chair me-2"></i>
                Chọn ghế ngồi
            </h5>
        </div>
        <div class="card-body p-4">
            {{-- Seat Map Legend --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="legend-container">
                        <div class="legend-item">
                            <span class="legend-box legend-available"></span>
                            <span class="legend-text">Còn trống</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-box legend-sold"></span>
                            <span class="legend-text">Đã bán</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-box legend-selected"></span>
                            <span class="legend-text">Đang chọn</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-box legend-unavailable"></span>
                            <span class="legend-text">Không khả dụng</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Seat Map --}}
            <div class="row">
                <div class="col-12">
                    <div class="seat-map-wrapper">
                        <div class="bus-header">
                            <i class="fas fa-steering-wheel"></i>
                            <span>Tài xế</span>
                        </div>
                        <div id="seat-map-container">
                            {{-- JS will render seats here --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Step 4: Ticket Summary & Checkout --}}
    <div class="card shadow-sm border-0 mb-4 animate-card" id="ticket-summary-card" style="display: none;">
        <div class="card-header bg-gradient-warning text-dark py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <span class="step-number-dark me-3">4</span>
                <i class="fas fa-ticket-alt me-2"></i>
                Thông tin vé
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h5 class="mb-4 fw-bold">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i>
                        Chi tiết vé đã chọn
                    </h5>
                    <div class="ticket-summary-box">
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-list-ol me-2 text-muted"></i>
                                Số ghế đã chọn:
                            </span>
                            <span class="summary-value">
                                <span id="selected-count" class="badge bg-primary px-3 py-2">0</span> ghế
                            </span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-chair me-2 text-muted"></i>
                                Danh sách ghế:
                            </span>
                            <span class="summary-value fw-bold" id="selected-seats-info">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">
                                <i class="fas fa-tag me-2 text-muted"></i>
                                Đơn giá:
                            </span>
                            <span class="summary-value fw-bold" id="unit-price">-</span>
                        </div>
                        <div class="summary-row-total">
                            <span class="summary-label-total">
                                <i class="fas fa-calculator me-2"></i>
                                Tổng tiền:
                            </span>
                            <span class="summary-value-total" id="total-price-info">0đ</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="checkout-box">
                        <div class="checkout-icon mb-3">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <button id="btn-show-customer-modal" class="btn btn-success btn-lg px-5 shadow">
                            <i class="fas fa-arrow-right me-2"></i>
                            Tiếp tục thanh toán
                        </button>
                        <p class="text-muted mt-3 mb-0 small">
                            <i class="fas fa-shield-alt me-1"></i>
                            Thanh toán an toàn và bảo mật
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ====== Gradient Backgrounds ====== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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

/* ====== Step Numbers ====== */
.step-number {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    font-weight: 700;
    font-size: 1.25rem;
}

.step-number-dark {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    font-weight: 700;
    font-size: 1.25rem;
}

/* ====== Form Controls ====== */
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-select {
    border-radius: 0.5rem;
    padding: 0.625rem 0.875rem;
    transition: all 0.2s ease;
}

.form-select:hover:not(:disabled) {
    border-color: #667eea;
}

/* ====== Info Boxes ====== */
.info-box {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.info-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.info-value {
    font-size: 1rem;
    font-weight: 700;
    color: #172B4D;
    margin-bottom: 0;
}

/* ====== Seat Map Styles ====== */
.seat-map-wrapper {
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid #e9ecef;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 700px;
    margin: 0 auto;
}

.bus-header {
    text-align: center;
    padding: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.75rem 0.75rem 0 0;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.bus-header i {
    font-size: 1.5rem;
    margin-right: 0.5rem;
}

#seat-map-container {
    display: grid;
    gap: 10px;
    padding: 1.5rem;
    background: white;
    border-radius: 0.75rem;
    box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
}

.seat {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid;
    border-radius: 0.5rem;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    position: relative;
}

.seat::after {
    content: '';
    position: absolute;
    top: -2px;
    right: -2px;
    bottom: -2px;
    left: -2px;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.seat.available {
    background: white;
    border-color: #28a745;
    color: #28a745;
}

.seat.available:hover {
    background: #28a745;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.seat.sold {
    background: #dc3545;
    border-color: #dc3545;
    color: white;
    cursor: not-allowed;
    opacity: 0.7;
}

.seat.selected {
    background: #ffc107;
    border-color: #ffc107;
    color: #000;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.5);
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0%, 100% { transform: scale(1.1); }
    50% { transform: scale(1.15); }
}

.seat.unavailable {
    background: #e9ecef;
    border-color: #adb5bd;
    color: #6c757d;
    cursor: not-allowed;
}

/* ====== Legend Styles ====== */
.legend-container {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 2rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 0.75rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-box {
    width: 28px;
    height: 28px;
    border-radius: 0.375rem;
    border: 2px solid;
    display: inline-block;
}

.legend-available {
    background-color: white;
    border-color: #28a745 !important;
}

.legend-sold {
    background-color: #dc3545;
    border-color: #dc3545 !important;
}

.legend-selected {
    background-color: #ffc107;
    border-color: #ffc107 !important;
}

.legend-unavailable {
    background-color: #e9ecef;
    border-color: #adb5bd !important;
}

.legend-text {
    font-weight: 500;
    color: #495057;
    font-size: 0.875rem;
}

/* ====== Ticket Summary Box ====== */
.ticket-summary-box {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 2px solid #e9ecef;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #dee2e6;
}

.summary-row:last-of-type {
    border-bottom: none;
}

.summary-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.summary-value {
    color: #172B4D;
    font-size: 1rem;
}

.summary-row-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 0 0;
    margin-top: 1rem;
    border-top: 2px solid #667eea;
}

.summary-label-total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #172B4D;
}

.summary-value-total {
    font-size: 1.75rem;
    font-weight: 700;
    color: #28a745;
}

/* ====== Checkout Box ====== */
.checkout-box {
    padding: 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 0.75rem;
    border: 2px dashed #dee2e6;
}

.checkout-icon {
    font-size: 3rem;
    color: #28a745;
}

/* ====== Button Styles ====== */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #155724 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
}

/* ====== Responsive ====== */
@media (max-width: 992px) {
    .info-box {
        padding: 1rem;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .seat {
        width: 45px;
        height: 45px;
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .step-number, .step-number-dark {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
    
    .seat {
        width: 40px;
        height: 40px;
    }
    
    .legend-container {
        gap: 1rem;
    }
    
    .checkout-box {
        padding: 1.5rem;
        margin-top: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const choicesConfig = {
        searchEnabled: true,
        itemSelectText: 'Chọn',
        shouldSort: false,
        allowHTML: true
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
                    // value now is machuyendi
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
        findSeatsBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang tìm...`;

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
            })
            .catch(error => {
                showToast(error.message || 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
            })
            .finally(() => {
                findSeatsBtn.disabled = false;
                findSeatsBtn.innerHTML = `<i class="fas fa-search me-2"></i>Tìm ghế`;
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
                return `Ghế ${seatCode}<br>Trạng thái: ${status}<br>Giá: ${number_format(data.gia_ve)}đ`;
            },
            allowHTML: true,
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
            title: 'Thông tin khách hàng & Thanh toán',
            html: `
                <form id="form-customer-info" method="POST" action="{{ route('nhan-vien-ban-ve.dat-ve.store') }}">
                    @csrf
                    <input type="hidden" name="machuyendi" value="${currentMachuyendi}">
                    <input type="hidden" name="seats" value="${selectedSeats.join(',')}">
                    <input type="hidden" name="gia_ve" value="${tripData.gia_ve}">
                    
                    <h5 class="text-start mb-3">Thông tin khách hàng</h5>
                    <div class="form-group text-start mb-3">
                        <label for="kh_hoten" class="form-label">Họ tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="kh_hoten" id="kh_hoten" class="form-control" required>
                    </div>
                    <div class="form-group text-start mb-3">
                        <label for="kh_sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="kh_sdt" id="kh_sdt" class="form-control" required pattern="[0-9]{10,11}">
                    </div>
                    <div class="form-group text-start mb-3">
                        <label for="kh_email" class="form-label">Email</label>
                        <input type="email" name="kh_email" id="kh_email" class="form-control">
                    </div>

                    <h5 class="text-start mb-3 mt-4">Phương thức thanh toán</h5>
                    <div class="form-group text-start mb-3">
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="phuongthuc_thanhtoan" id="pt_tienmat" value="tien-mat" checked>
                            <label class="btn btn-outline-success" for="pt_tienmat">
                                <i class="fas fa-money-bill-wave me-2"></i>Tiền mặt
                            </label>
                            
                            <input type="radio" class="btn-check" name="phuongthuc_thanhtoan" id="pt_chuyenkhoan" value="chuyen-khoan">
                            <label class="btn btn-outline-primary" for="pt_chuyenkhoan">
                                <i class="fas fa-university me-2"></i>Chuyển khoản
                            </label>
                            
                            <input type="radio" class="btn-check" name="phuongthuc_thanhtoan" id="pt_the" value="the">
                            <label class="btn btn-outline-info" for="pt_the">
                                <i class="fas fa-credit-card me-2"></i>Thẻ
                            </label>
                        </div>
                    </div>

                    <div class="form-group text-start mb-3">
                        <label for="ghi_chu" class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="alert alert-info text-start mt-3">
                        <strong>Tổng thanh toán:</strong> <span class="fs-5 text-success">${number_format(selectedSeats.length * Number(tripData.gia_ve || 0))}đ</span>
                    </div>
                </form>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check me-2"></i>Xác nhận đặt vé',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
            confirmButtonColor: '#00875A',
            cancelButtonColor: '#FF5630',
            preConfirm: () => {
                const form = document.getElementById('form-customer-info');
                if (form.checkValidity()) {
                    return true;
                } else {
                    Swal.showValidationMessage('Vui lòng điền đầy đủ thông tin bắt buộc.');
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
});
</script>
@endpush
