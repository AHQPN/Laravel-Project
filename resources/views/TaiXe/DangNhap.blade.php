<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập tài xế</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fe;
            font-family: 'Open Sans', sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 2rem 0 rgba(136,152,170,.15);
        }
        .login-header {
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
            color: white;
            text-align: center;
            padding: 2rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .login-header i {
            font-size: 4rem;
        }
        .login-header h3 {
            margin-top: 1rem;
            font-weight: 600;
        }
        .form-control {
            height: 50px;
            border-radius: .5rem;
        }
        .btn-login {
            height: 50px;
            border-radius: .5rem;
            background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
            border: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="login-header">
                <i class="fas fa-bus"></i>
                <h3>Tài xế</h3>
            </div>
            <div class="card-body p-4 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-2">Lỗi đăng nhập!</strong>
                                @foreach ($errors->all() as $error)
                                    <p class="mb-1">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-2">Lỗi!</strong>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{ route('tai-xe.dang-nhap.post') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="manv" class="form-label">Mã nhân viên</label>
                        <input type="text" class="form-control" id="manv" name="manv" value="{{ old('manv') }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password-taixe" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-taixe', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login">Đăng nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword(fieldId, button) {
        const field = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto-hide error alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('alert-danger')) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3000);
            }
        });
    });
    </script>
</body>
</html>

