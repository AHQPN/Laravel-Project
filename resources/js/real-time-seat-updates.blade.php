{{-- Real-time Seat Updates Component --}}
<script>
    (function() {
        const maChuyenDi = @json($maChuyenDi ?? null);
        
        if (!maChuyenDi) {
            console.warn('Mã chuyến đi không hợp lệ');
            return;
        }

        // Kiểm tra xem có Echo (Laravel Broadcasting) không
        if (typeof Echo !== 'undefined') {
            Echo.channel(`trip.${maChuyenDi}`)
                .listen('SeatUpdated', (e) => {
                    console.log('Seat updated:', e);
                    // Reload ghế đã đặt
                    if (typeof reloadSeats === 'function') {
                        reloadSeats();
                    }
                });
        } else {
            // Fallback: Poll mỗi 10 giây
            setInterval(() => {
                if (typeof reloadSeats === 'function') {
                    reloadSeats();
                }
            }, 10000);
        }
    })();
</script>
