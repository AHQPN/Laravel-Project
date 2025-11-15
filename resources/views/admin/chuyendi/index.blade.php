@extends('layouts.admin.app')

@section('title', 'Quản lý Chuyến đi')

@section('content')
<div class="container-fluid px-2 px-md-4 py-3">
    
    <!-- Header Section -->
    <div class="page-header mb-4 animate-fade-in">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h2 class="mb-1 fw-bold">
                    <i class="fas fa-route me-2 text-primary"></i>Quản lý Chuyến đi
                </h2>
                <p class="text-muted mb-0 small">Quản lý và theo dõi các chuyến đi của hệ thống</p>
            </div>
            <a href="{{ route('quan-ly.chuyendi.create') }}" class="btn btn-primary btn-add shadow-sm">
                <i class="fas fa-plus me-2"></i>Thêm Chuyến đi
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-down shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-4 animate-fade-in">
        <div class="card-header bg-gradient-light py-3">
            <h5 class="mb-0 fw-semibold text-primary">
                <i class="fas fa-filter me-2"></i>Bộ lọc tìm kiếm
            </h5>
        </div>
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('quan-ly.chuyendi.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-lg-5 col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-2">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </label>
                        <input type="text" 
                               name="search" 
                               class="form-control shadow-sm" 
                               placeholder="Mã chuyến, biển số xe..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Date Filter -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-2">
                            <i class="far fa-calendar-alt me-1"></i>Ngày đi
                        </label>
                        <input type="date" 
                               name="date" 
                               class="form-control shadow-sm" 
                               value="{{ request('date') }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-lg-4 col-md-12 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm flex-grow-1">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('quan-ly.chuyendi.index') }}" class="btn btn-outline-secondary shadow-sm">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm border-0 animate-fade-in">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-list me-2 text-info"></i>Danh sách chuyến đi
                </h5>
                @if($chuyendis->total() > 0)
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                        <i class="fas fa-database me-1"></i>
                        Tổng: {{ $chuyendis->total() }} chuyến
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 sortable-table" 
                       id="chuyendi-table"
                       data-sort-column="{{ $sortParams['sort'] ?? 'thoigiandi' }}"
                       data-sort-direction="{{ $sortParams['direction'] ?? 'desc' }}">
                    <thead class="table-light">
                        <tr>
                            <th class="sortable" data-sort="machuyendi" style="min-width: 120px;">
                                Mã chuyến <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="sortable" data-sort="maxe" style="min-width: 150px;">
                                Xe <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="sortable" data-sort="thoigiandi" style="min-width: 140px;">
                                Thời gian <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="sortable text-end" data-sort="gia" style="min-width: 120px;">
                                Giá vé <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="sortable text-center" data-sort="SLgheconlai" style="min-width: 110px;">
                                Ghế trống <i class="fas fa-sort ms-1 text-muted"></i>
                            </th>
                            <th class="text-center d-none d-lg-table-cell" style="min-width: 100px;">
                                Trạng thái
                            </th>
                            <th class="text-center" style="min-width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chuyendis as $item)
                        <tr class="trip-row">
                            <!-- Mã chuyến -->
                            <td>
                                <span class="badge bg-primary-subtle text-primary font-monospace fw-bold px-2 py-1">
                                    {{ $item->machuyendi }}
                                </span>
                            </td>

                            <!-- Xe -->
                            <td>
                                <div class="vehicle-info">
                                    <div class="fw-semibold text-dark mb-1">
                                        <i class="fas fa-bus me-1 text-muted small"></i>
                                        {{ $item->xe->soxe ?? 'N/A' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $item->xe->loaixe->tenloai ?? 'Chưa xác định' }}
                                    </small>
                                </div>
                            </td>

                            <!-- Thời gian -->
                            <td>
                                <div class="time-info">
                                    <div class="fw-semibold text-dark">
                                        {{ \Carbon\Carbon::parse($item->thoigiandi)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($item->thoigiandi)->format('H:i') }}
                                    </small>
                                </div>
                            </td>

                            <!-- Giá vé -->
                            <td class="text-end">
                                <span class="fw-bold text-success">
                                    {{ number_format($item->gia, 0, ',', '.') }}đ
                                </span>
                            </td>

                            <!-- Ghế trống -->
                            <td class="text-center">
                                @php
                                    $totalSeats = $item->xe->loaixe->soghe ?? 0;
                                    $availableSeats = $item->SLgheconlai;
                                    $percentage = $totalSeats > 0 ? ($availableSeats / $totalSeats) * 100 : 0;
                                @endphp
                                <div class="seat-info">
                                    <span class="fw-bold {{ $percentage <= 20 ? 'text-danger' : ($percentage <= 50 ? 'text-warning' : 'text-success') }}">
                                        {{ $availableSeats }}
                                    </span>
                                    <span class="text-muted">/{{ $totalSeats }}</span>
                                </div>
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar {{ $percentage <= 20 ? 'bg-danger' : ($percentage <= 50 ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ $percentage }}%">
                                    </div>
                                </div>
                            </td>

                            <!-- Trạng thái -->
                            <td class="text-center d-none d-lg-table-cell">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $departureTime = \Carbon\Carbon::parse($item->thoigiandi);
                                @endphp
                                @if($departureTime->isFuture())
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Hoạt động
                                    </span>
                                @elseif($departureTime->isToday())
                                    <span class="badge bg-warning-subtle text-warning">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Hôm nay
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Đã qua
                                    </span>
                                @endif
                            </td>

                            <!-- Thao tác -->
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('quan-ly.chuyendi.edit', $item->machuyendi) }}" 
                                       class="btn btn-warning shadow-sm"
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-xl-inline ms-1">Sửa</span>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger shadow-sm btn-delete"
                                            data-id="{{ $item->machuyendi }}"
                                            title="Xóa">
                                        <i class="fas fa-trash"></i>
                                        <span class="d-none d-xl-inline ms-1">Xóa</span>
                                    </button>
                                </div>

                                <!-- Hidden delete form -->
                                <form id="delete-form-{{ $item->machuyendi }}" 
                                      action="{{ route('quan-ly.chuyendi.destroy', $item->machuyendi) }}" 
                                      method="POST" 
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                                    <p class="text-muted mb-0">Không tìm thấy chuyến đi nào</p>
                                    <a href="{{ route('quan-ly.chuyendi.create') }}" class="btn btn-sm btn-primary mt-3">
                                        <i class="fas fa-plus me-1"></i>Thêm chuyến đi mới
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer -->
        @if($chuyendis->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-2">
                <!-- Pagination Info -->
                <div class="pagination-info text-muted small">
                    @if($chuyendis->total() > 0)
                        Hiển thị 
                        <span class="fw-semibold text-primary">{{ $chuyendis->firstItem() }}</span>
                        -
                        <span class="fw-semibold text-primary">{{ $chuyendis->lastItem() }}</span>
                        trong tổng số
                        <span class="fw-semibold text-primary">{{ $chuyendis->total() }}</span>
                        chuyến đi
                    @endif
                </div>

                <!-- Pagination Links -->
                <nav aria-label="Pagination">
                    @php
                        $currentPage = $chuyendis->currentPage();
                        $lastPage = $chuyendis->lastPage();
                        
                        if ($lastPage <= 7) {
                            $startPage = 1;
                            $endPage = $lastPage;
                        } else {
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

                    <ul class="pagination pagination-sm mb-0">
                        <!-- Previous Button -->
                        @if ($chuyendis->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $chuyendis->appends(request()->query())->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @endif

                        <!-- First Page -->
                        @if ($startPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $chuyendis->appends(request()->query())->url(1) }}">1</a>
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
                                    <a class="page-link" href="{{ $chuyendis->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endfor

                        <!-- Last Page -->
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $chuyendis->appends(request()->query())->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        <!-- Next Button -->
                        @if ($chuyendis->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $chuyendis->appends(request()->query())->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Bạn có chắc chắn muốn xóa chuyến đi này không?</p>
                <p class="text-muted small mb-0 mt-2">Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-1"></i>Xóa
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ====== Prevent Overflow ====== */
html, body {
    overflow-x: hidden !important;
}

.container-fluid {
    max-width: 100% !important;
}

/* ====== Animations ====== */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease;
}

.animate-slide-down {
    animation: slideDown 0.5s ease;
}

/* ====== Page Header ====== */
.page-header h2 {
    color: #2c3e50;
    font-size: 1.75rem;
}

.page-header .text-muted {
    font-size: 0.9rem;
}

/* ====== Card Styling ====== */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.card-header {
    background: #fff;
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* ====== Button Styling ====== */
.btn-add {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-add:hover {
    background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
}

/* ====== Form Controls ====== */
.form-control,
.form-select {
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* ====== Table Styling ====== */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

#chuyendi-table {
    font-size: 0.9rem;
}

#chuyendi-table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    font-weight: 600;
    color: #6c757d;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
    background: #f8f9fa;
}

#chuyendi-table thead th.sortable {
    cursor: pointer;
    user-select: none;
}

#chuyendi-table thead th.sortable:hover {
    background: #e9ecef;
    color: #667eea;
}

#chuyendi-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.trip-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.trip-row:hover {
    background: #f8f9fa;
    transform: translateX(2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* ====== Badge Styling ====== */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
    border-radius: 0.375rem;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

/* ====== Info Boxes ====== */
.vehicle-info,
.time-info {
    line-height: 1.4;
}

.seat-info {
    font-size: 0.9rem;
}

.progress {
    border-radius: 2px;
}

/* ====== Empty State ====== */
.empty-state i {
    display: block;
}

.opacity-25 {
    opacity: 0.25;
}

/* ====== Alert Styling ====== */
.alert {
    border-radius: 0.75rem;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

/* ====== Pagination ====== */
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
}

.pagination-sm .page-link:hover {
    background: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
    transform: translateY(-1px);
}

.pagination-sm .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.pagination-sm .page-item.disabled .page-link {
    background: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

/* ====== Modal Styling ====== */
.modal-content {
    border-radius: 0.75rem;
}

/* ====== Responsive ====== */
@media (max-width: 992px) {
    #chuyendi-table {
        font-size: 0.85rem;
    }
    
    #chuyendi-table thead th,
    #chuyendi-table tbody td {
        padding: 0.75rem 0.5rem;
    }
}

@media (max-width: 768px) {
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    #chuyendi-table {
        font-size: 0.8rem;
    }
    
    #chuyendi-table thead th,
    #chuyendi-table tbody td {
        padding: 0.625rem 0.4rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/table-sort.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Delete button click handlers
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteId = this.dataset.id;
            deleteModal.show();
        });
    });
    
    // Confirm delete
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteId) {
            document.getElementById(`delete-form-${deleteId}`).submit();
        }
    });
    
    // Auto-dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush