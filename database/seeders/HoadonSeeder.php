<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HoadonSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hoadon')->insert([
            [
                'mahd' => 'HD001',
                'thoigian' => '2025-10-20 09:30:15',
                'makh' => 'KH001',
                'manv' => 'NV002',
                'matt' => 'CK',
                'soluong' => 2,
                'thanhtien' => 900000,
                'trangthai' => 'Đã duyệt',
            ],
            [
                'mahd' => 'HD002',
                'thoigian' => '2025-10-21 10:05:00',
                'makh' => 'KH002',
                'manv' => 'NV002',
                'matt' => 'MM',
                'soluong' => 1,
                'thanhtien' => 350000,
                'trangthai' => 'Đã duyệt',
            ],
            [
                'mahd' => 'HD003',
                'thoigian' => '2025-10-21 14:15:30',
                'makh' => 'KH003',
                'manv' => 'NV002',
                'matt' => 'TM',
                'soluong' => 2,
                'thanhtien' => 1000000,
                'trangthai' => 'Chờ duyệt',
            ],
        ]);
    }
}
