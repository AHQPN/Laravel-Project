<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/index.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/Trip.css') }}" rel="stylesheet" />
</head>

<body>
    <div class="wrapper container-fluid">
        <header>
            <div class="container-fluid">
                <div class="row justify-content-between">
                    <a class="d-block col-auto" href="{{ route('home.index') }}">
                        <img src="https://futabus.vn/_next/static/media/logo_banner_mb.6e0db6f9.svg" alt="" width="150">
                    </a>

                    @if (session('UserID'))
                        <div class="col-auto">
                            <div class="d-inline-block position-relative user-menu-wrapper">
                                <span class="dropdown-toggle" id="userDropdown"
                                    style="cursor: pointer; color: white; font-weight: bold; padding: 10px 15px; background: rgba(255,255,255,0.2); border-radius: 25px; display: inline-block; transition: all 0.3s ease;">
                                    <i class="bi bi-person-circle me-2"></i>
                                    {{ session('UserName', 'Tài khoản') }}
                                </span>
                                <ul class="dropdown-menu-custom">
                                    <li>
                                        <a class="dropdown-item-custom" href="#">
                                            <i class="bi bi-person-fill"></i>
                                            Thông tin cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-custom" href="{{ route('bill.index') }}">
                                            <i class="bi bi-clock-history"></i>
                                            Lịch sử mua vé
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item-custom" href="{{ route('auth.logout') }}">
                                            <i class="bi bi-box-arrow-right"></i>
                                            Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="col-auto">
                            <button class="Login_Logout btn btn-light mt-1 " onclick="showLoginForm()">
                                <i class="bi bi-person"></i>
                                <span class="d-none d-lg-inline-block">Đăng nhập/Đăng kí</span>
                            </button>
                        </div>
                    @endif

                </div>
                <div class="row">
                    <nav class="navbar navbar-expand-lg">
                        <div class="container-fluid">

                            <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasNavbar"
                                aria-labelledby="offcanvasNavbarLabel">
                                <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body ">
                                    <ul class="navbar-nav justify-content-center flex-grow-1 ">
                                        <li class="nav-item mx-lg-3">
                                            <a class="nav-link text-white active" aria-current="page"
                                                href="{{ route('home.index') }}">Trang chủ</a>
                                        </li>

                                        <li class="nav-item mx-lg-3">
                                            <a class="nav-link text-white" href="{{ route('ticket.find') }}">Tra cứu
                                                vé</a>
                                        </li>
                                        <li class="nav-item mx-lg-3">
                                            <a class="nav-link text-white" href="{{ route('bill.index') }}">Hóa đơn</a>
                                        </li>
                                        <li class="nav-item mx-lg-3">
                                            <a class="nav-link text-white" href="#">Về chúng tôi</a>
                                        </li>

                                        <li class="nav-item login-offcanvs d-lg-none">
                                            <a class="nav-link text-white" href="#"></a>
                                        </li>
                                    </ul>

                                </div>
                            </div>

                            <button class="navbar-toggler bg-white border-0 ms-auto" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                        </div>
                    </nav>
                </div>
                <div class="banner row w-75 justify-content-center d-none d-md-block">
                    <img src="https://cdn.futabus.vn/futa-busline-web-cms-prod/2257_x_501_px_2ecaaa00d0/2257_x_501_px_2ecaaa00d0.png"
                        alt="">
                </div>
            </div>

        </header>
        <div class="content row">
            <div class="container">

                <div class=" row">
                    <div class="container p-4 my-4 border rounded-3 ticket-search-container shadow-lg">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">
                                <i class="bi bi-search"></i>
                                Tìm Kiếm Chuyến Xe
                            </h3>
                            <p class="text-muted mb-0">Đặt vé nhanh chóng và thuận tiện</p>
                        </div>

                        <form class="row g-3" id="find-trip-form" action="{{ route('trip.find') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Tái tạo logic @Html.Action("DSTinh", ...) bằng View Composer --}}
                            <div class="col-md-3">
                                <label for="fromCity" class="form-label fw-bold">
                                    <i class="bi bi-geo-alt-fill text-success"></i>
                                    Điểm đi
                                </label>
                                <select id="fromCity" name="FromCity" class="form-select info-ticket shadow-sm"
                                    required>
                                    <option value="" selected disabled>-- Chọn điểm đi --</option>
                                    @if (!empty($cities))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city }}" {{ request('FromCity') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>-- Không có dữ liệu --</option>
                                    @endif
                                </select>
                                <span class="form-message text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label for="toCity" class="form-label fw-bold">
                                    <i class="bi bi-geo-fill text-danger"></i>
                                    Điểm đến
                                </label>
                                <select id="toCity" name="ToCity" class="form-select info-ticket shadow-sm" required>
                                    <option value="" selected disabled>-- Chọn điểm đến --</option>
                                    @if (!empty($cities))
                                        @foreach ($cities as $city)
                                            <option value="{{ $city }}" {{ request('ToCity') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="form-message text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label for="date" class="form-label fw-bold">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                    Ngày đi
                                </label>
                                <input type="date" id="date" value="{{ request('txtDate', now()->format('Y-m-d')) }}"
                                    name="txtDate" class="form-control info-ticket shadow-sm" required>
                                <span class="form-message text-danger"></span>
                            </div>

                            <div class="col-md-3">
                                <label for="tickets" class="form-label fw-bold">
                                    <i class="bi bi-ticket-perforated text-warning"></i>
                                    Số vé
                                </label>
                                <select name="SoVe" id="tickets" class="form-select info-ticket shadow-sm">
                                    <option value="1" selected>1 vé</option>
                                    <option value="2">2 vé</option>
                                    <option value="3">3 vé</option>
                                    <option value="4">4 vé</option>
                                    <option value="5">5 vé</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <button class="btn btn-danger w-100 w-md-50 search-btn shadow pulse-on-hover"
                                    type="submit">
                                    <i class="bi bi-search me-2"></i>
                                    Tìm Chuyến Xe
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                @yield('content')

            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>TRUNG TÂM TỔNG ĐÀI & CSKH</h5>
                    <p class="contact-info">
                        <strong style="font-size: 24px; color: #dc3545;">1900 6067</strong><br>
                        <strong>CÔNG TY CỔ PHẦN XE KHÁCH PHƯƠNG TRANG - FUTA BUS LINES</strong><br>
                        Địa chỉ: Số 01 Tô Hiến Thành, Phường 3, Thành phố Đà Lạt, Tỉnh Lâm Đồng, Việt Nam.<br>
                        Email: <a href="mailto:hotro@futa.vn">hotro@futa.vn</a><br>
                        Điện thoại: 02838386852<br>
                        Fax: 02838386853
                    </p>
                </div>

                <div class="col-md-4">
                    <h5>TẢI APP FUTA</h5>
                    <div class="app-links">
                        <a href="#">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSADMTEo4YEurEn-gXFBOfumKYAJMviq-T9ww&s"
                                alt="Google Play" style="height: 40px; width: 100px;">
                        </a>
                        <a href="#">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQT5wbyQA1MXQWe_E1TtPb5zBklQjH_N_xVnQ&s"
                                alt="App Store" style="height: 40px; width: 100px;">
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <h5>FUTA Bus Lines</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Về chúng tôi</a></li>
                        <li>Tuyển dụng</li>
                        <li>Tin tức & Sự kiện</li>
                        <li>Mạng lưới văn phòng</li>
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <img src="https://cdn.futabus.vn/futa-busline-cms-dev/Bus_Lines_817c989817/Bus_Lines_817c989817.svg"
                        alt="FUTA Logo" style="height: 30px;">
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright-section row p-4 text-white justify-content-center align-items-center">
        <div class="col-12 text-center">
            <p class="mb-1">
                <i class="bi bi-c-circle"></i>
                {{ date('Y') }} | Bản quyền thuộc về Công ty Cổ Phần Xe khách Phương Trang - FUTA Bus Lines
            </p>
            <p class="mb-0 small">
                <i class="bi bi-person-badge"></i>
                Chịu trách nhiệm quản lý nội dung: Ông Võ Duy Thành
            </p>
        </div>
    </div>


    <div class="overlay"></div>

    <div class="login-form">
        <form id="login-form1" action="{{ route('auth.login') }}" method="post" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close-form border-0 p-0" aria-label="Close" onclick="closeLoginForm()">
                <span class="fs-2" aria-hidden="true">&times;</span>
            </button>
            <div class="header-form-text text-center co-6 fs-2">Đăng nhập</div>

            <div class="my-5">
                <input type="text" name="sdt" class="form-control-lg form-control" id="Phone-number-login"
                    placeholder="Số điện thoại" required>
                <span class="form-message"></span>
            </div>
            <div class="my-5">
                <input type="password" name="pw" class="form-control-lg form-control" id="Pw-login"
                    placeholder="Mật khẩu" required>
                <span class="form-message"></span>
            </div>

            @if (session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row justify-content-center">
                <button type="submit" class="btn btn-primary w-50" style="background-color: #f97019;">
                    Đăng nhập
                </button>
            </div>
            <div class="form-footer">
                <span>Chưa có tài khoản ?</span>
                <a href="#" class="register-link">Ấn vào đây để đăng ký</a>
            </div>
        </form>
    </div>

    <div class="register-form">
        <form action="{{ route('auth.signup') }}" id="register-form1" method="post" enctype="multipart/form-data">
            @csrf
            <button type="button" class="close-form border-0 p-0 " aria-label="Close" onclick="closeRegisterForm()">
                <span class="fs-2" aria-hidden="true">&times;</span>
            </button>
            <div class="header-form-text text-center co-6 fs-2">Đăng ký</div>

            <div class="my-3">
                <input type="text" name="ten" class="form-control" id="tenkh" placeholder="Họ Tên" required>
                <span class="form-message"></span>
            </div>
            <div class="my-3 form-group">
                <input name="sdt" type="text" class=" form-control" id="Phone-number" placeholder="Số điện thoại"
                    required>
                <span class="form-message"></span>
            </div>
            <div class="my-3 form-group">
                <input name="diachi" type="text" class=" form-control" id="Address" placeholder="Địa chỉ">
                <span class="form-message"></span>
            </div>
            <div class="my-3 form-group">
                <input name="pw" type="password" class=" form-control" id="Pw-register" placeholder="Mật khẩu" required>
                <span class="form-message"></span>
            </div>
            <div class="my-3 form-group">
                <input name="confrimed-pw" type="password" class=" form-control" id="Pw-confrim"
                    placeholder="Xác nhận mật khẩu" required>
                <span class="form-message"></span>
            </div>

            @if (session('error_register'))
                <div class="alert alert-danger text-center">
                    {{ session('error_register') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row justify-content-center">
                <button type="submit" class="btn w-50" style="background-color: #f97019;">Đăng ký</button>
            </div>
        </form>
    </div>


    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        const today = new Date().toISOString().split("T")[0];
        document.getElementById("date").setAttribute("min", today);
    </script>

    @if(session('ShowLogin'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showLoginForm();
            });
        </script>
    @endif

    @if(session('ShowRegister'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showRegisterForm();
            });
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const fromCity = document.getElementById("fromCity");
            const toCity = document.getElementById("toCity");

            function syncSelects(changedSelect, otherSelect) {
                if (!fromCity || !toCity) return; // Kiểm tra

                const selectedValue = changedSelect.value;
                for (let option of otherSelect.options) {
                    option.style.display = "block";
                }
                if (selectedValue) {
                    for (let option of otherSelect.options) {
                        if (option.value === selectedValue) {
                            option.style.display = "none";
                        }
                    }
                }
                if (otherSelect.value === selectedValue) {
                    otherSelect.value = "";
                }
            }

            if (fromCity && toCity) {
                fromCity.addEventListener("change", function () {
                    syncSelects(fromCity, toCity);
                });
                toCity.addEventListener("change", function () {
                    syncSelects(toCity, fromCity);
                });

                syncSelects(fromCity, toCity);
                syncSelects(toCity, fromCity);
            }
        });
    </script>
</body>

</html>