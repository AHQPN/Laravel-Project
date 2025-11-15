<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['maxe' => 'XE001', 'soxe' => '29B-12345', 'maloai' => 'LX1', 'manv' => 'NV003', 'manvpx' => 'NV004'],
            ['maxe' => 'XE002', 'soxe' => '51A-54321', 'maloai' => 'LX3', 'manv' => 'NV003', 'manvpx' => 'NV004'],
            ['maxe' => 'XE003', 'soxe' => '30F-67890', 'maloai' => 'LX4', 'manv' => 'NV003', 'manvpx' => 'NV004'],
            ['maxe' => 'XE004', 'soxe' => '35K-11111', 'maloai' => 'LX2', 'manv' => null, 'manvpx' => null],
            ['maxe' => 'XE005', 'soxe' => '60C-22222', 'maloai' => 'LX5', 'manv' => null, 'manvpx' => null],
        ];
        
        foreach ($vehicles as $data) {
            DB::table('Xe')->updateOrInsert(
                ['maxe' => $data['maxe']],
                $data
            );
        }
    }
}
