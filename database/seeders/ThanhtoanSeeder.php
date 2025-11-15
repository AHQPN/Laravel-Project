<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThanhtoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            ['matt' => 'TT', 'ptthanhtoan' => 'Tiền mặt'],
            ['matt' => 'CK', 'ptthanhtoan' => 'Chuyển khoản'],
            ['matt' => 'TD', 'ptthanhtoan' => 'Thẻ'],
        ];
        
        foreach ($payments as $data) {
            DB::table('Thanhtoan')->updateOrInsert(
                ['matt' => $data['matt']],
                $data
            );
        }
    }
}
