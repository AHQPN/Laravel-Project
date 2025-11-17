<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ve')->insert([
            ['mave' => 'VE00001', 'machuyendi' => 'SG-DN-221025A', 'maghe' => 'A01'],
            ['mave' => 'VE00002', 'machuyendi' => 'SG-DN-221025A', 'maghe' => 'A02'],
            ['mave' => 'VE00003', 'machuyendi' => 'SG-DL-221025L', 'maghe' => 'P05'],
            ['mave' => 'VE00004', 'machuyendi' => 'HN-DN-231025C', 'maghe' => 'B10'],
            ['mave' => 'VE00005', 'machuyendi' => 'HN-DN-231025C', 'maghe' => 'B11'],
        ]);
    }
}
