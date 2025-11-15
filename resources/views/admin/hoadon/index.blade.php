@extends('layouts.admin.app')

@section('title', 'Quản lý Đơn đặt vé')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-ticket-alt text-primary me-2"></i>Quản lý Đơn đặt vé
            </h2>
            <p class="text-muted mb-0 small">Duyệt và quản lý đơn đặt vé</p>
        </div>
        <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
            <i class="fas fa-sync-alt me-1"></i> Làm mới
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card bg-warning-subtle border-warning">
                <div class="stat-icon text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $hoadons->where('trangthai', 'Chờ duyệt')->count() }}</div>
                    <div class="stat-label">Chờ duyệt</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-success-subtle border-success">
                <div class="stat-icon text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $hoadons->where('trangthai', 'Đã duyệt')->count() }}</div>
                    <div class="stat-label">Đã duyệt</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-danger-subtle border-danger">
                <div class="stat-icon text-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $hoadons->where('trangthai', 'Đã hủy')->count() }}</div>
                    <div class="stat-label">Đã hủy</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-info-subtle border-info">
                <div class="stat-icon text-info">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $hoadons->total() }}</div>
                    <div class="stat-label">Tổng đơn</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-3">
            <form action="{{ route('quan-ly.hoadon.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="🔍 Tìm mã HĐ hoặc tên khách hàng..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Chờ duyệt" {{ request('status') == 'Chờ duyệt' ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                            <option value="Đã duyệt" {{ request('status') == 'Đã duyệt' ? 'selected' : '' }}>✅ Đã duyệt</option>
                            <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>❌ Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" 
                               name="date_from" 
                               class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Tìm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 py-3" style="width: 120px;">Mã HĐ</th>
                            <th class="px-3 py-3" style="width: 200px;">Khách hàng</th>
                            <th class="px-3 py-3 d-none d-md-table-cell" style="width: 130px;">Ngày đặt</th>
                            <th class="px-3 py-3 text-end" style="width: 140px;">Tổng tiền</th>
                            <th class="px-3 py-3 text-center" style="width: 130px;">Trạng thái</th>
                            <th class="px-3 py-3 text-center" style="width: 180px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hoadons as $item)
                        <tr class="order-row">
                            <td class="px-3 py-3">
                                <a href="#" 
                                   class="text-primary fw-semibold text-decoration-none" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#detailModal{{ $item->mahd }}">
                                    #{{ $item->mahd }}
                                </a>
                            </td>
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        {{ strtoupper(substr($item->khach->ten ?? 'N', 0, 1)) }}
                                    </div>
                                    <div class="text-truncate" style="max-width: 150px;">
                                        <div class="fw-medium small">{{ $item->khach->ten ?? 'N/A' }}</div>
                                        <div class="text-muted small d-none d-lg-block">{{ $item->nhanvien->ten ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 d-none d-md-table-cell">
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->thoigian)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($item->thoigian)->format('H:i') }}
                                </small>
                            </td>
                            <td class="px-3 py-3 text-end">
                                <span class="fw-bold text-success">{{ number_format($item->thanhtien, 0, ',', '.') }}₫</span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($item->trangthai == 'Chờ duyệt')
                                    <span class="badge-status badge-warning">Chờ duyệt</span>
                                @elseif($item->trangthai == 'Đã duyệt')
                                    <span class="badge-status badge-success">Đã duyệt</span>
                                @else
                                    <span class="badge-status badge-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($item->trangthai == 'Chờ duyệt')
                                    <div class="btn-group-sm d-flex gap-1 justify-content-center" role="group">
                                        <form action="{{ route('quan-ly.hoadon.duyet', $item->mahd) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-success btn-sm px-2 py-1" 
                                                    onclick="return confirm('Xác nhận duyệt đơn #{{ $item->mahd }}?')"
                                                    title="Duyệt">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('quan-ly.hoadon.huy', $item->mahd) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm px-2 py-1" 
                                                    onclick="return confirm('Xác nhận hủy đơn #{{ $item->mahd }}?')"
                                                    title="Hủy">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <button type="button" 
                                                class="btn btn-info btn-sm px-2 py-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $item->mahd }}"
                                                title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                @else
                                    <button type="button" 
                                            class="btn btn-outline-secondary btn-sm px-2 py-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal{{ $item->mahd }}">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal{{ $item->mahd }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-file-invoice me-2"></i>Chi tiết đơn #{{ $item->mahd }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="detail-row">
                                            <span class="detail-label">Mã hóa đơn:</span>
                                            <span class="detail-value fw-bold">#{{ $item->mahd }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Khách hàng:</span>
                                            <span class="detail-value">{{ $item->khach->ten ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Nhân viên xử lý:</span>
                                            <span class="detail-value">{{ $item->nhanvien->ten ?? 'N/A' }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Ngày đặt:</span>
                                            <span class="detail-value">{{ \Carbon\Carbon::parse($item->thoigian)->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Tổng tiền:</span>
                                            <span class="detail-value fw-bold text-success fs-5">{{ number_format($item->thanhtien, 0, ',', '.') }} ₫</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Trạng thái:</span>
                                            <span class="detail-value">
                                                @if($item->trangthai == 'Chờ duyệt')
                                                    <span class="badge-status badge-warning">Chờ duyệt</span>
                                                @elseif($item->trangthai == 'Đã duyệt')
                                                    <span class="badge-status badge-success">Đã duyệt</span>
                                                @else
                                                    <span class="badge-status badge-danger">Đã hủy</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if($item->trangthai == 'Chờ duyệt')
                                            <form action="{{ route('quan-ly.hoadon.duyet', $item->mahd) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-success" 
                                                        onclick="return confirm('Xác nhận duyệt đơn #{{ $item->mahd }}?')">
                                                    <i class="fas fa-check me-1"></i> Duyệt đơn
                                                </button>
                                            </form>
                                            <form action="{{ route('quan-ly.hoadon.huy', $item->mahd) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-danger" 
                                                        onclick="return confirm('Xác nhận hủy đơn #{{ $item->mahd }}?')">
                                                    <i class="fas fa-times me-1"></i> Hủy đơn
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">Không có đơn đặt vé nào</p>
                                    <small class="text-muted">Thử thay đổi bộ lọc</small>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        @if($hoadons->total() > 0)
        <div class="card-footer bg-white border-top py-3">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <div class="text-muted small">
                        Hiển thị <strong>{{ $hoadons->firstItem() }}</strong> - <strong>{{ $hoadons->lastItem() }}</strong> 
                        trong tổng số <strong>{{ $hoadons->total() }}</strong> kết quả
                    </div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                            {{-- Previous Button --}}
                            @if ($hoadons->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->appends(request()->query())->previousPageUrl() }}">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $start = max($hoadons->currentPage() - 2, 1);
                                $end = min($start + 4, $hoadons->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            {{-- First Page --}}
                            @if($start > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->appends(request()->query())->url(1) }}">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            {{-- Page Range --}}
                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $hoadons->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $i }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $hoadons->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endif
                            @endfor

                            {{-- Last Page --}}
                            @if($end < $hoadons->lastPage())
                                @if($end < $hoadons->lastPage() - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->appends(request()->query())->url($hoadons->lastPage()) }}">
                                        {{ $hoadons->lastPage() }}
                                    </a>
                                </li>
                            @endif

                            {{-- Next Button --}}
                            @if ($hoadons->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $hoadons->appends(request()->query())->nextPageUrl() }}">
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
        </div>
        @endif
    </div>
</div>

<style>
    /* Stat Cards */
    .stat-card {
        padding: 1.25rem;
        border-radius: 10px;
        border: 2px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    /* Avatar */
    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    /* Badge Status */
    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 20px;
        white-space: nowrap;
    }
    .badge-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }
    .badge-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #28a745;
    }
    .badge-danger {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #dc3545;
    }

    /* Table */
    .table {
        font-size: 0.9rem;
    }
    .table thead th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    .order-row:hover {
        background-color: #f8f9fa;
    }

    /* Empty State */
    .empty-state {
        color: #adb5bd;
    }
    .empty-state i {
        opacity: 0.5;
    }

    /* Modal Detail */
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 500;
        color: #6c757d;
    }
    .detail-value {
        text-align: right;
    }

    /* Pagination */
    .pagination-sm .page-link {
        padding: 0.4rem 0.75rem;
        font-size: 0.875rem;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem;
        }
        .stat-icon {
            font-size: 1.5rem;
        }
        .stat-value {
            font-size: 1.5rem;
        }
        .table {
            font-size: 0.85rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush