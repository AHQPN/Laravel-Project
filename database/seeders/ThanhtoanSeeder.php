<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThanhtoanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('thanhtoan')->insert([
            ['matt' => 'CK', 'ptthanhtoan' => 'Chuyển khoản'],
            ['matt' => 'MM', 'ptthanhtoan' => 'Ví Momo'],
            ['matt' => 'TM', 'ptthanhtoan' => 'Tiền mặt'],
            ['matt' => 'VN', 'ptthanhtoan' => 'VNPAY'],
        ]);
    }
}
