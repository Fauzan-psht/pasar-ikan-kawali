import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import select2 from 'select2';

window.$ = window.jQuery = $;
window.DataTable = DataTable;
select2(window, $);

document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');
    const loadingOverlay = document.getElementById('dashboardLoadingOverlay');
    const confirmedForms = new WeakSet();

    const updateThemeButton = () => {
        const isDark = document.documentElement.dataset.theme === 'dark';

        themeToggle.setAttribute('aria-pressed', String(isDark));
        themeToggle.innerHTML = isDark
            ? '<i class="bi bi-sun" aria-hidden="true"></i>'
            : '<i class="bi bi-moon-stars" aria-hidden="true"></i>';
        themeToggle.setAttribute('aria-label', isDark ? 'Gunakan tema terang' : 'Gunakan tema gelap');
    };

    if (themeToggle) {
        updateThemeButton();
        themeToggle.addEventListener('click', () => {
            const theme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';

            document.documentElement.dataset.theme = theme;
            localStorage.setItem('dashboard-theme', theme);
            updateThemeButton();
        });
    }

    const sanitizeDigitsOnlyInput = input => {
        const digits = input.value.replace(/\D/g, '');
        input.value = input.maxLength > 0 ? digits.slice(0, input.maxLength) : digits;
    };

    document.querySelectorAll('input[data-digits-only]').forEach(sanitizeDigitsOnlyInput);

    document.addEventListener('input', event => {
        const input = event.target;

        if (input instanceof HTMLInputElement && input.hasAttribute('data-digits-only')) {
            sanitizeDigitsOnlyInput(input);
        }
    });

    document.addEventListener('submit', event => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement)) {
            return;
        }

        if (form.dataset.submitting === 'true') {
            event.preventDefault();
            return;
        }

        const submitter = event.submitter || form.querySelector('button[type="submit"], input[type="submit"]');

        if (form.dataset.confirm && !confirmedForms.has(form)) {
            event.preventDefault();

            const submitConfirmedForm = () => {
                confirmedForms.add(form);

                if (submitter instanceof HTMLElement && submitter.form === form) {
                    form.requestSubmit(submitter);
                } else {
                    form.requestSubmit();
                }

                confirmedForms.delete(form);
            };

            if (window.Swal) {
                window.Swal.fire({
                    title: form.dataset.confirmTitle || 'Konfirmasi',
                    text: form.dataset.confirm,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-4' },
                }).then(result => {
                    if (result.isConfirmed) {
                        submitConfirmedForm();
                    }
                });
            } else if (window.confirm(form.dataset.confirm)) {
                submitConfirmedForm();
            }

            return;
        }

        if (confirmedForms.has(form)) {
            confirmedForms.delete(form);
        }

        if (form.dataset.skipLoading === 'true' || (submitter && submitter.dataset.skipLoading === 'true')) {
            return;
        }

        if (submitter && submitter.disabled) {
            return;
        }

        form.dataset.submitting = 'true';

        if (submitter) {
            submitter.disabled = true;
            const loadingText = submitter.dataset.loadingText || 'Memproses...';

            if (submitter instanceof HTMLButtonElement) {
                submitter.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
            } else {
                submitter.value = loadingText;
            }
        }

        if (form.method.toUpperCase() === 'POST') {
            loadingOverlay.classList.add('is-visible');
            loadingOverlay.setAttribute('aria-hidden', 'false');
        }
    }, true);

    document.querySelectorAll('.content-area .alert-dismissible').forEach(alert => {
        window.setTimeout(() => {
            window.bootstrap.Alert.getOrCreateInstance(alert).close();
        }, 3000);
    });

    window.initializeDashboardSelects = root => {
        $(root).find('.js-select2').addBack('.js-select2').each(function () {
            const $select = $(this);

            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }

            const $modal = $select.closest('.modal');

            $select.select2({
                width: '100%',
                dropdownParent: $modal.length ? $modal : $(document.body),
                language: {
                    noResults: () => 'Data tidak ditemukan',
                    searching: () => 'Mencari...',
                },
            });
        });
    };

    window.initializeDashboardSelects(document);
});
