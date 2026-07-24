<?php $currentPage = $_SERVER['REQUEST_URI'] ?? ''; ?>

<style>
    #admin-sidebar { transition: width 0.25s ease, transform 0.25s ease; }
    #admin-sidebar.collapsed { width: 4rem; }
    #admin-sidebar.collapsed .sidebar-label { display: none; }
    #admin-sidebar.collapsed .sidebar-logo-text { display: none; }
    #admin-sidebar.collapsed .sidebar-divider-label { display: none; }
    #admin-sidebar.collapsed a, #admin-sidebar.collapsed button { justify-content: center; padding-left: 0; padding-right: 0; }
    #admin-sidebar.collapsed .sidebar-nav-icon { margin: 0; }
    #admin-sidebar + #sidebar-overlay { display: none; }
    #main-content-wrap { transition: margin-left 0.25s ease; }
    #main-content-wrap.sidebar-expanded { margin-left: 16rem; }
    #main-content-wrap.sidebar-collapsed { margin-left: 4rem; }
</style>

<!-- Overlay móvil -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="closeSidebar()"></div>

<!-- Botón hamburguesa móvil (fijo arriba a la izq) -->
<button id="mob-sidebar-btn" type="button"
    class="md:hidden fixed top-3 left-3 z-50 p-2 rounded-full bg-primary text-white shadow-lg"
    onclick="toggleMobileSidebar()">
    <span class="material-symbols-outlined text-xl">menu</span>
</button>

<!-- Sidebar -->
<nav id="admin-sidebar"
     class="flex flex-col fixed left-0 top-0 h-full py-4 docked w-64 border-r border-outline-variant bg-white z-40 overflow-y-auto overflow-x-hidden
            -translate-x-full md:translate-x-0 transition-transform md:transition-none">

    <!-- Header sidebar con botón colapso -->
    <div class="flex items-center justify-between px-4 pb-4">
        <div class="sidebar-logo-text overflow-hidden">
            <h1 class="text-primary font-headline-md text-headline-md whitespace-nowrap">Edusaft Portal</h1>
            <p class="text-on-surface-variant text-xs whitespace-nowrap">Administración</p>
        </div>
        <!-- Toggle colapso (solo desktop) -->
        <button id="sidebar-collapse-btn" type="button"
                onclick="toggleSidebarCollapse()"
                title="Colapsar / Expandir"
                class="hidden md:flex w-8 h-8 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant shrink-0">
            <span id="collapse-icon" class="material-symbols-outlined text-xl">chevron_left</span>
        </button>
    </div>

    <!-- Nav Items -->
    <div class="flex-grow flex flex-col gap-0.5 px-2">

        <!-- Dashboard -->
        <a href="<?php echo URLROOT; ?>/admin/dashboard"
           title="Panel Principal"
           class="sidebar-item <?php echo (strpos($currentPage, '/admin/dashboard') !== false || $currentPage === '/admin/' || $currentPage === '/admin') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0" style="font-variation-settings:'FILL' 1">dashboard</span>
            <span class="sidebar-label truncate">Panel Principal</span>
        </a>

        <!-- Divisor Usuarios -->
        <p class="sidebar-divider-label text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/60 px-3 pt-3 pb-1">Usuarios</p>

        <a href="<?php echo URLROOT; ?>/admin/profesores"
           title="Profesores"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/profesores') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">school</span>
            <span class="sidebar-label truncate">Profesores</span>
        </a>

        <a href="<?php echo URLROOT; ?>/admin/familias"
           title="Familias"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/familias') !== false ? 'bg-secondary-container text-on-secondary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">family_restroom</span>
            <span class="sidebar-label truncate">Familias</span>
        </a>

        <a href="<?php echo URLROOT; ?>/admin/estudiantes"
           title="Estudiantes"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/estudiantes') !== false ? 'bg-tertiary-container text-on-tertiary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">groups</span>
            <span class="sidebar-label truncate">Estudiantes</span>
        </a>

        <!-- Divisor Gestión -->
        <p class="sidebar-divider-label text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/60 px-3 pt-3 pb-1">Gestión</p>

        <a href="<?php echo URLROOT; ?>/admin/sedes"
           title="Sedes"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/sedes') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">apartment</span>
            <span class="sidebar-label truncate">Sedes</span>
        </a>

        <a href="<?php echo URLROOT; ?>/admin/actividades"
           title="Actividades"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/actividades') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">event</span>
            <span class="sidebar-label truncate">Actividades</span>
        </a>

        <a href="<?php echo URLROOT; ?>/admin/asistencias"
           title="Asistencias"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/asistencias') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">how_to_reg</span>
            <span class="sidebar-label truncate">Asistencias</span>
        </a>

        <!-- Divisor Sistema -->
        <p class="sidebar-divider-label text-[10px] font-bold uppercase tracking-widest text-on-surface-variant/60 px-3 pt-3 pb-1">Sistema</p>

        <a href="<?php echo URLROOT; ?>/admin/auditoria"
           title="Auditoría"
           class="sidebar-item <?php echo strpos($currentPage, '/admin/auditoria') !== false ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-on-surface-variant hover:bg-surface-container'; ?> rounded-lg px-3 py-2.5 flex items-center gap-3 transition-colors text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">history</span>
            <span class="sidebar-label truncate">Auditoría</span>
        </a>
    </div>

    <!-- Footer del sidebar -->
    <div class="mt-auto border-t border-outline-variant pt-3 px-2 flex flex-col gap-0.5">
        <a href="<?php echo URLROOT; ?>/admin/configuracion"
           title="Configuración"
           class="sidebar-item text-on-surface-variant px-3 py-2.5 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3 text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">settings</span>
            <span class="sidebar-label truncate">Configuración</span>
        </a>
        <a href="<?php echo URLROOT; ?>/auth/logout"
           title="Cerrar Sesión"
           class="sidebar-item text-error px-3 py-2.5 hover:bg-error-container rounded-lg transition-colors flex items-center gap-3 text-sm">
            <span class="material-symbols-outlined sidebar-nav-icon shrink-0">logout</span>
            <span class="sidebar-label truncate">Cerrar Sesión</span>
        </a>
    </div>
</nav>

<script>
    (function() {
        const sidebar = document.getElementById('admin-sidebar');
        const collapseBtn = document.getElementById('sidebar-collapse-btn');
        const collapseIcon = document.getElementById('collapse-icon');
        const overlay = document.getElementById('sidebar-overlay');

        // Restaurar estado guardado
        const saved = localStorage.getItem('adminSidebarCollapsed');
        if (saved === 'true' && window.innerWidth >= 768) {
            sidebar.classList.add('collapsed');
            if (collapseIcon) collapseIcon.textContent = 'chevron_right';
            adjustMainContent(true);
        } else {
            adjustMainContent(false);
        }

        function adjustMainContent(collapsed) {
            const main = document.getElementById('main-content-wrap');
            if (!main) return;
            if (window.innerWidth < 768) { main.style.marginLeft = '0'; return; }
            main.style.marginLeft = collapsed ? '4rem' : '16rem';
        }

        window.toggleSidebarCollapse = function() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            collapseIcon.textContent = isCollapsed ? 'chevron_right' : 'chevron_left';
            localStorage.setItem('adminSidebarCollapsed', isCollapsed);
            adjustMainContent(isCollapsed);
        };

        window.toggleMobileSidebar = function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        };

        window.closeSidebar = function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };
    })();
</script>
