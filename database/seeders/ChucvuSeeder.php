<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChucvuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chucvu')->insert([
            ['macv' => 'NV', 'chucvu' => 'Nhân viên bán vé'],
            ['macv' => 'PX', 'chucvu' => 'Phụ xe'],
            ['macv' => 'QL', 'chucvu' => 'Quản lý'],
            ['macv' => 'TX', 'chucvu' => 'Tài xế'],
        ]);
    }
}
