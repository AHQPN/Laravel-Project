@extends('layouts.admin.app')

@section('title', 'Quản lý Tỉnh Thành')
@section('page-title', 'Quản lý Tỉnh Thành')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-map-marker-alt me-2"></i>Danh sách Tỉnh Thành</span>
        <a href="{{ route('quan-ly.tinhthanh.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Thêm mới
        </a>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ $search }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if($search)
                            <a href="{{ route('quan-ly.tinhthanh.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover sortable-table" 
                   data-sort-column="{{ $sortParams['sort'] ?? 'matinh' }}"
                   data-sort-direction="{{ $sortParams['direction'] ?? 'asc' }}">
                <thead>
                    <tr>
                        <th width="15%" data-sort="matinh">Mã tỉnh <i class="fas fa-sort"></i></th>
                        <th data-sort="ten">Tên tỉnh thành <i class="fas fa-sort"></i></th>
                        <th width="20%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tinhThanhs as $tinhThanh)
                    <tr>
                        <td><strong>{{ $tinhThanh->matinh }}</strong></td>
                        <td>{{ $tinhThanh->ten }}</td>
                        <td class="text-center">
                            <a href="{{ route('quan-ly.tinhthanh.edit', $tinhThanh->matinh) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('quan-ly.tinhthanh.destroy', $tinhThanh->matinh) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Không có dữ liệu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="pagination-info">
                @if($tinhThanhs->total() > 0)
                    Hiển thị {{ $tinhThanhs->firstItem() }} - {{ $tinhThanhs->lastItem() }} trong {{ $tinhThanhs->total() }} kết quả
                @endif
            </div>
            <div>
                {{ $tinhThanhs->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/table-sort.js') }}"></script>
@endpush
