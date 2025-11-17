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
                <table class="table table-striped table-hover" id="loaixe-table">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="maloai" style="cursor: pointer;">Mã Loại xe <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th class="sortable" data-sort="tenloai" style="cursor: pointer;">Tên Loại xe <i class="fas fa-sort ms-1 text-muted"></i></th>
                            <th class="sortable" data-sort="soghe" style="cursor: pointer;">Số ghế <i class="fas fa-sort ms-1 text-muted"></i></th>
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
            <div class="mt-3" id="loaixe-pagination"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #loaixe-table thead th.sortable {
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }
    #loaixe-table thead th.sortable:hover {
        background-color: #e9ecef;
        color: #667eea;
    }
    #loaixe-table thead th.sort-asc,
    #loaixe-table thead th.sort-desc {
        background-color: #e9ecef;
        color: #667eea;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/pagination.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loaixePagination = new Pagination({
        tableId: 'loaixe-table',
        paginationId: 'loaixe-pagination',
        itemsPerPage: 10
    });

    // Table Sorting
    const table = document.getElementById('loaixe-table');
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

                switch(sortType) {
                    case 'maloai':
                    case 'tenloai':
                        aValue = a.cells[sortType === 'maloai' ? 0 : 1]?.textContent.trim() || '';
                        bValue = b.cells[sortType === 'maloai' ? 0 : 1]?.textContent.trim() || '';
                        return currentSort.direction === 'asc'
                            ? aValue.localeCompare(bValue, 'vi')
                            : bValue.localeCompare(aValue, 'vi');
                    case 'soghe':
                        aValue = parseInt(a.cells[2]?.textContent) || 0;
                        bValue = parseInt(b.cells[2]?.textContent) || 0;
                        return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
                    default:
                        return 0;
                }
            });

            // Re-render table
            rows.forEach(row => tbody.appendChild(row));

            // Reset pagination
            loaixePagination.currentPage = 1;
            loaixePagination.render();
        });
    });
});
</script>
@endpush
