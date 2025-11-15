<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotrinhSeeder extends Seeder
{
    // Tạo dữ liệu mẫu cho lộ trình các chuyến xe
    public function run(): void
    {
        DB::table('Lotrinh')->insert([
            // HN-DN-141125C: Hà Nội -> Đà Nẵng
            ['machuyendi' => 'HN-DN-141125C', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-DN-141125C', 'matinh' => 'DN', 'trinhtu' => 2],
            
            // SG-DL-141125S: Sài Gòn -> Đà Lạt
            ['machuyendi' => 'SG-DL-141125S', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DL-141125S', 'matinh' => 'DL', 'trinhtu' => 2],
            
            // HN-HP-141125S: Hà Nội -> Hải Phòng
            ['machuyendi' => 'HN-HP-141125S', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-HP-141125S', 'matinh' => 'HP', 'trinhtu' => 2],
            
            // SG-DN-151125C: Sài Gòn -> Nha Trang -> Đà Nẵng
            ['machuyendi' => 'SG-DN-151125C', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DN-151125C', 'matinh' => 'NT', 'trinhtu' => 2],
            ['machuyendi' => 'SG-DN-151125C', 'matinh' => 'DN', 'trinhtu' => 3],
            
            // HN-DN-151125T: Hà Nội -> Đà Nẵng
            ['machuyendi' => 'HN-DN-151125T', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-DN-151125T', 'matinh' => 'DN', 'trinhtu' => 2],
            
            // SG-DL-161125L: Sài Gòn -> Đà Lạt
            ['machuyendi' => 'SG-DL-161125L', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DL-161125L', 'matinh' => 'DL', 'trinhtu' => 2],
            
            // HN-HP-161125C: Hà Nội -> Hải Phòng
            ['machuyendi' => 'HN-HP-161125C', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-HP-161125C', 'matinh' => 'HP', 'trinhtu' => 2],
            
            // SG-NT-171125S: Sài Gòn -> Nha Trang
            ['machuyendi' => 'SG-NT-171125S', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-NT-171125S', 'matinh' => 'NT', 'trinhtu' => 2],
            
            // HN-DN-171125S: Hà Nội -> Đà Nẵng
            ['machuyendi' => 'HN-DN-171125S', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-DN-171125S', 'matinh' => 'DN', 'trinhtu' => 2],
            
            // SG-VT-181125L: Sài Gòn -> Vũng Tàu
            ['machuyendi' => 'SG-VT-181125L', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-VT-181125L', 'matinh' => 'VT', 'trinhtu' => 2],
            // HN-DN-191125S: Hà Nội -> Đà Nẵng
            ['machuyendi' => 'HN-DN-191125S', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-DN-191125S', 'matinh' => 'DN', 'trinhtu' => 2],
            // SG-DL-191125C: Sài Gòn -> Đà Lạt
            ['machuyendi' => 'SG-DL-191125C', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DL-191125C', 'matinh' => 'DL', 'trinhtu' => 2],
            // HN-HP-201125C: Hà Nội -> Hải Phòng
            ['machuyendi' => 'HN-HP-201125C', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-HP-201125C', 'matinh' => 'HP', 'trinhtu' => 2],
            // SG-NT-201125S: Sài Gòn -> Nha Trang
            ['machuyendi' => 'SG-NT-201125S', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-NT-201125S', 'matinh' => 'NT', 'trinhtu' => 2],
            // SG-VT-211125L: Sài Gòn -> Vũng Tàu
            ['machuyendi' => 'SG-VT-211125L', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-VT-211125L', 'matinh' => 'VT', 'trinhtu' => 2],
            // HN-DN-211125T: Hà Nội -> Đà Nẵng
            ['machuyendi' => 'HN-DN-211125T', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-DN-211125T', 'matinh' => 'DN', 'trinhtu' => 2],
            // HN-HP-221125S: Hà Nội -> Hải Phòng
            ['machuyendi' => 'HN-HP-221125S', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'HN-HP-221125S', 'matinh' => 'HP', 'trinhtu' => 2],
            // SG-DL-221125S: Sài Gòn -> Đà Lạt
            ['machuyendi' => 'SG-DL-221125S', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DL-221125S', 'matinh' => 'DL', 'trinhtu' => 2],
        ]);
    }
}
