<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KhachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 60;
        $existing = DB::table('Khach')->count();
        
        for ($i = 1; $i <= $count; $i++) {
            $id = 'KH' . str_pad((string)($existing + $i), 3, '0', STR_PAD_LEFT);
            
            DB::table('Khach')->updateOrInsert(
                ['makh' => $id],
                [
                    'makh' => $id,
                    'ten' => 'Khách ' . ($existing + $i),
                    'sdt' => '08' . rand(100000000, 999999999),
                    'email' => 'khach' . ($existing + $i) . '@example.com',
                    'password' => Hash::make('123456'),
                ]
            );
        }
    }
}
