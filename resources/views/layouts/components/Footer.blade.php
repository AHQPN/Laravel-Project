@props([
    'text' => '© ' . now()->year . ' Hệ thống quản lý đặt vé',
])

<footer class="layout-footer py-3 bg-white border-top text-center">
    <small class="text-muted">{{ $text }}</small>
</footer>
