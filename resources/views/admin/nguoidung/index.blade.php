@extends('layouts.admin')

@section('title', 'Quản lý Người dùng')

@section('content')
<style>
    /* Smaller pagination buttons */
    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* Make tabs more prominent and always visible */
    .nav-tabs .nav-link {
        font-size: 1.1rem;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        color: #495057 !important;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .nav-tabs .nav-link:hover {
        color: #0056b3 !important;
        background-color: #e9ecef;
    }
    
    .nav-tabs .nav-link.active {
        color: #fff !important;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .nav-tabs .nav-link i {
        font-size: 1.2rem;
        margin-right: 0.5rem;
    }
</style>
<div class="container-fluid">
    <h2 class="mb-4">Quản lý Người dùng</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#khachhang" role="tab">
                <i class="fas fa-users"></i> Khách hàng
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#nhanvien" role="tab">
                <i class="fas fa-user-tie"></i> Nhân viên
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Tab Khách hàng -->
        <div class="tab-pane fade show active" id="khachhang" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form action="{{ route('admin.nguoidung.khach') }}" method="GET" class="row g-3 flex-grow-1">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm khách hàng..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Mã KH</th>
                                    <th>Họ tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($khachs ?? [] as $item)
                                <tr>
                                    <td>{{ $item->makh }}</td>
                                    <td>{{ $item->ten }}</td>
                                    <td>{{ $item->sdt }}</td>
                                    <td>{{ $item->email ?? '-' }}</td>
                                    <td>{{ $item->diachi ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.nguoidung.khach.edit', $item->makh) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.nguoidung.khach.destroy', $item->makh) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if(isset($khachs))
                            {{ $khachs->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Nhân viên -->
        <div class="tab-pane fade" id="nhanvien" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form action="{{ route('admin.nguoidung.nhanvien') }}" method="GET" class="row g-3 flex-grow-1">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nhân viên..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                    <a href="{{ route('admin.nguoidung.nhanvien.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm Nhân viên
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Mã NV</th>
                                    <th>Họ tên</th>
                                    <th>Chức vụ</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nhanviens ?? [] as $item)
                                <tr>
                                    <td>{{ $item->manv }}</td>
                                    <td>{{ $item->ten }}</td>
                                    <td>{{ $item->chucvu->chucvu ?? 'N/A' }}</td>
                                    <td>{{ $item->sdt }}</td>
                                    <td>{{ $item->email ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.nguoidung.nhanvien.edit', $item->manv) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form action="{{ route('admin.nguoidung.nhanvien.destroy', $item->manv) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if(isset($nhanviens))
                            {{ $nhanviens->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
