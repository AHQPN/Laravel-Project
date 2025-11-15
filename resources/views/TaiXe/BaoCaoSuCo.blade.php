@extends('layouts.TaiXeLayout')

@section('title', 'Báo cáo sự cố')
@section('page-title', 'Báo cáo sự cố')

@section('content')
    <form action="{{ route('tai-xe.su-co.store') }}" method="POST" enctype="multipart/form-data" class="driver-card">
        @csrf
        <div class="mb-3">
            <label for="machuyendi" class="form-label">Chọn chuyến gặp sự cố</label>
            <select name="machuyendi" id="machuyendi" class="form-select" data-trigger>
                <option value="">-- Chọn chuyến --</option>
                @foreach ($trips as $trip)
                    <option value="{{ $trip['machuyendi'] }}">{{ $trip['label'] }}</option>
                @endforeach
            </select>
            @error('machuyendi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="loai_suco" class="form-label">Loại sự cố</label>
            <select name="loai_suco" id="loai_suco" class="form-select">
                <option value="">-- Chọn loại sự cố --</option>
                <option value="Tắc đường">Tắc đường</option>
                <option value="Hư hỏng xe">Hư hỏng xe</option>
                <option value="Tai nạn nhẹ">Tai nạn nhẹ</option>
                <option value="Khác">Khác</option>
            </select>
            @error('loai_suco')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mota" class="form-label">Mô tả chi tiết</label>
            <textarea name="mota" id="mota" rows="4" class="form-control" placeholder="Mô tả tình huống, thời gian, hướng xử lý..."></textarea>
            @error('mota')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-4">
            <label for="anh" class="form-label d-flex align-items-center gap-2">
                <i class="fas fa-camera"></i> Ảnh hiện trường (tuỳ chọn)
            </label>
            <input type="file" name="anh" id="anh" class="form-control" accept="image/*">
            <div class="mt-2">
                <img id="preview-image" src="#" alt="Xem trước ảnh" class="img-fluid rounded" style="display:none; max-height: 300px; object-fit: contain;">
            </div>
            @error('anh')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane me-2"></i>Gửi báo cáo
        </button>
    </form>
@endsection

@push('scripts')
<script>
    const chuyendiSelect = new Choices('#machuyendi', {
        searchEnabled: true,
        itemSelectText: '',
        shouldSort: false,
        placeholder: true,
        searchPlaceholderValue: 'Tìm mã chuyến...'
    });

    const loaiSucoSelect = new Choices('#loai_suco', {
        searchEnabled: false,
        itemSelectText: '',
        shouldSort: false,
    });

    document.getElementById('anh').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush

