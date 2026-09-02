<?php
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard     = (strpos($currentPage, '/docentes/dashboard') !== false) || ($activePage ?? '') === 'dashboard';
$isActividades   = ((strpos($currentPage, '/docentes/actividades') !== false) || ($activePage ?? '') === 'actividades') && strpos($currentPage, '/docentes/actividades_proximas') === false;
$isAsistencia    = (strpos($currentPage, '/docentes/asistencia') !== false) || ($activePage ?? '') === 'asistencia';
$isMensajes      = (strpos($currentPage, '/docentes/mensajes') !== false) || ($activePage ?? '') === 'mensajes';
$isNotificaciones = (strpos($currentPage, '/docentes/notificaciones') !== false) || ($activePage ?? '') === 'notificaciones';
$isConfiguracion = (strpos($currentPage, '/docentes/configuracion') !== false) || ($activePage ?? '') === 'configuracion';
$isActProximas   = strpos($currentPage, '/docentes/actividades_proximas') !== false;
$isConfirmarAsistencia = strpos($currentPage, '/docentes/confirmar_asistencia') !== false;

$_sidebar_docente_nombre = htmlspecialchars(
    ($_SESSION['username'] ?? '') ?:
    (($_SESSION['nombre_profesor'] ?? '') . ' ' . ($_SESSION['apellidos_profesor'] ?? ''))
    ?: 'Profesor'
);
?>

<style>
    /* Sidebar collapse y responsividad con scroll independiente */
    @media (min-width: 1024px) {
        #docentesSidebar {
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

        body.sidebar-collapsed #docentesSidebar { width: 5.5rem !important; }
        body.sidebar-collapsed #mainContent, 
        body.sidebar-collapsed main, 
        body.sidebar-collapsed #main-content-wrap { margin-left: 5.5rem !important; }
        body.sidebar-collapsed .sidebar-text { display: none !important; }
        body.sidebar-collapsed .sidebar-profile-info { display: none !important; }
        body.sidebar-collapsed .sidebar-header { padding-left: 0.5rem !important; padding-right: 0.5rem !important; padding-top: 4.5rem !important; }
        body.sidebar-collapsed .sidebar-logo-container { flex-direction: column !important; gap: 0.25rem !important; }
        body.sidebar-collapsed .sidebar-item-link { padding-left: 0 !important; padding-right: 0 !important; justify-content: center !important; }
        body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }
    }
</style>

<!-- Mobile Overlay -->
<div id="docSidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="closeDocentesSidebar()"></div>

<!-- Sidebar principal -->
<nav id="docentesSidebar"
    class="fixed inset-y-0 left-0 w-72 h-screen bg-white/80 backdrop-blur-xl border-r border-outline-variant/30 z-40 transform -translate-x-full lg:translate-x-0 flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] shadow-[4px_0_24px_rgba(0,0,0,0.02)] overflow-hidden">
    
    <!-- Botón colapsar incrustado en el borde derecho -->
    <button id="collapseSidebarBtn" 
        class="absolute -right-3 top-9 hidden lg:flex items-center justify-center w-6 h-6 bg-surface border border-outline-variant rounded-full text-on-surface-variant hover:text-primary hover:border-primary transition-all shadow-sm z-[110]"
        title="Colapsar/Expandir menú">
        <span class="material-symbols-outlined transition-transform duration-300 text-[14px]">chevron_left</span>
    </button>
    <button id="closeDocSidebarBtn" onclick="closeDocentesSidebar()" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>

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
        <p class="text-[9px] text-outline uppercase tracking-widest font-bold text-center sidebar-text">Portal Docente</p>
    </div>

    <!-- Links de navegación -->
    <div class="flex-grow px-3 pb-2 space-y-1 overflow-y-auto overflow-x-hidden">
        <!-- Panel Principal -->
        <a class="sidebar-item-link <?php echo $isDashboard ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/dashboard">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isDashboard ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>dashboard</span>
            <span class="font-medium text-[13px] sidebar-text">Panel Principal</span>
        </a>

        <!-- Actividades propias del docente -->
        <a class="sidebar-item-link <?php echo $isActividades ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/actividades">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isActividades ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>assignment</span>
            <span class="font-medium text-[13px] sidebar-text">Mis Actividades</span>
        </a>


        <!-- Asistencia -->
        <a class="sidebar-item-link <?php echo $isAsistencia ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/asistencia">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isAsistencia ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>event_available</span>
            <span class="font-medium text-[13px] sidebar-text">Asistencia</span>
        </a>

        <!-- Mensajes -->
        <a class="sidebar-item-link <?php echo $isMensajes ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/mensajes">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isMensajes ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>mail</span>
            <span class="font-medium text-[13px] sidebar-text">Mensajes</span>
        </a>

        <!-- Notificaciones -->
        <a class="sidebar-item-link <?php echo $isNotificaciones ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/notificaciones">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isNotificaciones ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>notifications</span>
            <span class="font-medium text-[13px] sidebar-text">Notificaciones</span>
        </a>

        <!-- Confirmar Asistencia (QR) -->
        <a class="sidebar-item-link <?php echo $isConfirmarAsistencia ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2.5 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/docentes/confirmar_asistencia">
            <span class="material-symbols-outlined flex-shrink-0 text-[20px]" <?php echo $isConfirmarAsistencia ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>qr_code_scanner</span>
            <span class="font-medium text-[13px] sidebar-text">Confirmar Asistencia</span>
        </a>
    </div>

    <!-- Perfil de usuario / Cerrar sesión -->
    <div class="p-2.5 mt-auto border-t border-outline-variant/30 flex items-center justify-center lg:justify-between transition-all duration-300">
        <div class="flex items-center gap-2 w-full sidebar-item-link">
            <div class="w-8 h-8 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary text-[18px]">school</span>
            </div>

            <!-- Nombre + logout -->
            <div class="flex flex-col sidebar-profile-info min-w-0">
                <span class="text-[13px] font-bold text-on-surface truncate"><?php echo $_sidebar_docente_nombre; ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="text-[11px] text-error hover:underline cursor-pointer">Cerrar sesión</a>
            </div>
        </div>
    </div>
</nav>

<!-- Modal de Confirmación de Cierre de Sesión para Docentes -->
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
                Estás a punto de salir del Portal Docente. Tendrás que volver a iniciar sesión para acceder a tus clases.
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
document.addEventListener('DOMContentLoaded', () => {
    const docSidebar = document.getElementById('docentesSidebar');
    const docOverlay = document.getElementById('docSidebarOverlay');
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const desktopMenuToggle = document.getElementById('desktop-menu-toggle');

    function toggleCollapse(e) {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        document.body.classList.toggle('sidebar-collapsed');
    }

    if (collapseSidebarBtn) collapseSidebarBtn.addEventListener('click', toggleCollapse);
    if (desktopMenuToggle) desktopMenuToggle.addEventListener('click', toggleCollapse);

    window.toggleDocentesCollapse = toggleCollapse;

    window.toggleDocentesMobileSidebar = function() {
        if (docSidebar) docSidebar.classList.remove('-translate-x-full');
        if (docOverlay) docOverlay.classList.remove('hidden');
    };

    window.closeDocentesSidebar = function() {
        if (docSidebar) docSidebar.classList.add('-translate-x-full');
        if (docOverlay) docOverlay.classList.add('hidden');
    };

    // Modal Global Functions
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            if(modal.children[0]) modal.children[0].classList.remove('opacity-0');
            if(modal.children[1]) {
                modal.children[1].classList.remove('scale-95', 'opacity-0');
                modal.children[1].classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    };

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        if(modal.children[0]) modal.children[0].classList.add('opacity-0');
        if(modal.children[1]) {
            modal.children[1].classList.add('scale-95', 'opacity-0');
            modal.children[1].classList.remove('scale-100', 'opacity-100');
        }
        setTimeout(() => modal.classList.add('hidden'), 300);
    };

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
