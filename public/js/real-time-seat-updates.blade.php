<!-- 
    Real-Time Seat Updates với Laravel Echo + Pusher
    
    Thêm snippet này vào views chọn ghế (booking.blade.php, dat-ve.blade.php)
    Đảm bảo đã include Pusher JS và Laravel Echo trong layout
-->

<!-- Scripts cho Pusher và Laravel Echo -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

<script>
// Initialize Laravel Echo with Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ env('PUSHER_APP_KEY') }}',
    cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
    forceTLS: true
});

// Debug Pusher connection
window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Pusher connected successfully!');
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.error('❌ Pusher connection error:', err);
});

// Listen to seat updates for this trip
document.addEventListener('DOMContentLoaded', function() {
    const tripId = '{{ $machuyendi ?? $trip->machuyendi ?? '' }}'; // Adjust based on your view variable
    
    if (!tripId) {
        console.warn('⚠️ Trip ID not found. Real-time updates disabled.');
        return;
    }

    console.log('👂 Listening to seat updates for trip:', tripId);

    // Subscribe to trip channel
    window.Echo.channel('trip.' + tripId)
        .listen('.seat.booked', (event) => {
            console.log('🔔 Seat update received:', event);
            
            // Update seat display
            updateSeatDisplay(event.seat_number, event.status);
            
            // Show notification
            showSeatNotification(event.seat_number, event.status);
        });
});

/**
 * Update seat display in UI
 */
function updateSeatDisplay(seatNumber, status) {
    // Find seat element - adjust selector based on your HTML structure
    const seatElement = document.querySelector(`[data-seat="${seatNumber}"]`) 
        || document.querySelector(`[data-soghe="${seatNumber}"]`)
        || document.querySelector(`#seat-${seatNumber}`);
    
    if (!seatElement) {
        console.warn(`Seat ${seatNumber} element not found in DOM`);
        return;
    }

    // Remove all status classes
    seatElement.classList.remove('available', 'unavailable', 'pending', 'booked', 'selected');
    
    // Add new status class based on status
    if (status === 'Available') {
        seatElement.classList.add('available');
        seatElement.removeAttribute('disabled');
        seatElement.style.cursor = 'pointer';
        seatElement.style.backgroundColor = '#e9ecef'; // Light gray for available
    } else {
        // Unavailable states: Pending, Booked, approved, pending
        seatElement.classList.add('unavailable');
        seatElement.setAttribute('disabled', 'disabled');
        seatElement.style.cursor = 'not-allowed';
        seatElement.style.backgroundColor = '#6c757d'; // Dark gray for unavailable
        
        // If user had selected this seat, deselect it
        const selectedSeatsInput = document.querySelector('input[name="soghe[]"][value="' + seatNumber + '"]');
        if (selectedSeatsInput) {
            selectedSeatsInput.checked = false;
        }
    }
    
    // Update seat label/text if needed
    const seatLabel = seatElement.querySelector('.seat-label') || seatElement;
    if (seatLabel) {
        const statusText = status === 'Available' ? 'Trống' : 'Đã đặt';
        seatLabel.setAttribute('title', `Ghế ${seatNumber} - ${statusText}`);
    }
}

/**
 * Show notification when seat is updated
 */
function showSeatNotification(seatNumber, status) {
    const statusText = status === 'Available' ? 'đã trở về trạng thái trống' : 'đã được đặt';
    const message = `Ghế ${seatNumber} ${statusText}`;
    
    // Use Toastify if available
    if (typeof Toastify !== 'undefined') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: 'top',
            position: 'right',
            stopOnFocus: true,
            style: {
                background: status === 'Available' 
                    ? 'linear-gradient(to right, #00b09b, #96c93d)' 
                    : 'linear-gradient(to right, #ff5f6d, #ffc371)',
            },
        }).showToast();
    } 
    // Fallback to SweetAlert2 if available
    else if (typeof Swal !== 'undefined') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: status === 'Available' ? 'info' : 'warning',
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }
    // Fallback to console
    else {
        console.log('📢', message);
    }
}

/**
 * Optional: Cleanup when leaving page
 */
window.addEventListener('beforeunload', function() {
    if (window.Echo) {
        const tripId = '{{ $machuyendi ?? $trip->machuyendi ?? '' }}';
        if (tripId) {
            window.Echo.leave('trip.' + tripId);
            console.log('👋 Left trip channel:', tripId);
        }
    }
});
</script>
