@extends('layouts.NhanVienLayout')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Hồ sơ nhân viên')

@section('content')
<div class="container-fluid py-4">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- ====== PROFILE CARD (LEFT SIDEBAR) ====== --}}
        <div class="col-xl-4 order-xl-2">
            <div class="card border shadow-sm h-100">
                <div class="card-body text-center p-4">
                    {{-- Avatar Section --}}
                    <div class="mb-4 position-relative d-inline-block">
                        <label for="avatar-upload" class="cursor-pointer">
                            @php
                                $avatarSrc = 'https://ui-avatars.com/api/?name=' . urlencode($nhanvien->ten ?? 'User') . '&background=0d6efd&color=fff&size=200&bold=true';
                                if (optional($nhanvien)->hinhanh && optional($nhanvien)->hinhanh !== 'default-avatar.jpg') {
                                    $avatarPath = public_path('storage/avatars/' . $nhanvien->hinhanh);
                                    if (file_exists($avatarPath)) {
                                        $avatarSrc = asset('storage/avatars/' . $nhanvien->hinhanh);
                                    }
                                }
                            @endphp
                            <img 
                                src="{{ $avatarSrc }}" 
                                class="rounded-circle border border-3 border-light shadow-sm"
                                width="120"
                                height="120"
                                id="avatar-preview"
                                alt="Avatar"
                                style="object-fit: cover;"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($nhanvien->ten ?? 'User') }}&background=0d6efd&color=fff&size=200&bold=true'"
                            >
                            <input type="file" id="avatar-upload" name="avatar" accept="image/*" hidden>
                            <div class="position-absolute bottom-0 end-0 bg-white rounded-circle shadow-sm p-2 border" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-camera text-secondary small"></i>
                            </div>
                        </label>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">
                        {{ optional($nhanvien)->ten ?? 'Chưa cập nhật' }}
                    </h5>
                    <p class="text-muted mb-3 small">
                        {{ optional(optional($nhanvien)->chucvu)->ten_chucvu ?? 'Nhân viên' }}
                    </p>

                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <span class="badge bg-light text-dark border fw-normal">
                            <i class="fas fa-id-badge me-1 text-secondary"></i> {{ optional($nhanvien)->manv ?? '--' }}
                        </span>
                        @if(optional($nhanvien)->trangthai == 'hoat-dong')
                            <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal">
                                Hoạt động
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-normal">
                                Bị khóa
                            </span>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('nhan-vien-ban-ve.ho-so.edit') }}" class="btn btn-primary">
                            <i class="fas fa-user-edit me-2"></i> Chỉnh sửa hồ sơ
                        </a>
                        <button type="button" id="btn-change-password" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-2"></i> Đổi mật khẩu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== PROFILE DETAILS (MAIN CONTENT) ====== --}}
        <div class="col-xl-8 order-xl-1">
            
            {{-- Thông tin cá nhân --}}
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Thông tin chi tiết
                    </h6>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Họ và tên</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">{{ $nhanvien->ten ?? '--' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Mã nhân viên</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">{{ $nhanvien->manv ?? '--' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Email</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">{{ $nhanvien->email ?? '--' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Số điện thoại</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">{{ $nhanvien->sdt ?? '--' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Ngày sinh</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">
                                    {{ optional($nhanvien)->ngaysinh ? \Carbon\Carbon::parse($nhanvien->ngaysinh)->format('d/m/Y') : '--' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Chức vụ</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">
                                    {{ optional(optional($nhanvien)->chucvu)->ten_chucvu ?? '--' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Địa chỉ</label>
                                <div class="fw-semibold text-dark border-bottom pb-2">{{ optional($nhanvien)->diachi ?? '--' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin hệ thống --}}
            <div class="card border shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-shield-alt me-2 text-secondary"></i>
                        Thông tin hệ thống
                    </h6>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="me-3 text-secondary">
                                    <i class="far fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <div class="small text-muted fw-bold">Ngày tạo tài khoản</div>
                                    <div class="fw-semibold text-dark">
                                        {{ optional($nhanvien)->created_at ? $nhanvien->created_at->format('d/m/Y H:i') : '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded bg-light">
                                <div class="me-3 text-secondary">
                                    <i class="fas fa-sync fa-lg"></i>
                                </div>
                                <div>
                                    <div class="small text-muted fw-bold">Cập nhật lần cuối</div>
                                    <div class="fw-semibold text-dark">
                                        {{ optional($nhanvien)->updated_at ? $nhanvien->updated_at->format('d/m/Y H:i') : '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
.btn {
    border-radius: 4px;
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.cursor-pointer {
    cursor: pointer;
}
.cursor-pointer:hover .rounded-circle {
    opacity: 0.9;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Avatar upload preview and upload
    const avatarUpload = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');
    
    if (avatarUpload) {
        avatarUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Kích thước ảnh không được vượt quá 2MB',
                        confirmButtonColor: '#0d6efd'
                    });
                    avatarUpload.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Chỉ chấp nhận file ảnh (JPEG, PNG, JPG, GIF)',
                        confirmButtonColor: '#0d6efd'
                    });
                    avatarUpload.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload via AJAX
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Show loading
                Swal.fire({
                    title: 'Đang tải lên...',
                    text: 'Vui lòng đợi',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch('{{ route("nhan-vien-ban-ve.ho-so.avatar") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            // Reload page to update avatar everywhere
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message,
                            confirmButtonColor: '#0d6efd'
                        });
                        // Reset preview to original
                        avatarPreview.src = '{{ asset("storage/avatars/" . (optional($nhanvien)->hinhanh ?? "default-avatar.jpg")) }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể tải lên ảnh. Vui lòng thử lại.',
                        confirmButtonColor: '#0d6efd'
                    });
                    // Reset preview to original
                    avatarPreview.src = '{{ asset("storage/avatars/" . (optional($nhanvien)->hinhanh ?? "default-avatar.jpg")) }}';
                });
            }
        });
    }

    // Change password modal
    const btnChangePassword = document.getElementById('btn-change-password');
    
    if (btnChangePassword) {
        btnChangePassword.addEventListener('click', function() {
            Swal.fire({
                title: '<strong>Đổi mật khẩu</strong>',
                html: `
                    <form id="form-change-password" method="POST" action="{{ route('nhan-vien-ban-ve.mat-khau.update') }}">
                        @csrf
                        <div class="mb-3 text-start">
                            <label for="current_password" class="form-label small fw-bold text-muted text-uppercase">
                                Mật khẩu hiện tại
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="form-control" 
                                   placeholder="Nhập mật khẩu hiện tại"
                                   required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="new_password" class="form-label small fw-bold text-muted text-uppercase">
                                Mật khẩu mới
                            </label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   class="form-control" 
                                   placeholder="Nhập mật khẩu mới"
                                   required>
                            <small class="text-muted">
                                Tối thiểu 8 ký tự
                            </small>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="new_password_confirmation" class="form-label small fw-bold text-muted text-uppercase">
                                Xác nhận mật khẩu mới
                            </label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation" 
                                   class="form-control" 
                                   placeholder="Nhập lại mật khẩu mới"
                                   required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cập nhật',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                focusConfirm: false,
                preConfirm: () => {
                    const form = document.getElementById('form-change-password');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return false;
                    }
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush