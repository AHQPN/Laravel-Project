<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Phụ xe') - Hệ thống đặt vé</title>

    <!-- Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">

    <!-- External Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        :root {
            --header-height: 64px;
            --primary-color: #f093fb;
            --secondary-color: #f5576c;
            --bottom-nav-height: 72px;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f8f9fe;
            margin: 0;
            padding-bottom: calc(var(--bottom-nav-height) + 20px);
        }

        .layout-header {
            height: var(--header-height);
            background: #f8f9fe;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .layout-header .header-title {
            font-size: 20px;
            font-weight: 600;
            color: #32325d;
            margin: 0;
        }

        .layout-header .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .layout-header .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .layout-header .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #525f7f;
            margin: 0;
        }

        .layout-header .user-role {
            font-size: 12px;
            color: #8898aa;
            margin: 0;
            text-align: right;
        }

        .layout-content {
            padding: 16px;
        }

        .phuxe-card {
            border-radius: 16px;
            background: white;
            box-shadow: 0 4px 16px rgba(136,152,170,0.15);
            padding: 16px;
            margin-bottom: 16px;
        }

        .phuxe-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #32325d;
        }

        .phuxe-card .meta {
            font-size: 14px;
            color: #5f6c7b;
        }

        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background: white;
            box-shadow: 0 -4px 16px rgba(136,152,170,0.15);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            z-index: 200;
        }

        .mobile-nav__item {
            text-decoration: none;
            color: #a0aec0;
            font-size: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
        }

        .mobile-nav__item i {
            font-size: 20px;
        }

        .mobile-nav__item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .layout-content {
                padding: 24px 32px 120px;
                max-width: 720px;
                margin: 0 auto;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $phuXe = session('phuxe');
        $navItems = [
            [
                'label' => 'Tổng quan',
                'icon' => 'fas fa-home',
                'url' => route('phu-xe.tong-quan'),
                'active' => request()->routeIs('phu-xe.tong-quan'),
            ],
            [
                'label' => 'Hành khách',
                'icon' => 'fas fa-users',
                'url' => route('phu-xe.hanh-khach'),
                'active' => request()->routeIs('phu-xe.hanh-khach*'),
            ],
            [
                'label' => 'Cá nhân',
                'icon' => 'fas fa-user',
                'url' => route('phu-xe.ho-so'),
                'active' => request()->routeIs('phu-xe.ho-so*'),
            ],
        ];
    @endphp

    @include('layouts.components.Header', [
        'title' => trim($__env->yieldContent('page-title', 'Bảng điều khiển Phụ xe')),
        'userName' => $phuXe->ten ?? 'Phụ xe',
        'userRole' => 'Phụ xe',
        'avatarText' => strtoupper(mb_substr($phuXe->ten ?? 'PX', 0, 1, 'UTF-8')),
        'logoutRoute' => route('phu-xe.dang-xuat'),
    ])

    <main class="layout-content">
        @yield('content')
    </main>

    @include('layouts.components.Navbar', ['items' => $navItems])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        // Helper function for toast notifications
        function showToast(message, type = 'success') {
            const bgColors = {
                success: 'linear-gradient(to right, #00b09b, #96c93d)',
                error: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                warning: 'linear-gradient(to right, #f093fb, #f5576c)',
            };
            Toastify({
                text: message,
                duration: 3000,
                gravity: 'top',
                position: 'right',
                stopOnFocus: true,
                style: {
                    background: bgColors[type] || bgColors.success,
                },
            }).showToast();
        }
    </script>

    @stack('scripts')
</body>
</html>
