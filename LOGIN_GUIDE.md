# 🔐 HƯỚNG DẪN ĐĂNG NHẬP HỆ THỐNG

## 📋 Thông Tin Tài Khoản

### 👨‍💼 QUẢN LÝ (Admin)
- **URL**: `/quan-ly/dang-nhap`
- **Mã NV**: `NV001`
- **Mật khẩu**: `123456`
- **Email**: `admin@xekhach.com`
- **Quyền**: Quản lý toàn bộ hệ thống

**Chức năng:**
- Quản lý tỉnh thành
- Quản lý loại xe
- Quản lý xe
- Quản lý chuyến đi
- Quản lý hóa đơn (duyệt/hủy)
- Quản lý người dùng (khách hàng & nhân viên)
- Xem thống kê

---

### 👥 NHÂN VIÊN BÁN VÉ
- **URL**: `/nhan-vien-ban-ve/dang-nhap`
- **Mã NV**: `NV002` (hoặc bất kỳ NV nào có chức vụ BV)
- **Mật khẩu**: `123456`
- **Email**: `nhanvien@xekhach.com`
- **Quyền**: Bán vé và quản lý vé
- **Lưu ý**: Chức vụ phải là `BV` (không phải NVBV)

**Chức năng:**
- Đặt vé offline
- Quản lý vé
- Theo dõi chuyến đi
- Quản lý hóa đơn
- Xem hồ sơ cá nhân

---

### 🚗 TÀI XẾ
- **URL**: `/tai-xe/dang-nhap`
- **Mã NV**: `NV003`
- **Mật khẩu**: `123456`
- **Email**: `taixe@xekhach.com`
- **Quyền**: Xem chuyến đi và hành khách

**Chức năng:**
- Xem chuyến đi hôm nay
- Bắt đầu/Kết thúc chuyến đi
- Xem danh sách hành khách
- Đánh dấu trạng thái đón khách
- Báo cáo sự cố
- Quản lý hồ sơ

---

### 👤 KHÁCH HÀNG
- **URL**: `/home` (Đăng ký/Đăng nhập trên navbar)
- **Email**: `khach1@email.com` đến `khach80@email.com`
- **Mật khẩu**: `123456`

**Chức năng:**
- Tìm kiếm chuyến đi
- Đặt vé online
- Thanh toán
- Tra cứu hóa đơn

---

## 🗂️ Cấu Trúc Routes

### Admin Routes (Prefix: `/quan-ly`)
```
quan-ly/dang-nhap               → Trang đăng nhập
quan-ly/tong-quan               → Dashboard
quan-ly/tinhthanh               → CRUD Tỉnh thành
quan-ly/loaixe                  → CRUD Loại xe
quan-ly/xe                      → CRUD Xe
quan-ly/chuyendi                → CRUD Chuyến đi
quan-ly/hoadon                  → Quản lý hóa đơn
quan-ly/hoadon/{id}/duyet       → Duyệt hóa đơn
quan-ly/hoadon/{id}/huy         → Hủy hóa đơn
quan-ly/nguoidung/khach         → Quản lý khách hàng
quan-ly/nguoidung/nhanvien      → Quản lý nhân viên
quan-ly/thongke                 → Thống kê
```

### Nhân Viên Routes (Prefix: `/nhan-vien-ban-ve`)
```
nhan-vien-ban-ve/dang-nhap      → Trang đăng nhập
nhan-vien-ban-ve/tong-quan      → Dashboard
nhan-vien-ban-ve/dat-ve         → Đặt vé offline
nhan-vien-ban-ve/ve             → Quản lý vé
nhan-vien-ban-ve/chuyen-di      → Theo dõi chuyến đi
nhan-vien-ban-ve/hoa-don        → Quản lý hóa đơn
nhan-vien-ban-ve/ho-so          → Hồ sơ cá nhân
```

### Tài Xế Routes (Prefix: `/tai-xe`)
```
tai-xe/dang-nhap                → Trang đăng nhập
tai-xe/chuyen-hom-nay           → Chuyến đi hôm nay
tai-xe/hanh-khach               → Danh sách hành khách
tai-xe/su-co/bao-cao            → Báo cáo sự cố
tai-xe/ho-so                    → Hồ sơ cá nhân
```

### Customer Routes
```
/                               → Landing page
/home                           → Trang chủ
/trip/find                      → Tìm chuyến đi
/ticket/book/{tripID}           → Đặt vé
/ticket/payment                 → Thanh toán
/bill/search                    → Tra cứu hóa đơn
```

---

## 📊 Dữ Liệu Đã Seed

### Thống Kê Tổng Quan
- **Chức vụ**: 3 (Quản lý, Nhân viên, Tài xế)
- **Tỉnh thành**: 8 (Hà Nội, TP.HCM, Đà Nẵng, Đà Lạt, v.v.)
- **Loại xe**: 5 (18-45 chỗ)
- **Phương thức thanh toán**: 2 (Tiền mặt, Chuyển khoản)
- **Nhân viên**: 15 (1 QL, 7 BV, 7 TX)
- **Khách hàng**: 80
- **Xe**: 20
- **Chuyến đi**: ~90 (từ 10/11 đến 22/11/2025)
- **Vé**: ~1,900
- **Hóa đơn**: ~1,700

### Chuyến Đi (10/11 - 22/11/2025)
- **Tuyến đường**: 14 tuyến (HN-DN, SG-DL, SG-NT, v.v.)
- **Ca**: 4 ca/ngày (Sáng 6h, Chiều 12h, Tối 18h, Lượt 22h)
- **Mỗi ngày**: 5-9 chuyến
- **Trạng thái**:
  - Quá khứ: Completed (85%) / Cancelled (15%)
  - Hôm nay: In Progress / Scheduled
  - Tương lai: Scheduled

### Vé & Hóa Đơn
- **Tỷ lệ đặt vé**:
  - Chuyến quá khứ: 70-100%
  - Chuyến hôm nay: 50-80%
  - Chuyến ngày mai: 30-60%
  - Chuyến xa: 10-40%
- **Hóa đơn**: 90% vé có hóa đơn
- **Trạng thái**:
  - Đã duyệt: 87%
  - Chờ duyệt: 10%
  - Đã hủy: 3%

---

## 🚀 Chạy Seeder

### Seed toàn bộ database:
```bash
php artisan db:seed
```
Hoặc:
```bash
php artisan db:seed --class=MasterSeeder
```

### Reset và seed lại:
```bash
php artisan migrate:fresh --seed
```

---

## ⚠️ Lưu Ý

1. **Tất cả tài khoản** đều có mật khẩu mặc định: `123456`
2. **Middleware**:
   - Admin: `admin.auth`
   - Nhân viên: `nhanvien.auth`
   - Tài xế: `taixe.auth`
3. **Session-based authentication** (không dùng JWT)
4. **Broadcasting**: Pusher đã được cấu hình cho real-time seat updates
5. **Route prefix**:
   - Admin: `quan-ly`
   - Nhân viên: `nhan-vien-ban-ve`
   - Tài xế: `tai-xe`

---

## 🔗 URLs Quan Trọng

- Landing Page: http://localhost:8000/
- Admin Login: http://localhost:8000/quan-ly/dang-nhap
- Staff Login: http://localhost:8000/nhan-vien-ban-ve/dang-nhap
- Driver Login: http://localhost:8000/tai-xe/dang-nhap
- Customer Home: http://localhost:8000/home
- Stats Check: http://localhost:8000/check-seeder-stats

