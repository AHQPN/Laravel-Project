@props([
    'title' => 'Bộ lọc',
    'resetRoute' => null,
    'hasReset' => true,
])

<div {{ $attributes->merge(['class' => 'card mb-4 shadow-sm border-0']) }}>
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-uppercase small text-muted">{{ $title }}</span>
        @if($hasReset && $resetRoute)
            <x-button href="{{ $resetRoute }}" variant="link" class="text-decoration-none text-reset">
                <i class="fas fa-undo me-1"></i>Đặt lại
            </x-button>
        @endif
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>

