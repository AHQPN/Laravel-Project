@extends('layouts.khach')

@section('content')
    <style>
        .profile-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .profile-header {
            background: linear-gradient(135deg, #f97019 0%, #e5640f 100%);
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 15px rgba(249, 112, 25, 0.3);
        }

        .profile-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .profile-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        .profile-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .avatar-section {
            text-align: center;
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f97019 0%, #e5640f 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 3rem;
            color: white;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(249, 112, 25, 0.3);
        }

        .avatar-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .avatar-id {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f97019;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .action-btn-primary {
            background: #f97019;
            color: white;
        }

        .action-btn-primary:hover {
            background: #e5640f;
            transform: translateX(5px);
            color: white;
        }

        .action-btn-secondary {
            background: white;
            color: #495057;
            border: 2px solid #dee2e6;
        }

        .action-btn-secondary:hover {
            border-color: #f97019;
            color: #f97019;
            transform: translateX(5px);
        }

        .info-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f97019;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #f97019;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-label i {
            color: #f97019;
            font-size: 1rem;
        }

        .info-value {
            font-size: 1rem;
            color: #2c3e50;
            font-weight: 600;
            padding-left: 1.5rem;
        }

        .info-value.empty {
            color: #adb5bd;
            font-style: italic;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        @media (max-width: 992px) {
            .profile-content {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="profile-container">
        <div class="container">
            <!-- Header -->
            <div class="profile-header">
                <h2>
                    <i class="bi bi-person-circle me-2"></i>
                    Thông tin cá nhân
                </h2>
                <p>Quản lý thông tin tài khoản của bạn</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="profile-content">
                <!-- Sidebar -->
                <div class="profile-sidebar">
                    <!-- Avatar Card -->
                    <div class="profile-card avatar-section">
                        <div class="avatar-circle">
                            {{ strtoupper(substr($customer->ten, 0, 1)) }}
                        </div>
                        <div class="avatar-name">{{ $customer->ten }}</div>
                        <div class="avatar-id">ID: {{ $customer->makh }}</div>

                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $totalBookings }}</div>
                                <div class="stat-label">Vé đã đặt</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ number_format($totalSpent / 1000, 0) }}K</div>
                                <div class="stat-label">Tổng chi tiêu</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="profile-card">
                        <div class="section-title">
                            <i class="bi bi-lightning-fill"></i>
                            Thao tác nhanh
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('customer.profile.edit') }}" class="action-btn action-btn-primary">
                                <i class="bi bi-pencil-square"></i>
                                Chỉnh sửa thông tin
                            </a>
                            <a href="{{ route('bill.index') }}" class="action-btn action-btn-secondary">
                                <i class="bi bi-clock-history"></i>
                                Lịch sử mua vé
                            </a>
                            <a href="{{ route('home.index') }}" class="action-btn action-btn-secondary">
                                <i class="bi bi-search"></i>
                                Đặt vé mới
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Thông tin chi tiết
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-person-fill"></i>
                                Họ và tên
                            </div>
                            <div class="info-value">{{ $customer->ten }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-telephone-fill"></i>
                                Số điện thoại
                            </div>
                            <div class="info-value">{{ $customer->sdt }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-envelope-fill"></i>
                                Email
                            </div>
                            <div class="info-value">{{ $customer->email }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-calendar-fill"></i>
                                Ngày sinh
                            </div>
                            <div class="info-value {{ !$customer->ngaysinh ? 'empty' : '' }}">
                                {{ $customer->ngaysinh ? $customer->ngaysinh->format('d/m/Y') : 'Chưa cập nhật' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-gender-ambiguous"></i>
                                Giới tính
                            </div>
                            <div class="info-value {{ !$customer->gioitinh ? 'empty' : '' }}">
                                {{ $customer->gioitinh ?? 'Chưa cập nhật' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="bi bi-geo-alt-fill"></i>
                                Địa chỉ
                            </div>
                            <div class="info-value {{ !$customer->diachi ? 'empty' : '' }}">
                                {{ $customer->diachi ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection