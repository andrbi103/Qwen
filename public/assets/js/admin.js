/**
 * OmniCMS Admin JavaScript File
 */

$(document).ready(function() {
    // Sidebar toggle for mobile
    $('.navbar-toggler').on('click', function() {
        $('#sidebarMenu').toggleClass('collapse');
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts
    $('.alert').not('.alert-permanent').delay(5000).fadeOut('slow');

    // Confirm delete actions
    $('.confirm-delete').on('click', function(e) {
        if (!confirm('آیا از حذف این مورد اطمینان دارید؟')) {
            e.preventDefault();
        }
    });

    // Module toggle
    $('.module-toggle').on('change', function() {
        var $toggle = $(this);
        var module = $toggle.data('module');
        var action = $toggle.is(':checked') ? 'enable' : 'disable';
        
        $.ajax({
            url: '/admin/modules/toggle',
            type: 'POST',
            data: {
                module: module,
                action: action,
                csrf_token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                } else {
                    $toggle.prop('checked', !$toggle.is(':checked'));
                    showNotification('error', response.error);
                }
            },
            error: function() {
                $toggle.prop('checked', !$toggle.is(':checked'));
                showNotification('error', 'خطایی رخ داده است');
            }
        });
    });

    // Health check refresh
    $('#refresh-health').on('click', function() {
        loadHealthStatus();
    });

    function loadHealthStatus() {
        $.ajax({
            url: '/admin/health',
            type: 'GET',
            success: function(data) {
                updateHealthDisplay(data);
            }
        });
    }

    function updateHealthDisplay(data) {
        $('.health-indicator').each(function() {
            var service = $(this).data('service');
            var $icon = $(this).find('i');
            
            if (data[service]) {
                $icon.removeClass('fa-times text-danger').addClass('fa-check text-success');
            } else {
                $icon.removeClass('fa-check text-success').addClass('fa-times text-danger');
            }
        });
    }

    // Chart initialization helper
    window.initChart = function(canvasId, type, data, options) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: data,
            options: options || {}
        });
    };
});

// Show notification
function showNotification(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    var html = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
               '<i class="fas ' + icon + '"></i> ' + message +
               '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
               '</div>';
    
    $('.notifications-container').append(html);
    
    setTimeout(function() {
        $('.alert').first().fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
    var csv = [];
    var rows = document.querySelectorAll('#' + tableId + ' tr');
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length - 1; j++) {
            row.push(cols[j].innerText);
        }
        csv.push(row.join(','));
    }
    
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    var csvFile = new Blob([csv], {type: 'text/csv'});
    var downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
