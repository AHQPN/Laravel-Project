<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CthddSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cthd')->insert([
            ['mahd' => 'HD001', 'mave' => 'VE00001', 'dongia' => 450000],
            ['mahd' => 'HD001', 'mave' => 'VE00002', 'dongia' => 450000],
            ['mahd' => 'HD002', 'mave' => 'VE00003', 'dongia' => 350000],
            ['mahd' => 'HD003', 'mave' => 'VE00004', 'dongia' => 500000],
            ['mahd' => 'HD003', 'mave' => 'VE00005', 'dongia' => 500000],
        ]);
    }
}
