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
                        <span class="badge bg-light text-dark ms-1">{{ $khachs->total() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#nhanvien" role="tab">
                    <i class="fas fa-user-tie me-2"></i>Nhân viên
                    @if(isset($nhanviens))
                        <span class="badge bg-light text-dark ms-1">{{ $nhanviens->total() }}</span>
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
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-3 py-3" style="width: 100px;">Mã KH</th>
                                        <th class="px-3 py-3" style="width: 180px;">Họ tên</th>
                                        <th class="px-3 py-3" style="width: 130px;">Số điện thoại</th>
                                        <th class="px-3 py-3 d-none d-lg-table-cell">Email</th>
                                        <th class="px-3 py-3 d-none d-xl-table-cell">Địa chỉ</th>
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
                    @if(isset($khachs) && $khachs->total() > 0)
                        <div class="card-footer bg-white border-top py-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        Hiển thị <strong>{{ $khachs->firstItem() }}</strong> -
                                        <strong>{{ $khachs->lastItem() }}</strong>
                                        trong tổng số <strong>{{ $khachs->total() }}</strong> kết quả
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                                            @if ($khachs->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $khachs->appends(request()->query())->previousPageUrl() }}">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            @endif

                                            @php
                                                $start = max($khachs->currentPage() - 2, 1);
                                                $end = min($start + 4, $khachs->lastPage());
                                                $start = max($end - 4, 1);
                                            @endphp

                                            @if($start > 1)
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $khachs->appends(request()->query())->url(1) }}">1</a>
                                                </li>
                                                @if($start > 2)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                            @endif

                                            @for ($i = $start; $i <= $end; $i++)
                                                @if ($i == $khachs->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $i }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $khachs->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            @if($end < $khachs->lastPage())
                                                @if($end < $khachs->lastPage() - 1)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $khachs->appends(request()->query())->url($khachs->lastPage()) }}">
                                                        {{ $khachs->lastPage() }}
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($khachs->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $khachs->appends(request()->query())->nextPageUrl() }}">
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
                                    <a href="{{ route('quan-ly.nguoidung.nhanvien.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Thêm Nhân viên
                                    </a>
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
                                        <th class="px-3 py-3" style="width: 100px;">Mã NV</th>
                                        <th class="px-3 py-3" style="width: 180px;">Họ tên</th>
                                        <th class="px-3 py-3" style="width: 140px;">Chức vụ</th>
                                        <th class="px-3 py-3" style="width: 130px;">Số điện thoại</th>
                                        <th class="px-3 py-3 d-none d-lg-table-cell">Email</th>
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
                                                    <a href="{{ route('quan-ly.nguoidung.nhanvien.edit', $item->manv) }}"
                                                        class="btn btn-warning btn-sm px-2 py-1" title="Sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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
                    @if(isset($nhanviens) && $nhanviens->total() > 0)
                        <div class="card-footer bg-white border-top py-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <div class="text-muted small">
                                        Hiển thị <strong>{{ $nhanviens->firstItem() }}</strong> -
                                        <strong>{{ $nhanviens->lastItem() }}</strong>
                                        trong tổng số <strong>{{ $nhanviens->total() }}</strong> kết quả
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm justify-content-md-end justify-content-center mb-0">
                                            @if ($nhanviens->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $nhanviens->appends(request()->query())->previousPageUrl() }}">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                </li>
                                            @endif

                                            @php
                                                $start_nv = max($nhanviens->currentPage() - 2, 1);
                                                $end_nv = min($start_nv + 4, $nhanviens->lastPage());
                                                $start_nv = max($end_nv - 4, 1);
                                            @endphp

                                            @if($start_nv > 1)
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $nhanviens->appends(request()->query())->url(1) }}">1</a>
                                                </li>
                                                @if($start_nv > 2)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                            @endif

                                            @for ($i = $start_nv; $i <= $end_nv; $i++)
                                                @if ($i == $nhanviens->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $i }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link"
                                                            href="{{ $nhanviens->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endif
                                            @endfor

                                            @if($end_nv < $nhanviens->lastPage())
                                                @if($end_nv < $nhanviens->lastPage() - 1)
                                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                                @endif
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $nhanviens->appends(request()->query())->url($nhanviens->lastPage()) }}">
                                                        {{ $nhanviens->lastPage() }}
                                                    </a>
                                                </li>
                                            @endif

                                            @if ($nhanviens->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $nhanviens->appends(request()->query())->nextPageUrl() }}">
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

@push('scripts')
    <script>
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