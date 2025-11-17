<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LotrinhSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lotrinh')->insert([
            ['machuyendi' => 'HN-DN-231025C', 'matinh' => 'DN', 'trinhtu' => 2],
            ['machuyendi' => 'SG-DN-221025A', 'matinh' => 'DN', 'trinhtu' => 3],
            ['machuyendi' => 'SG-DL-221025L', 'matinh' => 'DL', 'trinhtu' => 2],
            ['machuyendi' => 'HN-DN-231025C', 'matinh' => 'HN', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DN-221025A', 'matinh' => 'NT', 'trinhtu' => 2],
            ['machuyendi' => 'SG-DL-221025L', 'matinh' => 'SG', 'trinhtu' => 1],
            ['machuyendi' => 'SG-DN-221025A', 'matinh' => 'SG', 'trinhtu' => 1],
        ]);
    }
}
