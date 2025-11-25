@extends('layouts.NhanVienLayout')

@section('title', 'Quản lý vé')
@section('page-title', 'Danh sách vé')

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
                <form action="{{ route('nhan-vien-ban-ve.ve.index') }}" method="GET">
                    <div class="row g-3">
                        <!-- Ngày đi -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">
                                Ngày đi
                            </label>
                            <input type="date" name="ngay_di" value="{{ request('ngay_di') }}"
                                class="form-control">
                        </div>

                        <!-- Chuyến đi -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">
                                Chuyến đi
                            </label>
                            <select name="chuyen_di" id="chuyen_di" class="form-select">
                                <option value="">Tất cả chuyến</option>
                                @foreach($chuyenDis as $cd)
                                    <option value="{{ $cd->machuyendi }}" @selected(request('chuyen_di') == $cd->machuyendi)>
                                        {{ formatRouteForDropdown($cd->tuyen_duong) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">
                                Trạng thái
                            </label>
                            <select name="trang_thai" id="trang_thai" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="Đã duyệt" @selected(request('trang_thai') == 'Đã duyệt')>Đã duyệt</option>
                                <option value="Chờ duyệt" @selected(request('trang_thai') == 'Chờ duyệt')>Chờ duyệt</option>
                                <option value="Đã hủy" @selected(request('trang_thai') == 'Đã hủy')>Đã hủy</option>
                            </select>
                        </div>

                        <!-- Tìm kiếm -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">
                                Tìm kiếm
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0"
                                    placeholder="Mã vé, tên, SĐT...">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('nhan-vien-ban-ve.ve.index') }}" class="btn btn-light border">
                            <i class="fas fa-undo me-2"></i> Đặt lại
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-search me-2"></i> Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ====== TABLE CARD ====== --}}
        <div class="card border shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">
                    Danh sách vé xe
                </h6>
                <span class="badge bg-light text-dark border fw-normal">
                    Tổng số: <span class="fw-bold">{{ $ves->count() }}</span> vé
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tickets-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-bold text-secondary text-uppercase small py-3 ps-4 sortable" data-sort="mave" style="cursor: pointer;">Mã vé <i class="fas fa-sort ms-1 text-muted"></i></th>
                                <th class="fw-bold text-secondary text-uppercase small py-3">Chuyến đi</th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 d-none d-lg-table-cell sortable" data-sort="khach" style="cursor: pointer;">Khách hàng <i class="fas fa-sort ms-1 text-muted"></i></th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 text-center d-none d-md-table-cell">Biển số</th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 text-center sortable" data-sort="ghe" style="cursor: pointer;">Ghế <i class="fas fa-sort ms-1 text-muted"></i></th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 text-end sortable" data-sort="gia" style="cursor: pointer;">Giá vé <i class="fas fa-sort ms-1 text-muted"></i></th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 text-center d-none d-md-table-cell">Trạng thái</th>
                                <th class="fw-bold text-secondary text-uppercase small py-3 text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($ves as $ve)
                                <tr class="border-bottom">
                                    <!-- Mã vé -->
                                    <td class="ps-4">
                                        <span class="fw-bold text-primary font-monospace">
                                            {{ $ve->mave }}
                                        </span>
                                    </td>

                                    <!-- Chuyến đi -->
                                    <td>
                                        @php
                                            $firstPoint = $ve->chuyendi->lotrinhs->sortBy('trinhtu')->first();
                                            $lastPoint = $ve->chuyendi->lotrinhs->sortBy('trinhtu')->last();
                                        @endphp
                                        <div class="d-flex flex-column">
                                            <div class="mb-1">
                                                <x-route-badge :route="$firstPoint->tinhthanh->ten . ' -> ' . $lastPoint->tinhthanh->ten" />
                                            </div>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ Carbon\Carbon::parse($ve->chuyendi->thoigiandi)->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                    </td>

                                    <!-- Khách hàng -->
                                    <td class="d-none d-lg-table-cell">
                                        @if($ve->hoadon?->khach)
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $ve->hoadon->khach->ten }}</span>
                                                <small class="text-muted">{{ $ve->hoadon->khach->sdt }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted small fst-italic">Chưa có thông tin</span>
                                        @endif
                                    </td>

                                    <!-- Biển số xe -->
                                    <td class="text-center d-none d-md-table-cell">
                                        <span class="badge bg-light text-dark border fw-normal">
                                            {{ $ve->chuyendi->xe->soxe ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Mã ghế -->
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark fw-bold">
                                            {{ $ve->maghe }}
                                        </span>
                                    </td>

                                    <!-- Giá -->
                                    <td class="text-end">
                                        <span class="fw-bold text-dark">
                                            {{ number_format($ve->chuyendi->gia, 0, ',', '.') }}đ
                                        </span>
                                    </td>

                                    <!-- Hóa đơn -->
                                    <td class="text-center d-none d-md-table-cell">
                                        <x-BadgeTrangThai :status="$ve->hoadon ? 'ready' : 'pending'" />
                                    </td>

                                    <!-- Thao tác -->
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-primary btn-view-ticket"
                                            data-mave="{{ $ve->mave }}" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-ticket-alt fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">Không tìm thấy vé nào phù hợp</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ====== PAGINATION ====== --}}
            <div class="card-footer bg-white border-top py-3" id="tickets-pagination"></div>
        </div>
    </div>

    {{-- ====== MODAL CHI TIẾT VÉ ====== --}}
    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-white border-bottom">
                    <h5 class="modal-title fw-bold text-dark"><i class="fas fa-ticket-alt me-2 text-primary"></i>Chi tiết vé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="ticketModalContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="text-muted mt-3 small">Đang tải thông tin vé...</p>
                    </div>
                </div>
            </div>
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
        
        /* Choices.js Customization */
        .choices__inner {
            background-color: #fff;
            border-radius: 4px;
            border: 1px solid #ced4da;
            min-height: auto;
            padding: 0.25rem 0.5rem;
        }
        .choices__input {
            background-color: transparent;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize JS Pagination
            const ticketsPagination = new Pagination({
                tableId: 'tickets-table',
                paginationId: 'tickets-pagination',
                itemsPerPage: 10
            });

            // Table Sorting
            const table = document.getElementById('tickets-table');
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
                        if (icon) icon.className = 'fas fa-sort ms-1 text-muted';
                    });

                    // Add sort indicator to current header
                    header.classList.add(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');
                    const icon = header.querySelector('i');
                    if (icon) icon.className = `fas fa-sort-${currentSort.direction === 'asc' ? 'up' : 'down'} ms-1 text-dark`;

                    // Sort rows
                    rows.sort((a, b) => {
                        let aValue, bValue;

                        switch(sortType) {
                            case 'mave':
                                aValue = a.cells[0].textContent.trim();
                                bValue = b.cells[0].textContent.trim();
                                break;
                            case 'khach':
                                aValue = a.cells[2]?.textContent.trim() || '';
                                bValue = b.cells[2]?.textContent.trim() || '';
                                break;
                            case 'ghe':
                                aValue = parseInt(a.cells[4]?.textContent.trim()) || 0;
                                bValue = parseInt(b.cells[4]?.textContent.trim()) || 0;
                                break;
                            case 'gia':
                                aValue = parseInt(a.cells[5]?.textContent.replace(/\D/g, '')) || 0;
                                bValue = parseInt(b.cells[5]?.textContent.replace(/\D/g, '')) || 0;
                                break;
                            default:
                                return 0;
                        }

                        if (typeof aValue === 'string') {
                            return currentSort.direction === 'asc'
                                ? aValue.localeCompare(bValue, 'vi')
                                : bValue.localeCompare(aValue, 'vi');
                        } else {
                            return currentSort.direction === 'asc' ? aValue - bValue : bValue - aValue;
                        }
                    });

                    // Re-render table
                    rows.forEach(row => tbody.appendChild(row));

                    // Reset pagination
                    ticketsPagination.currentPage = 1;
                    ticketsPagination.render();
                });
            });

            // Initialize Choices.js for select dropdowns
            if (typeof Choices !== 'undefined') {
                new Choices('#chuyen_di', {
                    searchEnabled: true,
                    placeholder: true,
                    placeholderValue: 'Chọn chuyến đi',
                    searchPlaceholderValue: 'Tìm kiếm...',
                    itemSelectText: ''
                });

                new Choices('#trang_thai', {
                    searchEnabled: false,
                    placeholder: true,
                    placeholderValue: 'Chọn trạng thái',
                    itemSelectText: ''
                });
            }

            // View ticket detail modal
            document.querySelectorAll('.btn-view-ticket').forEach(btn => {
                btn.addEventListener('click', function () {
                    const mave = this.dataset.mave;
                    const modalElement = document.getElementById('ticketModal');
                    const modal = new bootstrap.Modal(modalElement);
                    const body = document.getElementById('ticketModalContent');

                    // Show loading state
                    body.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="text-muted mt-3 small">Đang tải thông tin vé...</p>
                    </div>
                `;

                    modal.show();

                    // Fetch ticket details
                    fetch(`{{ url('nhan-vien-ban-ve/ve') }}/${mave}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Không tải được dữ liệu vé');
                            return response.text();
                        })
                        .then(html => {
                            body.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            body.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <p class="text-muted">Không thể tải thông tin vé. Vui lòng thử lại.</p>
                            </div>
                        `;
                        });
                });
            });
        });
    </script>
@endpush