@extends('layouts.admin')

@section('title', 'Quản lý Chuyến đi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Chuyến đi</h2>
        <a href="{{ route('admin.chuyendi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Chuyến đi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form action="{{ route('admin.chuyendi.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm chuyến đi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                            <th>Mã chuyến</th>
                            <th>Xe</th>
                            <th>Thời gian</th>
                            <th>Giá vé</th>
                            <th>Ghế trống</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($chuyendis as $item)
                        <tr>
                            <td>{{ $item->machuyendi }}</td>
                            <td>{{ $item->xe->soxe ?? 'N/A' }} - {{ $item->xe->loaixe->tenloai ?? '' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->thoigiandi)->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($item->gia, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $item->SLgheconlai }}/{{ $item->xe->loaixe->soghe ?? 0 }}</td>
                            <td>
                                <span class="badge bg-success">Hoạt động</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.chuyendi.edit', $item->machuyendi) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.chuyendi.destroy', $item->machuyendi) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $chuyendis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
