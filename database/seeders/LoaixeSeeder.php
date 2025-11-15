<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoaixeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loaixe = [
            ['maloai' => 'LX1', 'tenloai' => 'Giường nằm 44 chỗ', 'soghe' => 44],
            ['maloai' => 'LX2', 'tenloai' => 'Ghế ngồi 30 chỗ', 'soghe' => 30],
            ['maloai' => 'LX3', 'tenloai' => 'Limousine 18 chỗ', 'soghe' => 18],
            ['maloai' => 'LX4', 'tenloai' => 'Ghế ngồi 45 chỗ', 'soghe' => 45],
            ['maloai' => 'LX5', 'tenloai' => 'Limousine 22 chỗ', 'soghe' => 22],
        ];
        
        foreach ($loaixe as $data) {
            DB::table('Loaixe')->updateOrInsert(
                ['maloai' => $data['maloai']],
                $data
            );
        }
    }
}
