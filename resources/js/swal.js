import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const COLOR = {
    primary: '#0891b2',
    error: '#ba1a1a',
    surfaceContainer: '#ecebe8',
    onSurfaceVariant: '#464742',
    surfaceContainerHigh: '#e7e6e3',
};

const BASE_CLASS = {
    popup: 'rounded-3xl font-sans',
    title: 'text-lg font-bold text-on-surface',
    text: 'text-sm text-on-surface-variant',
    icon: 'border-none',
    input: 'rounded-xl text-sm',
};

const CONFIRM_DANGER =
    'rounded-xl bg-error px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-error/90 shadow-none';
const CONFIRM_PRIMARY =
    'rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-primary/90 shadow-none';
const CANCEL =
    'rounded-xl bg-surface-container px-5 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container-high shadow-none';

const MIXIN = Swal.mixin({
    customClass: BASE_CLASS,
    buttonsStyling: false,
    focusConfirm: false,
    focusDeny: true,
    reverseButtons: true,
    backdrop: false,
});

/**
 * Menampilkan dialog konfirmasi SweetAlert2.
 *
 * @param {Object}  options
 * @param {string}  options.title
 * @param {string}  [options.text]
 * @param {string}  [options.icon]                      success | error | warning | info | question
 * @param {string}  [options.confirmText='Ya, lanjutkan']
 * @param {string}  [options.cancelText='Batal']
 * @param {boolean} [options.danger=false]              Konfirmasi bergaya destructive.
 * @returns {Promise<boolean>} true bila pengguna mengonfirmasi.
 */
export async function confirmDialog({
    title,
    text = '',
    icon = 'warning',
    confirmText = 'Ya, lanjutkan',
    cancelText = 'Batal',
    danger = false,
} = {}) {
    const result = await MIXIN.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        confirmButtonColor: danger ? COLOR.error : COLOR.primary,
        customClass: {
            ...BASE_CLASS,
            confirmButton: danger ? CONFIRM_DANGER : CONFIRM_PRIMARY,
            cancelButton: CANCEL,
        },
    });

    return result.isConfirmed;
}

/**
 * Menampilkan notifikasi ringkas (toast) di pojok layar.
 *
 * @param {string} message
 * @param {'success'|'error'|'info'|'warning'} [icon='success']
 */
export function notify(message, icon = 'success') {
    const success = icon === 'success';

    MIXIN.fire({
        toast: true,
        position: 'top-end',
        icon,
        title: message,
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true,
        customClass: {
            popup: `rounded-2xl font-sans border-none ${success ? 'bg-primary-container' : 'bg-error-container'}`,
            title: `text-sm font-semibold ${success ? 'text-on-primary-container' : 'text-on-error-container'}`,
        },
    });
}

const CONFIRM_SELECTOR = 'form[data-swal-confirm]';

function readFormConfig(form) {
    return {
        title: form.dataset.swalConfirm || 'Yakin ingin melanjutkan?',
        text: form.dataset.swalText || '',
        icon: form.dataset.swalIcon || 'warning',
        confirmText: form.dataset.swalConfirmText || 'Ya, lanjutkan',
        cancelText: form.dataset.swalCancelText || 'Batal',
        danger: form.dataset.swalDanger !== 'false',
    };
}

function lockSubmit(form) {
    form
        .querySelectorAll('button[type="submit"], input[type="submit"]')
        .forEach((button) => {
            button.disabled = true;
        });
}

document.addEventListener('submit', async (event) => {
    const form = event.target.closest(CONFIRM_SELECTOR);

    if (!form || form.dataset.swalBypass === 'true') {
        return;
    }

    event.preventDefault();
    form.dataset.swalBypass = 'true';

    if (await confirmDialog(readFormConfig(form))) {
        lockSubmit(form);
        form.submit();
    } else {
        delete form.dataset.swalBypass;
    }
});

function flashToasts() {
    const body = document.body;

    if (!body) {
        return;
    }

    if (body.dataset.swalFlashSuccess) {
        notify(body.dataset.swalFlashSuccess, 'success');
    }

    if (body.dataset.swalFlashError) {
        notify(body.dataset.swalFlashError, 'error');
    }

    if (body.dataset.swalFlashWarning) {
        notify(body.dataset.swalFlashWarning, 'warning');
    }

    if (body.dataset.swalFlashInfo) {
        notify(body.dataset.swalFlashInfo, 'info');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', flashToasts);
} else {
    flashToasts();
}

export default MIXIN;
