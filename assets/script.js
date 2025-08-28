// CRUD Operations JavaScript Enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide messages after 5 seconds
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => {
                if (message.parentNode) {
                    message.parentNode.removeChild(message);
                }
            }, 500);
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('.user-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const name = form.querySelector('#name').value.trim();
            const email = form.querySelector('#email').value.trim();
            
            if (name.length < 2) {
                e.preventDefault();
                showValidationError('Name must be at least 2 characters long');
                return;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                showValidationError('Please enter a valid email address');
                return;
            }
        });
    });

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show validation error
    function showValidationError(message) {
        // Remove existing validation errors
        const existingError = document.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }

        // Create new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'message error validation-error';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        
        // Insert at the top of the form section
        const formSection = document.querySelector('.form-section');
        formSection.insertBefore(errorDiv, formSection.firstChild);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            errorDiv.style.transition = 'opacity 0.5s ease-out';
            errorDiv.style.opacity = '0';
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.parentNode.removeChild(errorDiv);
                }
            }, 500);
        }, 3000);
    }

    // Confirm delete with custom styling
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('⚠️ Are you sure you want to delete this user?\n\nThis action cannot be undone.')) {
                window.location.href = this.href;
            }
        });
    });

    // Search form enhancement
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        // Auto-submit search after user stops typing (debouncing)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });

        // Clear search on Escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                window.location.href = 'index.php';
            }
        });
    }

    // Add loading states to buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            this.disabled = true;
            
            // Re-enable button after 5 seconds (fallback)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 5000);
        });
    });

    // Table row click enhancement
    const tableRows = document.querySelectorAll('.users-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on action buttons
            if (e.target.closest('.actions')) {
                return;
            }
            
            // Add visual feedback
            this.style.background = 'rgba(102, 126, 234, 0.1)';
            setTimeout(() => {
                this.style.background = '';
            }, 200);
        });
    });

    // Smooth scrolling for form focus
    const editLinks = document.querySelectorAll('a[href*="edit="]');
    editLinks.forEach(link => {
        link.addEventListener('click', function() {
            setTimeout(() => {
                document.querySelector('.form-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        });
    });

    // Auto-focus first input when editing
    if (window.location.href.includes('edit=')) {
        setTimeout(() => {
            const firstInput = document.querySelector('#name');
            if (firstInput) {
                firstInput.focus();
                firstInput.select();
            }
        }, 100);
    }

    // Add tooltips to action buttons
    const actionButtons = document.querySelectorAll('.actions .btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            const tooltip = this.getAttribute('title');
            if (tooltip) {
                // Simple tooltip implementation
                const tooltipDiv = document.createElement('div');
                tooltipDiv.className = 'tooltip';
                tooltipDiv.textContent = tooltip;
                tooltipDiv.style.cssText = `
                    position: absolute;
                    background: #333;
                    color: white;
                    padding: 5px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    z-index: 1000;
                    white-space: nowrap;
                    top: ${this.getBoundingClientRect().top - 35}px;
                    left: ${this.getBoundingClientRect().left}px;
                `;
                document.body.appendChild(tooltipDiv);
                
                this.addEventListener('mouseleave', function() {
                    if (tooltipDiv.parentNode) {
                        tooltipDiv.parentNode.removeChild(tooltipDiv);
                    }
                }, { once: true });
            }
        });
    });
});

// Print function for records
function printTable() {
    const printWindow = window.open('', '_blank');
    const tableHTML = document.querySelector('.users-table').outerHTML;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Adsindia - Users List</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .actions { display: none; }
                @media print { .actions { display: none !important; } }
            </style>
        </head>
        <body>
            <h1>Adsindia - Users List</h1>
            <p>Generated on: ${new Date().toLocaleDateString()}</p>
            ${tableHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}

// Export to CSV function
function exportToCSV() {
    const table = document.querySelector('.users-table');
    const rows = table.querySelectorAll('tr');
    const csvData = [];
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        
        cols.forEach((col, index) => {
            // Skip actions column
            if (index < cols.length - 1) {
                rowData.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
            }
        });
        
        if (rowData.length > 0) {
            csvData.push(rowData.join(','));
        }
    });
    
    const csvContent = csvData.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = 'adsindia_users.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}