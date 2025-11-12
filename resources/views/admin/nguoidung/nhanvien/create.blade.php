@extends('layouts.admin')

@section('title', 'Thêm Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thêm Nhân viên</h2>
        <a href="{{ route('admin.nguoidung.nhanvien') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.nguoidung.nhanvien.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="manv" class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('manv') is-invalid @enderror" id="manv" name="manv" value="{{ old('manv') }}" required>
                            @error('manv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hoten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('hoten') is-invalid @enderror" id="hoten" name="hoten" value="{{ old('hoten') }}" required>
                            @error('hoten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="macv" class="form-label">Chức vụ <span class="text-danger">*</span></label>
                            <select class="form-select @error('macv') is-invalid @enderror" id="macv" name="macv" required>
                                <option value="">-- Chọn chức vụ --</option>
                                @foreach($chucvu as $cv)
                                    <option value="{{ $cv->macv }}" {{ old('macv') == $cv->macv ? 'selected' : '' }}>
                                        {{ $cv->tencv }}
                                    </option>
                                @endforeach
                            </select>
                            @error('macv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sdt') is-invalid @enderror" id="sdt" name="sdt" value="{{ old('sdt') }}" required>
                            @error('sdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="matkhau" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('matkhau') is-invalid @enderror" id="matkhau" name="matkhau" required>
                            @error('matkhau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="matkhau_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="matkhau_confirmation" name="matkhau_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('admin.nguoidung.nhanvien') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
