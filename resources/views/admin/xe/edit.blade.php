@extends('layouts.admin.app')

@section('title', 'Sửa Xe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sửa Xe</h2>
        <a href="{{ route('quan-ly.xe.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('quan-ly.xe.update', $xe->maxe) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="maxe" class="form-label">Mã xe</label>
                            <input type="text" class="form-control" id="maxe" value="{{ $xe->maxe }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="soxe" class="form-label">Biển số xe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('soxe') is-invalid @enderror" id="soxe" name="soxe" value="{{ old('soxe', $xe->soxe) }}" required>
                            @error('soxe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="maloai" class="form-label">Loại xe <span class="text-danger">*</span></label>
                            <select class="form-select @error('maloai') is-invalid @enderror" id="maloai" name="maloai" required>
                                <option value="">-- Chọn loại xe --</option>
                                @foreach($loaixes as $lx)
                                    <option value="{{ $lx->maloai }}" {{ old('maloai', $xe->maloai) == $lx->maloai ? 'selected' : '' }}>
                                        {{ $lx->tenloai }} ({{ $lx->soghe }} ghế)
                                    </option>
                                @endforeach
                            </select>
                            @error('maloai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="manv" class="form-label">Tài xế</label>
                            <select class="form-select @error('manv') is-invalid @enderror" id="manv" name="manv">
                                <option value="">-- Chọn tài xế --</option>
                                @foreach($taixe as $tx)
                                    <option value="{{ $tx->manv }}" {{ old('manv', $xe->manv) == $tx->manv ? 'selected' : '' }}>
                                        {{ $tx->hoten }}
                                    </option>
                                @endforeach
                            </select>
                            @error('manv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('quan-ly.xe.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
