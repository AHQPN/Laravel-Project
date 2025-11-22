<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MasterSeeder extends Seeder
{
    private int $ticketCounter = 0;
    private int $invoiceCounter = 0;

    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu seed toàn bộ database...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->truncateAllTables();
        $this->seedChucvu();
        $this->seedTinhThanh();
        $this->seedLoaixe();
        $this->seedThanhtoan();
        $this->seedNhanvien();
        $this->seedKhach();
        $this->seedXe();
        $this->seedChuyendiVaLotrinh();
        $this->seedVeVaHoadon();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Hoàn thành seed toàn bộ database!');
    }

    private function truncateAllTables(): void
    {
        $this->command->warn('🗑️  Xóa toàn bộ dữ liệu cũ...');

        $tables = ['CTHD', 'Hoadon', 'Ve', 'Lotrinh', 'Chuyendi', 'Xe', 'Khach', 'Nhanvien', 'Thanhtoan', 'Loaixe', 'TinhThanh', 'Chucvu'];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        $this->command->info('✓ Đã xóa dữ liệu cũ');
    }

    private function seedChucvu(): void
    {
        $this->command->info('👔 Tạo chức vụ...');

        DB::table('Chucvu')->insert([
            ['macv' => 'QL', 'chucvu' => 'Quản lý'],
            ['macv' => 'BV', 'chucvu' => 'Nhân viên bán vé'],
            ['macv' => 'TX', 'chucvu' => 'Tài xế'],
        ]);

        $this->command->info('✓ Đã tạo 3 chức vụ');
    }

    private function seedTinhThanh(): void
    {
        $this->command->info('🗺️  Tạo tỉnh thành...');

        DB::table('TinhThanh')->insert([
            ['matinh' => 'CT', 'ten' => 'Can Tho'],
            ['matinh' => 'DL', 'ten' => 'Da Lat'],
            ['matinh' => 'DN', 'ten' => 'Da Nang'],
            ['matinh' => 'HN', 'ten' => 'Ha Noi'],
            ['matinh' => 'HP', 'ten' => 'Hai Phong'],
            ['matinh' => 'NT', 'ten' => 'Nha Trang'],
            ['matinh' => 'SG', 'ten' => 'TP. Ho Chi Minh'],
            ['matinh' => 'VT', 'ten' => 'Vung Tau'],
        ]);

        $this->command->info('✓ Đã tạo 8 tỉnh thành');
    }

    private function seedLoaixe(): void
    {
        $this->command->info('🚍 Tạo loại xe...');

        DB::table('Loaixe')->insert([
            ['maloai' => 'LX1', 'tenloai' => 'Giuong nam 44 cho', 'soghe' => 44],
            ['maloai' => 'LX2', 'tenloai' => 'Ghe ngoi 30 cho', 'soghe' => 30],
            ['maloai' => 'LX3', 'tenloai' => 'Limousine 18 cho', 'soghe' => 18],
            ['maloai' => 'LX4', 'tenloai' => 'Ghe ngoi 45 cho', 'soghe' => 45],
            ['maloai' => 'LX5', 'tenloai' => 'Limousine 22 cho', 'soghe' => 22],
        ]);

        $this->command->info('✓ Đã tạo 5 loại xe');
    }

    private function seedThanhtoan(): void
    {
        $this->command->info('💳 Tạo phương thức thanh toán...');

        DB::table('Thanhtoan')->insert([
            ['matt' => 'TM', 'ptthanhtoan' => 'Tien mat'],
            ['matt' => 'CK', 'ptthanhtoan' => 'Chuyen khoan'],
        ]);

        $this->command->info('✓ Đã tạo 2 phương thức thanh toán');
    }

    private function seedNhanvien(): void
    {
        $this->command->info('👥 Tạo nhân viên...');

        DB::table('Nhanvien')->insert([
            [
                'manv' => 'NV001',
                'macv' => 'QL',
                'password' => Hash::make('123456'),
                'ten' => 'Nguyen Van An',
                'sdt' => '0905123456',
                'diachi' => '123 Vo Van Ngan, TP. Thu Duc',
                'cccd' => '079123456789',
                'email' => 'admin@xekhach.com',
                'ngaysinh' => '1990-01-15',
                'gioitinh' => 'Nam',
                'hinhanh' => null,
                'trangthai' => 1,
            ],
            [
                'manv' => 'NV002',
                'macv' => 'BV',
                'password' => Hash::make('123456'),
                'ten' => 'Tran Thi Binh',
                'sdt' => '0918765432',
                'diachi' => '456 Le Van Viet, Quan 9',
                'cccd' => '082123456789',
                'email' => 'nhanvien@xekhach.com',
                'ngaysinh' => '1995-05-20',
                'gioitinh' => 'Nu',
                'hinhanh' => null,
                'trangthai' => 1,
            ],
            [
                'manv' => 'NV003',
                'macv' => 'TX',
                'password' => Hash::make('123456'),
                'ten' => 'Le Hoang Cuong',
                'sdt' => '0987654321',
                'diachi' => '789 Do Xuan Hop, Quan 2',
                'cccd' => '083123456789',
                'email' => 'taixe@xekhach.com',
                'ngaysinh' => '1988-11-30',
                'gioitinh' => 'Nam',
                'hinhanh' => null,
                'trangthai' => 1,
            ],
        ]);

        $nhanvienCount = 3;
        for ($i = 4; $i <= 15; $i++) {
            $macv = ['BV', 'TX'][rand(0, 1)];
            DB::table('Nhanvien')->insert([
                'manv' => 'NV' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'macv' => $macv,
                'password' => Hash::make('123456'),
                'ten' => 'Nhan vien ' . $i,
                'sdt' => '09' . rand(10000000, 99999999),
                'diachi' => 'Dia chi ' . $i,
                'cccd' => '0' . rand(70, 89) . rand(100000000, 999999999),
                'email' => 'nhanvien' . $i . '@xekhach.com',
                'ngaysinh' => Carbon::create(rand(1985, 2000), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                'gioitinh' => ['Nam', 'Nu'][rand(0, 1)],
                'hinhanh' => null,
                'trangthai' => 1,
            ]);
            $nhanvienCount++;
        }

        $this->command->info("✓ Đã tạo {$nhanvienCount} nhân viên");
    }

    private function seedKhach(): void
    {
        $this->command->info('👤 Tạo khách hàng...');

        $count = 80;
        for ($i = 1; $i <= $count; $i++) {
            DB::table('Khach')->insert([
                'makh' => 'KH' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'ten' => 'Khach hang ' . $i,
                'sdt' => '08' . rand(10000000, 99999999),
                'email' => 'khach' . $i . '@email.com',
                'password' => Hash::make('123456'),
            ]);
        }

        $this->command->info("✓ Đã tạo {$count} khách hàng");
    }

    private function seedXe(): void
    {
        $this->command->info('🚌 Tạo xe...');

        $loaixe = ['LX1', 'LX2', 'LX3', 'LX4', 'LX5'];
        $taixe = DB::table('Nhanvien')->where('macv', 'TX')->pluck('manv')->toArray();

        $count = 20;
        for ($i = 1; $i <= $count; $i++) {
            $bienSo = rand(10, 99) . chr(rand(65, 90)) . '-' . rand(10000, 99999);

            DB::table('Xe')->insert([
                'maxe' => 'XE' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'maloai' => $loaixe[array_rand($loaixe)],
                'soxe' => $bienSo,
                'manv' => !empty($taixe) ? $taixe[array_rand($taixe)] : null,
            ]);
        }

        $this->command->info("✓ Đã tạo {$count} xe");
    }

    private function seedChuyendiVaLotrinh(): void
    {
        $this->command->info('🚌 Tạo chuyến đi và lộ trình...');

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
        ];

        $shifts = [
            ['S', '06:00'],
            ['C', '12:00'],
            ['T', '18:00'],
            ['L', '22:00'],
        ];

        $xeIds = DB::table('Xe')->pluck('maxe')->toArray();

        $tripCount = 0;
        $startDate = Carbon::create(2025, 11, 10);
        $endDate = Carbon::create(2025, 11, 22);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $tripsPerDay = rand(5, 9);

            for ($i = 0; $i < $tripsPerDay; $i++) {
                $route = $routes[array_rand($routes)];
                $shift = $shifts[array_rand($shifts)];

                $datetime = Carbon::parse($date->format('Y-m-d') . ' ' . $shift[1]);

                $baseCode = $route[0] . '-' . $route[1] . '-' . $datetime->format('dmy') . $shift[0];
                $code = $baseCode;
                $attempt = 0;

                while (DB::table('Chuyendi')->where('machuyendi', $code)->exists()) {
                    $attempt++;
                    $code = $baseCode . $attempt;
                    if ($attempt > 10)
                        break;
                }

                $maxe = $xeIds[array_rand($xeIds)];
                $xe = DB::table('Xe')->where('maxe', $maxe)->first();
                $loaixe = $xe ? DB::table('Loaixe')->where('maloai', $xe->maloai)->first() : null;
                $sucChua = $loaixe ? $loaixe->soghe : 40;

                $trangthai = 'Scheduled';
                if ($datetime->isPast()) {
                    $trangthai = rand(1, 100) <= 85 ? 'Completed' : 'Cancelled';
                } elseif ($datetime->isToday() && $datetime->lt(now())) {
                    $trangthai = 'In Progress';
                }

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

                DB::table('Lotrinh')->insert([
                    ['machuyendi' => $code, 'matinh' => $route[0], 'trinhtu' => 1],
                    ['machuyendi' => $code, 'matinh' => $route[1], 'trinhtu' => 2],
                ]);

                $tripCount++;
            }
        }

        $this->command->info("✓ Đã tạo {$tripCount} chuyến đi");
    }

    private function seedVeVaHoadon(): void
    {
        $this->command->info('🎫 Tạo vé và hóa đơn...');

        $trips = DB::table('Chuyendi')
            ->whereBetween('thoigiandi', ['2025-11-10 00:00:00', '2025-11-22 23:59:59'])
            ->orderBy('thoigiandi')
            ->get();

        $khachIds = DB::table('Khach')->pluck('makh')->toArray();
        $nhanvienIds = DB::table('Nhanvien')->where('macv', 'BV')->pluck('manv')->toArray();

        $ticketCount = 0;
        $invoiceCount = 0;

        foreach ($trips as $trip) {
            if ($trip->trangthai === 'Cancelled') {
                continue;
            }

            $xe = DB::table('Xe')->where('maxe', $trip->maxe)->first();
            $loaixe = $xe ? DB::table('Loaixe')->where('maloai', $xe->maloai)->first() : null;
            $sucChua = $loaixe ? $loaixe->soghe : 40;

            $tripDate = Carbon::parse($trip->thoigiandi);
            $soldPercentage = 0;

            if ($tripDate->isPast()) {
                $soldPercentage = rand(70, 100);
            } elseif ($tripDate->isToday()) {
                $soldPercentage = rand(50, 80);
            } elseif ($tripDate->isTomorrow()) {
                $soldPercentage = rand(30, 60);
            } else {
                $soldPercentage = rand(10, 40);
            }

            $soldSeats = (int) (($soldPercentage / 100) * $sucChua);
            $soldSeats = max(1, min($soldSeats, $sucChua));

            $seats = [];
            for ($i = 1; $i <= $sucChua; $i++) {
                $seats[] = $i <= 20 ? 'A' . str_pad($i, 2, '0', STR_PAD_LEFT) : 'B' . str_pad($i - 20, 2, '0', STR_PAD_LEFT);
            }
            shuffle($seats);

            $seatsToBook = array_slice($seats, 0, $soldSeats);

            foreach ($seatsToBook as $seatNumber) {
                $this->ticketCounter++;
                $mave = 'VE' . str_pad($this->ticketCounter, 5, '0', STR_PAD_LEFT);

                DB::table('Ve')->insert([
                    'mave' => $mave,
                    'machuyendi' => $trip->machuyendi,
                    'maghe' => $seatNumber,
                ]);

                $ticketCount++;

                if (rand(1, 100) <= 90) {
                    $result = $this->createInvoice($mave, $trip, $khachIds, $nhanvienIds, $tripDate);
                    if ($result) {
                        $invoiceCount++;
                    }
                }
            }

            DB::table('Chuyendi')
                ->where('machuyendi', $trip->machuyendi)
                ->update(['SLgheconlai' => $sucChua - $soldSeats]);
        }

        $this->command->info("✓ Đã tạo {$ticketCount} vé");
        $this->command->info("✓ Đã tạo {$invoiceCount} hóa đơn");
    }

    private function createInvoice($mave, $trip, $khachIds, $nhanvienIds, $tripDate): bool
    {
        $this->invoiceCounter++;
        $mahd = 'HD' . str_pad($this->invoiceCounter, 7, '0', STR_PAD_LEFT);

        $attempt = 0;
        while (DB::table('Hoadon')->where('mahd', $mahd)->exists()) {
            $this->invoiceCounter++;
            $mahd = 'HD' . str_pad($this->invoiceCounter, 7, '0', STR_PAD_LEFT);
            $attempt++;
            if ($attempt > 100)
                return false;
        }

        $makh = $khachIds[array_rand($khachIds)];
        $manv = !empty($nhanvienIds) ? $nhanvienIds[array_rand($nhanvienIds)] : null;

        $trangthai = 'Đã duyệt';
        if ($tripDate->isFuture() && rand(1, 100) <= 10) {
            $trangthai = 'Chờ duyệt';
        } elseif ($tripDate->isPast() && rand(1, 100) <= 3) {
            $trangthai = 'Đã hủy';
        }

        $createdTime = $tripDate->copy()->subDays(rand(1, 5))->subHours(rand(0, 23));

        DB::table('Hoadon')->insert([
            'mahd' => $mahd,
            'manv' => $manv,
            'makh' => $makh,
            'thoigian' => $createdTime->format('Y-m-d H:i:s'),
            'soluong' => 1,
            'thanhtien' => $trip->gia,
            'matt' => ['TM', 'CK'][rand(0, 1)],
            'trangthai' => $trangthai,
        ]);

        DB::table('CTHD')->insert([
            'mahd' => $mahd,
            'mave' => $mave,
            'dongia' => $trip->gia,
        ]);

        return true;
    }
}
