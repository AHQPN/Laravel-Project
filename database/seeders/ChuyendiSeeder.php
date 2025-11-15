<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChuyendiSeeder extends Seeder
{
    private Carbon $maxDate;

    public function __construct()
    {
        $this->maxDate = Carbon::create(2025, 11, 22, 23, 59, 59);
    }

    public function run(): void
    {
        $this->seedTripsAndRoutes(30);
    }

    private function randomDateTime(): Carbon
    {
        // Phân bổ: 30% quá khứ (10-14/11), 30% hôm nay/ngày mai (15-16/11), 40% tương lai xa (17-22/11)
        $rand = rand(1, 100);
        
        if ($rand <= 30) {
            // 30% - Quá khứ: 10/11 00:00 đến 14/11 23:59
            $start = Carbon::create(2025, 11, 10, 0, 0, 0);
            $end = Carbon::create(2025, 11, 14, 23, 59, 59);
        } elseif ($rand <= 60) {
            // 30% - Hôm nay & ngày mai: 15/11 13:00 (sau thời điểm hiện tại) đến 16/11 23:59
            $start = Carbon::create(2025, 11, 15, 13, 0, 0);
            $end = Carbon::create(2025, 11, 16, 23, 59, 59);
        } else {
            // 40% - Tương lai xa: 17/11 00:00 đến 22/11 23:59
            $start = Carbon::create(2025, 11, 17, 0, 0, 0);
            $end = $this->maxDate; // 22/11/2025 23:59:59
        }
        
        $diffSeconds = $end->diffInSeconds($start);
        if ($diffSeconds <= 0) {
            return $start;
        }
        $rand = rand(0, $diffSeconds);
        return $start->copy()->addSeconds($rand);
    }

    private function seedTripsAndRoutes(int $count): void
    {
        $routes = [
            ['HN','DN'],['SG','DL'],['HN','HP'],['SG','NT'],['SG','VT'],['HN','DL'],['SG','DN']
        ];
        $xeIds = DB::table('Xe')->pluck('maxe')->toArray();
        $usedCodes = [];
        
        for ($i=1;$i<=$count;$i++) {
            $pair = $routes[array_rand($routes)];
            $date = $this->randomDateTime();
            $shiftLetter = ['S','C','T','L'][rand(0,3)];
            
            // Ensure unique code by appending counter if collision
            $baseCode = $pair[0] . '-' . $pair[1] . '-' . $date->format('dmy') . $shiftLetter;
            $code = $baseCode;
            $attempt = 0;
            while (in_array($code, $usedCodes, true) || DB::table('Chuyendi')->where('machuyendi', $code)->exists()) {
                $attempt++;
                $code = $baseCode . $attempt;
            }
            $usedCodes[] = $code;
            
            $maxe = $xeIds[array_rand($xeIds)];
            $gia = [150000,250000,300000,350000,400000,450000,500000][rand(0,6)];
            
            DB::table('Chuyendi')->insert([
                'machuyendi' => $code,
                'tenchuyen' => $pair[0] . ' - ' . $pair[1] . ' (' . $shiftLetter . ')',
                'maxe' => $maxe,
                'SLgheconlai' => null,
                'thoigiandi' => $date->format('Y-m-d H:i:s'),
                'thoigiandichuyen' => rand(120, 960),
                'gia' => $gia,
            ]);
            
            // Lộ trình 2 điểm
            DB::table('Lotrinh')->insert([
                ['machuyendi' => $code,'matinh'=>$pair[0],'trinhtu'=>1],
                ['machuyendi' => $code,'matinh'=>$pair[1],'trinhtu'=>2],
            ]);
        }
    }
}
