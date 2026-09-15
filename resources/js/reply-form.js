function esc(value) {
    return String(value ?? '').replace(/[&<>"']/g, (m) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
}

function initials(name) {
    return esc(name).trim().slice(0, 2).toUpperCase();
}

export function initReplyForms() {
    document.querySelectorAll('form[id="replyForm"]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const textarea = form.querySelector('textarea[name="isi"]');
            const isi = textarea.value.trim();
            if (!isi) return;

            const originalBtnHtml = btn.innerHTML;
            const target = document.querySelector(form.dataset.target || '#balasanList');
            const mode = form.dataset.mode || 'list';
            const token = form.querySelector('input[name="_token"]')?.value || '';

            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            try {
                const res = await fetch(form.dataset.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ isi }),
                });
                const data = await res.json();
                if (data.success && target) {
                    const empty = target.querySelector('.text-center');
                    if (empty) empty.remove();

                    const div = document.createElement('div');
                    if (mode === 'chat') {
                        div.className = 'flex flex-col items-end max-w-xl ml-auto';
                        div.innerHTML = `
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-label-sm text-outline">${data.balasan.created_at}</span>
                                <span class="text-label-sm font-bold text-on-surface">Kamu</span>
                            </div>
                            <div class="bg-primary-container text-on-primary p-4 rounded-2xl rounded-tr-xs shadow-sm">
                                <p class="text-body-md leading-relaxed text-white">${esc(data.balasan.isi)}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-1 text-label-sm text-outline">
                                <span class="material-symbols-outlined text-xs text-primary">done_all</span>
                                <span>Terkirim terenkripsi</span>
                            </div>
                        `;
                    } else {
                        div.className = 'rounded-2xl bg-primary-fixed/40 border border-primary/20 p-4';
                        div.innerHTML = `
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center text-xs font-bold">${initials(data.balasan.pengirim_name)}</div>
                                    <div>
                                        <p class="text-label-md font-bold text-on-surface">${esc(data.balasan.pengirim_name)}</p>
                                        <span class="text-label-sm text-outline capitalize">${esc(data.balasan.pengirim_role)}</span>
                                    </div>
                                </div>
                                <span class="text-label-sm text-outline">${data.balasan.created_at}</span>
                            </div>
                            <p class="text-body-md text-on-surface leading-relaxed">${esc(data.balasan.isi)}</p>
                        `;
                    }
                    target.appendChild(div);
                    textarea.value = '';
                }
            } catch (err) {
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        });
    });
}