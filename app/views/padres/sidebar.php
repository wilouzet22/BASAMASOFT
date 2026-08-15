<?php
$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard = (strpos($currentPage, '/padres/dashboard') !== false) || ($activePage ?? '') === 'dashboard';
$isCamino    = (strpos($currentPage, '/padres/camino') !== false) || ($activePage ?? '') === 'camino';
$isPuntos    = (strpos($currentPage, '/padres/puntos') !== false) || ($activePage ?? '') === 'puntos';
$isMensajes  = (strpos($currentPage, '/padres/mensajes') !== false) || ($activePage ?? '') === 'mensajes';

// ── Cargar profesores y directivas directamente para el modal de contactos ──
// Esto evita depender de que el controlador pase $data['profesores']
if (!isset($_sidebar_profesores)) {
    $_sidebar_model = new FamiliaModel();
    $_sidebar_profesores  = $_sidebar_model->getAllProfesores();
    $_sidebar_directivas  = $_sidebar_model->getAllDirectivas();
    $_sidebar_familia_nombre = htmlspecialchars(
        ($_SESSION['username'] ?? '') ?:
        (($_SESSION['nombre_principal_acudiente'] ?? '') . ' ' . ($_SESSION['apellidos_principal_acudiente'] ?? ''))
        ?: 'Familia'
    );
}
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
<nav id="userSidebar"
    class="fixed inset-y-0 left-0 w-72 h-screen bg-white/80 backdrop-blur-xl border-r border-outline-variant/30 z-[100] transform -translate-x-full lg:translate-x-0 lg:sticky lg:top-0 flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    
    <!-- Botón colapsar incrustado en el borde derecho -->
    <button id="collapseSidebarBtn" 
        class="absolute -right-3 top-9 hidden lg:flex items-center justify-center w-6 h-6 bg-surface border border-outline-variant rounded-full text-on-surface-variant hover:text-primary hover:border-primary transition-all shadow-sm z-[110]"
        title="Colapsar/Expandir menú">
        <span class="material-symbols-outlined transition-transform duration-300 text-[14px]">chevron_left</span>
    </button>
    <button id="closeSidebarBtn" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>

    <!-- Header del sidebar -->
    <div class="p-4 pb-2 sidebar-header transition-all duration-300">
        <div class="flex flex-col items-center text-center gap-1 mb-1 sidebar-logo-container transition-all duration-300">
            <div class="p-1.5 bg-primary/10 rounded-xl flex-shrink-0">
                <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-8 w-8 object-contain" alt="Logo">
            </div>
            <span class="text-lg font-bold text-primary tracking-tight sidebar-text">EduSaft</span>
        </div>
        <p class="text-[9px] text-outline uppercase tracking-widest font-bold text-center sidebar-text">Portal de Padres</p>
    </div>


    <!-- Links de navegación -->
    <div class="flex-grow px-3 pb-2 space-y-0.5 overflow-y-auto overflow-x-hidden">
        <!-- Panel Principal -->
        <a class="sidebar-item-link <?php echo $isDashboard ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-2 flex items-center gap-2.5 transition-all" 
           href="<?php echo URLROOT; ?>/padres/dashboard">
            <span class="material-symbols-outlined flex-shrink-0 text-[18px]" <?php echo $isDashboard ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>dashboard</span>
            <span class="font-medium text-[12px] sidebar-text">Panel Principal</span>
        </a>

        <!-- Historial Asistencias (Dropdown) -->
        <div class="space-y-0.5">
            <button id="asistenciaDropdownBtn"
                class="sidebar-item-link w-full flex items-center justify-between px-3 py-2 rounded-lg <?php echo ($isCamino || $isPuntos || $isMensajes) ? 'text-primary bg-primary/5' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> transition-all group focus:outline-none">
                <div class="flex items-center gap-2.5">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">history</span>
                    <span class="font-medium text-[12px] sidebar-text">Historial Asistencias</span>
                </div>
                <span id="asistenciaDropdownChevron" class="material-symbols-outlined text-[16px] sidebar-text transition-transform duration-300" style="<?php echo ($isCamino || $isPuntos || $isMensajes) ? 'transform:rotate(180deg)' : ''; ?>">expand_more</span>
            </button>

            <div id="asistenciaSubmenu" class="space-y-0.5 <?php echo ($isCamino || $isPuntos || $isMensajes) ? 'open' : ''; ?>">
                <!-- Camino de Montaña -->
                <a class="submenu-item sidebar-item-link <?php echo $isCamino ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/padres/camino" style="animation-delay:0ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]" <?php echo $isCamino ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>mountain_flag</span>
                    <span class="font-medium text-[12px] sidebar-text">Camino de Montaña</span>
                </a>

                <!-- Mis Puntos -->
                <a class="submenu-item sidebar-item-link <?php echo $isPuntos ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/padres/puntos" style="animation-delay:40ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]" <?php echo $isPuntos ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>workspace_premium</span>
                    <span class="font-medium text-[12px] sidebar-text">Mis Puntos</span>
                </a>

                <!-- Mis Mensajes -->
                <a class="submenu-item sidebar-item-link <?php echo $isMensajes ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-primary/5 hover:text-primary'; ?> rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all" 
                   href="<?php echo URLROOT; ?>/padres/mensajes" style="animation-delay:80ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]" <?php echo $isMensajes ? 'style="font-variation-settings:\'FILL\' 1;"' : ''; ?>>mail</span>
                    <span class="font-medium text-[12px] sidebar-text">Mis Mensajes</span>
                </a>

                <!-- Contáctanos -->
                <button type="button" class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all cursor-pointer" onclick="openModal('contactosModal')" style="animation-delay:120ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">group</span>
                    <span class="font-medium text-[12px] sidebar-text">Contáctanos</span>
                </button>

                <!-- Opinión -->
                <button type="button" class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-lg px-3 py-1.5 flex items-center gap-2.5 transition-all cursor-pointer" onclick="openModal('opinionModal')" style="animation-delay:160ms">
                    <span class="material-symbols-outlined flex-shrink-0 text-[18px]">chat_bubble</span>
                    <span class="font-medium text-[12px] sidebar-text">Opinión</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Perfil de usuario / Cerrar sesión -->
    <div class="p-2.5 mt-auto border-t border-outline-variant/30 flex items-center justify-center lg:justify-between transition-all duration-300">
        <div class="flex items-center gap-2 w-full sidebar-item-link">
            
            <!-- Avatar con upload -->
            <div class="relative flex-shrink-0 group/avatar">
                <?php
                $fotoSesion = $_SESSION['foto_perfil'] ?? null;
                $fotoUrl = $fotoSesion
                    ? URLROOT . '/assets/img/perfiles/' . htmlspecialchars($fotoSesion)
                    : null;
                ?>
                <?php if ($fotoUrl): ?>
                <img id="sidebarAvatar" alt="Foto de perfil"
                     class="w-8 h-8 rounded-full object-cover border border-outline-variant cursor-pointer hover:border-primary transition-all duration-200"
                     src="<?php echo $fotoUrl; ?>"
                     onclick="document.getElementById('fotoPerfilInput').click()"
                     title="Cambiar foto de perfil" />
                <?php else: ?>
                <div id="sidebarAvatar"
                     class="w-8 h-8 rounded-full bg-primary/10 border border-dashed border-primary/40 flex items-center justify-center cursor-pointer hover:bg-primary/20 hover:border-primary transition-all duration-200"
                     onclick="document.getElementById('fotoPerfilInput').click()"
                     title="Subir foto de perfil">
                    <span class="material-symbols-outlined text-primary text-[16px]">person</span>
                </div>
                <?php endif; ?>
                <!-- Badge de cámara -->
                <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-primary rounded-full flex items-center justify-center pointer-events-none opacity-0 group-hover/avatar:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-white" style="font-size:10px">photo_camera</span>
                </div>
            </div>

            <!-- Nombre + logout -->
            <div class="flex flex-col sidebar-profile-info min-w-0">
                <span class="text-[13px] font-bold text-on-surface truncate"><?php echo htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['username'] ?? 'Familia'); ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="text-[11px] text-error hover:underline cursor-pointer">Cerrar sesión</a>
            </div>
        </div>
    </div>
</nav>

<!-- Formulario oculto de subida de foto -->
<form id="fotoPerfilForm" action="<?php echo URLROOT; ?>/padres/subir_foto" method="POST" enctype="multipart/form-data" style="display:none;">
    <input type="file" id="fotoPerfilInput" name="foto_perfil" accept="image/jpeg,image/png,image/webp,image/gif" />
</form>



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

    // Controles Desktop (Collapse)
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const desktopMenuToggle = document.getElementById('desktop-menu-toggle');

    function toggleCollapse(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        document.body.classList.toggle('sidebar-collapsed');
    }

    if (collapseSidebarBtn) {
        collapseSidebarBtn.addEventListener('click', toggleCollapse);
    }
    
    // Si existe el botón del header topbar, también permitimos que colapse
    if (desktopMenuToggle) {
        desktopMenuToggle.addEventListener('click', toggleCollapse);
    }

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
</script>

<!-- Global Modals for Padres -->
<!-- Contactos Modal -->
<div id="contactosModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('contactosModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-lg bg-surface text-on-surface rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0" style="max-height:90dvh;">

        <!-- Header del modal -->
        <div class="flex justify-between items-center px-6 pt-6 pb-4 border-b border-outline-variant/30">
            <h3 class="text-xl font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">contacts</span>
                Contáctanos
            </h3>
            <button onclick="closeModal('contactosModal')" class="text-outline hover:text-on-surface transition-colors p-1 rounded-full hover:bg-surface-variant">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-outline-variant/30">
            <button id="tabMaestros" onclick="switchContactTab('maestros')"
                class="flex-1 flex items-center justify-center gap-2 py-3 text-sm font-bold border-b-2 border-primary text-primary transition-all">
                <span class="material-symbols-outlined text-base">school</span> Maestros
            </button>
            <button id="tabDirectivas" onclick="switchContactTab('directivas')"
                class="flex-1 flex items-center justify-center gap-2 py-3 text-sm font-bold border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-all">
                <span class="material-symbols-outlined text-base">apartment</span> Contacto a Directivas
            </button>
        </div>

        <!-- Lista Maestros -->
        <div id="panelMaestros" class="overflow-y-auto" style="max-height:55dvh;">
            <div class="p-4 space-y-2">
                <?php
                if (empty($_sidebar_profesores)):
                ?>
                <div class="text-center text-on-surface-variant py-8">
                    <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                    <p class="text-sm">No hay maestros registrados.</p>
                </div>
                <?php else: foreach ($_sidebar_profesores as $prof): ?>
                <button type="button"
                    class="w-full flex items-center gap-4 p-3 bg-surface-container-low hover:bg-primary/5 rounded-xl border border-outline-variant/50 hover:border-primary/30 transition-all group text-left"
                    onclick="abrirFormContacto('profesor', <?= $prof->id_profesor ?>, '<?= htmlspecialchars(addslashes($prof->nombres . ' ' . $prof->apellidos)) ?>', '<?= htmlspecialchars(addslashes($prof->email)) ?>')">
                    <div class="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-sm text-on-surface block truncate">
                            <?= htmlspecialchars($prof->nombres . ' ' . $prof->apellidos) ?>
                        </span>
                        <span class="text-xs text-on-surface-variant truncate block"><?= htmlspecialchars($prof->email) ?></span>
                        <?php if (!empty($prof->grupos)): ?>
                        <span class="text-xs text-primary/70 truncate block"><?= htmlspecialchars($prof->grupos) ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
                </button>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Lista Directivas (oculto por default) -->
        <div id="panelDirectivas" class="overflow-y-auto hidden" style="max-height:55dvh;">
            <div class="p-4 space-y-2">
                <?php
                if (empty($_sidebar_directivas)):
                ?>
                <div class="text-center text-on-surface-variant py-8">
                    <span class="material-symbols-outlined text-4xl block mb-2">domain_disabled</span>
                    <p class="text-sm">No hay directivas registradas.</p>
                </div>
                <?php else: foreach ($_sidebar_directivas as $dir): ?>
                <button type="button"
                    class="w-full flex items-center gap-4 p-3 bg-surface-container-low hover:bg-secondary/5 rounded-xl border border-outline-variant/50 hover:border-secondary/30 transition-all group text-left"
                    onclick="abrirFormContacto('directiva', <?= $dir->id_administrador ?>, '<?= htmlspecialchars(addslashes($dir->nombres . ' ' . $dir->apellidos)) ?>', '<?= htmlspecialchars(addslashes($dir->email)) ?>')">
                    <div class="w-11 h-11 rounded-full bg-secondary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/20 transition-colors">
                        <span class="material-symbols-outlined text-secondary">badge</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-sm text-on-surface block truncate">
                            <?= htmlspecialchars($dir->nombres . ' ' . $dir->apellidos) ?>
                        </span>
                        <span class="text-xs text-on-surface-variant truncate block"><?= htmlspecialchars($dir->email) ?></span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors">chevron_right</span>
                </button>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Sub-modal: Formulario de mensaje de contacto -->
<div id="msgContactoModal" class="fixed inset-0 z-[110] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="cerrarFormContacto()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-md bg-surface text-on-surface rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0">

        <!-- Header con nombre familia -->
        <div id="msgHeaderColor" class="px-6 pt-5 pb-4 bg-primary/10 border-b border-primary/20">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-primary/70 uppercase tracking-widest mb-1">Familia</p>
                    <p id="msgFamiliaNombre" class="text-base font-black text-on-surface">
                        <?= $_sidebar_familia_nombre ?>
                    </p>
                </div>
                <button onclick="cerrarFormContacto()" class="text-outline hover:text-on-surface p-1 rounded-full hover:bg-surface-variant transition-colors mt-1">
                    <span class="material-symbols-outlined">arrow_back</span>
                </button>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span id="msgDestinatarioIcon" class="material-symbols-outlined text-primary text-sm">person</span>
                <span id="msgDestinatarioNombre" class="text-sm font-bold text-on-surface"></span>
                <span id="msgDestinatarioEmail" class="text-xs text-on-surface-variant ml-1"></span>
            </div>
        </div>

        <!-- Formulario -->
        <form id="formContacto" method="POST" action="<?php echo URLROOT; ?>/padres/enviar_mensaje" class="p-5 space-y-4">
            <input type="hidden" name="tipo" id="msgTipoInput">
            <input type="hidden" name="id_destinatario" id="msgIdInput">
            <input type="hidden" name="redirect_to" id="msgRedirectInput">

            <div>
                <label class="block text-xs font-black text-on-surface-variant uppercase tracking-wider mb-1">Título *</label>
                <input type="text" name="titulo" required
                    class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none transition-all"
                    placeholder="Ej: Consulta sobre actividad del 12 de agosto">
            </div>
            <div>
                <label class="block text-xs font-black text-on-surface-variant uppercase tracking-wider mb-1">
                    Asunto <span class="normal-case font-normal">(opcional)</span>
                </label>
                <input type="text" name="asunto"
                    class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none transition-all"
                    placeholder="Ej: Inasistencia justificada">
            </div>
            <div>
                <label class="block text-xs font-black text-on-surface-variant uppercase tracking-wider mb-1">Mensaje *</label>
                <textarea name="mensaje" rows="4" required
                    class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:outline-none transition-all resize-none"
                    placeholder="Escribe tu mensaje aquí..."></textarea>
            </div>
            <div class="flex gap-3 pt-1">
                <button type="button" onclick="cerrarFormContacto()"
                    class="flex-1 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant font-bold text-sm hover:bg-surface-variant transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="msgSubmitBtn"
                    class="flex-1 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-sm shadow-md hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-base">send</span> Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchContactTab(tab) {
    const tabs = { maestros: document.getElementById('tabMaestros'), directivas: document.getElementById('tabDirectivas') };
    const panels = { maestros: document.getElementById('panelMaestros'), directivas: document.getElementById('panelDirectivas') };
    Object.keys(tabs).forEach(k => {
        const isActive = k === tab;
        tabs[k].classList.toggle('border-primary', isActive);
        tabs[k].classList.toggle('text-primary', isActive);
        tabs[k].classList.toggle('border-transparent', !isActive);
        tabs[k].classList.toggle('text-on-surface-variant', !isActive);
        panels[k].classList.toggle('hidden', !isActive);
    });
}

function abrirFormContacto(tipo, id, nombre, email) {
    const modal = document.getElementById('msgContactoModal');
    if (!modal) return;
    document.getElementById('msgTipoInput').value = tipo;
    document.getElementById('msgIdInput').value = id;
    document.getElementById('msgRedirectInput').value = window.location.pathname;
    document.getElementById('msgDestinatarioNombre').textContent = nombre;
    document.getElementById('msgDestinatarioEmail').textContent = email ? '· ' + email : '';

    const icon = document.getElementById('msgDestinatarioIcon');
    const btn = document.getElementById('msgSubmitBtn');
    const header = document.getElementById('msgHeaderColor');
    if (tipo === 'directiva') {
        icon.textContent = 'badge';
        icon.style.color = 'var(--md-sys-color-secondary, #6750a4)';
        btn.className = btn.className.replace('bg-primary', 'bg-secondary').replace('text-on-primary', 'text-on-secondary');
        header.className = header.className.replace('bg-primary/10', 'bg-secondary/10').replace('border-primary/20', 'border-secondary/20');
    } else {
        icon.textContent = 'person';
        icon.style.color = 'var(--md-sys-color-primary, #1976d2)';
        btn.className = btn.className.replace('bg-secondary', 'bg-primary').replace('text-on-secondary', 'text-on-primary');
        header.className = header.className.replace('bg-secondary/10', 'bg-primary/10').replace('border-secondary/20', 'border-primary/20');
    }

    modal.classList.remove('hidden');
    setTimeout(() => {
        if(modal.children[0]) modal.children[0].classList.remove('opacity-0');
        if(modal.children[1]) {
            modal.children[1].classList.remove('scale-95', 'opacity-0');
            modal.children[1].classList.add('scale-100', 'opacity-100');
        }
    }, 10);
}

function cerrarFormContacto() {
    const modal = document.getElementById('msgContactoModal');
    if (!modal) return;
    if(modal.children[0]) modal.children[0].classList.add('opacity-0');
    if(modal.children[1]) {
        modal.children[1].classList.add('scale-95', 'opacity-0');
        modal.children[1].classList.remove('scale-100', 'opacity-100');
    }
    setTimeout(() => modal.classList.add('hidden'), 300);
}
</script>

<!-- Opinión Modal -->
<div id="opinionModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('opinionModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-md bg-surface text-on-surface rounded-2xl shadow-2xl p-6 transition-all duration-300 transform scale-95 opacity-0">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-secondary flex items-center gap-2"><span class="material-symbols-outlined">chat_bubble</span> Danos tu Opinión</h3>
            <button onclick="closeModal('opinionModal')" class="text-outline hover:text-on-surface transition-colors p-1 rounded-full hover:bg-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
        <p class="text-sm italic text-on-surface-variant mb-4">"El camino es tan importante como la cima."</p>
        <form method="POST" action="<?php echo URLROOT; ?>/padres/enviar_opinion" class="space-y-4">
            <div>
                <label class="block text-sm font-bold mb-1">¿Cómo podemos mejorar?</label>
                <textarea name="mensaje" rows="4" class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-3 text-sm focus:ring-2 focus:ring-secondary focus:outline-none" placeholder="Escribe tus comentarios..." required></textarea>
            </div>
            <button type="submit" class="w-full bg-secondary text-on-secondary font-bold rounded-xl py-3 shadow-md hover:opacity-90 transition-opacity">Enviar Opinión</button>
        </form>
    </div>
</div>

<?php if (isset($_GET['opinion']) && $_GET['opinion'] === 'ok'): ?>
<script>
(function() {
    const t = document.createElement('div');
    t.innerHTML = '<span class="material-symbols-outlined text-lg">check_circle</span><span>¡Gracias! Tu opinión fue enviada.</span>';
    Object.assign(t.style, {
        position:'fixed', bottom:'24px', left:'50%', transform:'translateX(-50%)',
        background:'#10b981', color:'#fff', display:'flex', alignItems:'center',
        gap:'8px', padding:'12px 24px', borderRadius:'999px', zIndex:'9999',
        boxShadow:'0 4px 20px rgba(0,0,0,0.3)', fontSize:'14px', fontWeight:'600',
        opacity:'0', transition:'opacity .4s'
    });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 4000);
})();
</script>
<?php endif; ?>

<?php if (isset($_GET['msg_ok']) && $_GET['msg_ok'] === '1'): ?>
<script>
(function() {
    const t = document.createElement('div');
    t.innerHTML = '<span class="material-symbols-outlined text-lg">mark_email_read</span><span>¡Mensaje enviado correctamente!</span>';
    Object.assign(t.style, {
        position:'fixed', bottom:'24px', left:'50%', transform:'translateX(-50%)',
        background:'#10b981', color:'#fff', display:'flex', alignItems:'center',
        gap:'8px', padding:'12px 24px', borderRadius:'999px', zIndex:'9999',
        boxShadow:'0 4px 20px rgba(0,0,0,0.3)', fontSize:'14px', fontWeight:'600',
        opacity:'0', transition:'opacity .4s'
    });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 4000);
})();
</script>
<?php endif; ?>

<?php if (isset($_GET['msg_error']) && $_GET['msg_error'] === '1'): ?>
<script>
(function() {
    const t = document.createElement('div');
    t.innerHTML = '<span class="material-symbols-outlined text-lg">error</span><span>Error al enviar. Verifica los campos.</span>';
    Object.assign(t.style, {
        position:'fixed', bottom:'24px', left:'50%', transform:'translateX(-50%)',
        background:'#ef4444', color:'#fff', display:'flex', alignItems:'center',
        gap:'8px', padding:'12px 24px', borderRadius:'999px', zIndex:'9999',
        boxShadow:'0 4px 20px rgba(0,0,0,0.3)', fontSize:'14px', fontWeight:'600',
        opacity:'0', transition:'opacity .4s'
    });
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.opacity = '1'; });
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 4000);
})();
</script>
<?php endif; ?>

<!-- ── Modal de Confirmación de Cierre de Sesión ── -->
<div id="logoutModal" class="fixed inset-0 z-[200] hidden items-center justify-center pointer-events-none">
    <!-- Backdrop con blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-auto" 
         onclick="closeLogoutModal()" id="logoutBackdrop"></div>
    
    <!-- Contenido del modal -->
    <div id="logoutContent" class="relative bg-surface rounded-3xl p-8 max-w-sm w-[90%] mx-auto shadow-2xl border border-outline-variant/50 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto">
        <div class="flex flex-col items-center text-center">
            <!-- Icono animado -->
            <div class="w-20 h-20 rounded-full bg-error-container text-on-error-container flex items-center justify-center mb-6 shadow-inner relative overflow-hidden">
                <span class="material-symbols-outlined text-4xl animate-[bounce_2s_ease-in-out_infinite]">logout</span>
                <div class="absolute inset-0 bg-white/10 rotate-45 transform translate-x-[-100%] transition-transform duration-1000" id="logoutShine"></div>
            </div>
            
            <h3 class="text-xl font-extrabold text-on-surface mb-2">¿Cerrar Sesión?</h3>
            <p class="text-sm text-on-surface-variant mb-8 leading-relaxed">
                Estás a punto de salir del Portal de Padres. Tendrás que volver a iniciar sesión para acceder a tu información.
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
function openLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const backdrop = document.getElementById('logoutBackdrop');
    const content = document.getElementById('logoutContent');
    const shine = document.getElementById('logoutShine');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    requestAnimationFrame(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
        
        // Animación de brillo en el icono
        setTimeout(() => {
            shine.classList.add('translate-x-[200%]');
        }, 300);
    });
}

function closeLogoutModal() {
    const modal = document.getElementById('logoutModal');
    const backdrop = document.getElementById('logoutBackdrop');
    const content = document.getElementById('logoutContent');
    const shine = document.getElementById('logoutShine');
    
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    shine.classList.remove('translate-x-[200%]');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

// ── Foto de Perfil: preview inmediato + submit automático ──
(function() {
    const input = document.getElementById('fotoPerfilInput');
    if (!input) return;

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        // Validaciones cliente
        const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!allowed.includes(file.type)) {
            showFotoToast('Formato no válido. Usa JPG, PNG, WEBP o GIF.', 'error');
            return;
        }
        if (file.size > 3 * 1024 * 1024) {
            showFotoToast('La imagen es demasiado grande (máx. 3 MB).', 'error');
            return;
        }

        // Preview instantáneo en el avatar del sidebar
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('sidebarAvatar');
            if (!avatar) return;

            if (avatar.tagName === 'IMG') {
                avatar.src = e.target.result;
            } else {
                // Reemplazar div placeholder por img
                const img = document.createElement('img');
                img.id = 'sidebarAvatar';
                img.alt = 'Foto de perfil';
                img.className = 'w-10 h-10 rounded-full object-cover border-2 border-primary cursor-pointer transition-all duration-200';
                img.src = e.target.result;
                img.onclick = () => document.getElementById('fotoPerfilInput').click();
                img.title = 'Cambiar foto de perfil';
                avatar.parentNode.replaceChild(img, avatar);
            }
        };
        reader.readAsDataURL(file);

        // Mostrar toast de carga y enviar form
        showFotoToast('Subiendo foto...', 'loading');
        setTimeout(() => {
            document.getElementById('fotoPerfilForm').submit();
        }, 300);
    });

    function showFotoToast(msg, type) {
        const existing = document.getElementById('fotoToast');
        if (existing) existing.remove();
        const t = document.createElement('div');
        t.id = 'fotoToast';
        const colors = {
            loading: { bg: '#6366f1', icon: 'upload' },
            success: { bg: '#10b981', icon: 'check_circle' },
            error:   { bg: '#ef4444', icon: 'error' },
        };
        const c = colors[type] || colors.loading;
        t.innerHTML = `<span class="material-symbols-outlined" style="font-size:18px">${c.icon}</span><span>${msg}</span>`;
        Object.assign(t.style, {
            position:'fixed', bottom:'24px', left:'50%', transform:'translateX(-50%)',
            background: c.bg, color:'#fff', display:'flex', alignItems:'center',
            gap:'8px', padding:'12px 24px', borderRadius:'999px', zIndex:'9999',
            boxShadow:'0 4px 20px rgba(0,0,0,0.25)', fontSize:'14px', fontWeight:'600',
            opacity:'0', transition:'opacity .35s', whiteSpace:'nowrap',
        });
        document.body.appendChild(t);
        requestAnimationFrame(() => { t.style.opacity = '1'; });
        if (type !== 'loading') {
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 3500);
        }
    }

    // Mostrar toast de éxito si viene foto_ok en la URL
    if (new URLSearchParams(window.location.search).get('foto_ok') === '1') {
        showFotoToast('¡Foto actualizada correctamente!', 'success');
        // Limpiar param de URL sin recargar
        const url = new URL(window.location);
        url.searchParams.delete('foto_ok');
        history.replaceState({}, '', url);
    }
    if (new URLSearchParams(window.location.search).get('foto_error')) {
        const errMap = { upload:'Error al subir el archivo.', type:'Formato no permitido.', size:'La imagen supera los 3 MB.', move:'Error al guardar el archivo.' };
        const err = new URLSearchParams(window.location.search).get('foto_error');
        showFotoToast(errMap[err] || 'Error desconocido.', 'error');
        const url = new URL(window.location);
        url.searchParams.delete('foto_error');
        history.replaceState({}, '', url);
    }
})();
</script>

