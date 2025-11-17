/**
 * Client-side Pagination Handler
 * Handles pagination for tables with JavaScript
 */

class Pagination {
    constructor(options) {
        this.tableId = options.tableId;
        this.paginationId = options.paginationId;
        this.itemsPerPage = options.itemsPerPage || 10;
        this.currentPage = 1;
        this.totalItems = 0;
        this.totalPages = 0;
        
        this.table = document.getElementById(this.tableId);
        this.paginationContainer = document.getElementById(this.paginationId);
        
        if (!this.table || !this.paginationContainer) {
            console.error('Table or pagination container not found');
            return;
        }
        
        this.tbody = this.table.querySelector('tbody');
        this.allRows = Array.from(this.tbody.querySelectorAll('tr')).filter(row => 
            !row.classList.contains('no-results')
        );
        
        this.init();
    }
    
    init() {
        this.totalItems = this.allRows.length;
        this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
        this.render();
    }
    
    render() {
        this.displayItems();
        this.renderPagination();
        this.updateInfo();
    }
    
    displayItems() {
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        
        this.allRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    renderPagination() {
        if (this.totalPages <= 1) {
            this.paginationContainer.innerHTML = '';
            return;
        }
        
        let html = '<nav><ul class="pagination justify-content-center mb-0">';
        
        // Previous button
        html += `
            <li class="page-item ${this.currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${this.currentPage - 1}">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
        
        // Smart page numbers
        const { startPage, endPage } = this.getPageRange();
        
        // First page
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <li class="page-item ${i === this.currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
        
        // Last page
        if (endPage < this.totalPages) {
            if (endPage < this.totalPages - 1) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${this.totalPages}">${this.totalPages}</a></li>`;
        }
        
        // Next button
        html += `
            <li class="page-item ${this.currentPage === this.totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${this.currentPage + 1}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
        
        html += '</ul></nav>';
        
        this.paginationContainer.innerHTML = html;
        
        // Add event listeners
        this.paginationContainer.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.currentTarget.getAttribute('data-page'));
                if (page && page !== this.currentPage && page >= 1 && page <= this.totalPages) {
                    this.goToPage(page);
                }
            });
        });
    }
    
    getPageRange() {
        const maxPagesToShow = 5;
        let startPage, endPage;
        
        if (this.totalPages <= maxPagesToShow) {
            startPage = 1;
            endPage = this.totalPages;
        } else {
            if (this.currentPage <= 3) {
                startPage = 1;
                endPage = 5;
            } else if (this.currentPage >= this.totalPages - 2) {
                startPage = this.totalPages - 4;
                endPage = this.totalPages;
            } else {
                startPage = this.currentPage - 2;
                endPage = this.currentPage + 2;
            }
        }
        
        return { startPage, endPage };
    }
    
    updateInfo() {
        const infoElement = document.querySelector(`[data-pagination-info="${this.tableId}"]`);
        if (infoElement) {
            const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
            const endItem = Math.min(this.currentPage * this.itemsPerPage, this.totalItems);
            infoElement.textContent = `Hiển thị ${startItem}-${endItem} trong tổng ${this.totalItems} mục`;
        }
    }
    
    goToPage(page) {
        this.currentPage = page;
        this.render();
        
        // Scroll to top of table
        this.table.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    refresh(newRows = null) {
        if (newRows) {
            this.allRows = newRows;
        } else {
            this.allRows = Array.from(this.tbody.querySelectorAll('tr')).filter(row => 
                !row.classList.contains('no-results')
            );
        }
        
        this.currentPage = 1;
        this.totalItems = this.allRows.length;
        this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
        this.render();
    }
    
    setItemsPerPage(count) {
        this.itemsPerPage = count;
        this.currentPage = 1;
        this.totalPages = Math.ceil(this.totalItems / this.itemsPerPage);
        this.render();
    }
}

// Export for use in modules or make global
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Pagination;
} else {
    window.Pagination = Pagination;
}
