<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChuyendiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chuyendi')->insert([
            [
                'machuyendi' => 'HN-DN-231025C',
                'tenchuyen' => 'Hà Nội - Đà Nẵng (Chiều)',
                'maxe' => 'XE001',
                'SLgheconlai' => 38,
                'thoigiandi' => '2025-10-23 15:00:00',
                'thoigiandichuyen' => 900,
                'gia' => 500000,
            ],
            [
                'machuyendi' => 'SG-DL-221025L',
                'tenchuyen' => 'Sài Gòn - Đà Lạt (Limousine)',
                'maxe' => 'XE002',
                'SLgheconlai' => 21,
                'thoigiandi' => '2025-10-22 10:00:00',
                'thoigiandichuyen' => 360,
                'gia' => 350000,
            ],
            [
                'machuyendi' => 'SG-DN-221025A',
                'tenchuyen' => 'Sài Gòn - Đà Nẵng (Sáng)',
                'maxe' => 'XE001',
                'SLgheconlai' => 43,
                'thoigiandi' => '2025-10-22 08:00:00',
                'thoigiandichuyen' => 960,
                'gia' => 450000,
            ],
        ]);
    }
}
