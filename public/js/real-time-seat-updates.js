/**
 * Real-time Seat Updates Script
 * Handles live seat availability updates for trip booking
 */

function initSeatUpdates(maChuyenDi) {
    if (!maChuyenDi) {
        console.warn('Mã chuyến đi không hợp lệ');
        return;
    }

    // Check if Laravel Echo is available for real-time updates
    if (typeof Echo !== 'undefined' && Echo) {
        Echo.channel(`trip.${maChuyenDi}`)
            .listen('SeatUpdated', (e) => {
                console.log('Seat updated:', e);
                if (typeof reloadSeats === 'function') {
                    reloadSeats();
                }
            });
    } else {
        // Fallback: Poll every 15 seconds
        console.log('Using polling fallback for seat updates');
        setInterval(() => {
            if (typeof reloadSeats === 'function') {
                reloadSeats();
            }
        }, 15000);
    }
}
