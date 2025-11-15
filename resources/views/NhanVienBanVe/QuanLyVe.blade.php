@extends('layouts.NhanVienLayout')

@section('title', 'Quản lý vé')
@section('page-title', 'Quản lý vé')

@section('content')
<div class="container-fluid px-2 px-md-4">

    {{-- ====== FILTER CARD ====== --}}
    <div class="card shadow-sm border-0 mb-4 animate-card">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc tìm kiếm
            </h5>
        </div>

        <div class="card-body p-3 p-md-4">
            <form action="{{ route('nhan-vien-ban-ve.ve.index') }}" method="GET">
                <div class="row g-3 mb-3">
                    <!-- Ngày đi -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="far fa-calendar-alt me-1"></i> Ngày đi
                        </label>
                        <input type="date" 
                               name="ngay_di" 
                               value="{{ request('ngay_di') }}" 
                               class="form-control shadow-sm">
                    </div>

                    <!-- Chuyến đi -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-route me-1"></i> Chuyến đi
                        </label>
                        <select name="chuyen_di" id="chuyen_di" class="form-select shadow-sm">
                            <option value="">Tất cả chuyến</option>
                            @foreach($chuyenDis as $cd)
                                <option value="{{ $cd->machuyendi }}" @selected(request('chuyen_di') == $cd->machuyendi)>
                                    {{ $cd->tuyen_duong }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-info-circle me-1"></i> Trạng thái
                        </label>
                        <select name="trang_thai" id="trang_thai" class="form-select shadow-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đã duyệt" @selected(request('trang_thai') == 'Đã duyệt')>✓ Đã duyệt</option>
                            <option value="Chờ duyệt" @selected(request('trang_thai') == 'Chờ duyệt')>⏱ Chờ duyệt</option>
                            <option value="Đã hủy" @selected(request('trang_thai') == 'Đã hủy')>✕ Đã hủy</option>
                        </select>
                    </div>

                    <!-- Tìm kiếm -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="form-control shadow-sm" 
                               placeholder="Mã vé, tên, SĐT...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('nhan-vien-ban-ve.ve.index') }}" class="btn btn-outline-secondary px-3 px-md-4">
                        <i class="fas fa-redo-alt me-1 me-md-2"></i> <span class="d-none d-sm-inline">Đặt lại</span>
                    </a>
                    <button type="submit" class="btn btn-primary px-3 px-md-4 shadow-sm">
                        <i class="fas fa-search me-1 me-md-2"></i> <span class="d-none d-sm-inline">Tìm kiếm</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TABLE CARD ====== --}}
    <div class="card shadow-sm border-0 animate-card">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-ticket-alt me-2 text-primary"></i>
                    <span class="d-none d-sm-inline">Danh sách vé xe</span>
                    <span class="d-inline d-sm-none">Vé xe</span>
                </h5>
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fas fa-list me-1"></i> {{ $ves->total() }} vé
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tickets-table">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold text-secondary" style="min-width: 90px;">Mã vé</th>
                            <th class="fw-semibold text-secondary" style="min-width: 180px;">Chuyến đi</th>
                            <th class="fw-semibold text-secondary d-none d-lg-table-cell" style="min-width: 150px;">Khách hàng</th>
                            <th class="fw-semibold text-secondary text-center d-none d-md-table-cell" style="min-width: 90px;">Biển số</th>
                            <th class="fw-semibold text-secondary text-center" style="min-width: 70px;">Ghế</th>
                            <th class="fw-semibold text-secondary text-end" style="min-width: 100px;">Giá vé</th>
                            <th class="fw-semibold text-secondary text-center d-none d-md-table-cell" style="min-width: 90px;">Hóa đơn</th>
                            <th class="fw-semibold text-secondary text-center" style="min-width: 80px;">Chi tiết</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($ves as $ve)
                            <tr class="ticket-row">
                                <!-- Mã vé -->
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 font-monospace fw-bold small">
                                        {{ $ve->mave }}
                                    </span>
                                </td>

                                <!-- Chuyến đi -->
                                <td>
                                    @php
                                        $firstPoint = $ve->chuyendi->lotrinhs->sortBy('trinhtu')->first();
                                        $lastPoint = $ve->chuyendi->lotrinhs->sortBy('trinhtu')->last();
                                    @endphp
                                    <div class="route-info">
                                        <div class="fw-semibold text-dark mb-1 text-truncate" style="max-width: 200px;">
                                            {{ $firstPoint->tinhthanh->ten }} 
                                            <i class="fas fa-arrow-right mx-1 text-primary" style="font-size: 0.7rem;"></i> 
                                            {{ $lastPoint->tinhthanh->ten }}
                                        </div>
                                        <small class="text-muted d-block">
                                            <i class="far fa-clock me-1"></i>
                                            {{ Carbon\Carbon::parse($ve->chuyendi->thoigiandi)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </td>

                                <!-- Khách hàng -->
                                <td class="d-none d-lg-table-cell">
                                    @if($ve->hoadon?->khach)
                                        <div class="customer-info">
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;">
                                                {{ $ve->hoadon->khach->ten }}
                                            </div>
                                            <small class="text-muted d-block">
                                                {{ $ve->hoadon->khach->sdt }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small">
                                            Chưa có
                                        </span>
                                    @endif
                                </td>

                                <!-- Biển số xe -->
                                <td class="text-center d-none d-md-table-cell">
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-1 small">
                                        {{ $ve->chuyendi->xe->soxe ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Mã ghế -->
                                <td class="text-center">
                                    <span class="badge bg-info text-white px-2 py-1 fw-bold">
                                        {{ $ve->maghe }}
                                    </span>
                                </td>

                                <!-- Giá -->
                                <td class="text-end">
                                    <span class="fw-bold text-success">
                                        {{ number_format($ve->chuyendi->gia, 0, ',', '.') }}đ
                                    </span>
                                </td>

                                <!-- Hóa đơn -->
                                <td class="text-center d-none d-md-table-cell">
                                    <x-badge-trang-thai :status="$ve->hoadon ? 'ready' : 'pending'" />
                                </td>

                                <!-- Thao tác -->
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-view-ticket shadow-sm" 
                                            data-mave="{{ $ve->mave }}"
                                            title="Xem chi tiết vé">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                                        <p class="mb-0">Không tìm thấy vé nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== PAGINATION - SMART VERSION ====== --}}
        @if($ves->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-2">
                <!-- Thông tin hiển thị -->
                <div class="text-muted small">
                    <span class="d-none d-sm-inline">Hiển thị </span>
                    <span class="fw-semibold text-primary">{{ $ves->firstItem() ?? 0 }}</span>
                    –
                    <span class="fw-semibold text-primary">{{ $ves->lastItem() ?? 0 }}</span>
                    <span class="d-none d-sm-inline"> trong tổng số</span>
                    <span class="d-inline d-sm-none">/</span>
                    <span class="fw-semibold text-primary">{{ $ves->total() }}</span>
                    <span class="d-none d-sm-inline"> vé</span>
                </div>

                <!-- Pagination controls -->
                <nav aria-label="Pagination Navigation">
                    @php
                        $currentPage = $ves->currentPage();
                        $lastPage = $ves->lastPage();
                        
                        // Smart pagination logic
                        if ($lastPage <= 7) {
                            // Show all pages if total is 7 or less
                            $startPage = 1;
                            $endPage = $lastPage;
                        } else {
                            // Show smart range
                            if ($currentPage <= 3) {
                                $startPage = 1;
                                $endPage = 5;
                            } elseif ($currentPage >= $lastPage - 2) {
                                $startPage = $lastPage - 4;
                                $endPage = $lastPage;
                            } else {
                                $startPage = $currentPage - 2;
                                $endPage = $currentPage + 2;
                            }
                        }
                    @endphp

                    <!-- Mobile pagination -->
                    <div class="d-flex d-sm-none gap-2">
                        @if ($ves->onFirstPage())
                            <span class="btn btn-sm btn-outline-secondary disabled">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $ves->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <span class="btn btn-sm btn-primary disabled px-3">
                            {{ $currentPage }}/{{ $lastPage }}
                        </span>

                        @if ($ves->hasMorePages())
                            <a href="{{ $ves->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="btn btn-sm btn-outline-secondary disabled">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Desktop pagination -->
                    <div class="d-none d-sm-flex align-items-center gap-2">
                        <ul class="pagination pagination-sm mb-0">
                            <!-- Previous button -->
                            @if ($ves->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $ves->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            <!-- First page -->
                            @if ($startPage > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $ves->url(1) }}">1</a>
                                </li>
                                @if ($startPage > 2)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            <!-- Page Numbers -->
                            @for ($page = $startPage; $page <= $endPage; $page++)
                                @if ($page == $currentPage)
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $ves->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endfor

                            <!-- Last page -->
                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $ves->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                            @endif

                            <!-- Next button -->
                            @if ($ves->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $ves->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ====== MODAL CHI TIẾT VÉ ====== --}}
<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="fas fa-ticket-alt me-2"></i>Chi tiết vé</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ticketModalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-3">Đang tải thông tin vé...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ====== CRITICAL: Prevent all horizontal overflow ====== */
html, body {
    overflow-x: hidden !important;
    max-width: 100vw !important;
}

* {
    box-sizing: border-box !important;
}

.container-fluid {
    max-width: 100% !important;
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
}

@media (min-width: 768px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}

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

.card {
    max-width: 100% !important;
    overflow: hidden !important;
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
    max-width: 100%;
}

.form-control:hover,
.form-select:hover {
    border-color: #667eea;
}

/* ====== Fix Choices.js overflow ====== */
.choices, 
.choices__inner {
    max-width: 100% !important;
    width: 100% !important;
}

/* ====== Table Styling ====== */
.table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
    max-width: 100% !important;
    margin: 0 !important;
}

#tickets-table {
    font-size: 0.875rem;
    width: 100%;
    margin-bottom: 0 !important;
}

#tickets-table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}

#tickets-table tbody td {
    padding: 0.875rem 0.75rem;
    vertical-align: middle !important;
}

/* ====== Row Hover Effect ====== */
.ticket-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.ticket-row:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* ====== Badge Styling ====== */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    border-radius: 0.375rem;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

/* ====== Button Styling ====== */
.btn-view-ticket {
    transition: all 0.2s ease;
    border-radius: 0.375rem;
    padding: 0.375rem 0.625rem;
}

.btn-view-ticket:hover {
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

/* ====== Pagination Styling ====== */
.pagination-sm .page-link {
    min-width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #495057;
    transition: all 0.2s ease;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.pagination-sm .page-link:hover {
    background-color: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-1px);
}

.pagination-sm .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.pagination-sm .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}

/* ====== Route & Customer Info ====== */
.route-info,
.customer-info {
    line-height: 1.5;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ====== Modal Styling ====== */
.modal-content {
    border-radius: 0.75rem;
    overflow: hidden;
}

.modal-dialog {
    max-width: 95%;
    margin: 1rem auto;
}

@media (min-width: 992px) {
    .modal-dialog {
        max-width: 800px;
    }
}

.modal-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

/* ====== Responsive Adjustments ====== */
@media (max-width: 992px) {
    #tickets-table {
        font-size: 0.8rem;
    }
    
    #tickets-table thead th,
    #tickets-table tbody td {
        padding: 0.75rem 0.5rem;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 0.75rem !important;
    }
    
    #tickets-table {
        font-size: 0.75rem;
    }
    
    #tickets-table thead th {
        font-size: 0.7rem;
        padding: 0.625rem 0.4rem;
    }
    
    #tickets-table tbody td {
        padding: 0.625rem 0.4rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }
    
    .card-body {
        padding: 0.5rem !important;
    }
    
    .card-footer {
        padding: 0.75rem 0.5rem;
    }
    
    #tickets-table thead th,
    #tickets-table tbody td {
        padding: 0.5rem 0.3rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem !important;
    }
}

/* ====== Empty State ====== */
.opacity-25 {
    opacity: 0.25;
}

/* ====== Utility Classes ====== */
.d-flex {
    flex-wrap: wrap !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.gap-3 {
    gap: 1rem !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices.js for select dropdowns
    if (typeof Choices !== 'undefined') {
        new Choices('#chuyen_di', { 
            searchEnabled: true,
            placeholder: true,
            placeholderValue: 'Chọn chuyến đi',
            searchPlaceholderValue: 'Tìm kiếm...'
        });
        
        new Choices('#trang_thai', { 
            searchEnabled: false,
            placeholder: true,
            placeholderValue: 'Chọn trạng thái'
        });
    }

    // View ticket detail modal
    document.querySelectorAll('.btn-view-ticket').forEach(btn => {
        btn.addEventListener('click', function() {
            const mave = this.dataset.mave;
            const modalElement = document.getElementById('ticketModal');
            const modal = new bootstrap.Modal(modalElement);
            const body = document.getElementById('ticketModalContent');

            // Show loading state
            body.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="text-muted mt-3">Đang tải thông tin vé...</p>
                </div>
            `;

            modal.show();

            // Fetch ticket details
            fetch(`{{ url('nhan-vien-ban-ve/ve') }}/${mave}`)
                .then(response => {
                    if (!response.ok) throw new Error('Không tải được dữ liệu vé');
                    return response.text();
                })
                .then(html => {
                    body.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    body.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <p class="text-muted">Không thể tải thông tin vé. Vui lòng thử lại.</p>
                        </div>
                    `;
                });
        });
    });
});
</script>
@endpush