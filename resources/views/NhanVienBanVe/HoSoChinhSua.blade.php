@extends('layouts.NhanVienLayout')

@section('title', 'Chỉnh sửa thông tin')
@section('page-title', 'Cập nhật hồ sơ')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            
            <div class="card border shadow-sm">
                
                {{-- Card Header --}}
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            Chỉnh sửa thông tin cá nhân
                        </h6>
                        <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="btn btn-sm btn-light border text-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">
                    <form action="{{ route('nhan-vien-ban-ve.ho-so.update') }}" method="POST" id="form-update-profile">
                        @csrf
                        
                        {{-- Thông tin nhân viên --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">
                                Thông tin cơ bản
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-secondary">Mã nhân viên</label>
                                    <input type="text" class="form-control bg-light" value="{{ session('nhanvien')->manv }}" readonly>
                                    <div class="form-text small">Không thể thay đổi mã nhân viên</div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label small fw-bold text-secondary">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', session('nhanvien')->email) }}"
                                           placeholder="example@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="hoten" class="form-label small fw-bold text-secondary">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           id="hoten" 
                                           name="hoten" 
                                           class="form-control @error('hoten') is-invalid @enderror" 
                                           value="{{ old('hoten', session('nhanvien')->ten) }}"
                                           placeholder="Nguyễn Văn A"
                                           required>
                                    @error('hoten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="sdt" class="form-label small fw-bold text-secondary">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           id="sdt" 
                                           name="sdt" 
                                           class="form-control @error('sdt') is-invalid @enderror" 
                                           value="{{ old('sdt', session('nhanvien')->sdt) }}"
                                           placeholder="0901234567"
                                           pattern="[0-9]{10}"
                                           required>
                                    @error('sdt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Thông tin khác --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3 border-bottom pb-2">
                                Thông tin bổ sung
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="diachi" class="form-label small fw-bold text-secondary">Địa chỉ</label>
                                    <input type="text" 
                                           id="diachi" 
                                           name="diachi" 
                                           class="form-control @error('diachi') is-invalid @enderror" 
                                           value="{{ old('diachi', session('nhanvien')->diachi) }}"
                                           placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">
                                    @error('diachi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="ngaysinh" class="form-label small fw-bold text-secondary">Ngày sinh</label>
                                    <input type="date" 
                                           id="ngaysinh" 
                                           name="ngaysinh" 
                                           class="form-control @error('ngaysinh') is-invalid @enderror" 
                                           value="{{ old('ngaysinh', \Carbon\Carbon::parse(session('nhanvien')->ngaysinh)->format('Y-m-d')) }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('ngaysinh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="btn btn-light border text-secondary">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
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
.form-label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
.form-control {
    border-radius: 4px;
    border-color: #ced4da;
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}
.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-update-profile');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            
            // Validate phone number
            const sdt = document.getElementById('sdt');
            if (sdt && sdt.value) {
                const phoneRegex = /^[0-9]{10}$/;
                if (!phoneRegex.test(sdt.value)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Số điện thoại phải có đúng 10 chữ số',
                        confirmButtonColor: '#0d6efd'
                    });
                    sdt.focus();
                    return false;
                }
            }
            
            // Show loading
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng đợi',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }
    
    // Show validation errors if any
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Lỗi xác thực',
            html: `
                <ul class="text-start small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#0d6efd'
        });
    @endif
});
</script>
@endpush