@extends('layouts.NhanVienLayout')

@section('title', 'Hóa đơn của tôi')
@section('page-title', 'Danh sách hóa đơn')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ====== FILTER CARD ====== --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="fas fa-filter me-2 text-secondary"></i>
                Bộ lọc tìm kiếm
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nhan-vien-ban-ve.hoa-don.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Ngày lập -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold text-secondary small text-uppercase">
                            Ngày lập
                        </label>
                        <input type="date" 
                               name="ngay_lap" 
                               value="{{ request('ngay_lap') }}" 
                               class="form-control">
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold text-secondary small text-uppercase">
                            Trạng thái
                        </label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="Đã duyệt" @selected(request('trang_thai')=='Đã duyệt')>Đã duyệt</option>
                            <option value="Chờ xử lý" @selected(request('trang_thai')=='Chờ xử lý')>Chờ xử lý</option>
                            <option value="Đã hủy" @selected(request('trang_thai')=='Đã hủy')>Đã hủy</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-4 col-md-12 d-flex align-items-end justify-content-end gap-2">
                        <a href="{{ route('nhan-vien-ban-ve.hoa-don.index') }}" 
                           class="btn btn-light border">
                            <i class="fas fa-undo me-2"></i> Đặt lại
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-filter me-2"></i> Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ====== TABLE CARD ====== --}}
    <div class="card border shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                Danh sách hóa đơn
            </h6>
            <span class="badge bg-light text-dark border fw-normal">
                Tổng số: <span class="fw-bold">{{ $hoadons->count() }}</span> hóa đơn
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="invoice-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="fw-bold text-secondary text-uppercase small py-3 ps-4 sortable" style="cursor: pointer;">Mã hóa đơn</th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 sortable" style="cursor: pointer;">Khách hàng</th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" style="cursor: pointer;">Số vé</th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-end sortable" style="cursor: pointer;">Tổng tiền</th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" style="cursor: pointer;">Trạng thái</th>
                            <th class="fw-bold text-secondary text-uppercase small py-3 pe-4 sortable" style="cursor: pointer;">Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hoadons as $hd)
                            <tr class="invoice-row border-bottom">
                                <!-- Mã hóa đơn -->
                                <td class="ps-4">
                                    <span class="fw-bold text-primary font-monospace">
                                        {{ $hd->mahd }}
                                    </span>
                                </td>

                                <!-- Khách hàng -->
                                <td>
                                    @if($hd->khach)
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark">
                                                {{ $hd->khach->hoten ?? $hd->khach->tenkhach ?? 'Khách lẻ' }}
                                            </span>
                                            @if($hd->khach->sdt)
                                                <small class="text-muted">
                                                    <i class="fas fa-phone-alt me-1 text-secondary small"></i>
                                                    {{ $hd->khach->sdt }}
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">
                                            Chưa có thông tin
                                        </span>
                                    @endif
                                </td>

                                <!-- Số vé -->
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border fw-normal">
                                        {{ $hd->soluong }}
                                    </span>
                                </td>

                                <!-- Tổng tiền -->
                                <td class="text-end">
                                    <span class="fw-bold text-dark">
                                        {{ number_format($hd->thanhtien, 0, ',', '.') }}đ
                                    </span>
                                </td>

                                <!-- Trạng thái -->
                                <td class="text-center">
                                    @php $status = $hd->trangthai; @endphp
                                    @if($status === 'Đã duyệt' || $status === 'Đã thanh toán')
                                        <span class="badge bg-success text-dark bg-opacity-10 border border-success px-2 py-1 fw-normal text-success">
                                            {{ $status }}
                                        </span>
                                    @elseif($status === 'Đã hủy')
                                        <span class="badge bg-danger text-dark bg-opacity-10 border border-danger px-2 py-1 fw-normal text-danger">
                                            Đã hủy
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark bg-opacity-10 border border-warning px-2 py-1 fw-normal text-warning">
                                            {{ $status ?? 'Chờ xử lý' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Thời gian -->
                                <td class="pe-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-semibold">
                                            {{ optional($hd->thoigian)->format('d/m/Y') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ optional($hd->thoigian)->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-file-invoice fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Chưa có hóa đơn nào phù hợp</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ====== PAGINATION ====== --}}
        <div class="card-footer bg-white border-top py-3" id="invoice-pagination"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enterprise UI Overrides */
.card {
    border-radius: 6px;
    border-color: #e0e0e0;
}
.shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.05)!important;
}
.form-label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
.form-select, .form-control {
    border-radius: 4px;
    border-color: #ced4da;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}
.form-select:focus, .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}
.btn {
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.table thead th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
.font-monospace {
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
}

/* Pagination Styles */
.smart-pagination .pagination {
    justify-content: flex-end;
    margin-bottom: 0;
}
.smart-pagination .page-link {
    border-radius: 4px;
    margin: 0 2px;
    color: #495057;
    border: 1px solid #dee2e6;
}
.smart-pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/pagination.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize JS Pagination
    const invoicePagination = new Pagination({
        tableId: 'invoice-table',
        paginationId: 'invoice-pagination',
        itemsPerPage: 10
    });

    // Table sorting functionality
    const table = document.getElementById('invoice-table');
    const headers = table.querySelectorAll('thead th.sortable');
    let currentSort = { index: null, dir: 'asc' };

    headers.forEach((th, index) => {
        th.addEventListener('click', () => {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr:not(:last-child)')); // Exclude empty state row if exists
            
            if (rows.length === 0) return;

            if (currentSort.index === index) {
                currentSort.dir = currentSort.dir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = { index, dir: 'asc' };
            }

            // Remove all sort indicators
            headers.forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
                // Reset icon if exists (not implemented in this view but good practice)
            });

            // Add sort indicator
            th.classList.add(currentSort.dir === 'asc' ? 'sort-asc' : 'sort-desc');

            rows.sort((a, b) => {
                const aText = a.children[index].innerText.trim();
                const bText = b.children[index].innerText.trim();
                
                // Try numeric comparison for money and quantity
                if (!isNaN(aText.replace(/[^0-9]/g, '')) && !isNaN(bText.replace(/[^0-9]/g, ''))) {
                    const aNum = parseInt(aText.replace(/[^0-9]/g, ''), 10);
                    const bNum = parseInt(bText.replace(/[^0-9]/g, ''), 10);
                    return currentSort.dir === 'asc' ? aNum - bNum : bNum - aNum;
                }
                
                // String comparison
                return currentSort.dir === 'asc'
                    ? aText.localeCompare(bText, 'vi')
                    : bText.localeCompare(aText, 'vi');
            });

            rows.forEach(r => tbody.appendChild(r));
        });
    });

    // Add sort indicator styles via JS to avoid cluttering CSS
    const style = document.createElement('style');
    style.textContent = `
        thead th.sort-asc::after {
            content: " ↑";
            color: #0d6efd;
            font-weight: bold;
        }
        thead th.sort-desc::after {
            content: " ↓";
            color: #0d6efd;
            font-weight: bold;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush