@props([
    'title' => '',
    'userName' => null,
    'userRole' => null,
    'avatarText' => null,
    'logoutRoute' => null,
])

<header class="layout-header bg-white border-bottom d-flex align-items-center justify-content-between px-4 py-3">
    <h1 class="h4 mb-0 text-primary fw-semibold">{{ $title }}</h1>

    @if($userName)
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <div class="fw-semibold text-dark">{{ $userName }}</div>
                @if($userRole)
                    <small class="text-muted">{{ $userRole }}</small>
                @endif
            </div>
            <div class="avatar rounded-circle d-flex align-items-center justify-content-center bg-gradient" style="width:42px;height:42px;">
                <span class="fw-bold text-white">{{ $avatarText ?? substr($userName, 0, 1) }}</span>
            </div>
            @if($logoutRoute)
                <form method="POST" action="{{ $logoutRoute }}">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                    </x-button>
                </form>
            @endif
        </div>
    @endif
</header>
