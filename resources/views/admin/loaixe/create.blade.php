@extends('layouts.admin.app')

@section('title', 'Thêm Loại xe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thêm Loại xe</h2>
        <a href="{{ route('quan-ly.loaixe.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('quan-ly.loaixe.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="tenloai" class="form-label">Tên Loại xe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tenloai') is-invalid @enderror" id="tenloai" name="tenloai" value="{{ old('tenloai') }}" required>
                    @error('tenloai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="soghe" class="form-label">Số ghế <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('soghe') is-invalid @enderror" id="soghe" name="soghe" value="{{ old('soghe') }}" min="1" required>
                    @error('soghe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('quan-ly.loaixe.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
