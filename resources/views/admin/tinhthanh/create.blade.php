@extends('layouts.admin.app')

@section('title', 'Thêm Tỉnh Thành')
@section('page-title', 'Thêm Tỉnh Thành')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i>Thêm Tỉnh Thành Mới
    </div>
    <div class="card-body">
        <form action="{{ route('quan-ly.tinhthanh.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Mã tỉnh <span class="text-danger">*</span></label>
                <input type="text" name="matinh" class="form-control @error('matinh') is-invalid @enderror" 
                       value="{{ old('matinh') }}" maxlength="4" placeholder="VD: SG, HN, DN...">
                @error('matinh')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tên tỉnh thành <span class="text-danger">*</span></label>
                <input type="text" name="ten" class="form-control @error('ten') is-invalid @enderror" 
                       value="{{ old('ten') }}" placeholder="Nhập tên tỉnh thành">
                @error('ten')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu
                </button>
                <a href="{{ route('quan-ly.tinhthanh.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
