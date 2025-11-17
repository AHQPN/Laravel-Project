<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaixeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('loaixe')->insert([
            ['maloai' => 'G45', 'tenloai' => 'Xe giường nằm 45 chỗ', 'soghe' => 45],
            ['maloai' => 'L22', 'tenloai' => 'Xe Limousine 22 phòng', 'soghe' => 22],
            ['maloai' => 'N40', 'tenloai' => 'Xe ghế ngồi 40 chỗ', 'soghe' => 40],
        ]);
    }
}
