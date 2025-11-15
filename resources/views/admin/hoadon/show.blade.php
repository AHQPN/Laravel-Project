@extends('layouts.admin.app')

@section('title', 'Chi tiết Hóa đơn')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết Hóa đơn #{{ $hoadon->mahd }}</h2>
        <a href="{{ route('quan-ly.hoadon.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Họ tên:</th>
                            <td>{{ $hoadon->khach->ten ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $hoadon->khach->sdt ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $hoadon->khach->email ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin hóa đơn</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Mã hóa đơn:</th>
                            <td>{{ $hoadon->mahd }}</td>
                        </tr>
                        <tr>
                            <th>Ngày đặt:</th>
                            <td>{{ \Carbon\Carbon::parse($hoadon->thoigian)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Nhân viên:</th>
                            <td>{{ $hoadon->nhanvien->ten ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                @if($hoadon->trangthai == 'Chờ duyệt')
                                    <span class="badge bg-warning text-dark">{{ $hoadon->trangthai }}</span>
                                @elseif($hoadon->trangthai == 'Đã duyệt')
                                    <span class="badge bg-success">{{ $hoadon->trangthai }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $hoadon->trangthai }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Chi tiết vé</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Chuyến đi</th>
                            <th>Thời gian</th>
                            <th>Số ghế</th>
                            <th>Giá vé</th>
                            <th>Phương thức TT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hoadon->cthd as $index => $ct)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($ct->ve && $ct->ve->chuyendi)
                                    Mã: {{ $ct->ve->chuyendi->machuyendi }}<br>
                                    Xe: {{ $ct->ve->chuyendi->xe->soxe ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($ct->ve && $ct->ve->chuyendi)
                                    {{ \Carbon\Carbon::parse($ct->ve->chuyendi->thoigiandi)->format('d/m/Y H:i') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($ct->ve)
                                    {{ $ct->ve->soghe }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ number_format($ct->dongia ?? $ct->ve->gia ?? 0, 0, ',', '.') }} VNĐ</td>
                            <td>
                                {{ $hoadon->thanhtoan->ptthanhtoan ?? 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Tổng cộng:</th>
                            <th colspan="2">{{ number_format($hoadon->thanhtien, 0, ',', '.') }} VNĐ</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($hoadon->trangthai == 'Chờ duyệt')
            <div class="mt-3 d-flex gap-2">
                <form action="{{ route('quan-ly.hoadon.duyet', $hoadon->mahd) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Xác nhận duyệt đơn này?')">
                        <i class="fas fa-check"></i> Duyệt đơn
                    </button>
                </form>
                <form action="{{ route('quan-ly.hoadon.huy', $hoadon->mahd) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận hủy đơn này?')">
                        <i class="fas fa-times"></i> Hủy đơn
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
