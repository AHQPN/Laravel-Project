<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TinhThanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('TinhThanh')->insert([
            ['matinh' => 'CT', 'ten' => 'Cần Thơ'],
            ['matinh' => 'DL', 'ten' => 'Đà Lạt'],
            ['matinh' => 'DN', 'ten' => 'Đà Nẵng'],
            ['matinh' => 'HN', 'ten' => 'Hà Nội'],
            ['matinh' => 'HP', 'ten' => 'Hải Phòng'],
            ['matinh' => 'NT', 'ten' => 'Nha Trang'],
            ['matinh' => 'SG', 'ten' => 'TP. Hồ Chí Minh'],
            ['matinh' => 'VT', 'ten' => 'Vũng Tàu'],
        ]);
    }
}
