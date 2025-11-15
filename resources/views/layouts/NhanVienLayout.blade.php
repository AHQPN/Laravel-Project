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
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #2dce89;
            --secondary-color: #2dcecc;
            --sidebar-bg: #172b4d;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fe;
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
            color: #fff;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .layout-sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 12px 24px;
            border-radius: 12px;
        }

        .layout-sidebar .nav-link i {
            width: 22px;
            text-align: center;
            font-size: 1rem;
        }

        .layout-sidebar .nav-link.active,
        .layout-sidebar .nav-link:hover {
            background: linear-gradient(87deg, var(--primary-color), var(--secondary-color));
            color: #fff;
        }

        .layout-sidebar .nav-link.active i {
            color: #fff;
        }

        .layout-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .layout-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .layout-content {
            padding: 30px;
            flex: 1;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 2rem 0 rgba(136,152,170,.15);
            margin-bottom: 30px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .table thead th {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .bg-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
