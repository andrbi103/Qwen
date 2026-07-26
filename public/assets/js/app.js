/**
 * OmniCMS Main JavaScript File
 */

$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    $('.alert').not('.alert-permanent').delay(5000).fadeOut('slow');

    // Confirm delete actions
    $('.confirm-delete').on('click', function(e) {
        if (!confirm('آیا از حذف این مورد اطمینان دارید؟')) {
            e.preventDefault();
        }
    });

    // AJAX form submission
    $('form.ajax-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method') || 'POST',
            data: $form.serialize(),
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr) {
                showNotification('error', 'خطایی رخ داده است');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });

    // Language switcher
    $('.lang-switcher a').on('click', function(e) {
        e.preventDefault();
        var lang = $(this).data('lang');
        $.cookie('lang', lang, { path: '/', expires: 365 });
        location.reload();
    });
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

// Loading overlay
function showLoading() {
    $('body').append('<div class="spinner-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
}

function hideLoading() {
    $('.spinner-overlay').remove();
}

// AJAX helper
function ajaxRequest(url, method, data, callback) {
    $.ajax({
        url: url,
        type: method || 'GET',
        data: data || {},
        success: callback,
        error: function(xhr) {
            console.error('AJAX Error:', xhr.statusText);
        }
    });
}
