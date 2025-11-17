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
            <table class="table table-hover" id="tinhthanh-table">
                <thead>
                    <tr>
                        <th width="15%" class="sortable" data-sort="matinh" style="cursor: pointer;">Mã tỉnh <i class="fas fa-sort ms-1 text-muted"></i></th>
                        <th class="sortable" data-sort="ten" style="cursor: pointer;">Tên tỉnh thành <i class="fas fa-sort ms-1 text-muted"></i></th>
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
        <div class="mt-3" id="tinhthanh-pagination"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #tinhthanh-table thead th.sortable {
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
    }
    #tinhthanh-table thead th.sortable:hover {
        background-color: #e9ecef;
        color: #667eea;
    }
    #tinhthanh-table thead th.sort-asc,
    #tinhthanh-table thead th.sort-desc {
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
    const tinthanPagination = new Pagination({
        tableId: 'tinhthanh-table',
        paginationId: 'tinhthanh-pagination',
        itemsPerPage: 10
    });

    // Table Sorting
    const table = document.getElementById('tinhthanh-table');
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
                    case 'matinh':
                        aValue = a.cells[0]?.textContent.trim() || '';
                        bValue = b.cells[0]?.textContent.trim() || '';
                        break;
                    case 'ten':
                        aValue = a.cells[1]?.textContent.trim() || '';
                        bValue = b.cells[1]?.textContent.trim() || '';
                        break;
                    default:
                        return 0;
                }

                return currentSort.direction === 'asc'
                    ? aValue.localeCompare(bValue, 'vi')
                    : bValue.localeCompare(aValue, 'vi');
            });

            // Re-render table
            rows.forEach(row => tbody.appendChild(row));

            // Reset pagination
            tinthanPagination.currentPage = 1;
            tinthanPagination.render();
        });
    });
});
</script>
@endpush
