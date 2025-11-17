<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinhtanhSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tinhthanh')->insert([
            ['matinh' => 'CT', 'ten' => 'Cần Thơ'],
            ['matinh' => 'DL', 'ten' => 'Đà Lạt'],
            ['matinh' => 'DN', 'ten' => 'Đà Nẵng'],
            ['matinh' => 'HN', 'ten' => 'Hà Nội'],
            ['matinh' => 'NT', 'ten' => 'Nha Trang'],
            ['matinh' => 'SG', 'ten' => 'TP. Hồ Chí Minh'],
        ]);
    }
}
