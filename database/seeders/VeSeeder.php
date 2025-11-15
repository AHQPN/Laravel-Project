<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VeSeeder extends Seeder
{
    private static int $veCounter = 0;

    public function run(): void
    {
        self::$veCounter = DB::table('Ve')->count();
        
        $trips = DB::table('Chuyendi')->get();
        
        foreach ($trips as $trip) {
            // Random tickets sold for this trip (5-20 tickets)
            $sold = rand(5, 20);
            $usedSeats = [];
            
            for ($i = 0; $i < $sold; $i++) {
                $seatNum = rand(1, 50);
                $seatCode = 'A' . str_pad((string)$seatNum, 2, '0', STR_PAD_LEFT);
                
                if (in_array($seatCode, $usedSeats, true)) {
                    continue; // avoid duplicates
                }
                $usedSeats[] = $seatCode;
                
                $date = Carbon::parse($trip->thoigiandi);
                $veCode = $this->generateMaVe($date);
                
                DB::table('Ve')->insert([
                    'mave' => $veCode,
                    'machuyendi' => $trip->machuyendi,
                    'maghe' => $seatCode,
                ]);
            }
            
            // Update remaining seats
            $soghe = DB::table('Xe')
                ->join('Loaixe', 'Xe.maloai', '=', 'Loaixe.maloai')
                ->where('Xe.maxe', $trip->maxe)
                ->value('soghe');
                
            if ($soghe) {
                DB::table('Chuyendi')
                    ->where('machuyendi', $trip->machuyendi)
                    ->update(['SLgheconlai' => max(0, $soghe - count($usedSeats))]);
            }
        }
    }

    private function generateMaVe(Carbon $date): string
    {
        self::$veCounter++;
        $suffix = strtoupper(base_convert(self::$veCounter, 10, 36));
        return substr('VE' . $date->format('ymd') . $suffix, 0, 10);
    }
}
