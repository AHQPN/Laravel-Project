@extends('layouts.admin.app')

@section('title', 'Quản lý Loại xe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Loại xe</h2>
        <a href="{{ route('quan-ly.loaixe.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm Loại xe
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
            <form action="{{ route('quan-ly.loaixe.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm loại xe..." value="{{ request('search') }}">
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
                <table class="table table-striped table-hover sortable-table" 
                       data-sort-column="{{ $sortParams['sort'] ?? 'maloai' }}"
                       data-sort-direction="{{ $sortParams['direction'] ?? 'asc' }}">
                    <thead>
                        <tr>
                            <th data-sort="maloai">Mã Loại xe <i class="fas fa-sort"></i></th>
                            <th data-sort="tenloai">Tên Loại xe <i class="fas fa-sort"></i></th>
                            <th data-sort="soghe">Số ghế <i class="fas fa-sort"></i></th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loaixe as $item)
                        <tr>
                            <td>{{ $item->maloai }}</td>
                            <td>{{ $item->tenloai }}</td>
                            <td>{{ $item->soghe }}</td>
                            <td class="text-center">
                                <a href="{{ route('quan-ly.loaixe.edit', $item->maloai) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('quan-ly.loaixe.destroy', $item->maloai) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-info">
                    @if($loaixe->total() > 0)
                        Hiển thị {{ $loaixe->firstItem() }} - {{ $loaixe->lastItem() }} trong {{ $loaixe->total() }} kết quả
                    @endif
                </div>
                <div>
                    {{ $loaixe->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/table-sort.js') }}"></script>
@endpush
