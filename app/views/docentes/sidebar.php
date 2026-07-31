<?php $currentPage = $_SERVER['REQUEST_URI'] ?? ''; ?>

<style>
    #docentes-sidebar { transition: width 0.25s ease, transform 0.25s ease; }
    #docentes-sidebar.collapsed { width: 4rem; }
    #docentes-sidebar.collapsed .sidebar-label { display: none; }
    #docentes-sidebar.collapsed .sidebar-logo-text { display: none; }
    #docentes-sidebar.collapsed a, #docentes-sidebar.collapsed button { justify-content: center; padding-left: 0; padding-right: 0; }
    #docentes-sidebar.collapsed .sidebar-nav-icon { margin: 0; }
</style>

<!-- Overlay móvil -->
<div id="doc-sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden" onclick="closeDocentesSidebar()"></div>

<!-- Botón hamburguesa móvil -->
<button id="doc-mob-btn" type="button"
    class="md:hidden fixed top-3 left-3 z-50 p-2 rounded-full bg-primary text-white shadow-lg"
    onclick="toggleDocentesMobileSidebar()">
    <span class="material-symbols-outlined text-xl">menu</span>
</button>

<!-- Sidebar docentes -->
<nav id="docentes-sidebar"
     class="flex flex-col fixed left-0 top-0 h-full py-4 docked w-64 border-r border-outline-variant bg-white z-40 overflow-y-auto overflow-x-hidden
            -translate-x-full md:translate-x-0">

    <div class="flex items-center justify-between px-4 pb-4">
        <div class="sidebar-logo-text overflow-hidden">
            <h1 class="text-primary font-headline-md text-headline-md whitespace-nowrap">Edusaft Portal</h1>
            <p class="text-on-surface-variant text-xs whitespace-nowrap">Gestión Docente</p>
        </div>
        <button id="doc-collapse-btn" type="button"
                onclick="toggleDocentesCollapse()"
                class="hidden md:flex w-8 h-8 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant shrink-0">
            <span id="doc-collapse-icon" class="material-symbols-outlined text-xl">chevron_left</span>
        </button>
    </div>

    <div class="flex-grow flex flex-col gap-0.5 px-2">
        <a href="<?php echo URLROOT; ?>/docentes/dashboard"
           title="Panel Principal"
           class="<?php echo strpos($currentPage, '/docentes/dashboard') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">dashboard</span>
            <span class="sidebar-label truncate">Panel Principal</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/actividades"
           title="Actividades"
           class="<?php echo strpos($currentPage, '/docentes/actividades') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0" style="font-variation-settings:'FILL' 1">assignment</span>
            <span class="sidebar-label truncate">Actividades</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/asistencia"
           title="Asistencia"
           class="<?php echo strpos($currentPage, '/docentes/asistencia') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">event_available</span>
            <span class="sidebar-label truncate">Asistencia</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/notificaciones"
           title="Notificaciones"
           class="<?php echo strpos($currentPage, '/docentes/notificaciones') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">notifications</span>
            <span class="sidebar-label truncate">Notificaciones</span>
        </a>
    </div>

    <div class="mt-auto border-t border-outline-variant pt-3 px-2 flex flex-col gap-0.5">
        <a href="<?php echo URLROOT; ?>/docentes/configuracion"
           title="Configuración"
           class="text-on-surface-variant px-3 py-2.5 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3 text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">settings</span>
            <span class="sidebar-label truncate">Configuración</span>
        </a>
        <a href="<?php echo URLROOT; ?>/auth/logout"
           title="Cerrar Sesión"
           class="text-error px-3 py-2.5 hover:bg-error-container rounded-lg transition-colors flex items-center gap-3 text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">logout</span>
            <span class="sidebar-label truncate">Cerrar Sesión</span>
        </a>
    </div>
</nav>

<script>
    (function() {
        const sidebar = document.getElementById('docentes-sidebar');
        const collapseIcon = document.getElementById('doc-collapse-icon');
        const overlay = document.getElementById('doc-sidebar-overlay');

        const saved = localStorage.getItem('docentesSidebarCollapsed');
        if (saved === 'true' && window.innerWidth >= 768) {
            sidebar.classList.add('collapsed');
            if (collapseIcon) collapseIcon.textContent = 'chevron_right';
            adjustMain(true);
        } else {
            adjustMain(false);
        }

        function adjustMain(collapsed) {
            const main = document.getElementById('main-content-wrap');
            if (!main) return;
            if (window.innerWidth < 768) { main.style.marginLeft = '0'; return; }
            main.style.marginLeft = collapsed ? '4rem' : '16rem';
        }

        window.toggleDocentesCollapse = function() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            collapseIcon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
            localStorage.setItem('docentesSidebarCollapsed', isCollapsed);
            adjustMain(isCollapsed);
        };

        window.toggleDocentesMobileSidebar = function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        };

        window.closeDocentesSidebar = function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };
    })();
</script>
