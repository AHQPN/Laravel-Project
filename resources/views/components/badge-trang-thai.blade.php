@props(['status', 'text' => null])

@php
    // Mapping trạng thái chuyến đi
    $statusMap = [
        'chua-khoi-hanh' => ['class' => 'bg-warning text-dark', 'text' => 'Chưa khởi hành'],
        'dang-chay' => ['class' => 'bg-info text-white', 'text' => 'Đang chạy'],
        'hoan-thanh' => ['class' => 'bg-success text-white', 'text' => 'Hoàn thành'],
        'huy' => ['class' => 'bg-danger text-white', 'text' => 'Đã hủy'],
        
        // Trạng thái vé
        'da-dat' => ['class' => 'bg-primary text-white', 'text' => 'Đã đặt'],
        'da-thanh-toan' => ['class' => 'bg-success text-white', 'text' => 'Đã thanh toán'],
        'da-huy' => ['class' => 'bg-secondary text-white', 'text' => 'Đã hủy'],
        
        // Trạng thái hóa đơn
        'ready' => ['class' => 'bg-success text-white', 'text' => 'Đã tạo'],
        'pending' => ['class' => 'bg-warning text-dark', 'text' => 'Chưa tạo'],
    ];
    
    $config = $statusMap[$status] ?? ['class' => 'bg-secondary text-white', 'text' => ucfirst($status)];
    $displayText = $text ?? $config['text'];
@endphp

<span class="badge {{ $config['class'] }} px-3 py-2">{{ $displayText }}</span>
