<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NhanvienSeeder extends Seeder
{
    public function run(): void
    {
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
                'trangthai' => 1,
            ],
            [
                'manv' => 'NV002',
                'macv' => 'NV',
                'password' => '123',
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
                'trangthai' => 1,
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
                'trangthai' => 1,
            ],
        ]);
    }
}
