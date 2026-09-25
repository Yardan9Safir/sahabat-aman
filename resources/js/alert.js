const FADE_MS = 300;

function dismiss(alert) {
    if (!alert || alert.dataset.alertDismissed === 'true') {
        return;
    }

    alert.dataset.alertDismissed = 'true';
    alert.classList.add('opacity-0');

    window.setTimeout(() => alert.remove(), FADE_MS);
}

document.addEventListener('click', (event) => {
    const dismissButton = event.target.closest('[data-alert-dismiss]');

    if (dismissButton) {
        dismiss(dismissButton.closest('[data-alert]'));
    }
});

function startAutoHide() {
    document.querySelectorAll('[data-alert-autohide]').forEach((alert) => {
        window.setTimeout(() => dismiss(alert), Number(alert.dataset.alertAutohide) || 6000);
    });
}

let lastTrigger = null;

function openModal(modal) {
    lastTrigger = document.activeElement;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');

    const panel = modal.querySelector('[data-alert-modal-panel]');
    const confirm = modal.querySelector('[data-alert-modal-confirm]');

    if (confirm) {
        confirm.focus();
    } else if (panel) {
        panel.setAttribute('tabindex', '-1');
        panel.focus();
    }
}

function closeModal(modal) {
    if (!modal || modal.classList.contains('hidden')) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');

    if (lastTrigger) {
        lastTrigger.focus();
        lastTrigger = null;
    }
}

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-alert-modal-open]');

    if (opener) {
        const modal = document.getElementById(opener.dataset.alertModalOpen);

        if (modal) {
            event.preventDefault();
            openModal(modal);
        }

        return;
    }

    const modal = event.target.closest('[data-alert-modal]');

    if (!modal) {
        return;
    }

    if (event.target.closest('[data-alert-modal-close]') || event.target.matches('[data-alert-modal-overlay]')) {
        closeModal(modal);
        return;
    }

    if (event.target.closest('[data-alert-modal-confirm]')) {
        window.setTimeout(() => closeModal(modal), 0);
        modal.dispatchEvent(new CustomEvent('alert:confirmed', { bubbles: true }));
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('[data-alert-modal]:not(.hidden)').forEach(closeModal);
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', startAutoHide);
} else {
    startAutoHide();
}
