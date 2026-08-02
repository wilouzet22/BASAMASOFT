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

        /* ── SVG overlay: sigue el zoom de cuevaImg para quedar "incrustado" ── */
        #cuevaWaypointsContainer {
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1) !important;
            transform-origin: center center;
        }
        body.sidebar-collapsed #cuevaWaypointsContainer {
            transform: scale(1.18) !important;
        }
        body.sidebar-hidden #cuevaWaypointsContainer {
            transform: scale(1.28) !important;
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

        /* Animación para la flecha del último punto apuntando al nororiente (45º) con movimiento */
        @keyframes peak-arrow-bounce {
            0%, 100% {
                transform: rotate(45deg) translate(0px, 0px);
            }
            50% {
                transform: rotate(45deg) translate(0px, -15px);
            }
        }
        .peak-arrow-animated {
            animation: peak-arrow-bounce 1.4s ease-in-out infinite;
            transform-origin: 0px 0px;
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
    <!-- Sidebar reusable -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <!-- Main content — fondo cueva unificado en SVG -->
    <main id="mainContent" class="flex-1 lg:ml-72 min-h-screen relative transition-all duration-300 bg-[#0d141f] overflow-hidden">

        <!-- Contenedor único SVG que incluye imagen de fondo y waypoints. Panneado vía JS -->
        <div id="panoContainer" class="absolute pointer-events-none" style="z-index:10; top:0; left:0;">
            <svg id="cuevaWaypointsSVG"
                 viewBox="0 0 1632 964"
                 preserveAspectRatio="none"
                 style="width:100%; height:100%; pointer-events:none;">
                
                <!-- Imagen de fondo incrustada (1632x964) -->
                <image href="<?= URLROOT ?>/public/assets/img/caverna.png" 
                       x="0" y="0" width="1632" height="964" 
                       preserveAspectRatio="xMidYMid slice" />
                <defs>
                    <filter id="cuevaGlow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="5" />
                    </filter>
                </defs>

                <?php
                // Coordenadas exactas del camino de la cueva (imagen 1632x964)
                $cuevaPoints = [
                    1  => ['x' => 300,  'y' => 550],
                    2  => ['x' => 294,  'y' => 446],
                    3  => ['x' => 365,  'y' => 415],
                    4  => ['x' => 435,  'y' => 374],
                    5  => ['x' => 510,  'y' => 340],
                    6  => ['x' => 591,  'y' => 340],
                    7  => ['x' => 674,  'y' => 335],
                    8  => ['x' => 759,  'y' => 330],
                    9  => ['x' => 842,  'y' => 340],
                    10 => ['x' => 921,  'y' => 342],
                    11 => ['x' => 1003, 'y' => 370],
                    12 => ['x' => 1087, 'y' => 368],
                    13 => ['x' => 1175, 'y' => 363],
                    14 => ['x' => 1235, 'y' => 336],
                    15 => ['x' => 1297, 'y' => 323],
                    16 => ['x' => 1346, 'y' => 300],
                ];

                // Mapa de colores igual que camino.php
                $colorMap = [
                    'completado'  => ['fill' => '#10b981', 'stroke' => '#047857', 'outer' => '#34d399'],
                    'actual'      => ['fill' => '#f59e0b', 'stroke' => '#b45309', 'outer' => '#fbbf24'],
                    'inasistencia'=> ['fill' => '#ef4444', 'stroke' => '#991b1b', 'outer' => '#f87171'],
                    'mixto'       => ['fill' => '#a855f7', 'stroke' => '#7e22ce', 'outer' => '#d8b4fe'],
                    'bloqueado'   => ['fill' => '#94a3b8', 'stroke' => '#475569', 'outer' => '#cbd5e1'],
                ];

                // Por ahora todos bloqueados (sin datos de actividad en cueva)
                $estadoDefault = 'bloqueado';

                $totalPuntos = count($cuevaPoints);
                foreach ($cuevaPoints as $semana => $pt):
                    $c = $colorMap[$estadoDefault];
                    $cx = $pt['x'];
                    $cy = $pt['y'];
                    $esUltimoPunto = ($semana === $totalPuntos); // Punto destino final
                    if ($esUltimoPunto) {
                        $c = ['fill' => '#f59e0b', 'stroke' => '#b45309', 'outer' => '#fbbf24'];
                    }
                ?>
                <!-- Semana <?= $semana ?> -->
                <g style="transform-origin:<?= $cx ?>px <?= $cy ?>px; cursor:pointer; pointer-events:all;"
                   class="cueva-wp" data-semana="<?= $semana ?>">
                    <?php if ($esUltimoPunto): ?>
                    <!-- Anillo glow exterior -->
                    <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="28"
                        fill="<?= $c['outer'] ?>" opacity="0.5"
                        filter="url(#cuevaGlow)" />
                    <!-- Flecha animada idéntica a camino.php -->
                    <g transform="translate(<?= $cx ?>, <?= $cy ?>)">
                        <g class="peak-arrow-animated">
                            <path d="M 0,-36 L 20,6 L 8,2 L 8,26 C 8,29 -8,29 -8,26 L -8,2 L -20,6 Z"
                                fill="<?= $c['fill'] ?>"
                                stroke="<?= $c['stroke'] ?>"
                                stroke-width="3"
                                stroke-linejoin="round" />
                            <path d="M 0,-25 L 10,4 L 3,1 L 3,19 L -3,19 L -3,1 L -10,4 Z"
                                fill="#ffffff"
                                opacity="0.7" />
                        </g>
                    </g>
                    <?php else: ?>
                    <!-- Anillo glow exterior (idéntico a camino.php) -->
                    <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="22"
                        fill="<?= $c['outer'] ?>" opacity="0.55"
                        filter="url(#cuevaGlow)" />
                    <!-- Círculo principal -->
                    <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="16"
                        fill="<?= $c['fill'] ?>"
                        stroke="<?= $c['stroke'] ?>"
                        stroke-width="2.5" />
                    <?php endif; ?>
                </g>
                <?php endforeach; ?>

            </svg>
        </div>

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
    // ── Panorama slider & Responsive SVG sync ──
    const mainContent = document.getElementById('mainContent');
    const panoContainer = document.getElementById('panoContainer');
    const panoSlider = document.getElementById('panoSlider');
    const panoBar = document.getElementById('panoSliderBar');

    function syncPano() {
        if (!panoContainer || !mainContent) return;
        
        const w = mainContent.clientWidth;
        const h = mainContent.clientHeight;
        if (w === 0 || h === 0) return;
        
        const imgW = 1632;
        const imgH = 964;

        // Calcular object-fit: cover exacto
        const scale = Math.max(w / imgW, h / imgH);
        const scaledW = imgW * scale;
        const scaledH = imgH * scale;

        panoContainer.style.width = scaledW + 'px';
        panoContainer.style.height = scaledH + 'px';

        // Centrar verticalmente siempre (object-position: ... 50%)
        const top = (h - scaledH) / 2;
        panoContainer.style.top = top + 'px';

        // Paneado horizontal basado en el slider (0 a 100)
        let pct = 50;
        if (panoSlider) {
            pct = parseFloat(panoSlider.value);
            panoSlider.style.setProperty('--pano-fill', pct + '%');
        }
        
        // maxScroll es cuánto espacio sobra horizontalmente que podemos scrollear
        const maxScroll = scaledW - w;
        const left = -(maxScroll * (pct / 100));
        panoContainer.style.left = left + 'px';
    }

    if (panoSlider) {
        panoSlider.addEventListener('input', syncPano);
        
        // Touch panning
        let touchStartX = null;
        let startVal = null;
        document.addEventListener('touchstart', e => {
            if (e.target.closest('#panoSliderBar')) return;
            touchStartX = e.touches[0].clientX;
            startVal = parseFloat(panoSlider.value);
        }, { passive: true });
        
        document.addEventListener('touchmove', e => {
            if (touchStartX === null) return;
            const dx = e.touches[0].clientX - touchStartX;
            const winW = window.innerWidth;
            const deltaPct = -(dx / winW) * 100;
            const newVal = Math.min(100, Math.max(0, startVal + deltaPct));
            panoSlider.value = newVal;
            syncPano();
        }, { passive: true });
        
        document.addEventListener('touchend', () => { touchStartX = null; }, { passive: true });

        // Fade suave del slider
        let hideTimer;
        function showBar() {
            panoBar.style.opacity = '1';
            clearTimeout(hideTimer);
            hideTimer = setTimeout(() => { panoBar.style.opacity = '0.55'; }, 2500);
        }
        panoSlider.addEventListener('input', showBar);
        panoBar.addEventListener('mouseenter', () => clearTimeout(hideTimer));
        panoBar.addEventListener('mouseleave', showBar);
        showBar();
    }

    // Usar ResizeObserver para que el paneo se recalcule 60fps durante la transición del sidebar
    if (mainContent) {
        const resizeObserver = new ResizeObserver(() => {
            syncPano();
        });
        resizeObserver.observe(mainContent);
    }
    
    // Inicializar
    syncPano();

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>