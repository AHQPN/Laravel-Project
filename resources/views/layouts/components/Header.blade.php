@props([
    'title' => '',
    'userName' => null,
    'userRole' => null,
    'avatarText' => null,
    'logoutRoute' => null,
])

<header class="layout-header bg-white border-bottom d-flex align-items-center justify-content-between px-2 px-md-4 py-3">
    <div class="d-flex align-items-center gap-2 gap-md-3">
        <button class="btn btn-link text-dark d-lg-none mobile-menu-toggle p-2" type="button" onclick="toggleSidebar()" style="display: none;">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <h1 class="h5 h4-md mb-0 text-primary fw-semibold">{{ $title }}</h1>
    </div>

    @if($userName)
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold text-dark small">{{ $userName }}</div>
                @if($userRole)
                    <small class="text-muted">{{ $userRole }}</small>
                @endif
            </div>
            <div class="avatar rounded-circle d-flex align-items-center justify-content-center bg-gradient" style="width:40px;height:40px;">
                <span class="fw-bold text-white">{{ $avatarText ?? substr($userName, 0, 1) }}</span>
            </div>
            @if($logoutRoute)
                <form method="POST" action="{{ $logoutRoute }}" class="d-none d-md-block">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">
                        <i class="fas fa-sign-out-alt me-1"></i><span class="d-none d-lg-inline">Đăng xuất</span>
                    </x-button>
                </form>
            @endif
        </div>
    @endif
</header>

<script>
function toggleSidebar() {
    document.querySelector('.layout-sidebar').classList.toggle('show');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('.layout-sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth < 992 && sidebar && toggle) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target) && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    }
});
</script>
