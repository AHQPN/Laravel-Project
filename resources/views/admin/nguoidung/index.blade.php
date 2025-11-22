@extends('layouts.admin.app')

@section('title', 'Quản lý Người dùng')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="fas fa-users text-primary me-2"></i>Quản lý Người dùng
                </h2>
                <p class="text-muted mb-0 small">Quản lý khách hàng và nhân viên</p>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabs Navigation -->
        <ul class="nav nav-pills mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#khachhang" role="tab">
                    <i class="fas fa-users me-2"></i>Khách hàng
                    @if(isset($khachs))
                        <span class="badge bg-light text-dark ms-1">{{ $khachs->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#nhanvien" role="tab">
                    <i class="fas fa-user-tie me-2"></i>Nhân viên
                    @if(isset($nhanviens))
                        <span class="badge bg-light text-dark ms-1">{{ $nhanviens->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Tab Khách hàng -->
            <div class="tab-pane fade show active" id="khachhang" role="tabpanel">
                <!-- Filter Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <form action="{{ route('quan-ly.nguoidung.khach') }}" method="GET">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="🔍 Tìm mã KH, tên, SĐT hoặc email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
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
                            <table class="table table-hover align-middle mb-0" id="khach-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 py-3 sortable" data-sort="makh"
                                            style="width: 100px; cursor: pointer;">Mã KH <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable" data-sort="ten"
                                            style="width: 180px; cursor: pointer;">Họ tên <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable" data-sort="sdt"
                                            style="width: 130px; cursor: pointer;">Số điện thoại <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable d-none d-lg-table-cell" data-sort="email"
                                            style="cursor: pointer;">Email <i class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 d-none d-xl-table-cell" style="width: auto;">Địa chỉ</th>
                                        <th class="px-3 py-3 text-center" style="width: 150px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($khachs ?? [] as $item)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <span
                                                    class="badge bg-light text-dark border fw-semibold">{{ $item->makh }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-2">
                                                        {{ strtoupper(substr($item->ten, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-medium">{{ $item->ten }}</span>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <i class="fas fa-phone text-muted me-1"></i>{{ $item->sdt }}
                                            </td>
                                            <td class="px-3 py-3 d-none d-lg-table-cell">
                                                @if($item->email)
                                                    <i class="fas fa-envelope text-muted me-1"></i>{{ $item->email }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 d-none d-xl-table-cell">
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                    {{ $item->diachi ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <div class="btn-group-sm d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-info btn-sm px-2 py-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailKhachModal{{ $item->makh }}" title="Chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('quan-ly.nguoidung.khach.edit', $item->makh) }}"
                                                        class="btn btn-warning btn-sm px-2 py-1" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('quan-ly.nguoidung.khach.destroy', $item->makh) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa khách hàng {{ $item->ten }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm px-2 py-1"
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailKhachModal{{ $item->makh }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-user me-2"></i>Chi tiết Khách hàng
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center mb-3">
                                                            <div class="avatar-lg mx-auto mb-2">
                                                                {{ strtoupper(substr($item->ten, 0, 1)) }}
                                                            </div>
                                                            <h5 class="mb-0">{{ $item->ten }}</h5>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Mã khách hàng:</span>
                                                            <span class="detail-value fw-bold">{{ $item->makh }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Số điện thoại:</span>
                                                            <span class="detail-value">{{ $item->sdt }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Email:</span>
                                                            <span class="detail-value">{{ $item->email ?? '-' }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Địa chỉ:</span>
                                                            <span class="detail-value">{{ $item->diachi ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ route('quan-ly.nguoidung.khach.edit', $item->makh) }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                                                        </a>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-users fa-3x mb-3"></i>
                                                    <p class="mb-0">Không có khách hàng nào</p>
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
                    <div class="card-footer bg-white border-top py-3" id="khach-pagination"></div>
                </div>
            </div>

            <!-- Tab Nhân viên -->
            <div class="tab-pane fade" id="nhanvien" role="tabpanel">
                <!-- Filter Card -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <form action="{{ route('quan-ly.nguoidung.nhanvien') }}" method="GET">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="🔍 Tìm mã NV, tên, SĐT hoặc email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    @can('create', App\Models\Nhanvien::class)
                                        <a href="{{ route('quan-ly.nguoidung.nhanvien.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus me-1"></i> Thêm Nhân viên
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="nhanvien-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 py-3 sortable" data-sort="manv"
                                            style="width: 100px; cursor: pointer;">Mã NV <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable" data-sort="ten"
                                            style="width: 180px; cursor: pointer;">Họ tên <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable" data-sort="chucvu"
                                            style="width: 140px; cursor: pointer;">Chức vụ <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable" data-sort="sdt"
                                            style="width: 130px; cursor: pointer;">Số điện thoại <i
                                                class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 sortable d-none d-lg-table-cell" data-sort="email"
                                            style="cursor: pointer;">Email <i class="fas fa-sort ms-1 text-muted"></i></th>
                                        <th class="px-3 py-3 text-center" style="width: 150px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nhanviens ?? [] as $item)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <span
                                                    class="badge bg-light text-dark border fw-semibold">{{ $item->manv }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success me-2">
                                                        {{ strtoupper(substr($item->ten, 0, 1)) }}
                                                    </div>
                                                    <span class="fw-medium">{{ $item->ten }}</span>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <span class="badge bg-primary-subtle text-primary border border-primary">
                                                    {{ $item->chucvu->tencv ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <i class="fas fa-phone text-muted me-1"></i>{{ $item->sdt }}
                                            </td>
                                            <td class="px-3 py-3 d-none d-lg-table-cell">
                                                @if($item->email)
                                                    <i class="fas fa-envelope text-muted me-1"></i>{{ $item->email }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <div class="btn-group-sm d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-info btn-sm px-2 py-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#detailNhanVienModal{{ $item->manv }}" title="Chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @can('update', $item)
                                                        <a href="{{ route('quan-ly.nguoidung.nhanvien.edit', $item->manv) }}"
                                                            class="btn btn-warning btn-sm px-2 py-1" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete', $item)
                                                        <form
                                                            action="{{ route('quan-ly.nguoidung.nhanvien.destroy', $item->manv) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên {{ $item->ten }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm px-2 py-1"
                                                                title="Xóa">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailNhanVienModal{{ $item->manv }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-user-tie me-2"></i>Chi tiết Nhân viên
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center mb-3">
                                                            <div class="avatar-lg bg-success mx-auto mb-2">
                                                                {{ strtoupper(substr($item->ten, 0, 1)) }}
                                                            </div>
                                                            <h5 class="mb-0">{{ $item->ten }}</h5>
                                                            <span
                                                                class="badge bg-primary mt-1">{{ $item->chucvu->tencv ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Mã nhân viên:</span>
                                                            <span class="detail-value fw-bold">{{ $item->manv }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Chức vụ:</span>
                                                            <span
                                                                class="detail-value">{{ $item->chucvu->tencv ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Số điện thoại:</span>
                                                            <span class="detail-value">{{ $item->sdt }}</span>
                                                        </div>
                                                        <div class="detail-row">
                                                            <span class="detail-label">Email:</span>
                                                            <span class="detail-value">{{ $item->email ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ route('quan-ly.nguoidung.nhanvien.edit', $item->manv) }}"
                                                            class="btn btn-warning">
                                                            <i class="fas fa-edit me-1"></i> Chỉnh sửa
                                                        </a>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Đóng</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                                                    <p class="mb-0">Không có nhân viên nào</p>
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
                    <div class="card-footer bg-white border-top py-3" id="nhanvien-pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Nav Pills */
        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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

        .avatar-sm.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2rem;
        }

        .avatar-lg.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

        /* Empty State */
        .empty-state {
            color: #adb5bd;
        }

        .empty-state i {
            opacity: 0.5;
        }

        /* Modal Detail.detail-row {
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
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.2s;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-weight: 600;
        }

        .page-link:hover {
            background-color: #e9ecef;
        }

        .page-item.disabled .page-link {
            background-color: transparent;
            border-color: #dee2e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
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

@push('styles')
    <style>
        #khach-table thead th.sortable,
        #nhanvien-table thead th.sortable {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        #khach-table thead th.sortable:hover,
        #nhanvien-table thead th.sortable:hover {
            background-color: #e9ecef;
            color: #667eea;
        }

        #khach-table thead th.sort-asc,
        #khach-table thead th.sort-desc,
        #nhanvien-table thead th.sort-asc,
        #nhanvien-table thead th.sort-desc {
            background-color: #e9ecef;
            color: #667eea;
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize JS Pagination for both tables
            const khachPagination = new Pagination({
                tableId: 'khach-table',
                paginationId: 'khach-pagination',
                itemsPerPage: 10
            });

            const nhanvienPagination = new Pagination({
                tableId: 'nhanvien-table',
                paginationId: 'nhanvien-pagination',
                itemsPerPage: 10
            });

            // Sorting for Khach table
            initTableSorting('khach-table', khachPagination);

            // Sorting for Nhanvien table
            initTableSorting('nhanvien-table', nhanvienPagination);

            function initTableSorting(tableId, pagination) {
                const table = document.getElementById(tableId);
                const headers = table.querySelectorAll('th.sortable');
                let currentSort = { column: null, direction: 'asc' };

                headers.forEach(header => {
                    header.addEventListener('click', () => {
                        const sortType = header.getAttribute('data-sort');
                        const tbody = table.querySelector('tbody');
                        const rows = Array.from(tbody.querySelectorAll('tr'));

                        if (currentSort.column === sortType) {
                            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                        } else {
                            currentSort.column = sortType;
                            currentSort.direction = 'asc';
                        }

                        // Remove previous sort indicators
                        headers.forEach(h => {
                            h.classList.remove('sort-asc', 'sort-desc');
                            const icon = h.querySelector('i');
                            if (icon) {
                                icon.className = 'fas fa-sort ms-1 text-muted';
                            }
                        });

                        // Add sort indicator to current header
                        header.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');
                        const icon = header.querySelector('i');
                        if (icon) {
                            icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} ms-1 text-primary`;
                        }

                        // Sort rows
                        rows.sort((a, b) => {
                            let aValue, bValue;
                            let cellIndex = 0;

                            switch (sortType) {
                                case 'makh':
                                case 'manv':
                                    cellIndex = 0;
                                    break;
                                case 'ten':
                                    cellIndex = 1;
                                    break;
                                case 'sdt':
                                    cellIndex = tableId === 'khach-table' ? 2 : 3;
                                    break;
                                case 'chucvu':
                                    cellIndex = 2;
                                    break;
                                case 'email':
                                    cellIndex = tableId === 'khach-table' ? 3 : 4;
                                    break;
                                default:
                                    return 0;
                            }

                            aValue = a.cells[cellIndex]?.textContent.trim() || '';
                            bValue = b.cells[cellIndex]?.textContent.trim() || '';

                            return currentSort.direction === 'asc'
                                ? aValue.localeCompare(bValue, 'vi')
                                : bValue.localeCompare(aValue, 'vi');
                        });

                        // Re-render table
                        rows.forEach(row => tbody.appendChild(row));

                        // Reset pagination
                        pagination.currentPage = 1;
                        pagination.render();
                    });
                });
            }
        });

        // Auto dismiss alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush