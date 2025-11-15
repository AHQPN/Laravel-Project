<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanvienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Nhanvien')->insert([
            // Quản lý
            [
                'manv' => 'NV001',
                'macv' => 'QL',
                'password' => Hash::make('123456'),
                'ten' => 'Nguyễn Văn An',
                'sdt' => '0905123456',
                'diachi' => '123 Võ Văn Ngân, TP. Thủ Đức',
                'cccd' => '079123456789',
                'email' => 'an.nguyen@email.com',
                'ngaysinh' => '1990-01-15',
                'gioitinh' => 'Nam',
                'hinhanh' => 'avatar1.jpg',
                'trangthai' => 1,
            ],
            // Nhân viên bán vé
            [
                'manv' => 'NV002',
                'macv' => 'NVBV',
                'password' => Hash::make('123456'),
                'ten' => 'Trần Thị Bình',
                'sdt' => '0918765432',
                'diachi' => '456 Lê Văn Việt, Quận 9',
                'cccd' => '082123456789',
                'email' => 'binh.tran@email.com',
                'ngaysinh' => '1995-05-20',
                'gioitinh' => 'Nữ',
                'hinhanh' => 'avatar2.jpg',
                'trangthai' => 1,
            ],
            // Tài xế
            [
                'manv' => 'NV003',
                'macv' => 'TX',
                'password' => Hash::make('123456'),
                'ten' => 'Lê Hoàng Cường',
                'sdt' => '0987654321',
                'diachi' => '789 Đỗ Xuân Hợp, Quận 2',
                'cccd' => '083123456789',
                'email' => 'cuong.le@email.com',
                'ngaysinh' => '1988-11-30',
                'gioitinh' => 'Nam',
                'hinhanh' => 'avatar3.jpg',
                'trangthai' => 1,
            ],
            // Phụ xe
            [
                'manv' => 'NV004',
                'macv' => 'PX',
                'password' => Hash::make('123456'),
                'ten' => 'Phạm Thị Dung',
                'sdt' => '0933444555',
                'diachi' => '101 Man Thiện, Quận 9',
                'cccd' => '084123456789',
                'email' => 'dung.pham@email.com',
                'ngaysinh' => '1998-02-10',
                'gioitinh' => 'Nữ',
                'hinhanh' => 'avatar4.jpg',
                'trangthai' => 1,
            ],
            // Phụ xe thứ 2
            [
                'manv' => 'NV005',
                'macv' => 'PX',
                'password' => Hash::make('123456'),
                'ten' => 'Võ Minh Tuấn',
                'sdt' => '0912345678',
                'diachi' => '202 Hoàng Diệu 2, Quận Thủ Đức',
                'cccd' => '085123456789',
                'email' => 'tuan.vo@email.com',
                'ngaysinh' => '1992-07-25',
                'gioitinh' => 'Nam',
                'hinhanh' => 'avatar5.jpg',
                'trangthai' => 1,
            ],
        ]);
    }
}
