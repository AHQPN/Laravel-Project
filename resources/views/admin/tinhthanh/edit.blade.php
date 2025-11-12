@extends('layouts.admin')

@section('title', 'Sửa Tỉnh Thành')
@section('page-title', 'Sửa Tỉnh Thành')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i>Sửa Tỉnh Thành
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tinhthanh.update', $tinhThanh->matinh) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Mã tỉnh</label>
                <input type="text" class="form-control" value="{{ $tinhThanh->matinh }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tên tỉnh thành <span class="text-danger">*</span></label>
                <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" 
                       value="{{ old('ten', $tinhThanh->ten) }}" placeholder="Nhập tên tỉnh thành">
                @error('ten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Cập nhật
                </button>
                <a href="{{ route('admin.tinhthanh.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
