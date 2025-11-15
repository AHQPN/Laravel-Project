# BÁO CÁO DỰ ÁN
# HỆ THỐNG QUẢN LỶ ĐẶT VÉ XE KHÁCH

**Nhóm 2 - Website Quản Lý Xe Khách**

---

## MỤC LỤC

1. [Giới thiệu dự án](#1-giới-thiệu-dự-án)
2. [Công nghệ sử dụng](#2-công-nghệ-sử-dụng)
3. [Kiến trúc hệ thống](#3-kiến-trúc-hệ-thống)
4. [Cơ sở dữ liệu](#4-cơ-sở-dữ-liệu)
5. [Các chức năng chính](#5-các-chức-năng-chính)
6. [Phân tích mã nguồn](#6-phân-tích-mã-nguồn)
7. [Giao diện người dùng](#7-giao-diện-người-dùng)
8. [Hướng dẫn triển khai](#8-hướng-dẫn-triển-khai)
9. [Đánh giá và hướng phát triển](#9-đánh-giá-và-hướng-phát-triển)

---

## 1. GIỚI THIỆU DỰ ÁN

### 1.1. Tổng quan

Hệ thống **Quản lý Đặt vé Xe Khách** là một ứng dụng web được xây dựng trên nền tảng Laravel 12, nhằm số hóa và tự động hóa quy trình quản lý vé xe khách từ khâu đặt vé, thanh toán, quản lý chuyến đi đến thống kê doanh thu. Dự án hướng tới việc cải thiện trải nghiệm người dùng, tăng hiệu quả vận hành và cung cấp công cụ quản lý toàn diện cho các nhà xe.

Hệ thống được thiết kế theo mô hình đa vai trò (multi-role) với giao diện và chức năng riêng biệt cho từng đối tượng sử dụng, đảm bảo tính bảo mật và phân quyền rõ ràng.

### 1.2. Mục tiêu xây dựng hệ thống

#### 1.2.1. Mục tiêu chung
- **Số hóa quy trình kinh doanh**: Chuyển đổi quy trình đặt vé và quản lý từ thủ công sang tự động
- **Tối ưu hóa vận hành**: Giảm thiểu thời gian xử lý, tránh sai sót và nâng cao hiệu suất làm việc
- **Nâng cao trải nghiệm khách hàng**: Cung cấp hệ thống đặt vé nhanh chóng, minh bạch và tiện lợi
- **Quản lý tập trung**: Tích hợp quản lý tuyến đường, xe, nhân viên, vé và doanh thu trên một nền tảng duy nhất
- **Hỗ trợ ra quyết định**: Cung cấp báo cáo và thống kê chi tiết để hỗ trợ quản lý đưa ra quyết định kinh doanh

#### 1.2.2. Mục tiêu cụ thể
- **Đối với khách hàng**: 
  - Tìm kiếm và đặt vé trực tuyến dễ dàng
  - Xem thông tin chi tiết về chuyến đi, tuyến đường, giá vé
  - Quản lý vé đã đặt và lịch sử giao dịch

- **Đối với nhân viên bán vé**:
  - Đặt vé offline tại quầy nhanh chóng
  - Quản lý vé, hóa đơn và theo dõi chuyến đi realtime
  - Xử lý các yêu cầu của khách hàng hiệu quả

- **Đối với tài xế và phụ xe**:
  - Xem lịch trình và danh sách hành khách
  - Cập nhật trạng thái chuyến đi (bắt đầu, kết thúc)
  - Báo cáo sự cố trong quá trình vận hành

- **Đối với quản trị viên**:
  - Quản lý toàn bộ hệ thống: tuyến đường, xe, nhân viên
  - Theo dõi và phê duyệt hóa đơn
  - Xem báo cáo thống kê chi tiết về doanh thu và hoạt động

### 1.3. Đối tượng sử dụng

Hệ thống phục vụ 5 nhóm đối tượng chính với vai trò và quyền hạn khác nhau:

#### 1.3.1. Khách hàng (Customer)
- **Vai trò**: Người sử dụng dịch vụ đặt vé
- **Quyền hạn**: 
  - Tìm kiếm chuyến đi theo tuyến và thời gian
  - Đặt vé trực tuyến hoặc tại quầy
  - Xem thông tin cá nhân và lịch sử đặt vé
  - Hủy vé (theo chính sách)
- **Đặc điểm**: Không yêu cầu kỹ năng kỹ thuật cao, giao diện thân thiện và đơn giản

#### 1.3.2. Nhân viên bán vé (Ticket Sales Staff - NVBV)
- **Vai trò**: Nhân viên tại quầy vé hỗ trợ khách hàng
- **Quyền hạn**:
  - Đặt vé offline cho khách hàng tại quầy
  - Quản lý và tra cứu thông tin vé
  - Theo dõi trạng thái chuyến đi realtime
  - Xem và quản lý hóa đơn
  - Cập nhật thông tin cá nhân và đổi mật khẩu
- **Đặc điểm**: Giao diện tối ưu cho thao tác nhanh, hỗ trợ nhiều giao dịch đồng thời

#### 1.3.3. Tài xế (Driver - TX)
- **Vai trò**: Người lái xe, điều khiển phương tiện
- **Quyền hạn**:
  - Xem lịch trình chuyến đi được phân công
  - Xem danh sách hành khách và thông tin liên hệ
  - Cập nhật trạng thái chuyến đi (sắp chạy → đang chạy → hoàn thành)
  - Báo cáo sự cố (tai nạn, hư hỏng xe, tắc đường...)
  - Quản lý hồ sơ cá nhân
- **Đặc điểm**: Giao diện mobile-friendly, dễ sử dụng khi đang di chuyển

#### 1.3.4. Phụ xe (Assistant Driver - PX)
- **Vai trò**: Nhân viên hỗ trợ tài xế trên xe
- **Quyền hạn**:
  - Xem danh sách hành khách theo chuyến
  - Đánh dấu trạng thái đón/trả khách
  - Kiểm tra vé hành khách
  - Hỗ trợ khách hàng trên xe
- **Đặc điểm**: Giao diện đơn giản, tập trung vào quản lý hành khách

#### 1.3.5. Quản trị viên (Administrator - QL)
- **Vai trò**: Quản lý toàn bộ hệ thống
- **Quyền hạn**:
  - Quản lý tỉnh thành và tuyến đường
  - Quản lý loại xe và phương tiện
  - Quản lý chuyến đi (tạo, sửa, xóa)
  - Quản lý người dùng (khách hàng và nhân viên)
  - Phê duyệt và hủy hóa đơn
  - Xem báo cáo thống kê chi tiết (doanh thu, số lượng vé, tỷ lệ lấp đầy...)
- **Đặc điểm**: Giao diện dashboard tổng quan, nhiều biểu đồ và báo cáo

### 1.4. Phạm vi chức năng

#### 1.4.1. Module quản lý chuyến đi và tuyến đường
- Quản lý tỉnh thành (điểm đi, điểm đến)
- Quản lý lộ trình (điểm dừng trung gian)
- Tạo và quản lý chuyến đi với thông tin:
  - Mã chuyến, tên chuyến
  - Xe được phân công
  - Thời gian khởi hành, thời gian di chuyển
  - Giá vé, số ghế còn lại
  - Trạng thái chuyến (sắp chạy, đang chạy, hoàn thành, đã hủy)

#### 1.4.2. Module quản lý vé
- Đặt vé online và offline
- Chọn ghế trực quan (seat map)
- Quản lý trạng thái vé (đã đặt, đã thanh toán, đã sử dụng, đã hủy)
- Hủy vé và hoàn tiền
- Tìm kiếm và lọc vé theo nhiều tiêu chí

#### 1.4.3. Module quản lý xe và nhân viên
- Quản lý loại xe (số ghế, tiện nghi)
- Quản lý phương tiện (biển số, tài xế phụ trách)
- Quản lý nhân viên theo chức vụ:
  - Quản lý (QL)
  - Nhân viên bán vé (NVBV)
  - Tài xế (TX)
  - Phụ xe (PX)
- Phân quyền dựa trên vai trò

#### 1.4.4. Module thanh toán và hóa đơn
- Tạo hóa đơn tự động khi đặt vé
- Quản lý phương thức thanh toán:
  - Tiền mặt (TM)
  - Chuyển khoản (CK)
  - MoMo (MM)
  - VNPay (VN)
- Chi tiết hóa đơn (CTHD) liên kết với vé
- Theo dõi trạng thái thanh toán

#### 1.4.5. Module báo cáo và thống kê
- Thống kê doanh thu theo ngày/tháng/năm
- Thống kê số lượng vé bán ra
- Thống kê tỷ lệ lấp đầy xe
- Báo cáo top tuyến đường doanh thu cao
- Báo cáo hiệu suất nhân viên

#### 1.4.6. Module báo cáo sự cố (dành cho tài xế)
- Báo cáo sự cố khi đang vận hành:
  - Tắc đường
  - Hư hỏng xe
  - Tai nạn nhẹ
  - Khác
- Đính kèm ảnh hiện trường
- Mô tả chi tiết tình huống
- Theo dõi trạng thái xử lý

### 1.5. Lợi ích mang lại

#### Đối với nhà xe:
- ✅ Tăng hiệu quả quản lý và giảm chi phí vận hành
- ✅ Giảm thiểu sai sót trong quá trình đặt vé và thu tiền
- ✅ Dễ dàng mở rộng quy mô kinh doanh
- ✅ Có dữ liệu chính xác để phân tích và ra quyết định

#### Đối với khách hàng:
- ✅ Đặt vé nhanh chóng, tiện lợi 24/7
- ✅ Xem trước sơ đồ ghế và chọn vị trí yêu thích
- ✅ Quản lý vé và lịch sử đặt vé dễ dàng
- ✅ Thanh toán linh hoạt qua nhiều phương thức

#### Đối với nhân viên:
- ✅ Giao diện thân thiện, dễ sử dụng
- ✅ Xử lý giao dịch nhanh hơn
- ✅ Giảm khối lượng công việc thủ công
- ✅ Theo dõi thông tin realtime

---

## 2. CÔNG NGHỆ SỬ DỤNG

### 2.1. Backend Framework

#### 2.1.1. Laravel Framework
- **Phiên bản**: Laravel 12.0
- **PHP phiên bản**: ^8.2
- **Lý do lựa chọn**:
  - Framework PHP hiện đại, bảo mật cao
  - Hệ sinh thái phong phú với nhiều package hỗ trợ
  - ORM Eloquent mạnh mẽ cho xử lý database
  - Routing linh hoạt và middleware system tốt
  - Blade templating engine hiệu quả
  - Artisan CLI hỗ trợ development và deployment

#### 2.1.2. Các Package Laravel chính

**Core Packages:**
- `laravel/framework` (^12.0): Core framework
- `laravel/tinker` (^2.10.1): REPL console tương tác với application

**Development Packages:**
- `laravel/sail` (^1.41): Docker development environment
- `laravel/pint` (^1.24): PHP code style fixer
- `laravel/pail` (^1.2.2): Log viewer
- `laravel/boost` (^1.7): Performance optimization

**Testing Packages:**
- `phpunit/phpunit` (^11.5.3): Unit và integration testing
- `mockery/mockery` (^1.6): Mocking framework
- `fakerphp/faker` (^1.23): Fake data generator cho testing/seeding

**Utility Packages:**
- `nesbot/carbon` (via Laravel): Date/time manipulation
- `guzzlehttp/guzzle`: HTTP client
- `monolog/monolog`: Logging library
- `symfony/*`: Các component Symfony được sử dụng qua Laravel

### 2.2. Cơ sở dữ liệu

#### 2.2.1. Database Management System
- **DBMS**: MySQL 5.7+
- **Driver**: mysqli / PDO_MySQL
- **Charset**: utf8mb4 (hỗ trợ tiếng Việt và emoji)
- **Collation**: utf8mb4_unicode_ci

#### 2.2.2. Công cụ quản lý database
- **phpMyAdmin**: Giao diện web quản lý MySQL (thông qua XAMPP)
- **MySQL Workbench**: Alternative tool cho database design và query
- **Laravel Migrations**: Version control cho database schema

#### 2.2.3. Cấu hình database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nhom2_websitexekhach
DB_USERNAME=root
DB_PASSWORD=
```

### 2.3. Frontend Technologies

#### 2.3.1. Template Engine
- **Blade**: Laravel's native templating engine
  - Syntax đơn giản, dễ học
  - Hỗ trợ components và layouts
  - Caching tự động cho performance
  - Directives mạnh mẽ (@if, @foreach, @auth...)

#### 2.3.2. CSS Framework và Styling
- **Bootstrap 5.3**: Main CSS framework
  - Responsive grid system
  - Pre-built components (cards, modals, forms...)
  - Utility classes
- **Font Awesome 6.4**: Icon library (1000+ icons)
- **Custom CSS**: 
  - `design-system.css`: Design tokens và components
  - `table-custom.css`: Custom table styling
  - `ui-fixes.css`: UI adjustments và overrides

#### 2.3.3. JavaScript
- **Vanilla JavaScript**: Core interactions và DOM manipulation
- **Libraries sử dụng**:
  - **jQuery 3.7**: DOM manipulation và AJAX
  - **Bootstrap JS Bundle**: Bootstrap components interactivity
  - **SweetAlert2**: Beautiful alert và confirmation dialogs
  - **Toastify JS**: Toast notifications
  - **Choices.js**: Enhanced select dropdowns
  - **Axios 1.11**: HTTP client cho API calls
  - **Tippy.js**: Tooltips
  - **Popper.js**: Positioning engine

#### 2.3.4. Build Tools
- **Vite 7.0**: Modern build tool
  - Lightning fast HMR (Hot Module Replacement)
  - Optimized production builds
  - Native ES modules support
- **Tailwind CSS 4.0**: Utility-first CSS (optional, configured)
- **Laravel Vite Plugin 2.0**: Integration với Laravel
- **Concurrently**: Run multiple npm commands simultaneously

### 2.4. Session và Cache

#### 2.4.1. Session Management
- **Driver**: Database
- **Lifetime**: 120 minutes
- **Table**: `sessions` (migration included)
- **Encryption**: Disabled (không cần thiết cho session data)

#### 2.4.2. Cache System
- **Store**: Database
- **Table**: `cache` và `cache_locks`
- **Usage**: Cache query results, view compilation

### 2.5. Authentication & Security

#### 2.5.1. Authentication
- **Custom Authentication**: Không sử dụng Laravel Breeze/Jetstream
- **Session-based**: Sử dụng session để lưu trạng thái đăng nhập
- **Password Hashing**: Bcrypt (12 rounds)
- **Multi-guard**: Phân biệt authentication cho các vai trò khác nhau

#### 2.5.2. Middleware Security
- **Custom Middleware**:
  - `AdminAuth`: Bảo vệ routes quản trị viên
  - `NhanVienAuth`: Bảo vệ routes nhân viên bán vé
  - `TaiXeAuth`: Bảo vệ routes tài xế
  - `PhuXeAuth`: Bảo vệ routes phụ xe

#### 2.5.3. Security Features
- **CSRF Protection**: Tự động bởi Laravel
- **XSS Protection**: Blade auto-escaping
- **SQL Injection Prevention**: Eloquent ORM và prepared statements
- **Password Requirements**: Minimum 6 characters (có thể tùy chỉnh)

### 2.6. Development Environment

#### 2.6.1. Local Development
- **XAMPP**: Apache + MySQL + PHP stack
- **Alternative**: Laravel Sail (Docker-based)
- **Composer**: PHP dependency management
- **NPM**: JavaScript package management

#### 2.6.2. Version Control
- **Git**: Source code version control
- **Platform**: GitHub (AHQPN/Laravel-Project)
- **Branch**: main

#### 2.6.3. Code Quality Tools
- **Laravel Pint**: Automatic code formatting theo PSR-12
- **EditorConfig**: Consistent coding styles
- **PHPUnit**: Automated testing

### 2.7. APIs và Integrations

#### 2.7.1. Internal APIs
Hệ thống có các API endpoints nội bộ cho AJAX calls:
- `GET /api/chuyen-di`: Lấy danh sách chuyến đi
- `GET /api/gio-khoi-hanh`: Lấy giờ khởi hành theo tuyến
- `GET /api/vehicles`: Lấy thông tin xe
- `GET /api/so-do-ghe`: Lấy sơ đồ ghế và trạng thái

#### 2.7.2. Payment Gateway (Prepared)
- **MoMo**: Mobile wallet payment
- **VNPay**: Online banking payment
- *(Chưa tích hợp API thực tế, chỉ có database schema)*

### 2.8. Logging và Monitoring

#### 2.8.1. Logging
- **Channel**: Stack (single file)
- **Log Level**: Debug (development), Error (production)
- **Storage**: `storage/logs/laravel.log`
- **Tool**: Laravel Pail cho real-time log viewing

#### 2.8.2. Error Handling
- **Whoops**: Pretty error pages (development)
- **Custom error pages**: 404, 500... (production)
- **Exception handling**: Centralized trong `app/Exceptions/Handler.php`

### 2.9. Queue và Background Jobs

#### 2.9.1. Queue Configuration
- **Connection**: Database
- **Table**: `jobs`, `failed_jobs`
- **Usage**: Xử lý email, notifications, heavy computations

#### 2.9.2. Broadcasting (Prepared)
- **Connection**: Log (development)
- **Future**: Có thể integrate Pusher/Redis cho real-time features

---

## 3. KIẾN TRÚC HỆ THỐNG

### 3.1. Mô hình MVC (Model-View-Controller)

Dự án tuân thủ nghiêm ngặt kiến trúc MVC của Laravel, đảm bảo tách biệt logic nghiệp vụ, giao diện và dữ liệu.

#### 3.1.1. Model Layer (Tầng dữ liệu)

**Các Model chính:**

```
app/Models/
├── BaocaoSuco.php      # Báo cáo sự cố
├── Chucvu.php          # Chức vụ nhân viên
├── Chuyendi.php        # Chuyến đi
├── CTHD.php            # Chi tiết hóa đơn
├── Hoadon.php          # Hóa đơn
├── Khach.php           # Khách hàng
├── Loaixe.php          # Loại xe
├── Lotrinh.php         # Lộ trình
├── Nhanvien.php        # Nhân viên
├── Thanhtoan.php       # Phương thức thanh toán
├── TinhThanh.php       # Tỉnh thành
├── Ve.php              # Vé xe
└── Xe.php              # Phương tiện
```

**Đặc điểm:**
- Sử dụng Eloquent ORM
- Quan hệ đầy đủ (hasMany, belongsTo, hasOneThrough...)
- Accessors và Mutators cho data transformation
- Scopes cho reusable queries
- Mass assignment protection với `$fillable`
- Type casting với `$casts`

#### 3.1.2. View Layer (Tầng giao diện)

**Cấu trúc Views:**

```
resources/views/
├── landing.blade.php              # Trang chủ chọn vai trò
├── test-suite.blade.php           # Test utilities
│
├── layouts/                       # Layout templates
│   ├── NhanVienLayout.blade.php   # Layout cho NVBV
│   ├── TaiXeLayout.blade.php      # Layout cho tài xế
│   ├── PhuXeLayout.blade.php      # Layout cho phụ xe
│   ├── admin/
│   │   └── app.blade.php          # Admin layout
│   └── components/                # Shared components
│       ├── Header.blade.php
│       ├── Sidebar.blade.php
│       ├── Footer.blade.php
│       ├── Navbar.blade.php
│       ├── Modal.blade.php
│       ├── Toast.blade.php
│       └── Scripts.blade.php
│
├── components/                    # Reusable components
│   ├── Input.blade.php            # Form input component
│   ├── Button.blade.php           # Button component
│   ├── BadgeTrangThai.blade.php   # Status badge
│   ├── FilterBar.blade.php        # Filter component
│   └── ...
│
├── admin/                         # Admin views
│   ├── DangNhap.blade.php
│   ├── TrangChu.blade.php
│   ├── ChuyenDi/                  # CRUD chuyến đi
│   ├── HoaDon/                    # Quản lý hóa đơn
│   ├── LoaiXe/                    # Quản lý loại xe
│   ├── NguoiDung/                 # Quản lý người dùng
│   ├── ThongKe/                   # Thống kê
│   ├── TinhThanh/                 # Quản lý tỉnh thành
│   └── Xe/                        # Quản lý xe
│
├── NhanVienBanVe/                # NVBV views
│   ├── DangNhap.blade.php
│   ├── TrangChu.blade.php
│   ├── DatVeOffline.blade.php    # Đặt vé offline
│   ├── QuanLyVe.blade.php        # Quản lý vé
│   ├── TheoDoiChuyenDi.blade.php # Theo dõi chuyến
│   ├── HoaDon.blade.php          # Danh sách hóa đơn
│   ├── HoSo.blade.php            # Profile
│   ├── HoSoChinhSua.blade.php    # Edit profile
│   └── partials/
│       └── TicketDetail.blade.php
│
├── TaiXe/                        # Driver views
│   ├── DangNhap.blade.php
│   ├── ChuyenDiHomNay.blade.php  # Chuyến hôm nay
│   ├── DanhSachHanhKhach.blade.php
│   ├── ChiTietHanhKhach.blade.php
│   ├── BaoCaoSuCo.blade.php      # Report incident
│   └── HoSo.blade.php
│
└── PhuXe/                        # Assistant views
    ├── DangNhap.blade.php
    ├── TrangChu.blade.php
    ├── DanhSachHanhKhach.blade.php
    ├── ChiTietHanhKhach.blade.php
    └── HoSo.blade.php
```

**Blade Features sử dụng:**
- `@extends`, `@section`, `@yield`: Layout inheritance
- `@include`: Partial views
- `@component`, `<x-component>`: Reusable components
- `@if`, `@foreach`, `@forelse`: Control structures
- `@auth`, `@guest`: Authentication directives
- `{{ }}`: Auto-escaped output
- `{!! !!}`: Raw output
- `@csrf`, `@method`: Form helpers

#### 3.1.3. Controller Layer (Tầng điều khiển)

**Cấu trúc Controllers:**

```
app/Http/Controllers/
├── Controller.php                 # Base controller
│
├── Admin/                         # Admin controllers
│   ├── AuthController.php         # Authentication
│   ├── DashboardController.php    # Dashboard
│   ├── ChuyendiController.php     # Chuyến đi CRUD
│   ├── HoadonController.php       # Hóa đơn
│   ├── LoaixeController.php       # Loại xe CRUD
│   ├── NguoiDungController.php    # User management
│   ├── ThongKeController.php      # Statistics
│   ├── TinhThanhController.php    # Tỉnh thành CRUD
│   └── XeController.php           # Xe CRUD
│
├── NhanVienBanVe/                # NVBV controllers
│   ├── DashboardController.php
│   └── NhanVienBanVeController.php # Main NVBV logic
│       ├── dashboard()            # Trang chủ
│       ├── profile()              # Hồ sơ
│       ├── createDatVe()          # Form đặt vé
│       ├── storeDatVe()           # Xử lý đặt vé
│       ├── indexVe()              # Danh sách vé
│       ├── indexChuyenDi()        # Theo dõi chuyến
│       └── API methods            # Internal APIs
│
├── TaiXe/                        # Driver controllers
│   ├── AuthTaiXeController.php    # Driver auth
│   ├── BaoCaoController.php       # Incident reports
│   ├── ChuyenController.php       # Trip management
│   │   ├── today()                # Chuyến hôm nay
│   │   ├── start()                # Bắt đầu chuyến
│   │   └── end()                  # Kết thúc chuyến
│   ├── HanhKhachController.php    # Passenger list
│   └── ProfileController.php      # Driver profile
│
└── PhuXe/                        # Assistant controllers
    ├── AuthPhuXeController.php
    ├── DashboardController.php
    ├── HanhKhachController.php
    └── ProfileController.php
```

**Controller Responsibilities:**
- Nhận request từ routes
- Validate input data
- Gọi services/models xử lý logic
- Return views hoặc JSON responses
- Handle exceptions và errors

### 3.2. Sơ đồ luồng xử lý dữ liệu

#### 3.2.1. Request Lifecycle

```
1. User Request
   ↓
2. Routing (web.php)
   ↓
3. Middleware (Auth, CSRF...)
   ↓
4. Controller
   ↓
5. Service Layer (optional)
   ↓
6. Model / Eloquent ORM
   ↓
7. Database Query
   ↓
8. Response (View / JSON)
   ↓
9. Browser Render
```

#### 3.2.2. Authentication Flow

```
Landing Page (/)
   ↓
User chọn vai trò
   ↓
Login Form (/quan-ly/dang-nhap, /tai-xe/dang-nhap...)
   ↓
AuthController::login()
   ↓
Validate credentials
   ↓
Check role (macv = 'QL', 'NVBV', 'TX', 'PX')
   ↓
Hash::check password
   ↓
Session::put('admin'/'nhanvien'/'taixe'/'phuxe', $user)
   ↓
Redirect to dashboard
   ↓
Middleware kiểm tra session
   ↓
Allow access hoặc redirect login
```

#### 3.2.3. Booking Flow (Đặt vé offline)

```
NVBV: Chọn "Đặt vé offline"
   ↓
NhanVienBanVeController::createDatVe()
   ↓
Load form với dropdowns (tuyến, giờ)
   ↓
User nhập thông tin khách + chọn ghế
   ↓
API call: getSoDoGheApi()
   ↓
Return seat map (ghế trống/đã đặt)
   ↓
User submit form
   ↓
NhanVienBanVeController::storeDatVe()
   ↓
DB::beginTransaction()
   ├─> Tìm/tạo Khach
   ├─> Tạo Hoadon
   ├─> Tạo CTHD
   ├─> Tạo Ve (cho từng ghế)
   └─> Cập nhật SLgheconlai trên Chuyendi
   ↓
DB::commit()
   ↓
Redirect với success message
```

#### 3.2.4. Trip Management Flow (Tài xế)

```
Tài xế login (/tai-xe/dang-nhap)
   ↓
Dashboard: Chuyến hôm nay
   ↓
ChuyenController::today()
   ↓
Fetch trips where xe.manv = taixe.manv
   ↓
Display trips với status và actions
   ↓
Tài xế click "Bắt đầu chuyến"
   ↓
JavaScript confirm (SweetAlert2)
   ↓
POST /tai-xe/chuyen-di/{machuyendi}/bat-dau
   ↓
ChuyenController::start()
   ↓
Update Chuyendi:
   - trangthai = 'dang_chay'
   - batdau_luc = now()
   ↓
Return JSON success
   ↓
Frontend update UI (disable nút, enable "Kết thúc")
   ↓
...sau khi hoàn thành chuyến...
   ↓
Tài xế click "Kết thúc chuyến"
   ↓
ChuyenController::end()
   ↓
Update Chuyendi:
   - trangthai = 'hoan_thanh'
   - ketthuc_luc = now()
   ↓
Return JSON success
```

### 3.3. Các Module chính

#### 3.3.1. Module Authentication & Authorization

**Components:**
- Custom authentication cho từng vai trò
- Middleware: `AdminAuth`, `NhanVienAuth`, `TaiXeAuth`, `PhuXeAuth`
- Session-based authentication
- Password hashing với Bcrypt

**Flow:**
1. User đăng nhập → Validate credentials
2. Kiểm tra `macv` (mã chức vụ) và `trangthai`
3. Lưu user vào session
4. Middleware kiểm tra session cho mỗi request
5. Redirect về login nếu unauthorized

#### 3.3.2. Module Quản lý Tuyến đường

**Entities:**
- `TinhThanh`: Tỉnh thành (điểm đi/đến)
- `Lotrinh`: Lộ trình (điểm dừng trung gian)
- `Chuyendi`: Chuyến đi

**Relationships:**
```
Chuyendi (1) ---< hasMany >--- (N) Lotrinh
Lotrinh (N) ---< belongsTo >--- (1) TinhThanh
```

**Features:**
- CRUD tỉnh thành (admin)
- Tạo chuyến đi với nhiều điểm dừng
- Sắp xếp điểm dừng theo `trinhtu`
- Hiển thị tuyến dạng "Điểm A → Điểm B"

#### 3.3.3. Module Đặt vé

**Entities:**
- `Ve`: Vé xe
- `Khach`: Khách hàng
- `Hoadon`: Hóa đơn
- `CTHD`: Chi tiết hóa đơn

**Relationships:**
```
Khach (1) ---< hasMany >--- (N) Hoadon
Hoadon (1) ---< hasMany >--- (N) CTHD
CTHD (N) ---< belongsTo >--- (1) Ve
Ve (N) ---< belongsTo >--- (1) Chuyendi
```

**Features:**
- Tìm kiếm chuyến theo tuyến + ngày
- Hiển thị sơ đồ ghế (seat map)
- Chọn nhiều ghế cùng lúc
- Nhập thông tin khách hàng
- Chọn phương thức thanh toán
- Tạo hóa đơn tự động
- Hủy vé (cập nhật `trangthai`, hoàn ghế)

**Seat Map Logic:**
```javascript
// API trả về danh sách ghế
seats: [
  { code: 'A01', booked: false },
  { code: 'A02', booked: true },
  ...
]

// Frontend render:
- Ghế trống: clickable, màu xanh
- Ghế đã đặt: disabled, màu xám
- Ghế đang chọn: highlight, màu vàng

// Khi submit:
selectedSeats = ['A05', 'A06', 'A07']
→ Tạo 3 vé với maghe tương ứng
```

#### 3.3.4. Module Quản lý Xe

**Entities:**
- `Loaixe`: Loại xe (16 chỗ, 22 chỗ, 44 chỗ...)
- `Xe`: Phương tiện cụ thể
- `Nhanvien`: Tài xế được phân công

**Relationships:**
```
Loaixe (1) ---< hasMany >--- (N) Xe
Xe (N) ---< belongsTo >--- (1) Nhanvien (tài xế)
Xe (1) ---< hasMany >--- (N) Chuyendi
```

**Features:**
- Quản lý loại xe (CRUD)
- Quản lý phương tiện:
  - Biển số xe
  - Loại xe
  - Tài xế phụ trách
  - Trạng thái
- Phân công xe cho chuyến đi
- Kiểm tra xe có sẵn hay đang chạy

#### 3.3.5. Module Quản lý Người dùng

**Entities:**
- `Nhanvien`: Nhân viên (NVBV, TX, PX, QL)
- `Khach`: Khách hàng
- `Chucvu`: Chức vụ

**Features:**
- CRUD nhân viên
- CRUD khách hàng
- Phân quyền theo `macv`
- Quản lý trạng thái (active/inactive)
- Profile management
- Change password

#### 3.3.6. Module Thống kê

**Entities:**
- `Hoadon`: Hóa đơn (doanh thu)
- `Ve`: Vé (số lượng)
- `Chuyendi`: Chuyến đi (tần suất, tỷ lệ lấp đầy)

**Statistics Available:**
- Tổng doanh thu (theo ngày/tháng/năm)
- Số lượng vé bán ra
- Số lượng khách hàng
- Tỷ lệ lấp đầy xe
- Top tuyến đường doanh thu cao
- Hiệu suất nhân viên

**Visualization:**
- Biểu đồ cột (doanh thu theo tháng)
- Biểu đồ tròn (phân bổ theo tuyến)
- Cards (KPIs)
- Tables (chi tiết)

#### 3.3.7. Module Báo cáo Sự cố

**Entities:**
- `BaocaoSuco`: Incident report

**Relationships:**
```
BaocaoSuco (N) ---< belongsTo >--- (1) Chuyendi
BaocaoSuco (N) ---< belongsTo >--- (1) Nhanvien (tài xế)
```

**Features:**
- Tài xế báo cáo khi đang chạy chuyến
- Chọn loại sự cố (dropdown)
- Mô tả chi tiết
- Upload ảnh hiện trường
- Trạng thái xử lý
- Admin xem và xử lý báo cáo

---

## 4. CƠ SỞ DỮ LIỆU

### 4.1. Database Schema

#### 4.1.1. Tổng quan
- **Database name**: `nhom2_websitexekhach`
- **Tables**: 15 bảng chính + 3 bảng hệ thống
- **Relationships**: Có ràng buộc foreign keys
- **Charset**: utf8mb4_unicode_ci (hỗ trợ tiếng Việt)

#### 4.1.2. Các bảng chính

**1. Chucvu (Chức vụ)**
```sql
CREATE TABLE Chucvu (
    macv VARCHAR(10) PRIMARY KEY,
    ten_chucvu VARCHAR(50),
    mota TEXT
);
```
**Data mẫu:**
- `QL`: Quản lý
- `NVBV`: Nhân viên bán vé
- `TX`: Tài xế
- `PX`: Phụ xe

---

**2. Nhanvien (Nhân viên)**
```sql
CREATE TABLE Nhanvien (
    manv VARCHAR(5) PRIMARY KEY,
    macv VARCHAR(10),
    password VARCHAR(255),
    ten VARCHAR(100),
    sdt VARCHAR(15),
    diachi VARCHAR(200),
    cccd VARCHAR(12),
    email VARCHAR(100),
    ngaysinh DATE,
    gioitinh VARCHAR(10),
    hinhanh VARCHAR(255),
    trangthai BOOLEAN DEFAULT 1,
    FOREIGN KEY (macv) REFERENCES Chucvu(macv)
);
```
**Quan hệ:**
- `belongsTo Chucvu`
- `hasMany Xe` (nếu là tài xế)
- `hasMany Hoadon` (nếu là NVBV)
- `hasMany BaocaoSuco` (nếu là TX)

---

**3. TinhThanh (Tỉnh thành)**
```sql
CREATE TABLE TinhThanh (
    matinh VARCHAR(5) PRIMARY KEY,
    ten VARCHAR(100)
);
```
**Usage:** Điểm đi, điểm đến, điểm dừng

---

**4. Loaixe (Loại xe)**
```sql
CREATE TABLE Loaixe (
    maloai VARCHAR(5) PRIMARY KEY,
    tenloai VARCHAR(50),
    soghe INT,
    mota TEXT
);
```
**Ví dụ:**
- Xe 16 chỗ
- Xe 22 chỗ giường nằm
- Xe 44 chỗ ngồi

---

**5. Xe (Phương tiện)**
```sql
CREATE TABLE Xe (
    maxe VARCHAR(5) PRIMARY KEY,
    maloai VARCHAR(5),
    soxe VARCHAR(10),      -- Biển số
    manv VARCHAR(5),        -- Tài xế
    FOREIGN KEY (maloai) REFERENCES Loaixe(maloai),
    FOREIGN KEY (manv) REFERENCES Nhanvien(manv)
);
```
**Quan hệ:**
- `belongsTo Loaixe`
- `belongsTo Nhanvien` (tài xế)
- `hasMany Chuyendi`

---

**6. Chuyendi (Chuyến đi)**
```sql
CREATE TABLE Chuyendi (
    machuyendi VARCHAR(15) PRIMARY KEY,
    tenchuyen VARCHAR(100),
    maxe VARCHAR(5),
    SLgheconlai INT,
    thoigiandi DATETIME,
    thoigiandichuyen INT,   -- Phút
    gia INT,
    trangthai VARCHAR(20),  -- sap_chay, dang_chay, hoan_thanh, da_huy
    batdau_luc DATETIME,
    ketthuc_luc DATETIME,
    FOREIGN KEY (maxe) REFERENCES Xe(maxe)
);
```
**Quan hệ:**
- `belongsTo Xe`
- `hasMany Lotrinh`
- `hasMany Ve`
- `hasMany BaocaoSuco`

**Trạng thái:**
- `sap_chay`: Sắp khởi hành
- `dang_chay`: Đang vận hành
- `hoan_thanh`: Đã hoàn thành
- `da_huy`: Đã hủy

---

**7. Lotrinh (Lộ trình)**
```sql
CREATE TABLE Lotrinh (
    malotrinh INT AUTO_INCREMENT PRIMARY KEY,
    machuyendi VARCHAR(15),
    matinh VARCHAR(5),
    trinhtu INT,             -- Thứ tự điểm dừng
    FOREIGN KEY (machuyendi) REFERENCES Chuyendi(machuyendi),
    FOREIGN KEY (matinh) REFERENCES TinhThanh(matinh)
);
```
**Ví dụ:**
```
Chuyến CD001:
- trinhtu=1: TP.HCM (điểm đi)
- trinhtu=2: Biên Hòa (điểm dừng)
- trinhtu=3: Đà Lạt (điểm đến)
```

---

**8. Khach (Khách hàng)**
```sql
CREATE TABLE Khach (
    makh VARCHAR(10) PRIMARY KEY,
    ten VARCHAR(100),
    sdt VARCHAR(15),
    email VARCHAR(100),
    diachi VARCHAR(200),
    ngaysinh DATE,
    password VARCHAR(255)
);
```
**Quan hệ:**
- `hasMany Hoadon`

---

**9. Thanhtoan (Phương thức thanh toán)**
```sql
CREATE TABLE Thanhtoan (
    matt VARCHAR(5) PRIMARY KEY,
    ten VARCHAR(50)
);
```
**Data:**
- `TM`: Tiền mặt
- `CK`: Chuyển khoản
- `MM`: MoMo
- `VN`: VNPay

---

**10. Hoadon (Hóa đơn)**
```sql
CREATE TABLE Hoadon (
    mahd VARCHAR(10) PRIMARY KEY,
    makh VARCHAR(10),
    manv VARCHAR(5),         -- NVBV xử lý
    matt VARCHAR(5),
    tongtien INT,
    ngaydat DATETIME,
    trangthai VARCHAR(20),   -- Đã đặt, Đã thanh toán, Đã hủy
    FOREIGN KEY (makh) REFERENCES Khach(makh),
    FOREIGN KEY (manv) REFERENCES Nhanvien(manv),
    FOREIGN KEY (matt) REFERENCES Thanhtoan(matt)
);
```
**Quan hệ:**
- `belongsTo Khach`
- `belongsTo Nhanvien`
- `belongsTo Thanhtoan`
- `hasMany CTHD`

---

**11. Ve (Vé)**
```sql
CREATE TABLE Ve (
    mave VARCHAR(10) PRIMARY KEY,
    machuyendi VARCHAR(15),
    maghe VARCHAR(10),       -- Mã ghế (A01, B02...)
    trangthai VARCHAR(20),
    trangthai_don VARCHAR(20), -- da_don, chua_don (pickup status)
    FOREIGN KEY (machuyendi) REFERENCES Chuyendi(machuyendi)
);
```
**Quan hệ:**
- `belongsTo Chuyendi`
- `hasMany CTHD`

**Trạng thái:**
- `Đã đặt`: Vé mới tạo
- `Đã thanh toán`: Đã thu tiền
- `Đã sử dụng`: Đã lên xe
- `Đã hủy`: Vé bị hủy

**Pickup Status (trangthai_don):**
- `chua_don`: Chưa đón khách
- `da_don`: Đã đón khách lên xe

---

**12. CTHD (Chi tiết hóa đơn)**
```sql
CREATE TABLE CTHD (
    macthd INT AUTO_INCREMENT PRIMARY KEY,
    mahd VARCHAR(10),
    mave VARCHAR(10),
    FOREIGN KEY (mahd) REFERENCES Hoadon(mahd),
    FOREIGN KEY (mave) REFERENCES Ve(mave)
);
```
**Mục đích:** Liên kết nhiều vé với một hóa đơn

---

**13. BaocaoSuco (Báo cáo sự cố)**
```sql
CREATE TABLE BaocaoSuco (
    id_baocao INT AUTO_INCREMENT PRIMARY KEY,
    machuyendi VARCHAR(15),
    manv VARCHAR(5),         -- Tài xế báo cáo
    loai_suco VARCHAR(50),   -- Tắc đường, Hư hỏng xe, Tai nạn...
    mota TEXT,
    anh VARCHAR(255),
    trangthai VARCHAR(20),   -- Chưa xử lý, Đang xử lý, Đã xử lý
    tao_luc DATETIME DEFAULT CURRENT_TIMESTAMP,
    capnhat_luc DATETIME,
    FOREIGN KEY (machuyendi) REFERENCES Chuyendi(machuyendi),
    FOREIGN KEY (manv) REFERENCES Nhanvien(manv)
);
```

---

**14-18. System Tables**
- `cache`, `cache_locks`: Laravel cache
- `jobs`, `failed_jobs`: Laravel queue
- `sessions`: Laravel session storage

### 4.2. Entity Relationship Diagram (ERD)

```
┌─────────────┐
│   Chucvu    │
│ (PK: macv)  │
└──────┬──────┘
       │
       │ 1:N
       ↓
┌─────────────┐         ┌──────────────┐
│  Nhanvien   │ 1:N     │   Loaixe     │
│ (PK: manv)  ├────────→│ (PK: maloai) │
└──────┬──────┘         └───────┬──────┘
       │                        │
       │ 1:N                    │ 1:N
       ↓                        ↓
┌──────────────┐         ┌────────────┐
│   Hoadon     │         │     Xe     │
│ (PK: mahd)   │         │ (PK: maxe) │
└───────┬──────┘         └─────┬──────┘
        │                      │
        │ 1:N                  │ 1:N
        ↓                      ↓
┌──────────────┐         ┌──────────────┐
│     CTHD     │         │   Chuyendi   │
│ (PK: macthd) │         │(PK:machuyendi│
└───────┬──────┘         └───────┬──────┘
        │                        │
        │ N:1              ┌─────┴─────┬──────┐
        ↓                  │           │      │
┌──────────────┐     ┌─────▼─────┐ ┌──▼───┐ │
│      Ve      │     │  Lotrinh  │ │Baocao│ │
│ (PK: mave)   │     │           │ │Suco  │ │
└───────┬──────┘     └─────┬─────┘ └──────┘ │
        │                  │                 │
        │ N:1              │ N:1             │
        └──────────────────▼─────────────────┘
               ┌────────────────┐
               │   TinhThanh    │
               │  (PK: matinh)  │
               └────────────────┘

┌──────────────┐
│    Khach     │
│ (PK: makh)   │
└───────┬──────┘
        │
        │ 1:N
        └────→ Hoadon

┌──────────────┐
│  Thanhtoan   │
│ (PK: matt)   │
└───────┬──────┘
        │
        │ 1:N
        └────→ Hoadon
```

### 4.3. Eloquent Relationships

**Trong code (app/Models/):**

```php
// Chuyendi.php
public function xe() {
    return $this->belongsTo(Xe::class, 'maxe', 'maxe');
}
public function lotrinhs() {
    return $this->hasMany(Lotrinh::class, 'machuyendi');
}
public function ves() {
    return $this->hasMany(Ve::class, 'machuyendi');
}
public function baocaosucos() {
    return $this->hasMany(BaocaoSuco::class, 'machuyendi');
}

// Ve.php
public function chuyendi() {
    return $this->belongsTo(Chuyendi::class, 'machuyendi');
}
public function cthds() {
    return $this->hasMany(CTHD::class, 'mave');
}
public function hoadon() {
    return $this->hasOneThrough(Hoadon::class, CTHD::class, 
        'mave', 'mahd', 'mave', 'mahd');
}

// Nhanvien.php
public function chucvu() {
    return $this->belongsTo(Chucvu::class, 'macv');
}
public function xes() {
    return $this->hasMany(Xe::class, 'manv');
}
public function hoadons() {
    return $this->hasMany(Hoadon::class, 'manv');
}

// Xe.php
public function loaixe() {
    return $this->belongsTo(Loaixe::class, 'maloai');
}
public function taixe() {
    return $this->belongsTo(Nhanvien::class, 'manv');
}
public function chuyendis() {
    return $this->hasMany(Chuyendi::class, 'maxe');
}

// Khach.php
public function hoadons() {
    return $this->hasMany(Hoadon::class, 'makh');
}

// Hoadon.php
public function khach() {
    return $this->belongsTo(Khach::class, 'makh');
}
public function nhanvien() {
    return $this->belongsTo(Nhanvien::class, 'manv');
}
public function thanhtoan() {
    return $this->belongsTo(Thanhtoan::class, 'matt');
}
public function cthds() {
    return $this->hasMany(CTHD::class, 'mahd');
}
```

### 4.4. Migrations

**Thứ tự migrations (theo timestamp):**

1. `create_cache_table` - System cache
2. `create_jobs_table` - System queue
3. `create_chucvu_table` - Base data
4. `create_tinhthanh_table`
5. `create_loaixe_table`
6. `create_thanhtoan_table`
7. `create_nhanvien_table`
8. `create_khach_table`
9. `create_xe_table`
10. `create_chuyendi_table`
11. `create_lotrinh_table`
12. `create_ve_table`
13. `create_hoadon_table`
14. `create_cthd_table`
15. `add_trangthai_to_chuyendi_table` - Thêm cột trangthai, batdau_luc, ketthuc_luc
16. `add_pickup_status_to_ve_table` - Thêm cột trangthai_don
17. `create_baocaosuco_table`
18. `create_sessions_table` - Laravel sessions

**Chạy migrations:**
```bash
php artisan migrate
```

**Roll back:**
```bash
php artisan migrate:rollback
php artisan migrate:fresh  # Drop all tables and re-migrate
```

### 4.5. Seeders

**Dữ liệu mẫu (database/seeders/):**

```
DatabaseSeeder.php          # Main seeder
├── ChucvuSeeder           # 4 chức vụ
├── TinhThanhSeeder        # Tỉnh thành VN
├── LoaixeSeeder           # 3 loại xe
├── ThanhtoanSeeder        # 4 phương thức thanh toán
├── NhanvienSeeder         # 5 nhân viên (QL, NVBV, TX, PX)
├── KhachSeeder            # 3 khách hàng
├── XeSeeder               # 3 xe
├── ChuyendiSeeder         # 3 chuyến đi
├── LotrinhSeeder          # 7 điểm dừng
├── VeSeeder               # 5 vé
├── HoadonSeeder           # 3 hóa đơn
└── CTHDSeeder             # 5 chi tiết hóa đơn
```

**Chạy seeders:**
```bash
php artisan db:seed
# hoặc
php artisan migrate:fresh --seed  # Migrate + seed cùng lúc
```

**Dữ liệu login mặc định:**

| Vai trò | Mã NV  | Password |
|---------|--------|----------|
| Quản lý | NV001  | 123456   |
| NVBV    | NV002  | 123456   |
| Tài xế  | NV003  | 123456   |
| Phụ xe  | NV004  | 123456   |

---

*Báo cáo tiếp tục ở phần tiếp theo...*
