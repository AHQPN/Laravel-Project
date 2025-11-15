@extends('layouts.NhanVienLayout')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-card" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-card" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- ====== PROFILE CARD (LEFT SIDEBAR) ====== --}}
        <div class="col-xl-4 order-xl-2">
            <div class="card shadow-sm border-0 animate-card">
                
                {{-- Cover Image --}}
                <div class="profile-cover"></div>
                
                {{-- Avatar Section --}}
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar-container">
                        <label for="avatar-upload" class="avatar-label">
                            @php
                                $avatarSrc = 'https://ui-avatars.com/api/?name=' . urlencode($nhanvien->ten ?? 'User') . '&background=667eea&color=fff&size=200&bold=true';
                                if (optional($nhanvien)->hinhanh && optional($nhanvien)->hinhanh !== 'default-avatar.jpg') {
                                    $avatarPath = public_path('storage/avatars/' . $nhanvien->hinhanh);
                                    if (file_exists($avatarPath)) {
                                        $avatarSrc = asset('storage/avatars/' . $nhanvien->hinhanh);
                                    }
                                }
                            @endphp
                            <img 
                                src="{{ $avatarSrc }}" 
                                class="avatar-img"
                                id="avatar-preview"
                                alt="Avatar"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($nhanvien->ten ?? 'User') }}&background=667eea&color=fff&size=200&bold=true'"
                            >
                            <input type="file" id="avatar-upload" name="avatar" accept="image/*" hidden>
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                                <span class="overlay-text">Đổi ảnh</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Profile Info --}}
                <div class="card-body text-center pt-5 pb-4">
                    <h4 class="mb-2 fw-bold">
                        {{ optional($nhanvien)->ten ?? 'Chưa cập nhật' }}
                    </h4>
                    <p class="text-muted mb-2">
                        <i class="fas fa-briefcase me-2"></i>
                        {{ optional(optional($nhanvien)->chucvu)->ten_chucvu ?? 'Chưa có chức vụ' }}
                    </p>
                    <p class="text-muted small mb-4">
                        <i class="fas fa-envelope me-2"></i>
                        {{ optional($nhanvien)->email ?? 'Chưa có email' }}
                    </p>

                    {{-- Quick Stats --}}
                    <div class="row g-3 mt-3">
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-icon">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                <div class="stat-value">{{ optional($nhanvien)->manv ?? '--' }}</div>
                                <div class="stat-label">Mã nhân viên</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box">
                                <div class="stat-icon">
                                    <i class="fas fa-circle-check"></i>
                                </div>
                                <div class="stat-value">
                                    @if(optional($nhanvien)->trangthai == 'hoat-dong')
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="fas fa-check me-1"></i>Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-ban me-1"></i>Bị khóa
                                        </span>
                                    @endif
                                </div>
                                <div class="stat-label">Trạng thái</div>
                            </div>
                        </div>
                    </div>

                    {{-- Social Links / Actions --}}
                    <div class="social-links mt-4 pt-4 border-top">
                        <a href="{{ route('nhan-vien-ban-ve.ho-so.edit') }}" 
                           class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-user-edit me-2"></i>
                            Chỉnh sửa hồ sơ
                        </a>
                        <button type="button" 
                                id="btn-change-password" 
                                class="btn btn-outline-warning w-100">
                            <i class="fas fa-key me-2"></i>
                            Đổi mật khẩu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== PROFILE DETAILS (MAIN CONTENT) ====== --}}
        <div class="col-xl-8 order-xl-1">
            
            {{-- Thông tin cá nhân --}}
            <div class="card shadow-sm border-0 mb-4 animate-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center">
                        <i class="fas fa-user-circle me-2"></i>
                        Thông tin cá nhân
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-primary-subtle">
                                    <i class="fas fa-id-card text-primary"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Mã nhân viên</label>
                                    <div class="info-value">{{ $nhanvien->manv ?? '--' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-success-subtle">
                                    <i class="fas fa-user text-success"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Họ và tên</label>
                                    <div class="info-value">{{ $nhanvien->ten ?? '--' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-info-subtle">
                                    <i class="fas fa-envelope text-info"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Email</label>
                                    <div class="info-value">{{ $nhanvien->email ?? '--' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-warning-subtle">
                                    <i class="fas fa-phone text-warning"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Số điện thoại</label>
                                    <div class="info-value">{{ $nhanvien->sdt ?? '--' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-danger-subtle">
                                    <i class="fas fa-calendar text-danger"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Ngày sinh</label>
                                    <div class="info-value">
                                        {{ optional($nhanvien)->ngaysinh ? \Carbon\Carbon::parse($nhanvien->ngaysinh)->format('d/m/Y') : '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-group">
                                <div class="info-icon bg-secondary-subtle">
                                    <i class="fas fa-briefcase text-secondary"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Chức vụ</label>
                                    <div class="info-value">
                                        {{ optional(optional($nhanvien)->chucvu)->ten_chucvu ?? '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-group">
                                <div class="info-icon bg-primary-subtle">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Địa chỉ</label>
                                    <div class="info-value">{{ optional($nhanvien)->diachi ?? '--' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông tin hệ thống --}}
            <div class="card shadow-sm border-0 animate-card">
                <div class="card-header bg-gradient-info text-white">
                    <h5 class="mb-0 fw-semibold d-flex align-items-center">
                        <i class="fas fa-cog me-2"></i>
                        Thông tin hệ thống
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="system-info-box">
                                <div class="d-flex align-items-center">
                                    <div class="system-icon me-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="system-label">Ngày tạo tài khoản</div>
                                        <div class="system-value">
                                            {{ optional($nhanvien)->created_at ? $nhanvien->created_at->format('d/m/Y H:i') : '--' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="system-info-box">
                                <div class="d-flex align-items-center">
                                    <div class="system-icon me-3">
                                        <i class="fas fa-sync"></i>
                                    </div>
                                    <div>
                                        <div class="system-label">Cập nhật lần cuối</div>
                                        <div class="system-value">
                                            {{ optional($nhanvien)->updated_at ? $nhanvien->updated_at->format('d/m/Y H:i') : '--' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="system-info-box border-0 bg-light">
                                <div class="d-flex align-items-start">
                                    <div class="system-icon me-3">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <div class="system-label mb-2">Trạng thái tài khoản</div>
                                        <div>
                                            @if(optional($nhanvien)->trangthai == 'hoat-dong')
                                                <span class="badge bg-success px-4 py-2 fs-6">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Tài khoản đang hoạt động
                                                </span>
                                            @else
                                                <span class="badge bg-danger px-4 py-2 fs-6">
                                                    <i class="fas fa-ban me-2"></i>
                                                    Tài khoản bị khóa
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Liên hệ quản trị viên nếu có vấn đề với tài khoản
                                        </small>
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
/* ====== Gradient Backgrounds ====== */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

/* ====== Card Animation ====== */
.animate-card {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ====== Profile Cover ====== */
.profile-cover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    height: 200px;
    border-radius: 0.75rem 0.75rem 0 0;
    position: relative;
}

.profile-cover::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center / cover no-repeat;
}

/* ====== Avatar Styles ====== */
.profile-avatar-wrapper {
    position: relative;
    margin-top: -100px;
    text-align: center;
    z-index: 10;
}

.profile-avatar-container {
    display: inline-block;
    position: relative;
}

.avatar-label {
    cursor: pointer;
    display: block;
    position: relative;
    z-index: 1;
}

.avatar-img {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    border: 6px solid #fff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    object-fit: cover;
    object-position: center;
    display: block;
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
    background: #fff;
}

.avatar-label:hover .avatar-img {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
}

.avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 3;
}

.avatar-label:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay i {
    color: #fff;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.overlay-text {
    color: #fff;
    font-size: 0.875rem;
    font-weight: 600;
}

/* ====== Stats Box ====== */
.stat-box {
    padding: 1.5rem;
    border-radius: 0.75rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transition: all 0.3s ease;
    text-align: center;
    border: 2px solid transparent;
}

.stat-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-color: #667eea;
}

.stat-icon {
    font-size: 2rem;
    color: #667eea;
    margin-bottom: 0.75rem;
}

.stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: #172B4D;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* ====== Info Groups ====== */
.info-group {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    border-radius: 0.75rem;
    background: #f8f9fa;
    height: 100%;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.info-group:hover {
    background: #e9ecef;
    transform: translateX(4px);
    border-color: #667eea;
}

.info-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    color: #172B4D;
    font-weight: 600;
}

/* ====== System Info Box ====== */
.system-info-box {
    padding: 1.5rem;
    border-radius: 0.75rem;
    background: white;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.system-info-box:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.system-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.system-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.system-value {
    font-size: 1rem;
    color: #172B4D;
    font-weight: 600;
    margin-top: 0.25rem;
}

/* ====== Social Links ====== */
.social-links .btn {
    transition: all 0.3s ease;
}

.social-links .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* ====== Button Styles ====== */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #65408b 100%);
}

/* ====== Card Enhancements ====== */
.card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.card-header {
    padding: 1.25rem 1.5rem;
    border-bottom: none;
}

/* ====== Badge Styling ====== */
.badge {
    font-weight: 500;
    border-radius: 0.375rem;
}

/* ====== Responsive ====== */
@media (max-width: 1199.98px) {
    .profile-cover {
        height: 160px;
    }
    
    .profile-avatar-wrapper {
        margin-top: -80px;
    }
    
    .avatar-img {
        width: 150px;
        height: 150px;
    }
}

@media (max-width: 767.98px) {
    .profile-cover {
        height: 120px;
    }
    
    .profile-avatar-wrapper {
        margin-top: -60px;
    }
    
    .avatar-img {
        width: 120px;
        height: 120px;
        border-width: 4px;
    }
    
    .avatar-overlay i {
        font-size: 1.5rem;
    }
    
    .info-group {
        padding: 1rem;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
    
    .system-info-box {
        padding: 1rem;
    }
    
    .system-icon {
        width: 40px;
        height: 40px;
        font-size: 1.25rem;
    }
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
                        confirmButtonColor: '#667eea'
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
                        confirmButtonColor: '#667eea'
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
                            confirmButtonColor: '#667eea'
                        }).then(() => {
                            // Reload page to update avatar everywhere
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message,
                            confirmButtonColor: '#667eea'
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
                        confirmButtonColor: '#667eea'
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
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2"></i>Mật khẩu hiện tại
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="form-control" 
                                   placeholder="Nhập mật khẩu hiện tại"
                                   required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="new_password" class="form-label fw-semibold">
                                <i class="fas fa-key me-2"></i>Mật khẩu mới
                            </label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   class="form-control" 
                                   placeholder="Nhập mật khẩu mới"
                                   required>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Tối thiểu 8 ký tự
                            </small>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="new_password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-2"></i>Xác nhận mật khẩu mới
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
                width: '550px',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save me-2"></i> Lưu thay đổi',
                cancelButtonText: '<i class="fas fa-times me-2"></i> Hủy',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'px-4',
                    cancelButton: 'px-4'
                },
                preConfirm: () => {
                    const form = document.getElementById('form-change-password');
                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('new_password_confirmation').value;
                    
                    if (newPassword !== confirmPassword) {
                        Swal.showValidationMessage('Mật khẩu xác nhận không khớp');
                        return false;
                    }
                    
                    if (newPassword.length < 8) {
                        Swal.showValidationMessage('Mật khẩu phải có ít nhất 8 ký tự');
                        return false;
                    }
                    
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-change-password').submit();
                }
            });
        });
    }
});
</script>
@endpush