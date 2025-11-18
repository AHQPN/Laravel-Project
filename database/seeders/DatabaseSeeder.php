<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Chucvu
        DB::table('chucvu')->insert([
            ['macv' => 'NVBV', 'chucvu' => 'Nhân viên bán vé'],
            ['macv' => 'PX', 'chucvu' => 'Phụ xe'],
            ['macv' => 'QL', 'chucvu' => 'Quản lý'],
            ['macv' => 'TX', 'chucvu' => 'Tài xế'],
        ]);

        // Seed TinhThanh
        DB::table('tinhthanh')->insert([
            ['matinh' => 'CT', 'ten' => 'Cần Thơ'],
            ['matinh' => 'DL', 'ten' => 'Đà Lạt'],
            ['matinh' => 'DN', 'ten' => 'Đà Nẵng'],
            ['matinh' => 'HN', 'ten' => 'Hà Nội'],
            ['matinh' => 'HP', 'ten' => 'Hải Phòng'],
            ['matinh' => 'NT', 'ten' => 'Nha Trang'],
            ['matinh' => 'SG', 'ten' => 'TP. Hồ Chí Minh'],
            ['matinh' => 'VT', 'ten' => 'Vũng Tàu'],
        ]);

        // Seed Loaixe
        DB::table('loaixe')->insert([
            ['maloai' => 'G45', 'tenloai' => 'Xe giường nằm 45 chỗ', 'soghe' => 45],
            ['maloai' => 'L22', 'tenloai' => 'Xe Limousine 22 phòng', 'soghe' => 22],
            ['maloai' => 'N40', 'tenloai' => 'Xe ghế ngồi 40 chỗ', 'soghe' => 40],
        ]);

        // Seed Thanhtoan
        DB::table('thanhtoan')->insert([
            ['matt' => 'CK', 'ptthanhtoan' => 'Chuyển khoản'],
            ['matt' => 'TD', 'ptthanhtoan' => 'Thẻ Debit/Credit'],
            ['matt' => 'TT', 'ptthanhtoan' => 'Tiền mặt tại văn phòng'],
        ]);

        // Seed Nhanvien
        DB::table('nhanvien')->insert([
            [
                'manv' => 'NV001',
                'macv' => 'QL',
                'password' => '123',
                'ten' => 'Nguyễn Văn An',
                'sdt' => '0905123456',
                'diachi' => '123 Võ Văn Ngân, TP. Thủ Đức',
                'cccd' => '079123456789',
                'email' => 'an.nguyen@email.com',
                'ngaysinh' => '1990-01-15',
                'gioitinh' => 'Nam',
                'hinhanh' => 'avatar1.jpg',
                'trangthai' => 1
            ],
            [
                'manv' => 'NV002',
                'macv' => 'NVBV',
                'password' => '123',
                'ten' => 'Trần Thị Bình',
                'sdt' => '0918765432',
                'diachi' => '456 Lê Văn Việt, Quận 9',
                'cccd' => '082123456789',
                'email' => 'binh.tran@email.com',
                'ngaysinh' => '1995-05-20',
                'gioitinh' => 'Nữ',
                'hinhanh' => 'avatar2.jpg',
                'trangthai' => 1
            ],
            [
                'manv' => 'NV003',
                'macv' => 'TX',
                'password' => '123',
                'ten' => 'Lê Hoàng Cường',
                'sdt' => '0987654321',
                'diachi' => '789 Đỗ Xuân Hợp, Quận 2',
                'cccd' => '083123456789',
                'email' => 'cuong.le@email.com',
                'ngaysinh' => '1988-11-30',
                'gioitinh' => 'Nam',
                'hinhanh' => 'avatar3.jpg',
                'trangthai' => 1
            ],
            [
                'manv' => 'NV004',
                'macv' => 'PX',
                'password' => '123',
                'ten' => 'Phạm Thị Dung',
                'sdt' => '0933444555',
                'diachi' => '101 Man Thiện, Quận 9',
                'cccd' => '084123456789',
                'email' => 'dung.pham@email.com',
                'ngaysinh' => '1998-02-10',
                'gioitinh' => 'Nữ',
                'hinhanh' => 'avatar4.jpg',
                'trangthai' => 1
            ],
        ]);

        // Seed Khach
        DB::table('khach')->insert([
            [
                'makh' => 'KH001',
                'password' => '123',
                'ten' => 'Trần Văn Bảo',
                'sdt' => '0912345678',
                'diachi' => '123 Nguyễn Huệ, Quận 1',
                'ngaysinh' => '1999-03-12',
                'gioitinh' => 'Nam'
            ],
            [
                'makh' => 'KH002',
                'password' => '123',
                'ten' => 'Lê Thị Thu Thủy',
                'sdt' => '0987123456',
                'diachi' => '456 Đồng Khởi, Bến Nghé, Quận 1',
                'ngaysinh' => '2001-07-25',
                'gioitinh' => 'Nữ'
            ],
            [
                'makh' => 'KH003',
                'password' => '123',
                'ten' => 'Phạm Minh Tuấn',
                'sdt' => '0977888999',
                'diachi' => '789 Hai Bà Trưng, Quận 3',
                'ngaysinh' => '1995-11-05',
                'gioitinh' => 'Nam'
            ],
        ]);

        // Seed Xe
        DB::table('xe')->insert([
            ['maxe' => 'XE001', 'maloai' => 'G45', 'soxe' => '51A-123.45', 'manv' => 'NV003'],
            ['maxe' => 'XE002', 'maloai' => 'L22', 'soxe' => '51B-678.90', 'manv' => 'NV003'],
            ['maxe' => 'XE003', 'maloai' => 'N40', 'soxe' => '51C-111.22', 'manv' => 'NV003'],
        ]);

        // Seed Chuyendi (sample data)
        DB::table('chuyendi')->insert([
            [
                'machuyendi' => 'HN-DN-231025C',
                'tenchuyen' => 'Hà Nội - Đà Nẵng (Chiều)',
                'maxe' => 'XE001',
                'SLgheconlai' => 38,
                'thoigiandi' => '2025-10-23 15:00:00',
                'thoigiandichuyen' => 900,
                'gia' => 500000,
                'trangthai' => 'sap_chay',
                'batdau_luc' => null,
                'ketthuc_luc' => null
            ],
            [
                'machuyendi' => 'SG-DL-221025L',
                'tenchuyen' => 'Sài Gòn - Đà Lạt (Limousine)',
                'maxe' => 'XE002',
                'SLgheconlai' => 21,
                'thoigiandi' => '2025-10-22 10:00:00',
                'thoigiandichuyen' => 360,
                'gia' => 350000,
                'trangthai' => 'sap_chay',
                'batdau_luc' => null,
                'ketthuc_luc' => null
            ],
            [
                'machuyendi' => 'SG-DN-221025A',
                'tenchuyen' => 'Sài Gòn - Đà Nẵng (Sáng)',
                'maxe' => 'XE001',
                'SLgheconlai' => 43,
                'thoigiandi' => '2025-10-22 08:00:00',
                'thoigiandichuyen' => 960,
                'gia' => 450000,
                'trangthai' => 'sap_chay',
                'batdau_luc' => null,
                'ketthuc_luc' => null
            ],
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
