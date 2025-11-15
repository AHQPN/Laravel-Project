<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kiểm soát viên')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/table-custom.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>

    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    <style>
        :root {
            --sidebar-width: 220px;
            --header-height: 70px;
            --primary-color: #2dce89;
            --secondary-color: #2dcecc;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fe;
            margin: 0;
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
            height: 100vh;
            background: #172b4d;
            color: #fff;
            box-shadow: 4px 0 12px rgba(0,0,0,0.08);
        }

        .layout-sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 14px;
            border-radius: 10px;
        }

        .layout-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .layout-sidebar .nav-link.active,
        .layout-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .layout-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .layout-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #f8f9fe;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .layout-content {
            padding: 32px;
            flex: 1;
        }

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(136,152,170,0.15);
            margin-bottom: 24px;
        }

        .card-header {
            background: #fff;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            color: #32325d;
        }
    </style>

    @stack('styles')
</head>
<body>
    @php
        $kiemSoat = session('kiemsoat');
        $sidebarItems = [
            [
                'label' => 'Tổng quan',
                'icon' => 'fas fa-chart-pie',
                'url' => route('nhan-vien-kiem-soat.tong-quan'),
                'active' => request()->routeIs('nhan-vien-kiem-soat.tong-quan'),
            ],
            [
                'label' => 'Theo dõi chuyến',
                'icon' => 'fas fa-route',
                'url' => route('nhan-vien-kiem-soat.chuyen-di'),
                'active' => request()->routeIs('nhan-vien-kiem-soat.chuyen-di*'),
            ],
            [
                'label' => 'Tài xế',
                'icon' => 'fas fa-id-card',
                'url' => route('nhan-vien-kiem-soat.tai-xe'),
                'active' => request()->routeIs('nhan-vien-kiem-soat.tai-xe*'),
            ],
            [
                'label' => 'Vé theo chuyến',
                'icon' => 'fas fa-ticket-alt',
                'url' => route('nhan-vien-kiem-soat.ve'),
                'active' => request()->routeIs('nhan-vien-kiem-soat.ve*'),
            ],
            [
                'label' => 'Hồ sơ cá nhân',
                'icon' => 'fas fa-user-circle',
                'url' => route('nhan-vien-kiem-soat.ho-so'),
                'active' => request()->routeIs('nhan-vien-kiem-soat.ho-so*'),
            ],
        ];
    @endphp

    <div class="layout-shell">
        @include('layouts.components.Sidebar', [
            'brandIcon' => 'fas fa-shield-alt',
            'brandTitle' => 'Kiểm soát viên',
            'items' => $sidebarItems,
        ])

        <div class="layout-main">
            @include('layouts.components.Header', [
                'title' => trim($__env->yieldContent('page-title', 'Bảng điều khiển')),
                'userName' => $kiemSoat->ten ?? 'Kiểm soát viên',
                'userRole' => 'Kiểm soát vận hành',
                'avatarText' => strtoupper(mb_substr($kiemSoat->ten ?? 'KS', 0, 1, 'UTF-8')),
                'logoutRoute' => route('nhan-vien-kiem-soat.dang-xuat'),
            ])

            <main class="layout-content">
                @yield('content')
            </main>

            @include('layouts.components.Footer', ['text' => '© ' . now()->year . ' Bộ phận kiểm soát vận hành'])
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('js/table-sort.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    @include('layouts.components.Toast')
    @include('layouts.components.Modal')
    @stack('scripts')
</body>
</html>

