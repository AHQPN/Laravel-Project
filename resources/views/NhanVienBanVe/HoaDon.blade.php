@extends('layouts.NhanVienLayout')

@section('title', 'Hóa đơn của tôi')
@section('page-title', 'Hóa đơn của tôi')

@section('content')
<div class="container-fluid">
    
    {{-- ====== FILTER CARD ====== --}}
    <div class="card shadow-sm border-0 mb-4 animate-card">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc hóa đơn
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nhan-vien-ban-ve.hoa-don.index') }}" method="GET">
                <div class="row g-3 mb-3">
                    <!-- Ngày lập -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="far fa-calendar-alt me-1"></i> Ngày lập
                        </label>
                        <input type="date" 
                               name="ngay_lap" 
                               value="{{ request('ngay_lap') }}" 
                               class="form-control shadow-sm">
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold text-secondary mb-2">
                            <i class="fas fa-info-circle me-1"></i> Trạng thái
                        </label>
                        <select name="trang_thai" class="form-select shadow-sm">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đã duyệt" @selected(request('trang_thai')=='Đã duyệt')>Đã duyệt</option>
                            <option value="Chờ xử lý" @selected(request('trang_thai')=='Chờ xử lý')>Chờ xử lý</option>
                            <option value="Đã hủy" @selected(request('trang_thai')=='Đã hủy')>Đã hủy</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-4 col-md-12 d-flex align-items-end justify-content-end gap-2">
                        <a href="{{ route('nhan-vien-ban-ve.hoa-don.index') }}" 
                           class="btn btn-outline-secondary px-4">
                            <i class="fas fa-redo-alt me-2"></i> Đặt lại
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-search me-2"></i> Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TABLE CARD ====== --}}
    <div class="card shadow-sm border-0 animate-card">
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-file-invoice me-2 text-primary"></i>
                    Danh sách hóa đơn
                </h5>
                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                    <i class="fas fa-list me-1"></i> Tổng: {{ $hoadons->total() }} hóa đơn
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="invoice-table">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold text-secondary">Mã hóa đơn</th>
                            <th class="fw-semibold text-secondary">Khách hàng</th>
                            <th class="fw-semibold text-secondary text-center">Số vé</th>
                            <th class="fw-semibold text-secondary text-end">Tổng tiền</th>
                            <th class="fw-semibold text-secondary text-center">Trạng thái</th>
                            <th class="fw-semibold text-secondary">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hoadons as $hd)
                            <tr class="invoice-row">
                                <!-- Mã hóa đơn -->
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 font-monospace fw-bold">
                                        {{ $hd->mahd }}
                                    </span>
                                </td>

                                <!-- Khách hàng -->
                                <td>
                                    @if($hd->khach)
                                        <div class="customer-info">
                                            <div class="fw-semibold text-dark">
                                                {{ $hd->khach->hoten ?? $hd->khach->tenkhach ?? 'Khách lẻ' }}
                                            </div>
                                            @if($hd->khach->sdt)
                                                <small class="text-muted">
                                                    <i class="fas fa-phone-alt me-1"></i>
                                                    {{ $hd->khach->sdt }}
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="fas fa-user-slash me-1"></i>
                                            Chưa có thông tin
                                        </span>
                                    @endif
                                </td>

                                <!-- Số vé -->
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info px-3 py-2 fs-6 fw-bold">
                                        {{ $hd->soluong }}
                                    </span>
                                </td>

                                <!-- Tổng tiền -->
                                <td class="text-end">
                                    <span class="fw-bold text-success fs-6">
                                        {{ number_format($hd->thanhtien, 0, ',', '.') }}đ
                                    </span>
                                </td>

                                <!-- Trạng thái -->
                                <td class="text-center">
                                    @php $status = $hd->trangthai; @endphp
                                    @if($status === 'Đã duyệt' || $status === 'Đã thanh toán')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>{{ $status }}
                                        </span>
                                    @elseif($status === 'Đã hủy')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-times-circle me-1"></i>Đã hủy
                                        </span>
                                    @else
                                        <span class="badge bg-warning px-3 py-2">
                                            <i class="fas fa-clock me-1"></i>{{ $status ?? 'Chờ xử lý' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Thời gian -->
                                <td>
                                    <div class="time-info">
                                        <i class="far fa-calendar me-1 text-muted"></i>
                                        <span class="text-dark">
                                            {{ optional($hd->thoigian)->format('d/m/Y') }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ optional($hd->thoigian)->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <p class="mb-0">Chưa có hóa đơn nào</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== PAGINATION ====== --}}
        @if($hoadons->hasPages())
        <div class="card-footer bg-white border-top">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 py-2">
                <!-- Thông tin hiển thị -->
                <div class="text-muted small">
                    Hiển thị
                    <span class="fw-semibold text-primary">{{ $hoadons->firstItem() ?? 0 }}</span>
                    –
                    <span class="fw-semibold text-primary">{{ $hoadons->lastItem() ?? 0 }}</span>
                    trong tổng số
                    <span class="fw-semibold text-primary">{{ $hoadons->total() }}</span> hóa đơn
                </div>

                <!-- Pagination controls -->
                <nav aria-label="Pagination Navigation">
                    <!-- Mobile pagination -->
                    <div class="d-flex d-sm-none gap-2">
                        @if ($hoadons->onFirstPage())
                            <span class="btn btn-sm btn-outline-secondary disabled">
                                <i class="fas fa-chevron-left me-1"></i> Trước
                            </span>
                        @else
                            <a href="{{ $hoadons->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-chevron-left me-1"></i> Trước
                            </a>
                        @endif

                        @if ($hoadons->hasMorePages())
                            <a href="{{ $hoadons->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                                Sau <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        @else
                            <span class="btn btn-sm btn-outline-secondary disabled">
                                Sau <i class="fas fa-chevron-right ms-1"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Desktop pagination -->
                    <div class="d-none d-sm-flex align-items-center gap-3">
                        <div class="text-sm text-muted me-2">
                            Trang
                            <span class="fw-semibold text-primary">{{ $hoadons->currentPage() }}</span>
                            /
                            <span class="fw-semibold text-primary">{{ $hoadons->lastPage() }}</span>
                        </div>

                        <ul class="pagination pagination-sm mb-0">
                            <!-- Previous button -->
                            @if ($hoadons->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            <!-- Page Numbers -->
                            @foreach ($hoadons->getUrlRange(1, $hoadons->lastPage()) as $page => $url)
                                @if ($page == $hoadons->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            <!-- Next button -->
                            @if ($hoadons->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->nextPageUrl() }}" rel="next">
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
#invoice-table {
    font-size: 0.9rem;
}

#invoice-table thead th {
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
}

#invoice-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

/* ====== Row Hover Effect ====== */
.invoice-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.invoice-row:hover {
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
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

/* ====== Button Styling ====== */
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
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    margin: 0 2px;
    border: 1px solid #dee2e6;
    color: #495057;
    transition: all 0.2s ease;
    font-weight: 500;
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

/* ====== Customer & Time Info ====== */
.customer-info,
.time-info {
    line-height: 1.5;
}

/* ====== Responsive ====== */
@media (max-width: 992px) {
    #invoice-table {
        font-size: 0.85rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.6rem !important;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem !important;
    }
    
    #invoice-table thead th {
        font-size: 0.7rem;
        padding: 0.75rem 0.5rem;
    }
    
    #invoice-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .card-footer {
        padding: 0.75rem 1rem;
    }
    
    .d-sm-none .btn {
        min-width: 100px;
    }
}

/* ====== Empty State ====== */
.fa-inbox {
    opacity: 0.3;
}

/* ====== Text Size ====== */
.text-sm {
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table sorting functionality
    const table = document.getElementById('invoice-table');
    const headers = table.querySelectorAll('thead th');
    let currentSort = { index: null, dir: 'asc' };

    headers.forEach((th, index) => {
        // Make headers sortable (except the last column if needed)
        th.style.cursor = 'pointer';
        th.title = 'Click để sắp xếp';
        
        th.addEventListener('click', () => {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:last-child)')); // Exclude empty state row if exists
            
            if (rows.length === 0) return;

            if (currentSort.index === index) {
                currentSort.dir = currentSort.dir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = { index, dir: 'asc' };
            }

            // Remove all sort indicators
            headers.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });

            // Add sort indicator
            th.classList.add(currentSort.dir === 'asc' ? 'sort-asc' : 'sort-desc');

            rows.sort((a, b) => {
                const aText = a.children[index].innerText.trim();
                const bText = b.children[index].innerText.trim();
                
                // Try numeric comparison for money and quantity
                if (!isNaN(aText.replace(/[^0-9]/g, '')) && !isNaN(bText.replace(/[^0-9]/g, ''))) {
                    const aNum = parseInt(aText.replace(/[^0-9]/g, ''), 10);
                    const bNum = parseInt(bText.replace(/[^0-9]/g, ''), 10);
                    return currentSort.dir === 'asc' ? aNum - bNum : bNum - aNum;
                }
                
                // String comparison
                return currentSort.dir === 'asc'
                    ? aText.localeCompare(bText, 'vi')
                    : bText.localeCompare(aText, 'vi');
            });

            rows.forEach(r => tbody.appendChild(r));
        });
    });

    // Add sort indicator styles
    const style = document.createElement('style');
    style.textContent = `
        thead th.sort-asc::after {
            content: " ↑";
            color: #667eea;
            font-weight: bold;
        }
        thead th.sort-desc::after {
            content: " ↓";
            color: #667eea;
            font-weight: bold;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush