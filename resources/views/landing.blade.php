<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Quản Lý Xe Khách - Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .role-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .role-card:hover {
            border-color: var(--primary-color);
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .role-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .role-info {
            flex: 1;
        }

        .role-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .role-desc {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        .role-arrow {
            font-size: 20px;
            color: #9ca3af;
        }

        /* Role-specific colors */
        .role-admin .role-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .role-banve .role-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .role-taixe .role-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .role-kiemsoat .role-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .login-footer {
            padding: 20px 30px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .login-footer p {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
        }

        @media (max-width: 576px) {
            .login-header h1 {
                font-size: 24px;
            }
            
            .role-card {
                padding: 15px;
            }
            
            .role-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .role-title {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="bi bi-bus-front"></i> Website Xe Khách</h1>
                <p>Hệ thống quản lý vận tải hành khách</p>
            </div>

            <div class="login-body">
                <h5 class="mb-4 text-center text-muted">Chọn vai trò để đăng nhập</h5>

                <a href="{{ route('home.index') }}" class="role-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
                    <div class="role-icon" style="background: rgba(255, 255, 255, 0.3);">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="role-info">
                        <div class="role-title" style="color: white;">Khách Hàng</div>
                        <p class="role-desc" style="color: rgba(255, 255, 255, 0.9);">Tìm chuyến, đặt vé, tra cứu</p>
                    </div>
                    <div class="role-arrow" style="color: white;">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('quan-ly.dang-nhap') }}" class="role-card role-admin">
                    <div class="role-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="role-info">
                        <div class="role-title">Quản Lý</div>
                        <p class="role-desc">Quản trị hệ thống, thống kê, báo cáo</p>
                    </div>
                    <div class="role-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('nhan-vien-ban-ve.dang-nhap') }}" class="role-card role-banve">
                    <div class="role-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <div class="role-info">
                        <div class="role-title">Nhân Viên Bán Vé</div>
                        <p class="role-desc">Đặt vé, quản lý vé, theo dõi chuyến đi</p>
                    </div>
                    <div class="role-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

                <a href="{{ route('tai-xe.dang-nhap') }}" class="role-card role-taixe">
                    <div class="role-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="role-info">
                        <div class="role-title">Tài Xế</div>
                        <p class="role-desc">Quản lý chuyến đi, hành khách, báo cáo</p>
                    </div>
                    <div class="role-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>

            </div>

            <div class="login-footer">
                <p>&copy; 2025 Website Quản Lý Xe Khách. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
