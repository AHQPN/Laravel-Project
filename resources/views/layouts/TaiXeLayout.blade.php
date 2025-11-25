<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tài xế') - Hệ thống đặt vé</title>

    <!-- Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">

    <!-- External Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>

    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        :root {
            --header-height: 60px;
            --primary-color: #00796b;
            --secondary-color: #607d8b;
            --bottom-nav-height: 64px;
            --body-bg: #f5f7fa;
            --card-border-color: #e0e0e0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--body-bg);
            margin: 0;
            padding-bottom: calc(var(--bottom-nav-height) + 20px);
            color: #212529;
        }

        .layout-header {
            height: var(--header-height);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #dee2e6;
        }

        .layout-header .header-title {
            font-size: 18px;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        .layout-content {
            padding: 16px;
        }

        .driver-card {
            border-radius: 8px;
            background: white;
            border: 1px solid var(--card-border-color);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 16px;
            margin-bottom: 16px;
        }

        .driver-card h3 {
            font-size: 16px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 8px;
        }

        .driver-card .meta {
            font-size: 13px;
            color: #6c757d;
        }

        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--bottom-nav-height);
            background: white;
            border-top: 1px solid #dee2e6;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            z-index: 1030;
        }

        .mobile-nav__item {
            text-decoration: none;
            color: #6c757d;
            font-size: 11px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px;
            transition: all 0.2s ease;
        }

        .mobile-nav__item i {
            font-size: 20px;
            margin-bottom: 2px;
        }

        .mobile-nav__item:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .mobile-nav__item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .layout-content {
                padding: 24px;
                max-width: 800px;
                margin: 0 auto;
            }
            
            .driver-card {
                padding: 24px;
            }
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Route Badge Styles */
        .route-display {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            flex-wrap: nowrap;
        }
        .route-badge {
            display: inline-block;
            padding: 0.3rem 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8125rem;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }
        .route-from {
            background-color: #e0f2f1;
            color: #00695c;
            border: 1px solid #80cbc4;
        }
        .route-to {
            background-color: #fce4ec;
            color: #c2185b;
            border: 1px solid #f48fb1;
        }
        .route-arrow {
            color: #6c757d;
            font-size: 0.75rem;
            flex-shrink: 0;
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $taiXe = session('taixe');
        $navItems = [
            [
                'label' => 'Chuyến hôm nay',
                'icon' => 'fas fa-route',
                'url' => route('tai-xe.chuyen-hom-nay'),
                'active' => request()->routeIs('tai-xe.chuyen-hom-nay'),
            ],
            [
                'label' => 'Hành khách',
                'icon' => 'fas fa-users',
                'url' => route('tai-xe.hanh-khach'),
                'active' => request()->routeIs('tai-xe.hanh-khach*'),
            ],
            [
                'label' => 'Cá nhân',
                'icon' => 'fas fa-user',
                'url' => route('tai-xe.ho-so'),
                'active' => request()->routeIs('tai-xe.ho-so*'),
            ],
        ];
    @endphp

    @include('layouts.components.Header', [
        'title' => trim($__env->yieldContent('page-title', 'Bảng điều khiển tài xế')),
        'userName' => $taiXe->ten ?? 'Tài xế',
        'userRole' => 'Tài xế',
        'avatarText' => strtoupper(mb_substr($taiXe->ten ?? 'TX', 0, 1, 'UTF-8')),
        'logoutRoute' => route('tai-xe.dang-xuat'),
    ])

    <main class="layout-content">
        @yield('content')
    </main>

    @include('layouts.components.Navbar', ['items' => $navItems])

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('js/table-sort.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    @include('layouts.components.Toast')
    @include('layouts.components.Modal')
    @stack('scripts')
</body>
</html>

