<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ChucvuSeeder::class,
            TinhThanhSeeder::class,
            LoaixeSeeder::class,
            ThanhtoanSeeder::class,
            NhanvienSeeder::class,
            XeSeeder::class,
            KhachSeeder::class,
            ChuyendiSeeder::class,
            VeSeeder::class,
            HoadonSeeder::class,
        ]);
    }
}