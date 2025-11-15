/**
 * Table Sorting Utility
 * Tự động thêm sorting functionality cho các bảng có class 'sortable-table'
 * 
 * Usage:
 * <table class="table sortable-table" data-sort-column="created_at" data-sort-direction="desc">
 *   <thead>
 *     <tr>
 *       <th data-sort="manv">Mã NV <i class="fas fa-sort"></i></th>
 *       <th data-sort="ten">Họ tên <i class="fas fa-sort"></i></th>
 *       <th>Thao tác</th> <!-- No data-sort = not sortable -->
 *     </tr>
 *   </thead>
 * </table>
 */

$(document).ready(function() {
    // Initialize sortable tables
    $('.sortable-table').each(function() {
        const $table = $(this);
        const currentSort = $table.data('sort-column') || getUrlParam('sort') || '';
        const currentDirection = $table.data('sort-direction') || getUrlParam('direction') || 'asc';
        
        // Update header icons based on current sort
        updateSortIcons($table, currentSort, currentDirection);
        
        // Add click handlers to sortable headers
        $table.find('th[data-sort]').css('cursor', 'pointer').on('click', function() {
            const column = $(this).data('sort');
            const newDirection = (column === currentSort && currentDirection === 'asc') ? 'desc' : 'asc';
            
            // Navigate to sorted URL
            const url = updateUrlParam('sort', column);
            const finalUrl = updateUrlParam('direction', newDirection, url);
            window.location.href = finalUrl;
        });
    });
    
    /**
     * Update sort icons in table headers
     */
    function updateSortIcons($table, sortColumn, direction) {
        $table.find('th[data-sort]').each(function() {
            const $th = $(this);
            const column = $th.data('sort');
            const $icon = $th.find('i');
            
            if (!$icon.length) return;
            
            if (column === sortColumn) {
                // Active sort column
                $icon.removeClass('fa-sort fa-sort-up fa-sort-down text-muted')
                     .addClass(direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down')
                     .addClass('text-primary');
                $th.addClass('text-primary fw-bold');
            } else {
                // Inactive sort column
                $icon.removeClass('fa-sort-up fa-sort-down text-primary')
                     .addClass('fa-sort text-muted');
                $th.removeClass('text-primary fw-bold');
            }
        });
    }
    
    /**
     * Get URL parameter value
     */
    function getUrlParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param) || '';
    }
    
    /**
     * Update URL parameter and return new URL
     */
    function updateUrlParam(param, value, baseUrl = window.location.href) {
        const url = new URL(baseUrl);
        url.searchParams.set(param, value);
        return url.toString();
    }
});

/**
 * Per-page selector handler
 * Usage: <select class="per-page-selector">...</select>
 */
$(document).on('change', '.per-page-selector', function() {
    const perPage = $(this).val();
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to page 1
    window.location.href = url.toString();
});
