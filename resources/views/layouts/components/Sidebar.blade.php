@props([
    'brandIcon' => 'fas fa-bus',
    'brandTitle' => 'Bảng điều khiển',
    'items' => [],
])

<aside class="layout-sidebar collapsed" id="sidebar">
    <div class="sidebar-toggle" id="sidebar-toggle-btn">
        <i class="fas fa-chevron-left"></i>
    </div>

    <div class="sidebar-brand text-center py-4 border-bottom border-opacity-25 border-light mb-3">
        <div class="d-flex flex-column align-items-center">
            <i class="{{ $brandIcon }} fs-2 text-primary mb-2"></i>
            <h4 class="text-white fw-semibold mb-0 sidebar-brand-text">{{ $brandTitle }}</h4>
        </div>
    </div>
    <nav class="sidebar-nav px-3">
        <ul class="nav flex-column gap-1">
            @foreach($items as $item)
                @if(isset($item['type']) && $item['type'] === 'header')
                    <li class="nav-item sidebar-section-header mt-3 mb-2">
                        <small class="text-light text-uppercase fw-bold px-3 d-block" style="font-size: 0.7rem; letter-spacing: 0.5px; opacity: 0.6;">
                            {{ $item['label'] }}
                        </small>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ $item['url'] }}"
                            class="nav-link {{ ($item['active'] ?? false) ? 'active' : '' }}">
                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>

<script>
function toggleSidebar() {
    console.log('toggleSidebar() function called!');
    const sidebar = document.getElementById('sidebar');
    
    if (!sidebar) {
        console.error('ERROR: Sidebar element not found!');
        return;
    }
    
    const wasCollapsed = sidebar.classList.contains('collapsed');
    
    console.log('Toggle - Before:', wasCollapsed ? 'collapsed' : 'expanded');
    console.log('Toggle - classList:', sidebar.classList.toString());
    
    if (wasCollapsed) {
        sidebar.classList.remove('collapsed');
        localStorage.setItem('sidebarCollapsed', 'false');
        console.log('Toggle - After: expanded');
        console.log('Toggle - New classList:', sidebar.classList.toString());
    } else {
        sidebar.classList.add('collapsed');
        localStorage.setItem('sidebarCollapsed', 'true');
        console.log('Toggle - After: collapsed');
        console.log('Toggle - New classList:', sidebar.classList.toString());
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle-btn');
    const savedState = localStorage.getItem('sidebarCollapsed');
    
    if (savedState === 'false') {
        sidebar.classList.remove('collapsed');
    } else {
        sidebar.classList.add('collapsed');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const wasCollapsed = sidebar.classList.contains('collapsed');
            
            if (wasCollapsed) {
                sidebar.classList.remove('collapsed');
                localStorage.setItem('sidebarCollapsed', 'false');
            } else {
                sidebar.classList.add('collapsed');
                localStorage.setItem('sidebarCollapsed', 'true');
            }
        });
    }

    sidebar.addEventListener('click', function(e) {
        if (e.target.closest('a')) {
            return;
        }
        if (e.target.closest('.sidebar-toggle')) {
            return;
        }
        
        if (this.classList.contains('collapsed')) {
            this.classList.remove('collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    });
});
</script>