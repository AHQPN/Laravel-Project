<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucvuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Chucvu')->insert([
            ['macv' => 'QL', 'chucvu' => 'Quản lý'],
            ['macv' => 'NVBV', 'chucvu' => 'Nhân viên bán vé'],
            ['macv' => 'TX', 'chucvu' => 'Tài xế'],
            ['macv' => 'PX', 'chucvu' => 'Phụ xe']
        ]);
    }
}
