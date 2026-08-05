<?php
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard = (strpos($currentPage, '/padres/dashboard') !== false) || ($activePage ?? '') === 'dashboard';
$isCamino    = (strpos($currentPage, '/padres/camino') !== false) || ($activePage ?? '') === 'camino';
$isPuntos    = (strpos($currentPage, '/padres/puntos') !== false) || ($activePage ?? '') === 'puntos';
?>

<style>
    /* Sidebar collapse y responsividad */
    @media (min-width: 1024px) {
        body.sidebar-collapsed #userSidebar { width: 5.5rem !important; }
        body.sidebar-collapsed #mainContent, 
        body.sidebar-collapsed main, 
        body.sidebar-collapsed #mainScrollContainer { margin-left: 5.5rem !important; }
        body.sidebar-collapsed .sidebar-text { display: none !important; }
        body.sidebar-collapsed .sidebar-search-container { display: none !important; }
        body.sidebar-collapsed .sidebar-profile-info { display: none !important; }
        body.sidebar-collapsed .sidebar-header { padding-left: 0.5rem !important; padding-right: 0.5rem !important; padding-top: 4.5rem !important; }
        body.sidebar-collapsed .sidebar-logo-container { flex-direction: column !important; gap: 0.25rem !important; }
        body.sidebar-collapsed .sidebar-item-link { padding-left: 0 !important; padding-right: 0 !important; justify-content: center !important; }
        body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }
        body.sidebar-collapsed .sidebar-controls-container { flex-direction: column !important; left: 0 !important; right: 0 !important; align-items: center !important; gap: 0.25rem !important; }

        /* Sidebar completamente oculto */
        body.sidebar-hidden #userSidebar {
            transform: translateX(-100%) !important;
        }
        body.sidebar-hidden #mainContent, 
        body.sidebar-hidden main, 
        body.sidebar-hidden #mainScrollContainer {
            margin-left: 0 !important;
        }
        body.sidebar-hidden #showSidebarFloatingBtn {
            display: flex !important;
        }
    }

    /* Submenu desplegable animación */
    #asistenciaSubmenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
        opacity: 0;
    }
    #asistenciaSubmenu.open {
        max-height: 320px;
        opacity: 1;
    }

    @keyframes submenu-drop {
        0%   { opacity: 0; transform: translateY(-18px); }
        60%  { opacity: 1; transform: translateY(4px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    #asistenciaSubmenu.open .submenu-item {
        animation: submenu-drop 0.32s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
</style>

<!-- Mobile Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

<!-- Sidebar principal -->
<nav id="userSidebar" class="flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-50 transition-all duration-300 -translate-x-full lg:translate-x-0 overflow-hidden">
    <!-- Botón cerrar (móvil) -->
    <button id="closeSidebarBtn" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>

    <!-- Header del sidebar -->
    <div class="p-8 pb-4 sidebar-header transition-all duration-300">
        <div class="flex flex-col items-center text-center gap-3 mb-2 sidebar-logo-container transition-all duration-300">
            <div class="p-3 bg-primary/10 rounded-2xl flex-shrink-0">
                <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-16 w-16 object-contain" alt="Logo">
            </div>
            <span class="text-2xl font-bold text-primary tracking-tight sidebar-text">EduSaft</span>
        </div>
        <p class="text-xs text-outline uppercase tracking-widest font-bold text-center sidebar-text">Portal de Padres</p>
    </div>

    <!-- Buscador -->
    <div class="px-6 mb-4 sidebar-search-container transition-all duration-300">
        <div class="relative w-full">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-sm">search</span>
            <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full text-xs font-medium focus:ring-2 focus:ring-primary transition-all" placeholder="Buscar" type="text" />
        </div>
    </div>

    <!-- Links de navegación -->
    <div class="flex-grow px-4 space-y-1 overflow-y-auto">
        <!-- Panel Principal -->
        <a class="sidebar-item-link <?php echo $isDashboard ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" 
           href="<?php echo URLROOT; ?>/padres/dashboard">
            <span class="material-symbols-outlined flex-shrink-0" <?php echo $isDashboard ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>dashboard</span>
            <span class="font-medium text-sm sidebar-text">Panel Principal</span>
        </a>

        <!-- Historial Asistencias (Dropdown) -->
        <div class="space-y-1">
            <button id="asistenciaDropdownBtn"
                class="sidebar-item-link w-full flex items-center justify-between px-4 py-3 rounded-2xl <?php echo ($isCamino || $isPuntos || $isPico) ? 'text-primary bg-primary/5' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> transition-all group focus:outline-none">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined flex-shrink-0">history</span>
                    <span class="font-medium text-sm sidebar-text">Historial Asistencias</span>
                </div>
                <span id="asistenciaDropdownChevron" class="material-symbols-outlined text-sm sidebar-text transition-transform duration-300" style="<?php echo ($isCamino || $isPuntos || $isPico) ? 'transform:rotate(180deg)' : ''; ?>">expand_more</span>
            </button>

            <div id="asistenciaSubmenu" class="space-y-1 <?php echo ($isCamino || $isPuntos || $isPico) ? 'open' : ''; ?>">
                <!-- Camino de Montaña -->
                <a class="submenu-item sidebar-item-link <?php echo $isCamino ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" 
                   href="<?php echo URLROOT; ?>/padres/camino" style="animation-delay:0ms">
                    <span class="material-symbols-outlined flex-shrink-0" <?php echo $isCamino ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>mountain_flag</span>
                    <span class="font-medium text-sm sidebar-text">Camino de Montaña</span>
                </a>

                <!-- Mis Puntos -->
                <a class="submenu-item sidebar-item-link <?php echo $isPuntos ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" 
                   href="<?php echo URLROOT; ?>/padres/puntos" style="animation-delay:80ms">
                    <span class="material-symbols-outlined flex-shrink-0" <?php echo $isPuntos ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>workspace_premium</span>
                    <span class="font-medium text-sm sidebar-text">Mis Puntos</span>
                </a>

                <!-- Contáctanos -->
                <?php if ($isCamino): ?>
                    <button class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all cursor-pointer" onclick="openModal('contactosModal')" style="animation-delay:160ms">
                        <span class="material-symbols-outlined flex-shrink-0">group</span>
                        <span class="font-medium text-sm sidebar-text">Contáctanos</span>
                    </button>
                <?php else: ?>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino#contactos" style="animation-delay:160ms">
                        <span class="material-symbols-outlined flex-shrink-0">group</span>
                        <span class="font-medium text-sm sidebar-text">Contáctanos</span>
                    </a>
                <?php endif; ?>

                <!-- Opinión -->
                <?php if ($isCamino): ?>
                    <button class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all cursor-pointer" onclick="openModal('opinionModal')" style="animation-delay:240ms">
                        <span class="material-symbols-outlined flex-shrink-0">chat_bubble</span>
                        <span class="font-medium text-sm sidebar-text">Opinión</span>
                    </button>
                <?php else: ?>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino#opinion" style="animation-delay:240ms">
                        <span class="material-symbols-outlined flex-shrink-0">chat_bubble</span>
                        <span class="font-medium text-sm sidebar-text">Opinión</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Perfil de usuario / Cerrar sesión -->
    <div class="p-4 mt-auto border-t border-outline-variant/30 flex items-center justify-center lg:justify-between transition-all duration-300">
        <div class="flex items-center gap-3 w-full sidebar-item-link">
            <img alt="User Profile" class="w-10 h-10 rounded-full object-cover border border-outline-variant flex-shrink-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4-sZziL98gyg-93o6NhBHrP9O1Mjg_PrtJ-VzMuxDcwNbPGr5nxHChNA__Afx1axDdlsUMxN0xhHaIfyQ4BJfSa1VKn5BjHv8Hso4JGk4t_9P9ByngNDbUCc2P7c1f4pRZM6NBUD-aFvlmReMobzBGytlvFkVx0doS8C7fu7znh8lOkuwi3f_zoHfXtkbgbMl8I_rcZhDiqgDqlXFzj8xwpAy8gYUn9ysa3z36Snvz1Y8nZVPo8VBtjuCETR-kIr1O9lPZ0BJzoC3" />
            <div class="flex flex-col sidebar-profile-info">
                <span class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['username'] ?? 'Familia'); ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="return confirm('¿Seguro que deseas salir de tu cuenta?');" class="text-xs text-error hover:underline">Cerrar sesión</a>
            </div>
        </div>
    </div>
</nav>

<!-- Botones de control desktop -->
<button id="collapseSidebarBtn"
    type="button"
    class="sidebar-ctrl-btn fixed top-3 left-3 z-[200] hidden lg:flex items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
    title="Colapsar a iconos">
    <span class="material-symbols-outlined transition-transform duration-300" style="font-size:1.3rem">menu_open</span>
</button>

<button id="hideSidebarBtn"
    type="button"
    class="sidebar-ctrl-btn fixed top-3 z-[200] hidden lg:flex items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
    style="left: 3.25rem;"
    title="Ocultar menú completamente">
    <span class="material-symbols-outlined" style="font-size:1.3rem">visibility_off</span>
</button>

<button id="showSidebarFloatingBtn"
    type="button"
    class="fixed top-3 left-3 z-[200] hidden items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
    title="Mostrar menú de navegación">
    <span class="material-symbols-outlined" style="font-size:1.3rem">side_navigation</span>
</button>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const menuToggleBtn = document.getElementById('menuToggleBtn') || document.getElementById('mobile-menu-toggle');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const userSidebar = document.getElementById('userSidebar');

    function openSidebar() {
        if (userSidebar) userSidebar.classList.remove('-translate-x-full');
        if (sidebarOverlay) sidebarOverlay.classList.remove('hidden');
    }

    function closeSidebar() {
        if (userSidebar) userSidebar.classList.add('-translate-x-full');
        if (sidebarOverlay) sidebarOverlay.classList.add('hidden');
    }

    if (menuToggleBtn) menuToggleBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    // Controles Desktop
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const hideSidebarBtn = document.getElementById('hideSidebarBtn');
    const showSidebarFloatingBtn = document.getElementById('showSidebarFloatingBtn');

    function syncSidebarBtns() {
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        const isHidden = document.body.classList.contains('sidebar-hidden');

        if (showSidebarFloatingBtn) {
            showSidebarFloatingBtn.style.display = isHidden ? 'flex' : 'none';
        }
        if (collapseSidebarBtn) {
            collapseSidebarBtn.style.display = isHidden ? 'none' : 'flex';
        }
        if (hideSidebarBtn) {
            hideSidebarBtn.style.display = isHidden ? 'none' : 'flex';
        }
    }

    if (collapseSidebarBtn) {
        collapseSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            document.body.classList.remove('sidebar-hidden');
            document.body.classList.toggle('sidebar-collapsed');
            syncSidebarBtns();
        });
    }

    if (hideSidebarBtn) {
        hideSidebarBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            document.body.classList.remove('sidebar-collapsed');
            document.body.classList.add('sidebar-hidden');
            syncSidebarBtns();
        });
    }

    if (showSidebarFloatingBtn) {
        showSidebarFloatingBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            document.body.classList.remove('sidebar-hidden');
            syncSidebarBtns();
        });
    }

    syncSidebarBtns();

    // Dropdown submenu toggle
    const dropdownBtn = document.getElementById('asistenciaDropdownBtn');
    const submenu = document.getElementById('asistenciaSubmenu');
    const chevron = document.getElementById('asistenciaDropdownChevron');

    if (dropdownBtn && submenu) {
        dropdownBtn.addEventListener('click', () => {
            const isOpen = submenu.classList.toggle('open');
            if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }
});
</script>
