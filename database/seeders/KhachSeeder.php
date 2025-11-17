<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhachSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('khach')->insert([
            [
                'makh' => 'KH001',
                'password' => '123',
                'ten' => 'Trần Văn Bảo',
                'sdt' => '0912345678',
                'diachi' => '123 Nguyễn Huệ, Quận 1',
                'ngaysinh' => '1999-03-12',
                'gioitinh' => 'Nam',
            ],
            [
                'makh' => 'KH002',
                'password' => '123',
                'ten' => 'Lê Thị Thu Thủy',
                'sdt' => '0987123456',
                'diachi' => '456 Đồng Khởi, Bến Nghé, Quận 1',
                'ngaysinh' => '2001-07-25',
                'gioitinh' => 'Nữ',
            ],
            [
                'makh' => 'KH003',
                'password' => '123',
                'ten' => 'Phạm Minh Tuấn',
                'sdt' => '0977888999',
                'diachi' => '789 Hai Bà Trưng, Quận 3',
                'ngaysinh' => '1995-11-05',
                'gioitinh' => 'Nam',
            ],
        ]);
    }
}
