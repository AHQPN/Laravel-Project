@props([
    'brandIcon' => 'fas fa-bus',
    'brandTitle' => 'Bảng điều khiển',
    'items' => [],
])

<aside class="layout-sidebar">
    <div class="sidebar-brand text-center py-4 border-bottom border-opacity-25 border-light mb-3">
        <i class="{{ $brandIcon }} fs-2 text-primary mb-2"></i>
        <h4 class="text-white fw-semibold mb-0">{{ $brandTitle }}</h4>
    </div>
    <nav class="sidebar-nav px-3">
        <ul class="nav flex-column gap-1">
            @foreach($items as $item)
                @if(isset($item['type']) && $item['type'] === 'header')
                    <li class="nav-item mt-3 mb-2">
                        <small class="text-light text-uppercase fw-bold px-3" style="font-size: 0.7rem; letter-spacing: 0.5px; opacity: 0.6;">
                            {{ $item['label'] }}
                        </small>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ $item['url'] }}"
                            class="nav-link d-flex align-items-center gap-3 {{ ($item['active'] ?? false) ? 'active' : '' }}">
                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>

