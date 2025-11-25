<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang làm việc') - Nhân viên Bán vé</title>
    
    <!-- Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ui-fixes.css') }}">
    
    <!-- Required JS Libraries CSS -->
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Tippy.js -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>

    <!-- Custom App Style -->
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --header-height: 64px;
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --sidebar-bg: #212529;
            --sidebar-text: #e9ecef;
            --sidebar-active-bg: #0d6efd;
            --body-bg: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--body-bg);
            color: #212529;
        }

        .layout-shell {
            display: flex;
            min-height: 100vh;
        }

        .layout-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1030;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(255,255,255,0.1);
            overflow: visible;
        }

        /* Collapsed State */
        .layout-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Hover to expand when collapsed */
        .layout-sidebar.collapsed:hover {
            width: var(--sidebar-width);
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }

        /* Toggle Button */
        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: -15px;
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            border: 2px solid var(--sidebar-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .sidebar-toggle:hover {
            background: #0b5ed7;
            transform: scale(1.1);
        }

        .sidebar-toggle i {
            transition: transform 0.3s ease;
        }

        .layout-sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* Brand Section */
        .sidebar-brand {
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .layout-sidebar.collapsed .sidebar-brand-text {
            display: none;
        }

        .layout-sidebar.collapsed:hover .sidebar-brand-text {
            display: block;
        }

        /* Navigation Links */
        .layout-sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            white-space: nowrap;
            position: relative;
            transition: all 0.2s ease;
        }

        .layout-sidebar .nav-link i {
            width: 24px;
            min-width: 24px;
            text-align: center;
            font-size: 1rem;
            margin-right: 8px;
            transition: margin-right 0.3s ease;
        }

        .layout-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 10px;
        }

        .layout-sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        .layout-sidebar.collapsed .nav-link span {
            display: none;
        }

        .layout-sidebar.collapsed:hover .nav-link {
            justify-content: flex-start;
            padding: 10px 16px;
        }

        .layout-sidebar.collapsed:hover .nav-link i {
            margin-right: 8px;
        }

        .layout-sidebar.collapsed:hover .nav-link span {
            display: inline;
        }

        .layout-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }

        .layout-sidebar .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: #fff;
            font-weight: 500;
        }

        /* Section Headers */
        .sidebar-section-header {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .layout-sidebar.collapsed .sidebar-section-header {
            display: none;
        }

        .layout-sidebar.collapsed:hover .sidebar-section-header {
            display: block;
        }

        /* Main Content Area */
        .layout-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - var(--sidebar-width));
        }

        .layout-sidebar.collapsed ~ .layout-main {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        .layout-header {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
        }

        .layout-content {
            padding: 24px;
            flex: 1;
            overflow-x: hidden;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .layout-sidebar {
                transform: translateX(-100%);
            }

            .layout-sidebar.show {
                transform: translateX(0);
                width: var(--sidebar-width) !important;
            }

            .layout-sidebar.collapsed {
                transform: translateX(-100%);
            }

            .layout-main {
                margin-left: 0;
                width: 100%;
            }

            .layout-sidebar.collapsed ~ .layout-main {
                margin-left: 0;
                width: 100%;
            }

            .layout-content {
                padding: 16px;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        /* Utility Classes Overrides */
        .card {
            border: 1px solid #e0e0e0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
        
        /* Route Badge Styles */
        .route-display {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }
        .route-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100px;
        }
        .route-from {
            background-color: #e3f2fd;
            color: #1565c0;
            border: 1px solid #90caf9;
        }
        .route-to {
            background-color: #f3e5f5;
            color: #7b1fa2;
            border: 1px solid #ce93d8;
        }
        .route-arrow {
            color: #6c757d;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $nhanVien = session('nhanvien');
        $sidebarItems = [
            [
                'label' => 'Trang chủ',
                'icon' => 'fas fa-home',
                'url' => route('nhan-vien-ban-ve.tong-quan'),
                'active' => request()->routeIs('nhan-vien-ban-ve.tong-quan'),
            ],
            [
                'type' => 'header',
                'label' => 'NGHIỆP VỤ',
            ],
            [
                'label' => 'Đặt vé',
                'icon' => 'fas fa-ticket-alt',
                'url' => route('nhan-vien-ban-ve.dat-ve.create'),
                'active' => request()->routeIs('nhan-vien-ban-ve.dat-ve.*'),
            ],
            [
                'label' => 'Quản lý vé',
                'icon' => 'fas fa-tasks',
                'url' => route('nhan-vien-ban-ve.ve.index'),
                'active' => request()->routeIs('nhan-vien-ban-ve.ve.*'),
            ],
            [
                'type' => 'header',
                'label' => 'THÔNG TIN',
            ],
            [
                'label' => 'Theo dõi chuyến đi',
                'icon' => 'fas fa-road',
                'url' => route('nhan-vien-ban-ve.chuyen-di.index'),
                'active' => request()->routeIs('nhan-vien-ban-ve.chuyen-di.*'),
            ],
            [
                'label' => 'Hóa đơn',
                'icon' => 'fas fa-file-invoice',
                'url' => route('nhan-vien-ban-ve.hoa-don.index'),
                'active' => request()->routeIs('nhan-vien-ban-ve.hoa-don.*'),
            ],
            [
                'type' => 'header',
                'label' => 'TÀI KHOẢN',
            ],
            [
                'label' => 'Thông tin cá nhân',
                'icon' => 'fas fa-user-circle',
                'url' => route('nhan-vien-ban-ve.ho-so'),
                'active' => request()->routeIs('nhan-vien-ban-ve.ho-so*'),
            ],
        ];
    @endphp

    <div class="layout-shell">
        @include('layouts.components.Sidebar', [
            'brandIcon' => 'fas fa-user-tie',
            'brandTitle' => 'Nhân viên bán vé',
            'items' => $sidebarItems,
        ])

        <div class="layout-main">
            @include('layouts.components.Header', [
                'title' => trim($__env->yieldContent('page-title', 'Tổng quan')),
                'userName' => $nhanVien->ten ?? 'Nhân viên',
                'userRole' => $nhanVien->chucvu->ten_chucvu ?? 'Bán vé',
                'avatarText' => strtoupper(substr($nhanVien->ten ?? 'NV', 0, 1)),
                'logoutRoute' => route('nhan-vien-ban-ve.dang-xuat'),
            ])

            <main class="layout-content">
                @yield('content')
            </main>

            @include('layouts.components.Footer')
        </div>
    </div>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('js/table-sort.js') }}"></script>

    <!-- Required JS Libraries -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastify -->
    <!-- Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- Tippy.js -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lodash -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <!-- Day.js -->
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    
    @include('layouts.components.Toast')
    @include('layouts.components.Modal')
    @stack('scripts')
</body>
</html>
