<?php

namespace App\Http\Controllers\NhanVienBanVe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Nhanvien;
use App\Models\Chuyendi;
use App\Models\Lotrinh;
use App\Models\Ve;
use App\Models\Xe;
use App\Models\Khach;
use App\Models\Hoadon;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\NhanVienBanVe\UpdateProfileRequest;
use App\Http\Requests\NhanVienBanVe\UpdatePasswordRequest;

class NhanVienBanVeController extends Controller
{
    // Tạo mã hóa đơn ngắn (HD + yymmdd + 2 ký tự random)
    private function generateShortMaHoaDon(): string
    {
        do {
            $datePart = now()->format('ymd');
            $randPart = strtoupper(substr(base_convert(rand(0, 1295), 10, 36), 0, 2));
            $ma = 'HD' . $datePart . $randPart;
        } while (\App\Models\Hoadon::where('mahd', $ma)->exists());
        return $ma;
    }

    public function dashboard()
    {
        $nhanvien = session('nhanvien');
        
        // Thống kê vé trong tháng hiện tại
        $veThangNay = Ve::with('chuyendi')->whereHas('chuyendi', function($q) {
            $q->whereMonth('thoigiandi', Carbon::now()->month)
              ->whereYear('thoigiandi', Carbon::now()->year);
        })->get();
        
        $tongVeBan = $veThangNay->count();
        $tongDoanhThu = $veThangNay->sum(function($ve) {
            return $ve->chuyendi ? $ve->chuyendi->gia : 0;
        });
        $veHomNay = Ve::whereHas('chuyendi', function($q) {
            $q->whereDate('thoigiandi', Carbon::today());
        })->count();
        
        // Chuyến đi sắp tới (7 ngày)
        $now = Carbon::now();
        $chuyenDiSapToi = Chuyendi::with(['lotrinhs.tinhthanh', 'xe'])
            ->where('thoigiandi', '>', $now)
            ->where('thoigiandi', '<=', $now->copy()->addDays(7))
            ->orderBy('thoigiandi', 'asc')
            ->limit(5)
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                return [
                    'tuyen' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    'thoigian' => Carbon::parse($cd->thoigiandi)->format('H:i d/m/Y'),
                    'ghe_trong' => $cd->SLgheconlai ?? 0,
                ];
            });
        
        // Thống kê vé theo ngày (7 ngày gần nhất)
        $veTheoNgay = [];
        $today = Carbon::today();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $count = Ve::whereHas('chuyendi', function($q) use ($date) {
                $q->whereDate('thoigiandi', $date->format('Y-m-d'));
            })->count();
            $veTheoNgay[] = [
                'ngay' => $date->format('d/m'),
                'so_ve' => $count,
            ];
        }
        
        return view('NhanVienBanVe.TrangChu', compact(
            'tongVeBan',
            'tongDoanhThu',
            'veHomNay',
            'chuyenDiSapToi',
            'veTheoNgay'
        ));
    }

    public function indexHoadon(Request $request)
    {
        $nhanvien = session('nhanvien');

        $query = Hoadon::with(['khach', 'thanhtoan'])
            ->where('manv', $nhanvien->manv);

        if ($request->filled('trang_thai')) {
            $query->where('trangthai', $request->trang_thai);
        }
        if ($request->filled('ngay_lap')) {
            $query->whereDate('thoigian', $request->ngay_lap);
        }

        $hoadons = $query->orderByDesc('thoigian')->get();

        return view('NhanVienBanVe.HoaDon', compact('hoadons'));
    }

    public function createDatVe()
    {
        return view('NhanVienBanVe.DatVe');
    }

    public function storeDatVe(Request $request)
    {
        $request->validate([
            'machuyendi' => 'required|string|exists:chuyendi,machuyendi',
            'seats' => 'required|string',
            'gia_ve' => 'required|numeric',
            'kh_hoten' => 'required|string|max:255',
            'kh_sdt' => 'required|string|max:15',
            'kh_email' => 'nullable|email|max:255',
            'phuongthuc_thanhtoan' => 'required|in:tien-mat,chuyen-khoan,the',
            'ghi_chu' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            $chuyendi = Chuyendi::where('machuyendi', $request->machuyendi)->first();
            if (!$chuyendi) {
                throw new \Exception('Không tìm thấy chuyến đi.');
            }

            // Tìm hoặc tạo khách hàng
            $khach = Khach::firstOrCreate(
                ['sdt' => $request->kh_sdt],
                [
                    'makh' => 'KH' . strtoupper(Str::random(6)),
                    'hoten' => $request->kh_hoten,
                    'email' => $request->kh_email,
                    'password' => \Hash::make('123456'),
                    'trangthai' => 'hoat-dong',
                ]
            );

            $mattMap = [
                'tien-mat' => 'TM',
                'chuyen-khoan' => 'CK',
                'the' => 'THE',
            ];
            $matt = $mattMap[$request->phuongthuc_thanhtoan] ?? 'TM';

            $thanhtoan = \App\Models\Thanhtoan::firstOrCreate(
                ['matt' => $matt],
                ['ptthanhtoan' => ucfirst(str_replace('-', ' ', $request->phuongthuc_thanhtoan))]
            );

            $selectedSeats = explode(',', $request->seats);
            $soluong = count($selectedSeats);
            $thanhtien = $soluong * $request->gia_ve;

            $hoadon = \App\Models\Hoadon::create([
                'mahd' => $this->generateShortMaHoaDon(),
                'makh' => $khach->makh,
                'manv' => session('nhanvien')->manv,
                'thoigian' => now(),
                'matt' => $matt,
                'soluong' => $soluong,
                'thanhtien' => $thanhtien,
                'trangthai' => 'Đã duyệt',
            ]);

            // Tạo vé và CTHD
            $createdTickets = [];
            foreach ($selectedSeats as $seatNumber) {
                $datePartVe = now()->format('ymd');
                $randVe = strtoupper(substr(base_convert(rand(0,1295),10,36),0,2));
                $mave = 'VE' . $datePartVe . $randVe;

                $seatCode = trim($seatNumber);

                $ve = Ve::create([
                    'mave' => $mave,
                    'machuyendi' => $chuyendi->machuyendi,
                    'maghe' => $seatCode,
                    'trangthai' => 'Booked',
                ]);

                // event(new \App\Events\SeatBooked($chuyendi->machuyendi, $seatCode, 'Booked'));

                \App\Models\CTHD::create([
                    'mahd' => $hoadon->mahd,
                    'mave' => $ve->mave,
                    'dongia' => $request->gia_ve,
                ]);

                $createdTickets[] = $ve->mave;
            }

            \DB::commit();

            $message = 'Đặt vé thành công! Mã hóa đơn: ' . $hoadon->mahd . '. Đã tạo ' . count($createdTickets) . ' vé.';
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'mahd' => $hoadon->mahd,
                    'redirect_url' => route('nhan-vien-ban-ve.ve.index')
                ]);
            }
            
            return redirect()->route('nhan-vien-ban-ve.ve.index')->with('success', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi đặt vé: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Lỗi khi đặt vé: ' . $e->getMessage())->withInput();
        }
    }

    public function indexVe(Request $request)
    {
        $query = Ve::with(['chuyendi.lotrinhs.tinhthanh', 'chuyendi.xe', 'hoadon.khach']);

        if ($request->filled('ngay_di')) {
            $query->whereHas('chuyendi', function ($q) use ($request) {
                $q->whereDate('thoigiandi', $request->ngay_di);
            });
        }
        if ($request->filled('chuyen_di')) {
            $query->where('machuyendi', $request->chuyen_di);
        }
        if ($request->filled('trang_thai')) {
            $query->whereHas('hoadon', function ($q) use ($request) {
                $q->where('trangthai', $request->trang_thai);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('mave', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('hoadon.khach', function($subq) use ($searchTerm) {
                      $subq->where('ten', 'LIKE', "%{$searchTerm}%")
                           ->orWhere('sdt', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $ves = $query->latest('mave')->get();

        $chuyenDis = Chuyendi::with('lotrinhs.tinhthanh')
            ->whereDate('thoigiandi', '>=', today())
            ->get()
            ->map(function($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
                $cd->tuyen_duong = ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? '');
                return $cd;
            });

        return view('NhanVienBanVe.QuanLyVe', compact('ves', 'chuyenDis'));
    }

    public function showVe($id)
    {
        $ve = Ve::with(['chuyendi.lotrinhs.tinhthanh', 'chuyendi.xe', 'hoadon.khach'])
            ->where('mave', $id)
            ->firstOrFail();

        $chuyendi = $ve->chuyendi;
        $firstPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->last();

        $tuyen = ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A');

        return view('NhanVienBanVe.partials.TicketDetail', [
            've' => $ve,
            'tuyen' => $tuyen,
        ]);
    }

    public function destroyVe($id)
    {
        $ve = Ve::where('mave', $id)->firstOrFail();

        if ($ve->trangthai === 'Đã hủy') {
            return back()->with('error', 'Vé này đã được hủy từ trước.');
        }

        $ve->trangthai = 'Đã hủy';
        $ve->save();

        // Cập nhật lại số ghế còn lại
        $chuyendi = $ve->chuyendi;
        if ($chuyendi && isset($chuyendi->SLgheconlai)) {
            $chuyendi->SLgheconlai = max(0, ($chuyendi->SLgheconlai ?? 0) + 1);
            $chuyendi->save();
        }

        return back()->with('success', 'Đã hủy vé ' . $ve->mave . ' thành công.');
    }

    public function indexChuyenDi(Request $request)
    {
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
                ->whereDate('thoigiandi', $date)
                ->orderBy('thoigiandi', 'asc');
        } else {
            $query = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
                ->where('thoigiandi', '>=', Carbon::now())
                ->orderBy('thoigiandi', 'asc');
        }

        if ($request->filled('route')) {
            [$start, $end] = explode('-', $request->route);
            $query->whereHas('lotrinhs', function ($q) use ($start) {
                $q->where('matinh', $start)->where('trinhtu', 1);
            })->whereHas('lotrinhs', function ($q) use ($end) {
                $q->where('matinh', $end)
                    ->whereRaw('trinhtu = (SELECT MAX(trinhtu) FROM Lotrinh WHERE machuyendi = Chuyendi.machuyendi)');
            });
        }
        
        $chuyenDis = $query->get();
        
        $chuyenDis->transform(function ($chuyen) {
            $firstPoint = $chuyen->lotrinhs->sortBy('trinhtu')->first();
            $lastPoint = $chuyen->lotrinhs->sortBy('trinhtu')->last();
            $chuyen->tuyen_duong = ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A');
            
            $chuyen->so_ghe_da_dat = $chuyen->ves
                ->filter(function ($ve) {
                    return !isset($ve->trangthai) || $ve->trangthai !== 'Đã hủy';
                })
                ->count();
            
            // Xác định trạng thái chuyến
            $thoiGianDi = Carbon::parse($chuyen->thoigiandi);
            if (now()->lt($thoiGianDi)) {
                $chuyen->status_display = 'Sắp khởi hành';
                $chuyen->status_key = 'sap-khoi-hanh';
            } elseif (now()->between($thoiGianDi, $thoiGianDi->copy()->addMinutes($chuyen->thoigiandichuyen ?? 240))) {
                $chuyen->status_display = 'Đang chạy';
                $chuyen->status_key = 'dang-chay';
            } else {
                $chuyen->status_display = 'Đã hoàn thành';
                $chuyen->status_key = 'hoan-thanh';
            }

            return $chuyen;
        });
        
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            $chuyenDis = $chuyenDis->filter(function($chuyen) use ($statusFilter) {
                return $chuyen->status_key === $statusFilter;
            })->values();
        }

        $routes = Chuyendi::with(['lotrinhs.tinhthanh'])
            ->whereDate('thoigiandi', '>=', now()->subDays(30))
            ->get()
            ->map(function ($cd) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint  = $cd->lotrinhs->sortBy('trinhtu')->last();
                if ($firstPoint && $lastPoint) {
                    return [
                        'value' => $firstPoint->matinh . '-' . $lastPoint->matinh,
                        'label' => ($firstPoint->tinhthanh->ten ?? '') . ' → ' . ($lastPoint->tinhthanh->ten ?? ''),
                    ];
                }
                return null;
            })
            ->filter()
            ->unique('value')
            ->values();

        return view('NhanVienBanVe.TheoDoiChuyenDi', compact('chuyenDis', 'routes'));
    }
    
    public function getChuyenDiDetails($machuyendi)
    {
        $chuyendi = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves.hoadon.khach'])
            ->where('machuyendi', $machuyendi)
            ->firstOrFail();
        
        $firstPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $chuyendi->lotrinhs->sortBy('trinhtu')->last();
        
        $bookedSeats = $chuyendi->ves
            ->where('trangthai', '!=', 'Đã hủy')
            ->pluck('maghe')
            ->map(function($seat) {
                if (is_string($seat)) {
                    return strtoupper(trim($seat));
                }
                return (string)$seat;
            })
            ->toArray();
        
        $passengers = $chuyendi->ves->map(function($ve) {
            $seatCode = is_string($ve->maghe) ? strtoupper(trim($ve->maghe)) : (string)$ve->maghe;
            return [
                'mave' => $ve->mave,
                'maghe' => $seatCode,
                'ten_khach' => $ve->hoadon?->khach?->ten ?? 'Chưa có thông tin',
                'sdt' => $ve->hoadon?->khach?->sdt ?? '---',
            ];
        });
        
        return response()->json([
            'trip' => [
                'machuyendi' => $chuyendi->machuyendi,
                'tuyen' => ($firstPoint->tinhthanh->ten ?? 'N/A') . ' → ' . ($lastPoint->tinhthanh->ten ?? 'N/A'),
                'gio_khoi_hanh' => Carbon::parse($chuyendi->thoigiandi)->format('H:i d/m/Y'),
                'bien_so' => $chuyendi->xe->soxe ?? 'N/A',
            ],
            'loaixe' => [
                'tong_so_ghe' => $chuyendi->xe->loaixe->tong_so_ghe ?? 0,
            ],
            'booked_seats' => $bookedSeats,
            'passengers' => $passengers,
        ]);
    }

    // API Methods
    public function getChuyenDiApi(Request $request)
    {
        $now = Carbon::now();
        $chuyenDis = Chuyendi::with(['lotrinhs.tinhthanh', 'xe.loaixe', 'ves'])
            ->where('thoigiandi', '>=', $now)
            ->orderBy('thoigiandi', 'asc')
            ->get();
        
        $trips = $chuyenDis->map(function($cd) {
            $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
            $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
            
            $totalSeats = $cd->xe?->loaixe?->tong_so_ghe ?? 0;
            $bookedSeats = $cd->ves->where('trangthai', '!=', 'Đã hủy')->count();
            $availableSeats = max(0, $totalSeats - $bookedSeats);
            
            return [
                'machuyendi' => $cd->machuyendi,
                'tuyen' => ($firstPoint?->tinhthanh?->ten ?? 'N/A') . ' → ' . ($lastPoint?->tinhthanh?->ten ?? 'N/A'),
                'gio_khoi_hanh' => Carbon::parse($cd->thoigiandi)->format('H:i d/m/Y'),
                'bien_so' => $cd->xe?->soxe ?? 'N/A',
                'gia_ve' => $cd->gia ?? 0,
                'ghe_trong' => $availableSeats,
                'tong_ghe' => $totalSeats,
            ];
        });
        
        return response()->json($trips);
    }
    
    public function getSeatMapApi($machuyendi)
    {
        $chuyendi = Chuyendi::with(['xe.loaixe', 'ves'])
            ->where('machuyendi', $machuyendi)
            ->firstOrFail();
        
        $totalSeats = $chuyendi->xe?->loaixe?->tong_so_ghe ?? 0;
        
        // Lấy danh sách ghế đã đặt
        $bookedSeats = $chuyendi->ves
            ->where('trangthai', '!=', 'Đã hủy')
            ->pluck('maghe')
            ->map(function($seat) {
                return strtoupper(trim($seat));
            })
            ->toArray();
        
        // Tạo danh sách tất cả ghế
        $seats = [];
        for ($i = 1; $i <= $totalSeats; $i++) {
            $seatCode = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $seats[] = [
                'ma_ghe' => $seatCode,
                'gia' => $chuyendi->gia,
                'trang_thai' => in_array($seatCode, $bookedSeats) ? 'Đã bán' : 'Trống'
            ];
        }
        
        // Xác định layout (4 cột cho xe 40 ghế, 5 cột cho xe lớn hơn)
        $cols = $totalSeats > 40 ? 5 : 4;
        
        return response()->json([
            'layout' => [
                'cols' => $cols,
                'rows' => ceil($totalSeats / $cols)
            ],
            'seats' => $seats
        ]);
    }
    
    public function getGioKhoiHanhApi(Request $request)
    {
        $request->validate(['route' => 'required|string']);

        [$start, $end] = explode('-', $request->route);

        $now = Carbon::now();

        $chuyenDis = Chuyendi::with(['lotrinhs.tinhthanh', 'xe.loaixe'])
            ->where('thoigiandi', '>=', $now)
            ->get()
            ->filter(function ($cd) use ($start, $end) {
                $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
                $lastPoint  = $cd->lotrinhs->sortBy('trinhtu')->last();
                return ($firstPoint->matinh ?? '') == $start && ($lastPoint->matinh ?? '') == $end;
            });

        $timeSlots = $chuyenDis->map(function ($cd) {
                $datetime = Carbon::parse($cd->thoigiandi);
                return [
                    'value'       => $cd->machuyendi,
                    'label'       => $datetime->format('H:i d/m/Y'),
                    'machuyendi'  => $cd->machuyendi,
                    'timestamp'   => $datetime->timestamp,
                ];
            })
            ->sortBy('timestamp')
            ->values();

        return response()->json($timeSlots);
    }

    public function getVehiclesApi(Request $request)
    {
        $request->validate(['machuyendi' => 'required|string']);

        $cd = Chuyendi::with(['xe.loaixe', 'lotrinhs.tinhthanh', 'ves'])
            ->where('machuyendi', $request->machuyendi)
            ->firstOrFail();

        if (!($cd->xe && $cd->xe->loaixe && ($cd->xe->loaixe->tong_so_ghe ?? 0) > 0)) {
            return response()->json([]);
        }

        $ve_count = $cd->ves->count();
        $total_seats = $cd->xe?->loaixe?->tong_so_ghe ?? 0;
        $available_seats = max(0, $total_seats - $ve_count);

        $firstPoint = $cd->lotrinhs->sortBy('trinhtu')->first();
        $lastPoint = $cd->lotrinhs->sortBy('trinhtu')->last();
        $tuyen = ($firstPoint?->tinhthanh?->ten ?? 'N/A') . ' → ' . ($lastPoint?->tinhthanh?->ten ?? 'N/A');

        $vehicle = [
            'value' => $cd->machuyendi,
            'label' => ($cd->xe?->soxe ?? 'N/A') . ' - ' . ($cd->xe?->loaixe?->tenloai ?? 'N/A') . ' (' . $available_seats . '/' . $total_seats . ' ghế trống)',
            'biensoxe' => $cd->xe?->soxe ?? 'N/A',
            'loaixe' => $cd->xe?->loaixe?->tenloai ?? 'N/A',
            'ghe_trong' => $available_seats,
            'tuyen' => $tuyen,
            'gia' => $cd->gia ?? 0,
        ];

        return response()->json([$vehicle]);
    }

    public function getSoDoGheApi(Request $request)
    {
        $request->validate([
            'machuyendi' => 'required|string',
        ]);

        $chuyendi = Chuyendi::where('machuyendi', $request->machuyendi)->first();

        if (!$chuyendi) {
            return response()->json(['error' => 'Không tìm thấy chuyến đi phù hợp. Vui lòng kiểm tra lại thông tin.'], 404);
        }

        $xe = Xe::with('loaixe')->where('maxe', $chuyendi->maxe)->first();
        $ves = Ve::where('machuyendi', $chuyendi->machuyendi)->get();

        $rawBookedSeats = $ves
            ->filter(function ($ve) {
                return !isset($ve->trangthai) || $ve->trangthai !== 'Đã hủy';
            })
            ->pluck('maghe')
            ->filter()
            ->map(function ($code) {
                $code = strtoupper((string) $code);
                // Chuẩn hóa mã ghế sang dạng A01..An
                if (preg_match('/^[A-Z](\d{1,2})$/', $code, $matches)) {
                    $num = (int) $matches[1];
                    return 'A' . str_pad($num, 2, '0', STR_PAD_LEFT);
                }
                if (preg_match('/^A\d{2}$/', $code)) {
                    return $code;
                }
                return $code;
            })
            ->values()
            ->toArray();

        $bookedSeats = array_values(array_unique($rawBookedSeats));

        $totalSeats = $xe->loaixe->soghe ?? ($xe->loaixe->tong_so_ghe ?? 0);

        $allSeatCodes = [];
        if ($totalSeats > 0) {
            for ($i = 1; $i <= $totalSeats; $i++) {
                $allSeatCodes[] = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
            }
        }

        $unbookedSeatCodes = array_diff($allSeatCodes, $bookedSeats);

        $seats = array_map(function ($code) use ($bookedSeats) {
            return [
                'code' => $code,
                'booked' => in_array($code, $bookedSeats, true),
            ];
        }, $allSeatCodes);

        return response()->json([
            'machuyendi' => $chuyendi->machuyendi,
            'gia_ve' => $chuyendi->gia,
            'loaixe' => [
                'tong_so_ghe' => $totalSeats,
            ],
            'booked_seats' => $bookedSeats,
            'unbooked_seats' => array_values($unbookedSeatCodes),
            'seats' => $seats,
        ]);
    }

    public function profile()
    {
        $nhanvien = Nhanvien::with('chucvu')->find(session('nhanvien')->manv);
        
        return view('NhanVienBanVe.HoSo', compact('nhanvien'));
    }

    public function editProfile()
    {
        return view('NhanVienBanVe.HoSoChinhSua');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return redirect()->route('nhan-vien-ban-ve.dang-nhap')
                    ->with('error', 'Không tìm thấy thông tin nhân viên');
            }

            if ($request->filled('hoten')) {
                $nhanvien->ten = $request->input('hoten');
            }
            
            if ($request->filled('email')) {
                $nhanvien->email = $request->input('email');
            }
            
            if ($request->filled('sdt')) {
                $nhanvien->sdt = $request->input('sdt');
            }
            
            if ($request->filled('diachi')) {
                $nhanvien->diachi = $request->input('diachi');
            }
            
            if ($request->filled('ngaysinh')) {
                $nhanvien->ngaysinh = $request->input('ngaysinh');
            }
            
            $nhanvien->save();

            $updatedNhanvien = Nhanvien::with('chucvu')->find($nhanvien->manv);
            session(['nhanvien' => $updatedNhanvien]);

            return redirect()->route('nhan-vien-ban-ve.ho-so')
                ->with('success', 'Cập nhật thông tin thành công!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function uploadAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'avatar.required' => 'Vui lòng chọn ảnh',
                'avatar.image' => 'File phải là ảnh',
                'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif',
                'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB'
            ]);

            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin nhân viên'
                ], 404);
            }

            // Xóa ảnh cũ nếu có
            if ($nhanvien->hinhanh && $nhanvien->hinhanh !== 'default-avatar.jpg') {
                $oldImagePath = public_path('storage/avatars/' . $nhanvien->hinhanh);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            $image = $request->file('avatar');
            $filename = 'avatar_' . $nhanvien->manv . '_' . time() . '.' . $image->getClientOriginalExtension();
            
            $avatarPath = public_path('storage/avatars');
            if (!file_exists($avatarPath)) {
                mkdir($avatarPath, 0755, true);
            }
            
            $image->move($avatarPath, $filename);

            $nhanvien->hinhanh = $filename;
            $nhanvien->save();

            $updatedNhanvien = Nhanvien::with('chucvu')->find($nhanvien->manv);
            session(['nhanvien' => $updatedNhanvien]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật ảnh đại diện thành công!',
                'avatar_url' => asset('storage/avatars/' . $filename)
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $nhanvien = Nhanvien::find(session('nhanvien')->manv);
            
            if (!$nhanvien) {
                return redirect()->route('nhan-vien-ban-ve.dang-nhập')
                    ->with('error', 'Không tìm thấy thông tin nhân viên');
            }

            if (!Hash::check($request->input('current_password'), $nhanvien->password)) {
                return redirect()->back()
                    ->with('error', 'Mật khẩu hiện tại không đúng');
            }

            $nhanvien->password = Hash::make($request->input('new_password'));
            $nhanvien->save();

            return redirect()->route('nhan-vien-ban-ve.ho-so')
                ->with('success', 'Đổi mật khẩu thành công');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}