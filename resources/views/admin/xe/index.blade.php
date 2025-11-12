@extends('layouts.admin')

@section('title', 'Quản lý Xe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Xe</h2>
        <a href="{{ route('admin.xe.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Xe
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
            <form action="{{ route('admin.xe.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm biển số xe..." value="{{ request('search') }}">
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
                            <th>Biển số</th>
                            <th>Loại xe</th>
                            <th>Tài xế</th>
                            <th>Số ghế</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($xes as $item)
                        <tr>
                            <td>{{ $item->soxe }}</td>
                            <td>{{ $item->loaixe->tenloai ?? 'N/A' }}</td>
                            <td>{{ $item->taixe->hoten ?? 'Chưa phân công' }}</td>
                            <td>{{ $item->loaixe->soghe ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-success">Hoạt động</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.xe.edit', $item->maxe) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.xe.destroy', $item->maxe) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                {{ $xes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
