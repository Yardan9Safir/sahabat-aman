const READY = () =>
    new Promise((resolve) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', resolve, { once: true });
            return;
        }

        resolve();
    });

const readConfig = () => {
    const holder = document.querySelector('[data-datatable-config]');

    if (!holder) {
        return null;
    }

    try {
        return JSON.parse(holder.textContent);
    } catch (error) {
        console.error('Konfigurasi DataTable tidak bisa dibaca.', error);
        return null;
    }
};

const EMPTY_STATE =
    '<span class="flex flex-col items-center gap-2 py-6 text-center">' +
    '<span class="material-symbols-outlined text-4xl text-outline">format_quote</span>' +
    '<span class="text-lg font-bold text-on-surface">Belum ada quotes</span>' +
    '<span class="text-sm text-on-surface-variant">' +
    'Tambahkan quotes pertama agar bisa dipilih sebagai kata-kata hari ini.' +
    '</span></span>';

const init = async () => {
    const root = document.querySelector('[data-datatable]');

    if (!root) {
        return;
    }

    const config = readConfig();
    const table = root.querySelector('table');

    if (!config || !table) {
        return;
    }

    const [{ default: DataTable }] = await Promise.all([
        import('datatables.net-dt'),
        import('datatables.net-dt/css/dataTables.dataTables.css'),
    ]);

    new DataTable(table, {
        ...config,
        ajax: {
            ...config.ajax,
            url: config.ajax?.url ?? window.location.href,
        },
        language: {
            emptyTable: EMPTY_STATE,
            ...config.language,
        },
    });
};

READY().then(init);
