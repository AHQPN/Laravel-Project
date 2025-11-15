@extends('layouts.TaiXeLayout')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Hồ sơ cá nhân')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-xl-4">
            <div class="card shadow-sm border-0 profile-card">
                <div class="card-body text-center p-4">
                    <div class="profile-avatar-wrapper mb-3">
                        <img src="{{ $taixe->hinhanh ? asset('storage/' . $taixe->hinhanh) : 'https://ui-avatars.com/api/?name=' . urlencode($taixe->ten) . '&background=2dce89&color=fff&size=200' }}" 
                             alt="Avatar tài xế" class="profile-avatar-img">
                        <div class="online-badge"></div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $taixe->ten }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-id-badge me-1"></i>{{ $taixe->manv }}
                    </p>
                    <span class="badge bg-gradient-success px-3 py-2 mb-4">
                        <i class="fas fa-steering-wheel me-1"></i>{{ $taixe->chucvu->chucvu ?? 'Tài xế' }}
                    </span>
                    
                    <div class="status-box mb-3">
                        @if($taixe->trangthai == 1)
                            <div class="status-active">
                                <i class="fas fa-check-circle me-2"></i>
                                <span>Đang hoạt động</span>
                            </div>
                        @else
                            <div class="status-inactive">
                                <i class="fas fa-pause-circle me-2"></i>
                                <span>Ngưng hoạt động</span>
                            </div>
                        @endif
                    </div>
                    
                    <button id="btn-change-password" class="btn btn-primary w-100">
                        <i class="fas fa-key me-2"></i>Đổi mật khẩu
                    </button>
                </div>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-primary-subtle">
                                    <i class="fas fa-id-card text-primary"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Mã nhân viên</label>
                                    <div class="info-value">{{ $taixe->manv }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-success-subtle">
                                    <i class="fas fa-phone text-success"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Số điện thoại</label>
                                    <div class="info-value">{{ $taixe->sdt ?? 'Chưa cập nhật' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-info-subtle">
                                    <i class="fas fa-envelope text-info"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Email</label>
                                    <div class="info-value">{{ $taixe->email ?? 'Chưa cập nhật' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-icon bg-warning-subtle">
                                    <i class="fas fa-briefcase text-warning"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Chức vụ</label>
                                    <div class="info-value">{{ $taixe->chucvu->chucvu ?? 'Tài xế' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-icon bg-danger-subtle">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </div>
                                <div class="info-content">
                                    <label class="info-label">Địa chỉ</label>
                                    <div class="info-value">{{ $taixe->diachi ?? 'Chưa cập nhật' }}</div>
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
.profile-card {
    border-radius: 1rem;
    overflow: hidden;
}

.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
}

.profile-avatar-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border: 4px solid #fff;
}

.online-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    background: #28a745;
    border: 3px solid white;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.status-box {
    padding: 1rem;
    border-radius: 0.5rem;
    background: #f8f9fa;
}

.status-active {
    color: #28a745;
    font-weight: 600;
    font-size: 1rem;
}

.status-inactive {
    color: #6c757d;
    font-weight: 600;
    font-size: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    border-radius: 0.75rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
    height: 100%;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    margin-bottom: 0.25rem;
    display: block;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1rem;
    color: #172B4D;
    font-weight: 600;
}

.card {
    border-radius: 1rem;
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

.card-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('btn-change-password').addEventListener('click', function () {
        Swal.fire({
            title: '<strong>Đổi mật khẩu</strong>',
            html: `
                <form id="change-password-form" action="{{ route('tai-xe.ho-so.password') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label for="mat_khau_hien_tai" class="form-label fw-semibold">
                            <i class="fas fa-lock me-2"></i>Mật khẩu hiện tại
                        </label>
                        <input type="password" name="mat_khau_hien_tai" id="mat_khau_hien_tai" 
                               class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="mat_khau_moi" class="form-label fw-semibold">
                            <i class="fas fa-key me-2"></i>Mật khẩu mới
                        </label>
                        <input type="password" name="mat_khau_moi" id="mat_khau_moi" 
                               class="form-control" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" 
                               required minlength="6">
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>Tối thiểu 6 ký tự
                        </small>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="mat_khau_moi_confirmation" class="form-label fw-semibold">
                            <i class="fas fa-check-circle me-2"></i>Xác nhận mật khẩu mới
                        </label>
                        <input type="password" name="mat_khau_moi_confirmation" id="mat_khau_moi_confirmation" 
                               class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                    </div>
                </form>
            `,
            width: '550px',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-2"></i>Cập nhật',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Hủy',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'px-4',
                cancelButton: 'px-4'
            },
            focusConfirm: false,
            preConfirm: () => {
                const form = Swal.getPopup().querySelector('#change-password-form');
                const newPassword = document.getElementById('mat_khau_moi').value;
                const confirmPassword = document.getElementById('mat_khau_moi_confirmation').value;
                
                if (!form.checkValidity()) {
                    Swal.showValidationMessage('Vui lòng nhập đầy đủ và hợp lệ.');
                    return false;
                }
                
                if (newPassword !== confirmPassword) {
                    Swal.showValidationMessage('Mật khẩu xác nhận không khớp!');
                    return false;
                }
                
                if (newPassword.length < 6) {
                    Swal.showValidationMessage('Mật khẩu phải có ít nhất 6 ký tự!');
                    return false;
                }
                
                form.submit();
            }
        });
    });
</script>
@endpush

