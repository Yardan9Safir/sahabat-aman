const STORAGE_KEY = 'sahabat-aman.sidebar-collapsed';
const DESKTOP = '(min-width: 1024px)';

function sidebar() {
    return document.getElementById('appSidebar') || document.getElementById('adminSidebar');
}

function toggleButton() {
    return document.getElementById('btnSidebar');
}

function menuButton() {
    return document.getElementById('btnMenuMobile');
}

function isDesktop() {
    return window.matchMedia(DESKTOP).matches;
}

function isDrawerOpen() {
    return document.body.classList.contains('sidebar-open');
}

function isCollapsed() {
    return document.body.classList.contains('sidebar-collapsed');
}

function refreshButton() {
    const button = toggleButton();
    const icon = button?.querySelector('#btnSidebarIcon');

    if (!button || !icon) {
        return;
    }

    if (isDesktop()) {
        icon.textContent = isCollapsed() ? 'menu' : 'chevron_left';
        button.setAttribute('aria-expanded', String(!isCollapsed()));
        button.setAttribute('aria-label', isCollapsed() ? 'Buka sidebar' : 'Ciutkan sidebar');
        return;
    }

    icon.textContent = isDrawerOpen() ? 'close' : 'menu';
    button.setAttribute('aria-expanded', String(isDrawerOpen()));
    button.setAttribute('aria-label', isDrawerOpen() ? 'Tutup sidebar' : 'Buka sidebar');
}

function setCollapsed(collapsed) {
    if (!isDesktop()) {
        return;
    }

    document.body.classList.toggle('sidebar-collapsed', collapsed);

    try {
        localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
    } catch (error) {
        void error;
    }

    refreshButton();
}

function setDrawer(open) {
    document.body.classList.toggle('sidebar-open', open);
    document.documentElement.classList.toggle('drawer-locked', open);
    refreshButton();
}

document.addEventListener('click', (event) => {
    if (event.target.closest('#btnSidebar')) {
        if (isDesktop()) {
            setCollapsed(!isCollapsed());
        } else {
            setDrawer(!isDrawerOpen());
        }

        return;
    }

    if (event.target.closest('#btnMenuMobile')) {
        setDrawer(!isDrawerOpen());
        return;
    }

    if (event.target.closest('#sidebarOverlay')) {
        setDrawer(false);
        return;
    }

    if (event.target.closest('.sidebar-nav-link') && !isDesktop()) {
        setDrawer(false);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isDrawerOpen()) {
        setDrawer(false);
    }
});

function restoreState() {
    let collapsed = false;

    try {
        collapsed = localStorage.getItem(STORAGE_KEY) === '1';
    } catch (error) {
        void error;
    }

    document.body.classList.toggle('sidebar-collapsed', collapsed && isDesktop());
    refreshButton();
}

function syncViewport() {
    if (isDesktop()) {
        document.body.classList.remove('sidebar-open');
        document.documentElement.classList.remove('drawer-locked');
    }

    restoreState();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncViewport);
} else {
    syncViewport();
}

window.addEventListener('resize', syncViewport);
