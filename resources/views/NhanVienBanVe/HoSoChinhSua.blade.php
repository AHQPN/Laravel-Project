@extends('layouts.NhanVienLayout')

@section('title', 'Chỉnh sửa thông tin')
@section('page-title', 'Chỉnh sửa thông tin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            
            <div class="card shadow-sm border-0">
                
                {{-- Card Header --}}
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-edit me-2 text-primary"></i>
                            Chỉnh sửa thông tin cá nhân
                        </h5>
                        <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Quay lại
                        </a>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-4">
                    <form action="{{ route('nhan-vien-ban-ve.ho-so.update') }}" method="POST" id="form-update-profile">
                        @csrf
                        
                        {{-- Thông tin nhân viên --}}
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-id-badge me-2"></i>
                                Thông tin nhân viên
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-input name="manv" label="Mã nhân viên" :value="session('nhanvien')->manv" readonly help="Không thể thay đổi mã nhân viên" />
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label required">Email</label>
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
                                    <label for="hoten" class="form-label required">Họ và tên</label>
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
                                    <label for="sdt" class="form-label required">Số điện thoại</label>
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
                                    <small class="text-muted">Định dạng: 10 chữ số</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Thông tin khác --}}
                        <div class="form-section">
                            <h6 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin khác
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="diachi" class="form-label">Địa chỉ</label>
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
                                    <label for="ngaysinh" class="form-label">Ngày sinh</label>
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
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('nhan-vien-ban-ve.ho-so') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Lưu thay đổi
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
/* ====== Form Section ====== */
.form-section {
    margin-bottom: 2rem;
}

.section-title {
    color: #6c757d;
    text-transform: uppercase;
    font-size: 0.875rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

/* ====== Form Controls ====== */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.form-control {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.625rem 0.875rem;
    font-size: 0.9375rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.form-control[readonly] {
    background-color: #e9ecef;
    cursor: not-allowed;
}

.form-control.is-invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.invalid-feedback {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

/* ====== Card Enhancements ====== */
.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.card-header {
    padding: 1.25rem 1.5rem;
}

/* ====== Button Styles ====== */
.btn {
    padding: 0.5rem 1.25rem;
    font-weight: 500;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* ====== Responsive ====== */
@media (max-width: 767.98px) {
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .d-flex.gap-2 {
        width: 100%;
    }
    
    .d-flex.gap-2 .btn {
        flex: 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-update-profile');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            
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
                        confirmButtonColor: '#667eea'
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
                <ul class="text-start">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonColor: '#667eea'
        });
    @endif
});
</script>
@endpush