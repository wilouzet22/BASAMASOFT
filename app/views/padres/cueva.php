<?php
$data = $data ?? [];

$bodyClass = 'antialiased min-h-screen';
$extraStyles = '
    <style>
        /* Sidebar collapse */
        @media (min-width: 1024px) {
            body.sidebar-collapsed #userSidebar { width: 5.5rem !important; }
            body.sidebar-collapsed #mainContent { margin-left: 5.5rem !important; }
            body.sidebar-collapsed .sidebar-text { display: none !important; }
            body.sidebar-collapsed .sidebar-search-container { display: none !important; }
            body.sidebar-collapsed .sidebar-profile-info { display: none !important; }
            body.sidebar-collapsed .sidebar-header { padding-left: 0.5rem !important; padding-right: 0.5rem !important; padding-top: 4.5rem !important; }
            body.sidebar-collapsed .sidebar-logo-container { flex-direction: column !important; gap: 0.25rem !important; }
            body.sidebar-collapsed .sidebar-item-link { padding-left: 0 !important; padding-right: 0 !important; justify-content: center !important; }
            body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }
            body.sidebar-collapsed .sidebar-controls-container { flex-direction: column !important; left: 0 !important; right: 0 !important; align-items: center !important; gap: 0.25rem !important; }

            /* Sidebar desaparecible (completamente oculto) */
            body.sidebar-hidden #userSidebar {
                transform: translateX(-100%) !important;
            }
            body.sidebar-hidden #mainContent {
                margin-left: 0 !important;
            }
            body.sidebar-hidden #showSidebarFloatingBtn {
                display: flex !important;
            }
        }
        /* Dropdown submenu animation */
        #asistenciaSubmenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease;
            opacity: 0;
        }
        #asistenciaSubmenu.open {
            max-height: 320px;
            opacity: 1;
        }

        /* ── Anular fondo oscuro global en el main de esta vista ── */
        .dark main#mainContent,
        .superdark main#mainContent,
        main#mainContent {
            background: transparent !important;
            animation: none !important;
        }

        /* ── Anular height:auto global de img para la imagen de fondo y permitir Zoom compensatorio ── */
        img#cuevaImg {
            height: 100% !important;
            max-width: none !important;
            width: 100% !important;
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), object-position 0.15s ease !important;
            transform-origin: center center;
        }

        /* Zoom dinámico compensatorio al colapsar u ocultar el sidebar */
        body.sidebar-collapsed img#cuevaImg {
            transform: scale(1.18) !important;
        }
        body.sidebar-hidden img#cuevaImg {
            transform: scale(1.28) !important;
        }

        /* ── Panorama Slider: oculto en PC ── */
        @media (min-width: 1024px) {
            #panoSliderBar { display: none !important; }
        }

        /* ── Panorama Slider ── */
        #panoSliderBar {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 60;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 9999px;
            padding: 0.6rem 1.25rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            width: min(460px, 88vw);
            transition: opacity 0.3s ease;
        }
        #panoSliderBar:hover { opacity: 1 !important; }
        #panoSliderBar span.material-symbols-outlined {
            color: rgba(255,255,255,0.75);
            font-size: 1.1rem;
            flex-shrink: 0;
            user-select: none;
        }
        #panoSlider {
            -webkit-appearance: none;
            appearance: none;
            flex: 1;
            height: 4px;
            border-radius: 9999px;
            background: rgba(255,255,255,0.25);
            outline: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        #panoSlider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
            border: 2px solid rgba(255,255,255,0.8);
            cursor: grab;
            transition: transform 0.15s;
        }
        #panoSlider::-webkit-slider-thumb:active { cursor: grabbing; transform: scale(1.25); }
        #panoSlider::-moz-range-thumb {
            width: 18px; height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
            border: 2px solid rgba(255,255,255,0.8);
            cursor: grab;
        }
        /* Fill izquierdo animado */
        #panoSlider {
            background: linear-gradient(
                to right,
                rgba(255,255,255,0.75) var(--pano-fill, 50%),
                rgba(255,255,255,0.22) var(--pano-fill, 50%)
            );
        }
    </style>
';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <span class="font-bold text-primary text-lg">Camino de Cueva</span>
    </div>
    <button id="menuToggleBtn" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95">
        <span class="material-symbols-outlined">menu</span>
    </button>
</header>

<div class="flex">
    <!-- Sidebar -->
    <nav id="userSidebar" class="flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-50 transition-all duration-300 -translate-x-full lg:translate-x-0 overflow-hidden">
        <button id="closeSidebarBtn" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>


        <div class="p-8 pb-4 sidebar-header transition-all duration-300">
            <div class="flex flex-col items-center text-center gap-3 mb-2 sidebar-logo-container transition-all duration-300">
                <div class="p-3 bg-primary/10 rounded-2xl flex-shrink-0">
                    <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-16 w-16 object-contain" alt="Logo">
                </div>
                <span class="text-2xl font-bold text-primary tracking-tight sidebar-text">EduSaft</span>
            </div>
            <p class="text-xs text-outline uppercase tracking-widest font-bold text-center sidebar-text">Portal de Padres</p>
        </div>

        <div class="px-6 mb-4 sidebar-search-container transition-all duration-300">
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none text-sm">search</span>
                <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-full text-xs font-medium focus:ring-2 focus:ring-primary transition-all" placeholder="Buscar" type="text" />
            </div>
        </div>

        <div class="flex-grow px-4 space-y-1 overflow-y-auto">
            <a class="sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/dashboard">
                <span class="material-symbols-outlined flex-shrink-0">dashboard</span>
                <span class="font-medium text-sm sidebar-text">Panel Principal</span>
            </a>

            <!-- Historial Asistencias (Dropdown) -->
            <div class="space-y-1">
                <button id="asistenciaDropdownBtn"
                    class="sidebar-item-link w-full flex items-center justify-between px-4 py-3 rounded-2xl text-primary bg-primary/5 transition-all group focus:outline-none">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined flex-shrink-0">history</span>
                        <span class="font-medium text-sm sidebar-text">Historial Asistencias</span>
                    </div>
                    <span id="asistenciaDropdownChevron" class="material-symbols-outlined text-sm sidebar-text transition-transform duration-300" style="transform:rotate(180deg)">expand_more</span>
                </button>

                <div id="asistenciaSubmenu" class="space-y-1 open">
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino" style="animation-delay:0ms">
                        <span class="material-symbols-outlined flex-shrink-0">mountain_flag</span>
                        <span class="font-medium text-sm sidebar-text">Camino de Montaña</span>
                    </a>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/puntos" style="animation-delay:80ms">
                        <span class="material-symbols-outlined flex-shrink-0">workspace_premium</span>
                        <span class="font-medium text-sm sidebar-text">Mis Puntos</span>
                    </a>
                    <!-- Camino de Cueva — activo en esta vista -->
                    <a class="submenu-item sidebar-item-link bg-primary text-on-primary shadow-sm rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/cueva" style="animation-delay:120ms">
                        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings:'FILL' 1;">cave</span>
                        <span class="font-medium text-sm sidebar-text">Camino de Cueva</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 mt-auto border-t border-outline-variant/30 flex items-center justify-center lg:justify-between transition-all duration-300">
            <div class="flex items-center gap-3 w-full sidebar-item-link">
                <img alt="User Profile" class="w-10 h-10 rounded-full object-cover border border-outline-variant flex-shrink-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4-sZziL98gyg-93o6NhBHrP9O1Mjg_PrtJ-VzMuxDcwNbPGr5nxHChNA__Afx1axDdlsUMxN0xhHaIfyQ4BJfSa1VKn5BjHv8Hso4JGk4t_9P9ByngNDbUCc2P7c1f4pRZM6NBUD-aFvlmReMobzBGytlvFkVx0doS8C7fu7znh8lOkuwi3f_zoHfXtkbgbMl8I_rcZhDiqgDqlXFzj8xwpAy8gYUn9ysa3z36Snvz1Y8nZVPo8VBtjuCETR-kIr1O9lPZ0BJzoC3" />
                <div class="flex flex-col sidebar-profile-info">
                    <span class="text-sm font-bold text-on-surface">Usuario</span>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="return confirm('¿Seguro que deseas salir de tu cuenta?');" class="text-xs text-error hover:underline">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    <!-- ===== Controles de sidebar DESKTOP (fuera del nav para evitar stacking context) ===== -->
    <!-- Botón colapsar: visible solo en desktop cuando sidebar está expandido -->
    <button id="collapseSidebarBtn"
        type="button"
        class="sidebar-ctrl-btn fixed top-3 left-3 z-[200] hidden lg:flex items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
        title="Colapsar a iconos">
        <span class="material-symbols-outlined transition-transform duration-300" style="font-size:1.3rem">menu_open</span>
    </button>

    <!-- Botón ocultar: visible solo en desktop cuando sidebar está expandido o colapsado -->
    <button id="hideSidebarBtn"
        type="button"
        class="sidebar-ctrl-btn fixed top-3 z-[200] hidden lg:flex items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
        style="left: 3.25rem;"
        title="Ocultar menú completamente">
        <span class="material-symbols-outlined" style="font-size:1.3rem">visibility_off</span>
    </button>

    <!-- Botón flotante para restaurar sidebar cuando está oculto -->
    <button id="showSidebarFloatingBtn"
        type="button"
        class="fixed top-3 left-3 z-[200] hidden items-center justify-center p-2 bg-white/80 backdrop-blur-sm rounded-full shadow-md border border-outline-variant text-on-surface-variant hover:bg-surface-variant hover:text-primary active:scale-95 transition-all cursor-pointer"
        title="Mostrar menú de navegación">
        <span class="material-symbols-outlined" style="font-size:1.3rem">side_navigation</span>
    </button>

    <!-- Main content — fondo cueva -->
    <main id="mainContent" class="flex-1 lg:ml-72 min-h-screen relative transition-all duration-300">

        <!-- Fondo cueva responsive con paneo horizontal -->
        <img id="cuevaImg"
            src="<?= URLROOT ?>/public/assets/img/caverna.png"
            alt="Fondo Cueva"
            class="fixed inset-0 w-full h-full object-cover pointer-events-none"
            style="object-position: 50% 50%; z-index: 2;" />

        <!-- Slider de paneo horizontal -->
        <div id="panoSliderBar">
            <span class="material-symbols-outlined" title="Izquierda">chevron_left</span>
            <input id="panoSlider" type="range" min="0" max="100" value="50"
                aria-label="Deslizar imagen horizontalmente" />
            <span class="material-symbols-outlined" title="Derecha">chevron_right</span>
        </div>

    </main>
</div>

<script>
    // ── Sidebar toggle (mobile) ──
    const menuToggleBtn = document.getElementById('menuToggleBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const userSidebar = document.getElementById('userSidebar');

    function openSidebar() {
        userSidebar.classList.remove('-translate-x-full');
        sidebarOverlay.classList.remove('hidden');
    }

    function closeSidebar() {
        userSidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.add('hidden');
    }
    if (menuToggleBtn) menuToggleBtn.addEventListener('click', openSidebar);
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

    // ── Sidebar collapse & hide (desktop) ──
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const hideSidebarBtn = document.getElementById('hideSidebarBtn');
    const showSidebarFloatingBtn = document.getElementById('showSidebarFloatingBtn');

    // Actualiza la posición del botón ocultar según el ancho actual del sidebar
    function updateHideBtnPos() {
        if (!hideSidebarBtn) return;
        const collapsed = document.body.classList.contains('sidebar-collapsed');
        // sidebar expandido = 18rem (288px), colapsado = 5.5rem (88px)
        // el botón colapsar siempre está a left:12px (top-3/left-3)
        // el botón ocultar va justo al lado: 12px + 40px (botón) + 8px gap = 60px cuando expandido
        // colapsado: sidebar es 5.5rem → centramos los dos botones
        hideSidebarBtn.style.left = collapsed ? '3.75rem' : '3.25rem';
    }

    // Sincroniza visibilidad: hide mostrado ↔ show oculto y viceversa
    function syncSidebarBtns() {
        const hidden = document.body.classList.contains('sidebar-hidden');
        const collapsed = document.body.classList.contains('sidebar-collapsed');

        // Botones colapsar y ocultar: visibles cuando el sidebar NO está hidden
        if (collapseSidebarBtn) collapseSidebarBtn.style.display = hidden ? 'none' : '';
        if (hideSidebarBtn) hideSidebarBtn.style.display = hidden ? 'none' : '';

        // Botón flotante restaurar: solo cuando hidden
        if (showSidebarFloatingBtn) {
            showSidebarFloatingBtn.style.display = hidden ? 'flex' : 'none';
        }

        // Ajustar posición del botón ocultar
        updateHideBtnPos();

        // Girar ícono del botón colapsar según estado
        const collapseIcon = collapseSidebarBtn ? collapseSidebarBtn.querySelector('span') : null;
        if (collapseIcon) {
            collapseIcon.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';
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

    // Estado inicial
    syncSidebarBtns();

    // ── Dropdown submenu ──
    const dropdownBtn = document.getElementById('asistenciaDropdownBtn');
    const submenu = document.getElementById('asistenciaSubmenu');
    const chevron = document.getElementById('asistenciaDropdownChevron');
    if (dropdownBtn) {
        dropdownBtn.addEventListener('click', () => {
            const isOpen = submenu.classList.toggle('open');
            chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    }

    // ── Panorama horizontal slider ──
    const cuevaImg = document.getElementById('cuevaImg');
    const panoSlider = document.getElementById('panoSlider');
    const panoBar = document.getElementById('panoSliderBar');

    function applyPano(val) {
        const pct = parseFloat(val);
        cuevaImg.style.objectPosition = pct + '% 50%';
        panoSlider.style.setProperty('--pano-fill', pct + '%');
    }

    if (panoSlider && cuevaImg) {
        // Inicializar al centro
        applyPano(panoSlider.value);

        // Input en tiempo real (mouse + touch)
        panoSlider.addEventListener('input', e => applyPano(e.target.value));

        // Soporte táctil: swipe horizontal en la imagen
        let touchStartX = null;
        let startVal = null;
        document.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
            startVal = parseFloat(panoSlider.value);
        }, {
            passive: true
        });
        document.addEventListener('touchmove', e => {
            if (touchStartX === null) return;
            const dx = e.touches[0].clientX - touchStartX;
            const winW = window.innerWidth;
            const deltaPct = -(dx / winW) * 100; // izq → dcha
            const newVal = Math.min(100, Math.max(0, startVal + deltaPct));
            panoSlider.value = newVal;
            applyPano(newVal);
        }, {
            passive: true
        });
        document.addEventListener('touchend', () => {
            touchStartX = null;
        }, {
            passive: true
        });

        // Fade suave: si el usuario no interactúa, el bar se atenúa levemente
        let hideTimer;

        function showBar() {
            panoBar.style.opacity = '1';
            clearTimeout(hideTimer);
            hideTimer = setTimeout(() => {
                panoBar.style.opacity = '0.55';
            }, 2500);
        }
        panoSlider.addEventListener('input', showBar);
        panoBar.addEventListener('mouseenter', () => clearTimeout(hideTimer));
        panoBar.addEventListener('mouseleave', showBar);
        showBar();
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>