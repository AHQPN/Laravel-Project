<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tắt foreign key check để insert dữ liệu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Seed từng bảng theo thứ tự dependencies
        $this->call([
            ChucvuSeeder::class,
            TinhtanhSeeder::class,
            LoaixeSeeder::class,
            ThanhtoanSeeder::class,
            NhanvienSeeder::class,
            KhachSeeder::class,
            XeSeeder::class,
            ChuyendiSeeder::class,
            LotrinhSeeder::class,
            VeSeeder::class,
            HoadonSeeder::class,
            CthddSeeder::class,
        ]);

        // Bật lại foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
