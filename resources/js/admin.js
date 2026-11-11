
import 'bootstrap';
import 'admin-lte';
import Swal from 'sweetalert2';

// ======================================
// PENGATURAN LIGHT MODE DAN DARK MODE
// ======================================

document.addEventListener('DOMContentLoaded', () => {

    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const themeLabel = document.getElementById('theme-label');

    function getCurrentTheme() {
        return document.documentElement.getAttribute('data-bs-theme') || 'light';
    }

    function updateThemeButton(theme) {
        if (!themeToggle || !themeIcon || !themeLabel) {
            return;
        }

        if (theme === 'dark') {
            themeIcon.className = 'bi bi-sun';
            themeLabel.textContent = 'Light Mode';
            themeToggle.setAttribute('aria-label', 'Aktifkan Light Mode');
            themeToggle.setAttribute('title', 'Ganti ke Light Mode');
        } else {
            themeIcon.className = 'bi bi-moon-stars';
            themeLabel.textContent = 'Dark Mode';
            themeToggle.setAttribute('aria-label', 'Aktifkan Dark Mode');
            themeToggle.setAttribute('title', 'Ganti ke Dark Mode');
        }
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('inventaris-theme', theme);
        updateThemeButton(theme);
    }

    if (themeToggle) {
        updateThemeButton(getCurrentTheme());

        themeToggle.addEventListener('click', () => {
            const currentTheme = getCurrentTheme();
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            applyTheme(newTheme);
        });
    }

    // ======================================
    // SWEETALERT2 - KONFIRMASI PENGHAPUSAN
    // ======================================

    
document.addEventListener('submit', async (event) => {
    const form = event.target;

    // Hanya proses form yang meminta konfirmasi SweetAlert2.
    if (!form.matches('.js-confirm-delete, .js-confirm-action')) {
        return;
    }

    // Mencegah konfirmasi muncul berulang.
    if (form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();

    const isDark = getCurrentTheme() === 'dark';

    const isDelete = form.classList.contains('js-confirm-delete');

    const title = form.dataset.confirmTitle ||
        (isDelete ? 'Apakah Anda yakin?' : 'Konfirmasi tindakan');

    const message = form.dataset.confirmText ||
        (isDelete
            ? 'Data yang dihapus tidak dapat dikembalikan.'
            : 'Apakah Anda yakin ingin melanjutkan tindakan ini?');

    const confirmButton = form.dataset.confirmButton ||
        (isDelete ? 'Ya, Hapus' : 'Ya, Lanjutkan');

    const cancelButton = form.dataset.cancelButton || 'Batal';

    const icon = form.dataset.confirmIcon ||
        (isDelete ? 'warning' : 'question');

    const confirmColor = form.dataset.confirmColor ||
        (isDelete ? '#dc3545' : '#0d6efd');

    const result = await Swal.fire({
        title,
        text: message,
        icon,

        showCancelButton: true,
        confirmButtonText: confirmButton,
        cancelButtonText: cancelButton,

        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',

        reverseButtons: true,
        focusCancel: true,

        background: isDark ? '#343a40' : '#ffffff',
        color: isDark ? '#f8f9fa' : '#212529',

        customClass: {
            popup: 'rounded-3',
            confirmButton: 'btn px-3 mx-1',
            cancelButton: 'btn btn-secondary px-3 mx-1',
        },

        buttonsStyling: false,
        allowOutsideClick: false,
    });

    if (result.isConfirmed) {
        form.dataset.confirmed = 'true';

        HTMLFormElement.prototype.submit.call(form);
    }
});
});