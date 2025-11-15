@extends('layouts.PhuXeLayout')

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
                        <div class="profile-avatar" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                            {{ strtoupper(mb_substr($phuxe->ten, 0, 1, 'UTF-8')) }}
                        </div>
                        <div class="online-badge"></div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $phuxe->ten }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-id-badge me-1"></i>{{ $phuxe->manv }}
                    </p>
                    <span class="badge bg-gradient-primary px-3 py-2 mb-4">
                        <i class="fas fa-user-friends me-1"></i>Phụ xe
                    </span>
                    
                    <div class="status-box mb-3">
                        @if($phuxe->trangthai == 1)
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
                </div>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white">
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
                                    <div class="info-value">{{ $phuxe->manv }}</div>
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
                                    <div class="info-value">{{ $phuxe->sdt ?? 'Chưa cập nhật' }}</div>
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
                                    <div class="info-value">{{ $phuxe->email ?? 'Chưa cập nhật' }}</div>
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
                                    <div class="info-value">Phụ xe</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password Change Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </h5>
                        <button class="btn btn-sm btn-light" type="button" id="togglePasswordForm">
                            <i class="fas fa-chevron-down me-1"></i>
                            <span class="toggle-text">Hiển thị</span>
                        </button>
                    </div>
                </div>

                <div class="card-body p-4" id="passwordFormContainer" style="display: none;">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('phu-xe.ho-so.password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1"></i>Mật khẩu hiện tại
                            </label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu hiện tại" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label fw-semibold">
                                <i class="fas fa-key me-1"></i>Mật khẩu mới
                            </label>
                            <input type="password" name="new_password" id="new_password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 6 ký tự</small>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-1"></i>Xác nhận mật khẩu mới
                            </label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                   class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-save me-2"></i>Cập nhật mật khẩu
                        </button>
                    </form>
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

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: white;
    margin: 0 auto;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    position: relative;
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

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('togglePasswordForm').addEventListener('click', function() {
    const container = document.getElementById('passwordFormContainer');
    const icon = this.querySelector('i');
    const text = this.querySelector('.toggle-text');
    
    if (container.style.display === 'none') {
        container.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        text.textContent = 'Ẩn';
    } else {
        container.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        text.textContent = 'Hiển thị';
    }
});

// Nếu có lỗi validation, tự động hiển thị form
@if($errors->any() || session('error') || session('success'))
    document.getElementById('passwordFormContainer').style.display = 'block';
    document.getElementById('togglePasswordForm').querySelector('i').classList.remove('fa-chevron-down');
    document.getElementById('togglePasswordForm').querySelector('i').classList.add('fa-chevron-up');
    document.getElementById('togglePasswordForm').querySelector('.toggle-text').textContent = 'Ẩn';
@endif
</script>
@endpush
