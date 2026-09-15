export function initSidebar({ root, collapsedKey, navQuery }) {
    if (!document.querySelector(root)) return;
    const body = document.body;
    const btnSidebar = document.getElementById('btnSidebar');
    const btnMenuMobile = document.getElementById('btnMenuMobile');
    const overlay = document.getElementById('sidebarOverlay');
    const icon = document.getElementById('btnSidebarIcon');
    const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;

    function syncIcon() {
        const isOpen = isDesktop() ? !body.classList.contains('sidebar-collapsed') : body.classList.contains('sidebar-open');
        if (icon) icon.textContent = isOpen ? 'close' : 'menu';
        if (btnSidebar) btnSidebar.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    function syncLock() {
        const locked = !isDesktop() && body.classList.contains('sidebar-open');
        document.documentElement.classList.toggle('drawer-locked', locked);
    }

    function toggleSidebar() {
        if (isDesktop()) {
            body.classList.toggle('sidebar-collapsed');
            try { localStorage.setItem(collapsedKey, body.classList.contains('sidebar-collapsed') ? '1' : '0'); } catch (e) {}
        } else {
            body.classList.toggle('sidebar-open');
        }
        syncIcon();
    }

    function closeMobile() {
        body.classList.remove('sidebar-open');
        syncIcon();
        syncLock();
    }

    if (btnSidebar) btnSidebar.addEventListener('click', () => { toggleSidebar(); syncLock(); });
    if (btnMenuMobile) btnMenuMobile.addEventListener('click', () => { body.classList.add('sidebar-open'); syncIcon(); syncLock(); });
    if (overlay) overlay.addEventListener('click', closeMobile);
    document.querySelectorAll(navQuery).forEach((el) => el.addEventListener('click', closeMobile));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeMobile(); });
    window.addEventListener('resize', () => {
        if (isDesktop()) body.classList.remove('sidebar-open');
        syncIcon();
        syncLock();
    });
    try { if (isDesktop() && localStorage.getItem(collapsedKey) === '1') body.classList.add('sidebar-collapsed'); } catch (e) {}
    syncIcon();
    syncLock();
}