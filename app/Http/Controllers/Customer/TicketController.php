<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Chuyendi;
use App\Models\Khach;
use App\Models\Hoadon;
use App\Models\CTHD;
use App\Models\Ve;

class TicketController extends Controller
{
    public function findTicket()
    {
        if (!Session::has('UserID')) {
            Session::flash('ShowLogin', true);
            return redirect()->route('home.index');
        }
        return view('ticket.findticket');
    }

    private function releaseExpiredPendingSeats($tripID)
    {
        Ve::where('machuyendi', $tripID)
            ->where('trangthai', 'Pending')
            ->where('pending_expires_at', '<', Carbon::now())
            ->update([
                'trangthai' => 'Available',
                'pending_expires_at' => null
            ]);
    }

    public function bookTicket($tripID)
    {
        Session::forget('message');
        $this->releaseExpiredPendingSeats($tripID);

        $trip = Chuyendi::with(['xe.loaixe', 'ves'])->findOrFail($tripID);

        $userInfo = null;
        if (Session::has('UserID')) {
            $customerID = Session::get('UserID');
            $userInfo = Khach::find($customerID);
        }

        $takenSeats = $trip->ves
            ->whereIn('trangthai', ['Booked', 'Pending'])
            ->pluck('maghe')
            ->all();

        return view('ticket.book_ticket', compact('trip', 'userInfo', 'takenSeats'));
    }

    public function handleBookTicket(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'tripID' => 'required|string',
                'seats' => 'required|string',
                'fullname' => 'required|string',
                'phone' => 'required|string',
                'total' => 'required|numeric'
            ]);

            $tripID = $request->input('tripID');
            $seats = $request->input('seats');
            $fullname = $request->input('fullname');
            $phone = $request->input('phone');
            $total = $request->input('total');

            $seatList = array_filter(explode(',', $seats));

            if (empty($seatList)) {
                return redirect()->route('ticket.book', ['tripID' => $tripID])
                    ->with('message', 'Bạn chưa chọn ghế nào.')
                    ->with('messageType', 'danger');
            }

            $pendingTime = Carbon::now()->addMinutes(15);
            $bookedSeats = [];
            $failedSeats = [];

            $this->releaseExpiredPendingSeats($tripID);

            DB::beginTransaction();
            try {
                foreach ($seatList as $seat) {
                    $seat = trim($seat);
                    $existingTicket = Ve::where('machuyendi', $tripID)
                        ->where('maghe', $seat)
                        ->lockForUpdate()
                        ->first();

                    if ($existingTicket && $existingTicket->trangthai != 'Available') {
                        $failedSeats[] = $seat;
                    } else {
                        if ($existingTicket) {
                            $mave = $existingTicket->mave;
                        } else {
                            $mave = 'VE' . Str::upper(Str::random(8));
                        }

                        Ve::updateOrCreate(
                            ['machuyendi' => $tripID, 'maghe' => $seat],
                            [
                                'mave' => $mave,
                                'trangthai' => 'Pending',
                                'pending_expires_at' => $pendingTime
                            ]
                        );
                        
                        $bookedSeats[] = $seat;
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('ticket.book', ['tripID' => $tripID])
                    ->with('message', 'Đã xảy ra lỗi: ' . $e->getMessage())
                    ->with('messageType', 'danger');
            }


            if (!empty($failedSeats)) {
                return redirect()->route('ticket.book', ['tripID' => $tripID])
                    ->with('message', 'Ghế: ' . implode(', ', $failedSeats) . ' đã bị đặt. Vui lòng chọn lại.')
                    ->with('messageType', 'danger');
            }

            if (!empty($bookedSeats)) {
                // Lưu session với save() để đảm bảo dữ liệu được ghi
                session([
                    'booking_temp' => [
                        'tripID' => $tripID,
                        'seats' => implode(',', $bookedSeats),
                        'fullname' => $fullname,
                        'phone' => $phone,
                        'total' => $total,
                        'expires_at' => Carbon::now()->addMinutes(15)->timestamp
                    ]
                ]);

                session()->save();

                return redirect()->route('ticket.thanhToan');
            }

            return redirect()->route('ticket.book', ['tripID' => $tripID])
                ->with('message', 'Bạn chưa chọn ghế nào.')
                ->with('messageType', 'danger');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('ticket.book', ['tripID' => $request->input('tripID', '')])
                ->with('message', 'Lỗi hệ thống: ' . $e->getMessage())
                ->with('messageType', 'danger');
        }
    }

    public function thanhToan(Request $request)
    {
        $bookingData = session('booking_temp');
        if (!$bookingData) {
            return redirect()->route('ticket.find')
                ->with('message', 'Phiên đặt vé đã hết hạn. Vui lòng đặt lại.')
                ->with('messageType', 'danger');
        }

        if (isset($bookingData['expires_at']) && Carbon::now()->timestamp > $bookingData['expires_at']) {
            session()->forget('booking_temp');
            return redirect()->route('ticket.find')
                ->with('message', 'Phiên đặt vé đã hết hạn (15 phút). Vui lòng đặt lại.')
                ->with('messageType', 'danger');
        }

        $tripID = $bookingData['tripID'];
        $seats = $bookingData['seats'];
        $fullname = $bookingData['fullname'];
        $phone = $bookingData['phone'];

        try {
            $trip = Chuyendi::with(['xe.loaixe'])->findOrFail($tripID);
            $seatList = explode(',', $seats);

            $bookedTickets = Ve::where('machuyendi', $trip->machuyendi)
                ->whereIn('maghe', $seatList)
                ->where('trangthai', 'Pending')
                ->get();

            $calculatedTotal = $bookedTickets->count() * $trip->gia;

            if($bookedTickets->count() != count($seatList)) {
                session()->forget('booking_temp');
                return redirect()->route('ticket.book', ['tripID' => $trip->machuyendi])
                    ->with('message', 'Vé của bạn đã hết hạn hoặc bị đặt bởi người khác. Vui lòng đặt lại.')
                    ->with('messageType', 'danger');
            }

            return view('pay.thanhtoan', [
                'trip' => $trip,
                'seats' => $seatList,
                'fullname' => $fullname,
                'phone' => $phone,
                'total' => $calculatedTotal,
                'bookedTickets' => $bookedTickets
            ]);
        } catch (\Exception $e) {
            session()->forget('booking_temp');
            return redirect()->route('ticket.find')
                ->with('message', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->with('messageType', 'danger');
        }
    }

    public function paymentConfirm(Request $request)
    {
        $tripID = $request->input('tripID');
        $seats = $request->input('seats');
        $action = $request->input('action');
        $seatList = explode(',', $seats);

        try {
            $trip = Chuyendi::findOrFail($tripID);

            if ($action == 'cancel') {
                Ve::where('machuyendi', $tripID)
                    ->whereIn('maghe', $seatList)
                    ->where('trangthai', 'Pending')
                    ->update([
                        'trangthai' => 'Available',
                        'pending_expires_at' => null
                    ]);
                session()->forget('booking_temp');
                return redirect()->route('home.index')
                    ->with('message', 'Đã hủy đặt vé.')
                    ->with('messageType', 'info');
            }

            DB::beginTransaction();
            try {
                $tickets = Ve::where('machuyendi', $tripID)
                    ->whereIn('maghe', $seatList)
                    ->where('trangthai', 'Pending')
                    ->lockForUpdate()
                    ->get();

                if ($tickets->count() != count($seatList)) {
                    DB::rollBack();
                    session()->forget('booking_temp');
                    return redirect()->route('ticket.book', ['tripID' => $tripID])
                        ->with('message', 'Vé của bạn đã hết hạn hoặc đã bị đặt. Vui lòng đặt lại.')
                        ->with('messageType', 'danger');
                }

                foreach ($tickets as $ticket) {
                    $ticket->update(['trangthai' => 'Booked', 'pending_expires_at' => null]);
                }

                $trip->decrement('SLgheconlai', $tickets->count());

                $userID = Session::get('UserID');
                $total = $tickets->count() * $trip->gia;

                $bill = Hoadon::create([
                    'mahd' => 'HD'.Str::upper(Str::random(8)),
                    'makh' => $userID ?? 'GUEST',
                    'manv' => null,
                    'matt' => 'CK',
                    'thoigian' => Carbon::now(),
                    'soluong' => $tickets->count(),
                    'thanhtien' => $total,
                    'trangthai' => 'Paid'
                ]);

                $cthdData = [];
                foreach ($tickets as $ticket) {
                    $cthdData[] = [
                        'mahd' => $bill->mahd,
                        'mave' => $ticket->mave,
                        'dongia' => $trip->gia
                    ];
                }
                CTHD::insert($cthdData);

                DB::commit();

                session()->forget('booking_temp');
                return redirect()->route('ticket.paymentSuccess', ['billID' => $bill->mahd]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            session()->forget('booking_temp');
            return redirect()->route('home.index')
                ->with('message', 'Lỗi khi thanh toán: ' . $e->getMessage())
                ->with('messageType', 'danger');
        }
    }

    public function paymentSuccess(Request $request)
    {
        $billID = $request->input('billID');

        if (!$billID) {
            return redirect()->route('home.index')
                ->with('message', 'Không tìm thấy hóa đơn.')
                ->with('messageType', 'danger');
        }

        try {
            $bill = Hoadon::with(['khach', 'cthds.ve.chuyendi'])
                ->findOrFail($billID);

            return view('pay.payment-success', compact('bill'));
        } catch (\Exception $e) {
            return redirect()->route('home.index')
                ->with('message', 'Không tìm thấy hóa đơn: ' . $e->getMessage())
                ->with('messageType', 'danger');
        }
    }

    public function bookingResult(Request $request)
    {
        $bookingResult = Session::get('bookingResult');
        if (!$bookingResult) {
            return redirect()->route('home.index');
        }
        return view('ticket.bookingresult', compact('bookingResult'));
    }

    public function rollbackBooking(Request $request)
    {
        $tripId = $request->input('tripId');
        $seats = $request->input('seats');

        if (empty($tripId) || empty($seats)) {
            return response()->json(['success' => false, 'message' => 'Thiếu thông tin'], 400);
        }
        $seatList = explode(',', $seats);
        try {
            Ve::where('machuyendi', $tripId)
                ->whereIn('maghe', $seatList)
                ->where('trangthai', 'Pending')
                ->update([
                    'trangthai' => 'Available',
                    'pending_expires_at' => null
                ]);
            return response()->json(['success' => true, 'message' => 'Đã hủy các ghế pending.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi server'], 500);
        }
    }
}
