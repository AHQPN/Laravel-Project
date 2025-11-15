@php
    $flashSuccess = session('success');
    $flashError = session('error');
@endphp

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    function showToast(message, type = 'success') {
        const background = type === 'success'
            ? 'linear-gradient(to right, #00b09b, #96c93d)'
            : 'linear-gradient(to right, #ff5f6d, #ffc371)';

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: { background },
        }).showToast();
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if ($flashSuccess)
            showToast(@json($flashSuccess), 'success');
        @endif

        @if ($flashError)
            showToast(@json($flashError), 'error');
        @endif
    });
</script>


