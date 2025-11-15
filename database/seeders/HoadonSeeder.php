<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HoadonSeeder extends Seeder
{
    private static int $hdCounter = 0;

    public function run(): void
    {
        self::$hdCounter = DB::table('Hoadon')->count();
        
        $tickets = DB::table('Ve')
            ->join('Chuyendi', 'Ve.machuyendi', '=', 'Chuyendi.machuyendi')
            ->select('Ve.mave', 'Ve.machuyendi', 'Chuyendi.gia', 'Chuyendi.thoigiandi')
            ->get();
        
        $customers = DB::table('Khach')->pluck('makh')->toArray();
        $employees = DB::table('Nhanvien')->pluck('manv')->toArray();
        
        foreach ($tickets as $ticket) {
            $date = Carbon::parse($ticket->thoigiandi);
            $mahd = $this->generateMaHoaDon($date);
            
            $kh = $customers[array_rand($customers)];
            $nv = $employees[array_rand($employees)];
            
            DB::table('Hoadon')->insert([
                'mahd' => $mahd,
                'thoigian' => $date->copy()->addMinutes(rand(0, 60))->format('Y-m-d H:i:s'),
                'makh' => $kh,
                'manv' => $nv,
                'matt' => ['TT', 'CK', 'TD'][rand(0, 2)],
                'soluong' => 1,
                'thanhtien' => $ticket->gia,
                'trangthai' => ['Chờ duyệt', 'Đã thanh toán', 'Đã hủy'][rand(0, 2)],
            ]);
            
            // Create CTHD entry
            DB::table('CTHD')->insert([
                'mahd' => $mahd,
                'mave' => $ticket->mave,
                'dongia' => $ticket->gia,
            ]);
        }
    }

    private function generateMaHoaDon(Carbon $date): string
    {
        self::$hdCounter++;
        $suffix = strtoupper(base_convert(self::$hdCounter, 10, 36));
        return substr('HD' . $date->format('ymd') . $suffix, 0, 10);
    }
}
