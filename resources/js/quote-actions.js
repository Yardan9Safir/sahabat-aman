import { notify } from './swal';

const COPY_SELECTOR = '[data-copy-quote]';
const LIKE_SELECTOR = '[data-like-quote]';

const ACTIVE_LIKE = 'text-error';
const ACTIVE_ICON = 'favorite';
const IDLE_ICON = 'favorite_border';

document.addEventListener('click', async (event) => {
    const copyButton = event.target.closest(COPY_SELECTOR);

    if (copyButton) {
        try {
            await navigator.clipboard.writeText(copyButton.dataset.copyQuote);
            notify('Kutipan inspiration berhasil disalin!');
        } catch {
            notify('Gagal menyalin kutipan.', 'error');
        }

        return;
    }

    const likeButton = event.target.closest(LIKE_SELECTOR);

    if (!likeButton) {
        return;
    }

    const icon = likeButton.querySelector('.material-symbols-outlined');
    const liked = likeButton.classList.toggle(ACTIVE_LIKE);

    if (icon) {
        icon.textContent = liked ? ACTIVE_ICON : IDLE_ICON;
        icon.style.fontVariationSettings = liked ? "'FILL' 1" : "'FILL' 0";
    }
});
