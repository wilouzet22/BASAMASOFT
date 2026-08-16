<?php
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard   = (strpos($currentPage, '/admin/dashboard') !== false || $currentPage === '/admin/' || $currentPage === '/admin');
$isProfesores  = strpos($currentPage, '/admin/profesores') !== false;
$isFamilias    = strpos($currentPage, '/admin/familias') !== false;
$isEstudiantes = strpos($currentPage, '/admin/estudiantes') !== false;
$isSedes       = strpos($currentPage, '/admin/sedes') !== false;
$isGrupos      = strpos($currentPage, '/admin/grupos') !== false;
$isActividades = strpos($currentPage, '/admin/actividades') !== false && strpos($currentPage, '/admin/actividades_proximas') === false;
$isMensajes    = strpos($currentPage, '/admin/mensajes') !== false;
$isAuditoria   = strpos($currentPage, '/admin/auditoria') !== false;
$isActProximas = strpos($currentPage, '/admin/actividades_proximas') !== false;

$isUsuariosGroup      = ($isProfesores || $isFamilias || $isEstudiantes);
$isInstitucionalGroup = ($isSedes || $isGrupos || $isActividades);
$isSistemaGroup       = ($isMensajes || $isAuditoria);

$_sidebar_admin_nombre = htmlspecialchars($_SESSION['username'] ?? 'Administrador');
?>

<style>
    /* Sidebar collapse y responsividad con scroll independiente */
    @media (min-width: 1024px) {
        #adminSidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            z-index: 40 !important;
        }
        #mainContent, 
        main:not(.modal-main), 
        #main-content-wrap {
            margin-left: 18rem !important;
            min-width: 0 !important;
            flex: 1 1 0% !important;
        }

        body.sidebar-collapsed #adminSidebar { width: 5.5rem !important; }
        body.sidebar-collapsed #mainContent, 
        body.sidebar-collapsed main, 
        body.sidebar-collapsed #main-content-wrap { margin-left: 5.5rem !important; }
        body.sidebar-collapsed .sidebar-text { display: none !important; }
        body.sidebar-collapsed .sidebar-profile-info { display: none !important; }
        body.sidebar-collapsed .sidebar-header { padding-left: 0.5rem !important; padding-right: 0.5rem !important; padding-top: 4.5rem !important; }
        body.sidebar-collapsed .sidebar-logo-container { flex-direction: column !important; gap: 0.25rem !important; }
        body.sidebar-collapsed .sidebar-item-link { padding-left: 0 !important; padding-right: 0 !important; justify-content: center !important; }
        body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }
        body.sidebar-collapsed .admin-submenu { display: none !important; }
    }

    /* Submenús desplegables con animación estilo portal padres */
    .admin-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
        opacity: 0;
    }
    .admin-submenu.open {
        max-height: 320px;
        opacity: 1;
    }

    @keyframes submenu-drop {
        0%   { opacity: 0; transform: translateY(-12px); }
        60%  { opacity: 1; transform: translateY(2px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .admin-submenu.open .submenu-item {
        animation: submenu-drop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
</style>

<!-- Mobile Overlay -->
<div id="adminSidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="closeAdminSidebar()"></div>

<!-- Sidebar principal -->
<nav id="adminSidebar"
    class="fixed inset-y-0 left-0 w-72 h-screen bg-white/80 backdrop-blur-xl border-r border-outline-variant/30 z-40 transform -translate-x-full lg:translate-x-0 flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] shadow-[4px_0_24px_rgba(0,0,0,0.02)] overflow-hidden">
    
    <!-- Botón colapsar incrustado en el borde derecho -->
    <button id="collapseSidebarBtn" 
        class="absolute -right-3 top-9 hidden lg:flex items-center justify-center w-6 h-6 bg-surface border border-outline-variant rounded-full text-on-surface-variant hover:text-primary hover:border-primary transition-all shadow-sm z-[110]"
        title="Colapsar/Expandir menú">
        <span class="material-symbols-outlined transition-transform duration-300 text-[14px]">chevron_left</span>
    </button>
    <button id="closeAdminSidebarBtn" onclick="closeAdminSidebar()" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>

    <!-- Header del sidebar -->
    <div class="p-4 pb-3 sidebar-header transition-all duration-300">
        <div class="flex flex-col items-center text-center gap-2 mb-1 sidebar-logo-container transition-all duration-300">
            <div class="relative flex-shrink-0">
                <img src="<?php echo URLROOT; ?>/assets/img/logo.svg"
                     class="h-24 w-24 object-contain rounded-full border-4 border-primary/20 shadow-lg ring-2 ring-primary/10 bg-white p-1"
                     alt="Logo EduSaft">
                <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-green-400 border-2 border-white rounded-full shadow-sm"></span>
            </div>
            <span class="text-base font-extrabold text-primary tracking-tight sidebar-text mt-1">EduSaft</span>
        </div>
        <p class="text-[9px] text-outline uppercase tracking-widest font-bold text-center sidebar-text">Administración</p>
    </div>

    <!-- Links de navegación con desplegables estructurados como los Padres -->
    <div class="flex-grow px-3 pb-2 space-y-1 overflow-y-auto overflow-x-hidden">
        
        <!-- Panel Principal -->
        <a class="sidebar-item-link <?php echo $isDashboard ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/admin/dashboard">
            <span class="material-symbols-outlined flex-shrink-0 text-[18px]" <?php echo $isDashboard ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>dashboard</span>
            <span class="font-medium text-[12px] sidebar-text">Panel Principal</span>
        </a>


        <!-- Categoría 1: Gestión de Usuarios (Desplegable) -->
        <div class="space-y-0.5">
            <button type="button" onclick="toggleAdminDropdown('usuariosSubmenu', 'usuariosChevron')"
                class="sidebar-item-link w-full flex items-center justify-between px-3 py-2 rounded-lg <?php echo $isUsuariosGroup ? 'text-primary bg-primary/5 font-bold' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> transition-all group focus:outline-none cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">group</span>
                    <span class="font-medium text-[12px] sidebar-text">Gestión de Usuarios</span>
                </div>
                <span id="usuariosChevron" class="material-symbols-outlined text-[16px] sidebar-text transition-transform duration-300" style="<?php echo $isUsuariosGroup ? 'transform:rotate(180deg)' : ''; ?>">expand_more</span>
            </button>

            <div id="usuariosSubmenu" class="admin-submenu pl-3 space-y-0.5 <?php echo $isUsuariosGroup ? 'open' : ''; ?>">
                <a class="submenu-item sidebar-item-link <?php echo $isProfesores ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/profesores" style="animation-delay:0ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">school</span>
                    <span class="font-medium text-[12px] sidebar-text">Profesores</span>
                </a>

                <a class="submenu-item sidebar-item-link <?php echo $isFamilias ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/familias" style="animation-delay:40ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">family_restroom</span>
                    <span class="font-medium text-[12px] sidebar-text">Familias</span>
                </a>

                <a class="submenu-item sidebar-item-link <?php echo $isEstudiantes ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/estudiantes" style="animation-delay:80ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">groups</span>
                    <span class="font-medium text-[12px] sidebar-text">Estudiantes</span>
                </a>
            </div>
        </div>

        <!-- Categoría 2: Gestión Institucional (Desplegable) -->
        <div class="space-y-0.5">
            <button type="button" onclick="toggleAdminDropdown('institucionalSubmenu', 'institucionalChevron')"
                class="sidebar-item-link w-full flex items-center justify-between px-3 py-2 rounded-lg <?php echo $isInstitucionalGroup ? 'text-primary bg-primary/5 font-bold' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> transition-all group focus:outline-none cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">domain</span>
                    <span class="font-medium text-[12px] sidebar-text">Gestión Institucional</span>
                </div>
                <span id="institucionalChevron" class="material-symbols-outlined text-[16px] sidebar-text transition-transform duration-300" style="<?php echo $isInstitucionalGroup ? 'transform:rotate(180deg)' : ''; ?>">expand_more</span>
            </button>

            <div id="institucionalSubmenu" class="admin-submenu pl-3 space-y-0.5 <?php echo $isInstitucionalGroup ? 'open' : ''; ?>">
                <a class="submenu-item sidebar-item-link <?php echo $isSedes ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/sedes" style="animation-delay:0ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">apartment</span>
                    <span class="font-medium text-[12px] sidebar-text">Sedes</span>
                </a>

                <a class="submenu-item sidebar-item-link <?php echo $isGrupos ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/grupos" style="animation-delay:40ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">category</span>
                    <span class="font-medium text-[12px] sidebar-text">Grupos</span>
                </a>

                <a class="submenu-item sidebar-item-link <?php echo $isActividades ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/actividades" style="animation-delay:80ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">event</span>
                    <span class="font-medium text-[12px] sidebar-text">Actividades</span>
                </a>
            </div>
        </div>

        <!-- Categoría 3: Sistema & Auditoría (Desplegable) -->
        <div class="space-y-0.5">
            <button type="button" onclick="toggleAdminDropdown('sistemaSubmenu', 'sistemaChevron')"
                class="sidebar-item-link w-full flex items-center justify-between px-3 py-2 rounded-lg <?php echo $isSistemaGroup ? 'text-primary bg-primary/5 font-bold' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> transition-all group focus:outline-none cursor-pointer">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">settings_system_daydream</span>
                    <span class="font-medium text-[12px] sidebar-text">Sistema & Auditoría</span>
                </div>
                <span id="sistemaChevron" class="material-symbols-outlined text-[16px] sidebar-text transition-transform duration-300" style="<?php echo $isSistemaGroup ? 'transform:rotate(180deg)' : ''; ?>">expand_more</span>
            </button>

            <div id="sistemaSubmenu" class="admin-submenu pl-3 space-y-0.5 <?php echo $isSistemaGroup ? 'open' : ''; ?>">
                <a class="submenu-item sidebar-item-link <?php echo $isMensajes ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/mensajes" style="animation-delay:0ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">mail</span>
                    <span class="font-medium text-[12px] sidebar-text">Mensajes</span>
                </a>

                <a class="submenu-item sidebar-item-link <?php echo $isAuditoria ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/admin/auditoria" style="animation-delay:40ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">history</span>
                    <span class="font-medium text-[12px] sidebar-text">Auditoría</span>
                </a>
            </div>
        </div>

    </div>

    <!-- Perfil de usuario / Cerrar sesión -->
    <div class="p-2.5 mt-auto border-t border-outline-variant/30 flex items-center justify-center lg:justify-between transition-all duration-300">
        <div class="flex items-center gap-2 w-full sidebar-item-link">
            <div class="w-8 h-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary text-[18px]">admin_panel_settings</span>
            </div>

            <!-- Nombre + logout -->
            <div class="flex flex-col sidebar-profile-info min-w-0">
                <span class="text-[13px] font-bold text-on-surface truncate"><?php echo $_sidebar_admin_nombre; ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="text-[11px] text-error hover:underline cursor-pointer">Cerrar sesión</a>
            </div>
        </div>
    </div>
</nav>

<!-- Modal de Confirmación de Cierre de Sesión para Admin -->
<div id="logoutModal" class="fixed inset-0 z-[200] hidden items-center justify-center pointer-events-none">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-auto" 
         onclick="closeLogoutModal()" id="logoutBackdrop"></div>
    
    <div id="logoutContent" class="relative bg-surface rounded-3xl p-8 max-w-sm w-[90%] mx-auto shadow-2xl border border-outline-variant/50 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
        <div class="flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-full bg-error-container text-on-error-container flex items-center justify-center mb-6 shadow-inner relative overflow-hidden">
                <span class="material-symbols-outlined text-4xl animate-[bounce_2s_ease-in-out_infinite]">logout</span>
                <div class="absolute inset-0 bg-white/10 rotate-45 transform translate-x-[-100%] transition-transform duration-1000" id="logoutShine"></div>
            </div>
            
            <h3 class="text-xl font-extrabold text-on-surface mb-2">¿Cerrar Sesión?</h3>
            <p class="text-sm text-on-surface-variant mb-8 leading-relaxed">
                Estás a punto de salir de la Administración. Tendrás que volver a iniciar sesión para acceder al sistema.
            </p>
            
            <div class="flex gap-3 w-full">
                <button type="button" onclick="closeLogoutModal()"
                        class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-on-surface hover:bg-surface-container transition-colors border border-outline-variant">
                    Cancelar
                </button>
                <a href="<?php echo URLROOT; ?>/auth/logout"
                   class="flex-1 px-4 py-3 rounded-xl font-bold text-sm bg-error text-on-error hover:bg-error/90 hover:scale-105 active:scale-95 transition-all shadow-md flex items-center justify-center gap-2">
                   <span class="material-symbols-outlined text-[18px]">power_settings_new</span> Salir
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAdminDropdown(submenuId, chevronId) {
    const submenu = document.getElementById(submenuId);
    const chevron = document.getElementById(chevronId);
    if (!submenu) return;
    
    const isOpen = submenu.classList.contains('open');
    if (isOpen) {
        submenu.classList.remove('open');
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        submenu.classList.add('open');
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const adminSidebar = document.getElementById('adminSidebar');
    const adminOverlay = document.getElementById('adminSidebarOverlay');
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const desktopMenuToggle = document.getElementById('desktop-menu-toggle');

    function toggleCollapse(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        document.body.classList.toggle('sidebar-collapsed');
    }

    if (collapseSidebarBtn) collapseSidebarBtn.addEventListener('click', toggleCollapse);
    if (desktopMenuToggle) desktopMenuToggle.addEventListener('click', toggleCollapse);

    window.toggleSidebarCollapse = toggleCollapse;

    window.toggleMobileSidebar = function() {
        if (adminSidebar) adminSidebar.classList.remove('-translate-x-full');
        if (adminOverlay) adminOverlay.classList.remove('hidden');
    };

    window.closeAdminSidebar = function() {
        if (adminSidebar) adminSidebar.classList.add('-translate-x-full');
        if (adminOverlay) adminOverlay.classList.add('hidden');
    };

    // Modal Global Functions
    window.openLogoutModal = function() {
        const modal = document.getElementById('logoutModal');
        const backdrop = document.getElementById('logoutBackdrop');
        const content = document.getElementById('logoutContent');
        const shine = document.getElementById('logoutShine');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            if (backdrop) { backdrop.classList.remove('opacity-0'); backdrop.classList.add('opacity-100'); }
            if (content) { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }
            if (shine) { setTimeout(() => shine.classList.add('translate-x-[200%]'), 300); }
        });
    };

    window.closeLogoutModal = function() {
        const modal = document.getElementById('logoutModal');
        const backdrop = document.getElementById('logoutBackdrop');
        const content = document.getElementById('logoutContent');
        const shine = document.getElementById('logoutShine');
        if (!modal) return;
        if (backdrop) { backdrop.classList.remove('opacity-100'); backdrop.classList.add('opacity-0'); }
        if (content) { content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0'); }
        if (shine) shine.classList.remove('translate-x-[200%]');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    };
});
</script>
