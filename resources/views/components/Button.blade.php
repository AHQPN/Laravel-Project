@props([
    'type' => 'button',
    'variant' => 'primary',
    'icon' => null,
    'size' => 'md',
    'block' => false,
    'href' => null,
    'disabled' => false,
])

@php
    $variantClasses = [
        'primary' => 'btn btn-primary',
        'secondary' => 'btn btn-secondary',
        'outline' => 'btn btn-outline-primary',
        'success' => 'btn btn-success',
        'danger' => 'btn btn-danger',
        'link' => 'btn btn-link',
    ];

    $sizeClasses = [
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
    ];

    $classes = $variantClasses[$variant] ?? $variantClasses['primary'];
    $classes .= ' ' . ($sizeClasses[$size] ?? '');
    if ($block) {
        $classes .= ' w-100';
    }
@endphp

@if($href)
    <a href="{{ $disabled ? '#' : $href }}"
       {{ $attributes->merge(['class' => trim($classes . ($disabled ? ' disabled' : ''))]) }}
       @if($disabled) aria-disabled="true" tabindex="-1" @endif>
        @if($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
        {{ $attributes->merge(['class' => trim($classes)]) }}
        @if($disabled) disabled @endif>
        @if($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        {{ $slot }}
    </button>
@endif

