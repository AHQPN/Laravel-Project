@extends('layouts.admin.app')

@section('title', 'Quản lý Xe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý Xe</h2>
        <a href="{{ route('quan-ly.xe.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm xe
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
            <form action="{{ route('quan-ly.xe.index') }}" method="GET" class="row g-3">
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
                <table class="table table-striped table-hover sortable-table" 
                       data-sort-column="{{ $sortParams['sort'] ?? 'maxe' }}"
                       data-sort-direction="{{ $sortParams['direction'] ?? 'asc' }}">
                    <thead>
                        <tr>
                            <th data-sort="soxe">Biển số <i class="fas fa-sort"></i></th>
                            <th data-sort="maloai">Loại xe <i class="fas fa-sort"></i></th>
                            <th data-sort="manv">Tài xế <i class="fas fa-sort"></i></th>
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
                                <a href="{{ route('quan-ly.xe.edit', $item->maxe) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('quan-ly.xe.destroy', $item->maxe) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="pagination-info">
                    @if($xes->total() > 0)
                        Hiển thị {{ $xes->firstItem() }} - {{ $xes->lastItem() }} trong {{ $xes->total() }} kết quả
                    @endif
                </div>
                <div>
                    {{ $xes->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/table-sort.js') }}"></script>
@endpush
