<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class XeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('xe')->insert([
            ['maxe' => 'XE001', 'maloai' => 'G45', 'soxe' => '51A-123.45', 'manv' => 'NV003'],
            ['maxe' => 'XE002', 'maloai' => 'L22', 'soxe' => '51B-678.90', 'manv' => 'NV003'],
            ['maxe' => 'XE003', 'maloai' => 'N40', 'soxe' => '51C-111.22', 'manv' => 'NV003'],
        ]);
    }
}
