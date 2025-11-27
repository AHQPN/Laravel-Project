@extends('layouts.admin.app')

@section('title', 'Thêm Chuyến đi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thêm Chuyến đi</h2>
        <a href="{{ route('quan-ly.chuyendi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('quan-ly.chuyendi.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="tenchuyen" class="form-label">Tên chuyến <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tenchuyen') is-invalid @enderror" id="tenchuyen" name="tenchuyen" value="{{ old('tenchuyen') }}" required>
                            @error('tenchuyen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="maxe" class="form-label">Xe <span class="text-danger">*</span></label>
                            <select class="form-select @error('maxe') is-invalid @enderror" id="maxe" name="maxe" required>
                                <option value="">-- Chọn xe --</option>
                                @foreach($xes as $x)
                                    <option value="{{ $x->maxe }}" {{ old('maxe') == $x->maxe ? 'selected' : '' }}>
                                        {{ $x->soxe }} - {{ $x->loaixe->tenloai ?? '' }} ({{ $x->loaixe->soghe ?? 0 }} ghế)
                                    </option>
                                @endforeach
                            </select>
                            @error('maxe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="thoigiandi" class="form-label">Thời gian đi <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('thoigiandi') is-invalid @enderror" id="thoigiandi" name="thoigiandi" value="{{ old('thoigiandi') }}" required>
                            @error('thoigiandi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="thoigiandichuyen" class="form-label">Thời gian di chuyển (phút) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('thoigiandichuyen') is-invalid @enderror" id="thoigiandichuyen" name="thoigiandichuyen" value="{{ old('thoigiandichuyen') }}" min="1" required>
                            @error('thoigiandichuyen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gia" class="form-label">Giá vé (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('gia') is-invalid @enderror" id="gia" name="gia" value="{{ old('gia') }}" min="0" step="1000" required>
                            @error('gia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Lộ trình</h5>
                <div id="lotrinh-container">
                    <div class="row mb-3 lotrinh-item">
                        <div class="col-md-10">
                            <label class="form-label">Tỉnh thành</label>
                            <select class="form-select" name="lotrinh[]" required>
                                <option value="">-- Chọn tỉnh thành --</option>
                                @foreach($tinhThanhs as $tt)
                                    <option value="{{ $tt->matinh }}">{{ $tt->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger w-100 remove-lotrinh">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-3" id="add-lotrinh">
                    <i class="fas fa-plus"></i> Thêm điểm dừng
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('quan-ly.chuyendi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#add-lotrinh').click(function() {
        var html = `
            <div class="row mb-3 lotrinh-item">
                <div class="col-md-10">
                    <label class="form-label">Tỉnh thành</label>
                    <select class="form-select" name="lotrinh[]" required>
                        <option value="">-- Chọn tỉnh thành --</option>
                        @foreach($tinhThanhs as $tt)
                            <option value="{{ $tt->matinh }}">{{ $tt->ten }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger w-100 remove-lotrinh">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#lotrinh-container').append(html);
    });

    $(document).on('click', '.remove-lotrinh', function() {
        if ($('.lotrinh-item').length > 1) {
            $(this).closest('.lotrinh-item').remove();
        } else {
            alert('Phải có ít nhất 1 điểm trong lộ trình!');
        }
    });
});
</script>
@endpush
@endsection
