@extends('layouts.TaiXeLayout')

@section('title', 'Báo cáo sự cố')
@section('page-title', 'Gửi báo cáo sự cố')

@section('content')
    <div class="driver-card bg-white shadow-sm border-0">
        <div class="mb-4 text-center">
            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <h5 class="fw-bold text-dark">Báo cáo sự cố</h5>
            <p class="text-muted small">Vui lòng cung cấp thông tin chi tiết để được hỗ trợ kịp thời.</p>
        </div>

        <form action="{{ route('tai-xe.su-co.store') }}" method="POST" enctype="multipart/form-data" id="report-form">
            @csrf
            
            <div class="mb-3">
                <label for="machuyendi" class="form-label small fw-bold text-secondary">Chuyến gặp sự cố <span class="text-danger">*</span></label>
                <select name="machuyendi" id="machuyendi" class="form-select" required>
                    <option value="">-- Chọn chuyến đi --</option>
                    @foreach ($trips as $trip)
                        <option value="{{ $trip['machuyendi'] }}">{{ $trip['label'] }}</option>
                    @endforeach
                </select>
                @error('machuyendi')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="loai_suco" class="form-label small fw-bold text-secondary">Loại sự cố <span class="text-danger">*</span></label>
                <select name="loai_suco" id="loai_suco" class="form-select" required>
                    <option value="">-- Chọn loại sự cố --</option>
                    <option value="Tắc đường">Tắc đường / Kẹt xe</option>
                    <option value="Hư hỏng xe">Hư hỏng xe / Nổ lốp</option>
                    <option value="Tai nạn nhẹ">Va chạm / Tai nạn</option>
                    <option value="Sức khỏe khách">Sức khỏe hành khách</option>
                    <option value="Khác">Khác</option>
                </select>
                @error('loai_suco')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mota" class="form-label small fw-bold text-secondary">Mô tả chi tiết <span class="text-danger">*</span></label>
                <textarea name="mota" id="mota" rows="4" class="form-control" placeholder="Mô tả tình huống, địa điểm, thời gian và hướng xử lý hiện tại..." required></textarea>
                @error('mota')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary">Ảnh hiện trường (Tùy chọn)</label>
                <div class="image-upload-container border rounded-3 p-3 text-center bg-light position-relative" style="border-style: dashed !important;">
                    <input type="file" name="anh" id="anh" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept="image/*">
                    <div id="upload-placeholder">
                        <i class="fas fa-camera text-muted fa-2x mb-2"></i>
                        <p class="mb-0 text-muted small">Chạm để chụp hoặc chọn ảnh</p>
                    </div>
                    <img id="preview-image" src="#" alt="Preview" class="img-fluid rounded shadow-sm d-none" style="max-height: 200px;">
                    <button type="button" id="remove-image" class="btn btn-sm btn-circle btn-danger position-absolute top-0 end-0 m-2 d-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @error('anh')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                    <i class="fas fa-paper-plane me-2"></i> Gửi báo cáo
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Choices
        const chuyendiSelect = new Choices('#machuyendi', {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            placeholder: true,
            searchPlaceholderValue: 'Tìm chuyến...',
            classNames: {
                containerOuter: 'choices mb-0',
            }
        });

        const loaiSucoSelect = new Choices('#loai_suco', {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false,
            classNames: {
                containerOuter: 'choices mb-0',
            }
        });

        // Image Upload Logic
        const fileInput = document.getElementById('anh');
        const preview = document.getElementById('preview-image');
        const placeholder = document.getElementById('upload-placeholder');
        const removeBtn = document.getElementById('remove-image');

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                    removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent triggering file input
            fileInput.value = '';
            preview.src = '#';
            preview.classList.add('d-none');
            placeholder.classList.remove('d-none');
            removeBtn.classList.add('d-none');
        });

        // Form Submit Loading
        document.getElementById('report-form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang gửi...';
        });
    });
</script>
@endpush
