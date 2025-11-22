@extends('layouts.khach')

@section('content')

    @if (session('message'))
        <div id="mes" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-3 rounded-3 shadow text-white fw-bold"
            role="alert" aria-live="assertive" aria-atomic="true"
            style="z-index: 9999; min-width: 350px; text-align:center; background-color:#dc3545; transition: opacity 0.5s ease; opacity: 1;">
            {!! session('message') !!}
        </div>

        <script>
            const mes = document.getElementById('mes');
            if (mes) {
                setTimeout(() => {
                    mes.style.opacity = '0';
                    setTimeout(() => mes.remove(), 500);
                }, 2500);
            }
        </script>
    @endif

    <!-- Hero Welcome Section -->
    <div class="hero-welcome-section text-center my-5 animate-fade-in">
        <h1 class="display-4 fw-bold text-primary mb-3">
            <i class="bi bi-bus-front text-warning"></i>
            Chào Mừng Đến Với FUTA Bus Lines
        </h1>
        <p class="lead text-muted">Hệ thống vận chuyển hành khách uy tín hàng đầu Việt Nam</p>
        <div class="hero-stats d-flex justify-content-center gap-5 mt-4 flex-wrap">
            <div class="stat-item animate-slide-up" style="animation-delay: 0.1s">
                <h3 class="text-warning fw-bold"><i class="bi bi-building"></i> 200+</h3>
                <p class="text-muted">Văn phòng</p>
            </div>
            <div class="stat-item animate-slide-up" style="animation-delay: 0.2s">
                <h3 class="text-warning fw-bold"><i class="bi bi-bus-front-fill"></i> 1000+</h3>
                <p class="text-muted">Xe khách</p>
            </div>
            <div class="stat-item animate-slide-up" style="animation-delay: 0.3s">
                <h3 class="text-warning fw-bold"><i class="bi bi-people-fill"></i> 5M+</h3>
                <p class="text-muted">Khách hàng/năm</p>
            </div>
        </div>
    </div>

    <!-- Service Features Section -->
    <div class="service-features my-5">
        <h2 class="text-center mb-4 fw-bold">
            <i class="bi bi-star-fill text-warning"></i>
            Dịch Vụ Nổi Bật
        </h2>
        <div class="row g-4">
            <div class="col-md-4 animate-slide-up" style="animation-delay: 0.1s">
                <div class="feature-card text-center p-4 h-100 border rounded-3 shadow-sm hover-lift">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-shield-check text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">An Toàn Tuyệt Đối</h5>
                    <p class="text-muted">Đội ngũ tài xế chuyên nghiệp, xe được bảo dưỡng định kỳ, đảm bảo an toàn tối đa
                        cho hành khách</p>
                </div>
            </div>
            <div class="col-md-4 animate-slide-up" style="animation-delay: 0.2s">
                <div class="feature-card text-center p-4 h-100 border rounded-3 shadow-sm hover-lift">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-clock-history text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">Đúng Giờ</h5>
                    <p class="text-muted">Cam kết khởi hành đúng lịch trình, tiết kiệm thời gian quý báu của bạn</p>
                </div>
            </div>
            <div class="col-md-4 animate-slide-up" style="animation-delay: 0.3s">
                <div class="feature-card text-center p-4 h-100 border rounded-3 shadow-sm hover-lift">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-emoji-smile text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold">Tiện Nghi Cao Cấp</h5>
                    <p class="text-muted">Ghế massage, điều hòa hiện đại, WiFi miễn phí, nước uống và khăn lạnh phục vụ suốt
                        hành trình</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="why-choose-us my-5 p-5 bg-light rounded-3">
        <h2 class="text-center mb-4 fw-bold">
            <i class="bi bi-award-fill text-danger"></i>
            Tại Sao Chọn FUTA?
        </h2>
        <div class="row g-3">
            <div class="col-md-6 animate-slide-left">
                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Giá vé cạnh tranh</h6>
                        <p class="text-muted mb-0">Cam kết giá tốt nhất thị trường với nhiều chương trình ưu đãi hấp dẫn</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-slide-right">
                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Đặt vé online dễ dàng</h6>
                        <p class="text-muted mb-0">Giao diện thân thiện, thanh toán an toàn, nhận vé ngay lập tức</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-slide-left" style="animation-delay: 0.1s">
                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Hỗ trợ 24/7</h6>
                        <p class="text-muted mb-0">Đội ngũ CSKH luôn sẵn sàng hỗ trợ mọi lúc, mọi nơi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-slide-right" style="animation-delay: 0.1s">
                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-check-circle-fill text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Đổi trả vé linh hoạt</h6>
                        <p class="text-muted mb-0">Chính sách đổi trả vé thuận tiện, phù hợp với mọi tình huống</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="cta-section text-center my-5 p-5 bg-gradient rounded-3 text-white animate-fade-in"
        style="background: linear-gradient(135deg, #f97019 0%, #e65a32 100%);">
        <h2 class="fw-bold mb-3">Bạn Đã Sẵn Sàng Cho Chuyến Đi?</h2>
        <p class="lead mb-4">Đặt vé ngay hôm nay để nhận ưu đãi đặc biệt!</p>
        <a href="#find-trip-form" class="btn btn-light btn-lg px-5 shadow smooth-scroll">
            <i class="bi bi-search"></i> Tìm Chuyến Xe Ngay
        </a>
    </div>

    <style>
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .animate-slide-left {
            animation: slideLeft 0.6s ease-out;
            animation-fill-mode: both;
        }

        .animate-slide-right {
            animation: slideRight 0.6s ease-out;
            animation-fill-mode: both;
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .feature-card {
            background: white;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: linear-gradient(135deg, #fff9f5 0%, #ffffff 100%);
        }

        .feature-icon i {
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon i {
            transform: scale(1.1) rotate(5deg);
        }

        /* Stats styling */
        .stat-item {
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            min-width: 150px;
        }

        /* Smooth scroll */
        .smooth-scroll {
            scroll-behavior: smooth;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Button hover effect */
        .cta-section .btn-light:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        /* Why choose us section */
        .why-choose-us {
            position: relative;
            overflow: hidden;
        }

        .why-choose-us::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(249, 112, 25, 0.05) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-stats {
                gap: 1rem !important;
            }

            .stat-item {
                min-width: 120px;
            }

            .display-4 {
                font-size: 2rem;
            }
        }
    </style>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

@endsection