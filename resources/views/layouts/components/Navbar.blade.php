@props([
    'items' => [],
])

<nav class="mobile-nav">
    @foreach($items as $item)
        <a href="{{ $item['url'] }}"
           class="mobile-nav__item {{ ($item['active'] ?? false) ? 'active' : '' }}">
            <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
