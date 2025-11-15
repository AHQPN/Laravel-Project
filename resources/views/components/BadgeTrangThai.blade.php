@props([
    'status' => 'pending',
    'text' => null,
    'size' => 'md',
])

@php
    $map = [
        'pending' => ['label' => 'Đang chờ', 'class' => 'badge bg-warning text-dark'],
        'approved' => ['label' => 'Đã duyệt', 'class' => 'badge bg-success'],
        'cancelled' => ['label' => 'Đã hủy', 'class' => 'badge bg-danger'],
        'running' => ['label' => 'Đang chạy', 'class' => 'badge bg-info'],
        'completed' => ['label' => 'Hoàn thành', 'class' => 'badge bg-secondary'],
        'ready' => ['label' => 'Đã tạo', 'class' => 'badge bg-info text-dark'],
    ];

    $badge = $map[$status] ?? ['label' => ucfirst($status), 'class' => 'badge bg-secondary'];
    $sizeClass = [
        'sm' => 'badge-sm',
        'md' => '',
        'lg' => 'badge-lg px-3 py-2',
    ][$size] ?? '';
@endphp

<span {{ $attributes->merge(['class' => trim($badge['class'] . ' ' . $sizeClass)]) }}>
    {{ $text ?? $badge['label'] }}
</span>

