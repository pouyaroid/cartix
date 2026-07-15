/**
 * CardX - Main JavaScript
 */

// CSRF Token for AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Theme Toggle
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-bs-theme', newTheme);

            // Save via AJAX
            fetch('/dashboard/settings/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: newTheme })
            });

            // Update icon
            const icon = themeToggle.querySelector('i');
            icon.className = newTheme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        });
    }

    // Sidebar Toggle (Mobile)
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            const sidebar = document.getElementById('adminSidebar') || document.getElementById('dashboardSidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');

                // Create/toggle overlay
                let overlay = document.querySelector('.sidebar-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'sidebar-overlay';
                    document.body.appendChild(overlay);
                    overlay.addEventListener('click', () => {
                        sidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    });
                }
                overlay.classList.toggle('show');
            }
        });
    }

    // Initialize Select2
    if ($.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap-5',
            dir: 'rtl',
            width: '100%'
        });
    }

    // Initialize DataTables
    if ($.fn.DataTable) {
        $('.data-table').DataTable({
            language: {
                search: 'جستجو:',
                lengthMenu: 'نمایش _MENU_ رکورد',
                info: 'نمایش _START_ تا _END_ از _TOTAL_ رکورد',
                paginate: {
                    previous: 'قبلی',
                    next: 'بعدی'
                },
                emptyTable: 'داده‌ای یافت نشد',
                zeroRecords: 'داده‌ای یافت نشد'
            },
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
            pageLength: 15,
            order: [[0, 'desc']]
        });
    }
});

// SweetAlert2 Confirm Delete
function confirmDelete(url, name) {
    Swal.fire({
        title: 'آیا مطمئن هستید؟',
        text: `آیا می‌خواهید "${name}" را حذف کنید؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'بله، حذف شود',
        cancelButtonText: 'لغو'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// AJAX Helper
function ajaxPost(url, data, successCallback) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            if (successCallback) successCallback(response);
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: xhr.responseJSON?.message || 'خطایی رخ داده است',
                confirmButtonText: 'باشه'
            });
        }
    });
}

// Toast Notification
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-start',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });
    Toast.fire({ icon: type, title: message });
}
