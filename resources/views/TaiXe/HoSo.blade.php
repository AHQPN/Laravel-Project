@extends('layouts.TaiXeLayout')

@section('title', 'Hồ sơ cá nhân')
@section('page-title', 'Thông tin tài khoản')

@section('content')
    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-xl-4">
            <div class="driver-card text-center bg-white shadow-sm border-0">
                <div class="mb-3 position-relative d-inline-block">
                    <img src="{{ $taixe->hinhanh ? asset('storage/' . $taixe->hinhanh) : 'https://ui-avatars.com/api/?name=' . urlencode($taixe->ten) . '&background=0d6efd&color=fff&size=200' }}" 
                         alt="Avatar tài xế" 
                         class="rounded-circle border border-3 border-light shadow-sm"
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <div class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" 
                         style="width: 20px; height: 20px; border-width: 3px !important;"></div>
                </div>
                
                <h5 class="fw-bold text-dark mb-1">{{ $taixe->ten }}</h5>
                <p class="text-muted small mb-3">{{ $taixe->chucvu->chucvu ?? 'Tài xế' }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-light text-dark border fw-normal">
                        <i class="fas fa-id-badge me-1 text-secondary"></i> {{ $taixe->manv }}
                    </span>
                    @if($taixe->trangthai == 1)
                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-normal">
                            Đang hoạt động
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fw-normal">
                            Ngưng hoạt động
                        </span>
                    @endif
                </div>
                
                <button id="btn-change-password" class="btn btn-outline-primary w-100 btn-sm">
                    <i class="fas fa-key me-2"></i> Đổi mật khẩu
                </button>
            </div>
        </div>

        {{-- Information Card --}}
        <div class="col-xl-8">
            <div class="driver-card bg-white shadow-sm border-0">
                <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">
                    Thông tin chi tiết
                </h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Mã nhân viên</small>
                            <span class="fw-bold text-dark">{{ $taixe->manv }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Số điện thoại</small>
                            <span class="fw-bold text-dark">{{ $taixe->sdt ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Email</small>
                            <span class="fw-bold text-dark">{{ $taixe->email ?? '--' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Chức vụ</small>
                            <span class="fw-bold text-dark">{{ $taixe->chucvu->chucvu ?? 'Tài xế' }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="d-block text-muted text-uppercase mb-1" style="font-size: 0.7rem;">Địa chỉ</small>
                            <span class="fw-bold text-dark">{{ $taixe->diachi ?? '--' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('btn-change-password').addEventListener('click', function () {
        Swal.fire({
            title: '<strong>Đổi mật khẩu</strong>',
            html: `
                <form id="change-password-form" action="{{ route('tai-xe.ho-so.password') }}" method="POST">
                    @csrf
                    <div class="mb-3 text-start">
                        <label for="mat_khau_hien_tai" class="form-label small fw-bold text-secondary">Mật khẩu hiện tại</label>
                        <input type="password" name="mat_khau_hien_tai" id="mat_khau_hien_tai" 
                               class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="mat_khau_moi" class="form-label small fw-bold text-secondary">Mật khẩu mới</label>
                        <input type="password" name="mat_khau_moi" id="mat_khau_moi" 
                               class="form-control" placeholder="Nhập mật khẩu mới" required minlength="6">
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Tối thiểu 6 ký tự</small>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="mat_khau_moi_confirmation" class="form-label small fw-bold text-secondary">Xác nhận mật khẩu</label>
                        <input type="password" name="mat_khau_moi_confirmation" id="mat_khau_moi_confirmation" 
                               class="form-control" placeholder="Nhập lại mật khẩu mới" required>
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
                const form = Swal.getPopup().querySelector('#change-password-form');
                const newPassword = document.getElementById('mat_khau_moi').value;
                const confirmPassword = document.getElementById('mat_khau_moi_confirmation').value;
                
                if (!form.checkValidity()) {
                    Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin.');
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
