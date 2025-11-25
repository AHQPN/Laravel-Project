@php
    // Split route string by arrow symbols (→, ->, or -)
    $parts = preg_split('/\s*(?:→|->|-)\s*/u', $route);
    $from = trim($parts[0] ?? '');
    $to = trim($parts[1] ?? '');
    
    // Shorten city names for better display
    if (function_exists('shortenCityName')) {
        $from = shortenCityName($from);
        $to = shortenCityName($to);
    }
@endphp

@if($from && $to)
    <span class="route-display">
        <span class="route-badge route-from" title="{{ $parts[0] ?? '' }}">{{ $from }}</span>
        <i class="fas fa-arrow-right route-arrow"></i>
        <span class="route-badge route-to" title="{{ $parts[1] ?? '' }}">{{ $to }}</span>
    </span>
@else
    <span class="text-muted">{{ $route }}</span>
@endif
