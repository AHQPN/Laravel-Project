@extends('layouts.khach')

@section('content')
    <style>
        .edit-profile-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .edit-header {
            background: white;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border-bottom: 3px solid #f97019;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-header h2 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
        }

        .edit-header h2 i {
            color: #f97019;
            margin-right: 0.5rem;
        }

        .btn-back {
            background: white;
            border: 2px solid #dee2e6;
            color: #495057;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            border-color: #f97019;
            color: #f97019;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f8f9fa;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: #f97019;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: #f97019;
        }

        .form-label .required {
            color: #dc3545;
        }

        .form-input,
        .form-select {
            padding: 0.75rem 1rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #f97019;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(249, 112, 25, 0.15);
        }

        .form-input.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-submit {
            flex: 1;
            background: #f97019;
            color: white;
            border: none;
            padding: 0.95rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #e5640f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 112, 25, 0.3);
        }

        .btn-cancel {
            flex: 1;
            background: white;
            color: #6c757d;
            border: 2px solid #dee2e6;
            padding: 0.95rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
        }

        .btn-cancel:hover {
            border-color: #6c757d;
            color: #495057;
        }

        .password-input-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 1.25rem;
        }

        .toggle-password:hover {
            color: #f97019;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 1rem 1.25rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }

        .info-box i {
            color: #0d6efd;
            margin-right: 0.5rem;
        }

        .info-box p {
            margin: 0;
            color: #0c5aa6;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .edit-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>

    <div class="edit-profile-container">
        <div class="container">
            <!-- Header -->
            <div class="edit-header">
                <h2>
                    <i class="bi bi-pencil-square"></i>
                    Chỉnh sửa thông tin cá nhân
                </h2>
                <a href="{{ route('customer.profile') }}" class="btn-back">
                    <i class="bi bi-arrow-left"></i>
                    Quay lại
                </a>
            </div>

            <div class="form-container">
                <!-- Personal Information Form -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="bi bi-person-fill"></i>
                        Thông tin cá nhân
                    </div>

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person-badge"></i>
                                    Họ và tên
                                    <span class="required">*</span>
                                </label>
                                <input type="text" name="ten" class="form-input @error('ten') is-invalid @enderror"
                                    value="{{ old('ten', $customer->ten) }}" required>
                                @error('ten')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-telephone"></i>
                                    Số điện thoại
                                    <span class="required">*</span>
                                </label>
                                <input type="tel" name="sdt" class="form-input @error('sdt') is-invalid @enderror"
                                    value="{{ old('sdt', $customer->sdt) }}" required>
                                @error('sdt')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email
                                    <span class="required">*</span>
                                </label>
                                <input type="email" name="email" class="form-input @error('email') is-invalid @enderror"
                                    value="{{ old('email', $customer->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-calendar"></i>
                                    Ngày sinh
                                </label>
                                <input type="date" name="ngaysinh"
                                    class="form-input @error('ngaysinh') is-invalid @enderror"
                                    value="{{ old('ngaysinh', $customer->ngaysinh ? $customer->ngaysinh->format('Y-m-d') : '') }}">
                                @error('ngaysinh')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-gender-ambiguous"></i>
                                    Giới tính
                                </label>
                                <select name="gioitinh" class="form-select @error('gioitinh') is-invalid @enderror">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" {{ old('gioitinh', $customer->gioitinh) == 'Nam' ? 'selected' : '' }}>
                                        Nam</option>
                                    <option value="Nữ" {{ old('gioitinh', $customer->gioitinh) == 'Nữ' ? 'selected' : '' }}>Nữ
                                    </option>
                                    <option value="Khác" {{ old('gioitinh', $customer->gioitinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gioitinh')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt"></i>
                                    Địa chỉ
                                </label>
                                <input type="text" name="diachi" class="form-input @error('diachi') is-invalid @enderror"
                                    value="{{ old('diachi', $customer->diachi) }}"
                                    placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">
                                @error('diachi')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle me-2"></i>
                                Lưu thay đổi
                            </button>
                            <a href="{{ route('customer.profile') }}" class="btn-cancel">
                                <i class="bi bi-x-circle me-2"></i>
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="form-card">
                    <div class="form-section-title">
                        <i class="bi bi-shield-lock"></i>
                        Đổi mật khẩu
                    </div>

                    <div class="info-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <p>Mật khẩu phải có ít nhất 6 ký tự để đảm bảo an toàn.</p>
                    </div>

                    <form action="{{ route('customer.profile.password') }}" method="POST">
                        @csrf

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">
                                    <i class="bi bi-key"></i>
                                    Mật khẩu hiện tại
                                    <span class="required">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" name="current_password"
                                        class="form-input @error('current_password') is-invalid @enderror"
                                        id="current_password">
                                    <button type="button" class="toggle-password"
                                        onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-key-fill"></i>
                                    Mật khẩu mới
                                    <span class="required">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" name="new_password"
                                        class="form-input @error('new_password') is-invalid @enderror" id="new_password">
                                    <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-check2-circle"></i>
                                    Xác nhận mật khẩu mới
                                    <span class="required">*</span>
                                </label>
                                <div class="password-input-wrapper">
                                    <input type="password" name="new_password_confirmation" class="form-input"
                                        id="new_password_confirmation">
                                    <button type="button" class="toggle-password"
                                        onclick="togglePassword('new_password_confirmation')">
                                        <i class="bi bi-eye" id="new_password_confirmation_icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-shield-check me-2"></i>
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endsection