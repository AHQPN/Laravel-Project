<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập tài xế</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2dce89, #2dcecc);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Open Sans', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 16px 40px rgba(50, 55, 65, 0.2);
        }
        .login-card h1 {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: #2d3748;
        }
        .login-card p {
            text-align: center;
            color: #718096;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="fas fa-bus fa-3x mb-3 text-success"></i>
            <h1>Đăng nhập tài xế</h1>
            <p>Truy cập nhanh bảng điều khiển của bạn</p>
        </div>
        <form method="POST" action="{{ route('tai-xe.dang-nhap.post') }}">
            @csrf
            <x-input
                name="manv"
                label="Mã nhân viên"
                class="mb-3"
                :required="true"
            />

            <div class="mb-4">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" name="password" id="password-taixe" class="form-control @error('password') is-invalid @enderror" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-taixe', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <x-button type="submit" variant="primary" icon="fas fa-sign-in-alt" :block="true">
                Đăng nhập
            </x-button>
        </form>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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

        function showToast(message, type = 'error') {
            const backgroundColor = type === 'success'
                ? 'linear-gradient(to right, #00b09b, #96c93d)'
                : 'linear-gradient(to right, #ff5f6d, #ffc371)';

            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                style: { background: backgroundColor },
            }).showToast();
        }

        @if ($errors->any())
            showToast("{{ $errors->first() }}", 'error');
        @endif

        @if (session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</body>
</html>

