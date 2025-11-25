<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewTripsSeeder extends Seeder
{
    private int $ticketCounter = 0;
    private int $invoiceCounter = 0;

    /**
     * Seed dữ liệu cho các ngày 25/11, 27/11 và 28/11/2025
     */
    public function run(): void
    {
        $this->command->info('Bắt đầu seed chuyến đi mới...');

        // Lấy counter hiện tại
        $this->ticketCounter = DB::table('Ve')->count();
        $this->invoiceCounter = DB::table('Hoadon')->count();

        // Seed cho 3 ngày
        $this->seedChuyendiForDates();
        
        $this->command->info('Hoàn thành seed chuyến đi mới!');
    }

    private function seedChuyendiForDates(): void
    {
        $this->command->info('Tạo chuyến đi cho ngày 25/11, 27/11 và 28/11/2025...');

        // Các tuyến đường phổ biến
        $routes = [
            ['HN', 'DN', 'Ha Noi - Da Nang', 350000, 720],
            ['DN', 'HN', 'Da Nang - Ha Noi', 350000, 720],
            ['SG', 'DL', 'Sai Gon - Da Lat', 250000, 360],
            ['DL', 'SG', 'Da Lat - Sai Gon', 250000, 360],
            ['HN', 'HP', 'Ha Noi - Hai Phong', 150000, 120],
            ['HP', 'HN', 'Hai Phong - Ha Noi', 150000, 120],
            ['SG', 'NT', 'Sai Gon - Nha Trang', 300000, 480],
            ['NT', 'SG', 'Nha Trang - Sai Gon', 300000, 480],
            ['SG', 'VT', 'Sai Gon - Vung Tau', 200000, 150],
            ['VT', 'SG', 'Vung Tau - Sai Gon', 200000, 150],
            ['HN', 'DL', 'Ha Noi - Da Lat', 500000, 1200],
            ['DL', 'HN', 'Da Lat - Ha Noi', 500000, 1200],
            ['SG', 'DN', 'Sai Gon - Da Nang', 400000, 960],
            ['DN', 'SG', 'Da Nang - Sai Gon', 400000, 960],
            ['SG', 'CT', 'Sai Gon - Can Tho', 180000, 240],
            ['CT', 'SG', 'Can Tho - Sai Gon', 180000, 240],
        ];

        // Các ca chạy
        $shifts = [
            ['S', '06:00'],  // Sáng
            ['C', '12:00'],  // Chiều
            ['T', '18:00'],  // Tối
            ['L', '22:00'],  // Đêm
        ];

        $xeIds = DB::table('Xe')->pluck('maxe')->toArray();
        
        if (empty($xeIds)) {
            $this->command->error('Không có xe nào trong database!');
            return;
        }

        // Các ngày cần seed
        $dates = [
            Carbon::create(2025, 11, 25),
            Carbon::create(2025, 11, 27),
            Carbon::create(2025, 11, 28),
        ];

        $tripCount = 0;

        foreach ($dates as $date) {
            $this->command->info("Tạo chuyến cho ngày {$date->format('d/m/Y')}...");
            
            // Tạo 8-12 chuyến mỗi ngày
            $tripsPerDay = rand(8, 12);

            for ($i = 0; $i < $tripsPerDay; $i++) {
                $route = $routes[array_rand($routes)];
                $shift = $shifts[array_rand($shifts)];

                $datetime = Carbon::parse($date->format('Y-m-d') . ' ' . $shift[1]);

                // Tạo mã chuyến đi
                $baseCode = $route[0] . '-' . $route[1] . '-' . $datetime->format('dmy') . $shift[0];
                $code = $baseCode;
                $attempt = 0;

                while (DB::table('Chuyendi')->where('machuyendi', $code)->exists()) {
                    $attempt++;
                    $code = $baseCode . $attempt;
                    if ($attempt > 10) break;
                }

                // Lấy thông tin xe
                $maxe = $xeIds[array_rand($xeIds)];
                $xe = DB::table('Xe')->where('maxe', $maxe)->first();
                $loaixe = $xe ? DB::table('Loaixe')->where('maloai', $xe->maloai)->first() : null;
                $sucChua = $loaixe ? $loaixe->soghe : 40;

                // Xác định trạng thái
                $trangthai = 'Scheduled';
                $now = Carbon::now();
                
                if ($datetime->isPast()) {
                    $trangthai = rand(1, 100) <= 85 ? 'Completed' : 'Cancelled';
                } elseif ($datetime->isToday() && $datetime->lt($now)) {
                    $trangthai = 'In Progress';
                }

                // Tạo chuyến đi
                DB::table('Chuyendi')->insert([
                    'machuyendi' => $code,
                    'tenchuyen' => $route[2] . ' (' . $shift[0] . ')',
                    'maxe' => $maxe,
                    'SLgheconlai' => $sucChua,
                    'thoigiandi' => $datetime->format('Y-m-d H:i:s'),
                    'thoigiandichuyen' => $route[4],
                    'gia' => $route[3],
                    'trangthai' => $trangthai,
                ]);

                // Tạo lộ trình
                DB::table('Lotrinh')->insert([
                    ['machuyendi' => $code, 'matinh' => $route[0], 'trinhtu' => 1],
                    ['machuyendi' => $code, 'matinh' => $route[1], 'trinhtu' => 2],
                ]);

                // Tạo vé và hóa đơn
                if ($trangthai !== 'Cancelled') {
                    $this->seedTicketsForTrip($code, $sucChua, $route[3], $datetime, $trangthai);
                }

                $tripCount++;
            }
        }

        $this->command->info("✓ Đã tạo {$tripCount} chuyến đi mới");
    }

    private function seedTicketsForTrip($machuyendi, $sucChua, $gia, $tripDate, $trangthai): void
    {
        $khachIds = DB::table('Khach')->pluck('makh')->toArray();
        $nhanvienIds = DB::table('Nhanvien')->where('macv', 'BV')->pluck('manv')->toArray();

        if (empty($khachIds)) {
            return;
        }

        // Xác định % ghế đã bán
        $soldPercentage = 0;
        $now = Carbon::now();

        if ($tripDate->isPast()) {
            $soldPercentage = rand(70, 100);
        } elseif ($tripDate->isToday()) {
            $soldPercentage = rand(50, 80);
        } elseif ($tripDate->isTomorrow()) {
            $soldPercentage = rand(30, 60);
        } else {
            $soldPercentage = rand(10, 40);
        }

        $soldSeats = (int)(($soldPercentage / 100) * $sucChua);
        $soldSeats = max(1, min($soldSeats, $sucChua));

        // Tạo danh sách ghế
        $seats = [];
        for ($i = 1; $i <= $sucChua; $i++) {
            $seats[] = $i <= 20 ? 'A' . str_pad($i, 2, '0', STR_PAD_LEFT) : 'B' . str_pad($i - 20, 2, '0', STR_PAD_LEFT);
        }
        shuffle($seats);

        $seatsToBook = array_slice($seats, 0, $soldSeats);

        foreach ($seatsToBook as $seatNumber) {
            $this->ticketCounter++;
            $mave = 'VE' . str_pad($this->ticketCounter, 5, '0', STR_PAD_LEFT);

            // Tạo vé
            DB::table('Ve')->insert([
                'mave' => $mave,
                'machuyendi' => $machuyendi,
                'maghe' => $seatNumber,
            ]);

            // 90% vé có hóa đơn
            if (rand(1, 100) <= 90) {
                $this->createInvoice($mave, $gia, $khachIds, $nhanvienIds, $tripDate);
            }
        }

        // Cập nhật số ghế còn lại
        DB::table('Chuyendi')
            ->where('machuyendi', $machuyendi)
            ->update(['SLgheconlai' => $sucChua - $soldSeats]);
    }

    private function createInvoice($mave, $gia, $khachIds, $nhanvienIds, $tripDate): void
    {
        $this->invoiceCounter++;
        $mahd = 'HD' . str_pad($this->invoiceCounter, 7, '0', STR_PAD_LEFT);

        $attempt = 0;
        while (DB::table('Hoadon')->where('mahd', $mahd)->exists()) {
            $this->invoiceCounter++;
            $mahd = 'HD' . str_pad($this->invoiceCounter, 7, '0', STR_PAD_LEFT);
            $attempt++;
            if ($attempt > 100) return;
        }

        $makh = $khachIds[array_rand($khachIds)];
        $manv = !empty($nhanvienIds) ? $nhanvienIds[array_rand($nhanvienIds)] : null;

        // Xác định trạng thái hóa đơn
        $trangthai = 'Đã duyệt';
        if ($tripDate->isFuture() && rand(1, 100) <= 10) {
            $trangthai = 'Chờ duyệt';
        } elseif ($tripDate->isPast() && rand(1, 100) <= 3) {
            $trangthai = 'Đã hủy';
        }

        // Thời gian tạo hóa đơn (1-5 ngày trước chuyến đi)
        $createdTime = $tripDate->copy()->subDays(rand(1, 5))->subHours(rand(0, 23));

        DB::table('Hoadon')->insert([
            'mahd' => $mahd,
            'manv' => $manv,
            'makh' => $makh,
            'thoigian' => $createdTime->format('Y-m-d H:i:s'),
            'soluong' => 1,
            'thanhtien' => $gia,
            'matt' => ['TM', 'CK'][rand(0, 1)],
            'trangthai' => $trangthai,
        ]);

        DB::table('CTHD')->insert([
            'mahd' => $mahd,
            'mave' => $mave,
            'dongia' => $gia,
        ]);
    }
}
