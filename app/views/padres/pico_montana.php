<?php
$data = $data ?? [];
$actividades = $data['actividades_camino'] ?? [];
$now = new DateTime();

// ── Agrupar actividades por clave año-semana ('YYYY-WW') para evitar colisiones entre años ──
$actsByWeek = []; // ['YYYY-WW' => [act, ...]]
foreach ($actividades as $act) {
    $fd  = new DateTime($act->fecha_hora_inicio);
    $key = $fd->format('o-W'); // ISO year + ISO week, e.g. "2026-27"
    $actsByWeek[$key][] = $act;
}

// Ordenar las claves cronológicamente
$allWeekKeys = array_keys($actsByWeek);
sort($allWeekKeys);

$etapasProg = [];
$actual_assigned = false;
for ($s = 1; $s <= 39; $s++) {
    $weekKey = $allWeekKeys[$s - 1] ?? null;
    $acts    = $weekKey ? ($actsByWeek[$weekKey] ?? []) : [];
    $count   = count($acts);

    // Determinar estado de la semana
    if ($count === 0) {
        $estado = 'bloqueado';
    } else {
        $completadas   = 0;
        $inasistencias = 0;
        $futuras       = 0;
        foreach ($acts as $act) {
            $fd = new DateTime($act->fecha_hora_inicio);
            if ($fd <= $now) {
                if ($act->asistencia_registrada > 0) $completadas++;
                else $inasistencias++;
            } else {
                $futuras++;
            }
        }
        if ($futuras > 0 && $completadas === 0 && $inasistencias === 0) {
            if (!$actual_assigned) {
                $estado = 'actual';
                $actual_assigned = true;
            } else {
                $estado = 'futuro';
            }
        } elseif ($completadas > 0 && $inasistencias === 0) {
            $estado = 'completado';
        } elseif ($inasistencias > 0 && $completadas === 0) {
            $estado = 'inasistencia';
        } else {
            $estado = 'mixto';
        }
    }

    $dias = [];
    $dayNames = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'];
    foreach (array_slice($acts, 0, 5) as $act) {
        $fd = new DateTime($act->fecha_hora_inicio);
        if ($fd <= $now) {
            $actEstado = $act->asistencia_registrada > 0 ? 'completado' : 'inasistencia';
        } else {
            $actEstado = 'futuro';
        }
        $dias[] = [
            'id'          => $act->id_actividad,
            'nombre'      => $act->nombre_actividad,
            'fecha'       => $fd->format('d M Y'),
            'hora'        => $fd->format('h:i A'),
            'dia_semana'  => $dayNames[$fd->format('N') - 1] ?? $fd->format('D'),
            'estado'      => $actEstado,
            'descripcion' => $act->descripcion ?? '',
            'tipo'        => $act->nombre_tipo ?? '',
            'sede'        => $act->nombre_sede ?? '',
        ];
    }

    $etapasProg[] = [
        'semana'     => $s,
        'nombre'     => 'Semana ' . $s . ($weekKey ? " ($weekKey)" : ''),
        'estado'     => $estado,
        'is_peak'    => ($s === 39),
        'multiple'   => ($count > 2),
        'dias'       => $dias,
        'total_acts' => $count,
    ];
}

// Coordenadas exactas del camino del pico de la montaña
$picoPoints = [
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

// Tomamos de la 24 a la 39 (índices 23 a 38)
$etapasPico = array_slice($etapasProg, 23, 16);
foreach ($etapasPico as $i => &$etapa) {
    $ptCoord = $picoPoints[$i + 1] ?? ['x' => 0, 'y' => 0];
    $etapa['cx'] = $ptCoord['x'];
    $etapa['cy'] = $ptCoord['y'];
}
unset($etapa);

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
        img#picoImg {
            height: 100% !important;
            max-width: none !important;
            width: 100% !important;
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1), object-position 0.15s ease !important;
            transform-origin: center center;
        }

        /* ── SVG overlay: sigue el zoom de picoImg para quedar "incrustado" ── */
        #picoWaypointsContainer {
            transition: transform 0.4s cubic-bezier(0.4,0,0.2,1) !important;
            transform-origin: center center;
        }
        body.sidebar-collapsed #picoWaypointsContainer {
            transform: scale(1.18) !important;
        }
        body.sidebar-hidden #picoWaypointsContainer {
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

        /* Animación para la flecha de retorno (primer punto) apuntando al occidente con inclinación de 25º (-115deg) */
        @keyframes return-arrow-bounce {
            0%, 100% {
                transform: rotate(-115deg) translate(0px, 0px);
            }
            50% {
                transform: rotate(-115deg) translate(0px, -15px);
            }
        }
        .return-arrow-animated {
            animation: return-arrow-bounce 1.4s ease-in-out infinite;
            transform-origin: 0px 0px;
        }
    </style>
';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <span class="font-bold text-primary text-lg">Pico de la Montaña</span>
    </div>
    <button id="menuToggleBtn" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95">
        <span class="material-symbols-outlined">menu</span>
    </button>
</header>

<div class="flex">
    <!-- Sidebar reusable -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <!-- Main content — fondo pico unificado en SVG -->
    <main id="mainContent" class="flex-1 lg:ml-72 min-h-screen relative transition-all duration-300 bg-[#0d141f] overflow-hidden">

        <!-- Contenedor único SVG que incluye imagen de fondo y waypoints. Panneado vía JS -->
        <div id="panoContainer" class="absolute pointer-events-none" style="z-index:10; top:0; left:0;">
            <svg id="picoWaypointsSVG"
                 viewBox="0 0 1632 964"
                 preserveAspectRatio="none"
                 style="width:100%; height:100%; pointer-events:none;">
                
                <!-- Imagen de fondo incrustada -->
                <image href="<?= URLROOT ?>/public/assets/img/pico_montaña_final.jpg" 
                       x="0" y="0" width="1632" height="964" 
                       preserveAspectRatio="xMidYMid slice" />
                <defs>
                    <filter id="picoGlow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="5" />
                    </filter>
                </defs>

                 <?php
                 // Mapa de colores igual que camino.php
                 $colorMap = [
                     'completado'  => ['fill' => '#10b981', 'stroke' => '#047857', 'outer' => '#34d399'],
                     'actual'      => ['fill' => '#f59e0b', 'stroke' => '#b45309', 'outer' => '#fbbf24'],
                     'futuro'      => ['fill' => '#3b82f6', 'stroke' => '#1d4ed8', 'outer' => '#93c5fd'],
                     'inasistencia'=> ['fill' => '#ef4444', 'stroke' => '#991b1b', 'outer' => '#f87171'],
                     'mixto'       => ['fill' => '#f97316', 'stroke' => '#c2410c', 'outer' => '#fed7aa'],
                     'bloqueado'   => ['fill' => '#94a3b8', 'stroke' => '#475569', 'outer' => '#cbd5e1'],
                 ];

                 $totalPuntos = count($picoPoints);
                 foreach ($picoPoints as $semana => $pt):
                     $etapaInfo = $etapasPico[$semana - 1] ?? null;
                     $estadoActual = $etapaInfo ? $etapaInfo['estado'] : 'bloqueado';

                     // Use attendance-based color only (no blue override for multiple)
                     $c = $colorMap[$estadoActual] ?? $colorMap['bloqueado'];

                     $cx = $pt['x'];
                     $cy = $pt['y'];
                     $esUltimoPunto = ($semana === $totalPuntos); // Punto destino final
                     $esPrimerPunto = ($semana === 1); // Punto de inicio (retorno)
                     if ($esUltimoPunto) {
                         $c = ['fill' => '#fcd34d', 'stroke' => '#f59e0b', 'outer' => '#fde68a'];
                     }
                 ?>
                 <!-- Semana <?= $semana ?> -->
                 <g style="transform-origin:<?= $cx ?>px <?= $cy ?>px; cursor:pointer; pointer-events:all;"
                    class="pico-wp" data-semana="<?= $semana ?>">
                     <?php if ($estadoActual === 'actual' && !$esUltimoPunto): ?>
                     <!-- Heartbeat pulse -->
                     <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="8" fill="none" stroke="#fbbf24" stroke-width="2" opacity="0.8">
                         <animate attributeName="r" values="8;13;8;18;8;8" keyTimes="0;0.15;0.3;0.45;0.7;1" dur="2.5s" repeatCount="indefinite" />
                     </circle>
                     <?php endif; ?>

                     <?php if ($esUltimoPunto): ?>
                     <!-- Anillo glow exterior -->
                     <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="28"
                         fill="<?= $c['outer'] ?>" opacity="0.5"
                         filter="url(#picoGlow)" />
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
                     <?php elseif ($esPrimerPunto): ?>
                     <!-- Flecha de retorno animada -->
                     <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="28"
                         fill="<?= $c['outer'] ?>" opacity="0.5"
                         filter="url(#picoGlow)" />
                     <g transform="translate(<?= $cx ?>, <?= $cy ?>)">
                         <g class="return-arrow-animated">
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
                         filter="url(#picoGlow)" />
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

    let zoomedPoint = null;
    let zoomedGroupEl = null;
    const etapasPico = <?= json_encode($etapasPico) ?>;

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

    function centerAtPoint(cx) {
        if (!panoContainer || !mainContent) return;
        const w = mainContent.clientWidth;
        const imgW = 1632;
        const imgH = 964;
        const h = mainContent.clientHeight;
        const scale = Math.max(w / imgW, h / imgH);
        const scaledW = imgW * scale;
        const maxScroll = scaledW - w;
        if (maxScroll <= 0) return;

        const rx = cx / imgW;
        const px = rx * scaledW;
        let left = (w / 2) - px;
        left = Math.max(-maxScroll, Math.min(0, left));
        const pct = (-left / maxScroll) * 100;
        if (panoSlider) {
            panoSlider.value = pct;
            syncPano();
        }
    }

    if (panoSlider) {
        panoSlider.addEventListener('input', syncPano);
        
        // Touch panning
        let touchStartX = null;
        let startVal = null;
        document.addEventListener('touchstart', e => {
            if (e.target.closest('#panoSliderBar') || e.target.closest('#cardFanOverlay')) return;
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

    // ── Week click interaction ──
    function handleWeekClick(e, pt, groupEl) {
        e.stopPropagation();
        if (!pt.dias || pt.dias.length === 0) {
            return; // No zoom/pan if there are no actividades
        }
        if (zoomedPoint === pt.semana) {
            resetCurrentZoom();
            return;
        }
        resetCurrentZoom();
        zoomedPoint = pt.semana;
        zoomedGroupEl = groupEl;

        // Centrar punto
        centerAtPoint(pt.cx);

        if (zoomedGroupEl) {
            zoomedGroupEl.style.transition = 'transform 0.3s cubic-bezier(0.34,1.56,0.64,1)';
            zoomedGroupEl.style.transform = 'scale(1.5)';
            setTimeout(() => {
                if (zoomedGroupEl) zoomedGroupEl.style.transform = 'scale(1.25)';
            }, 300);
        }

        if (pt.dias && pt.dias.length > 0) {
            showDayPicker(pt);
        } else {
            showWeekInfo(pt);
        }
    }

    function resetCurrentZoom() {
        if (zoomedGroupEl) {
            zoomedGroupEl.style.transition = 'transform 0.3s ease';
            zoomedGroupEl.style.transform = 'scale(1)';
        }
        zoomedPoint = null;
        zoomedGroupEl = null;
    }

    function showWeekInfo(pt) {
        alert(pt.nombre + ' — No hay actividades programadas esta semana.');
        resetCurrentZoom();
    }

    function closeDayPicker() {
        const overlay = document.getElementById('cardFanOverlay');
        if (!overlay) return;
        const fan = document.getElementById('cardFan');
        if (fan) {
            fan.style.opacity = '0';
            fan.style.transform = 'translateY(60px) scale(0.85)';
        }
        setTimeout(() => {
            if (overlay) overlay.remove();
            resetCurrentZoom();
        }, 320);
    }

    function showDayPicker(pt) {
        closeDayPicker();
        const n = pt.dias.length;
        const vw = window.innerWidth;
        
        let fanW, fanH, cardW, cardH, cardPad, txStep, fontSize, fontSm, fontXs, iconSize, badgeSz, badgePad, cardRadius, cardBorder;
        if (vw < 480) {
            fanW = 260; fanH = 200; cardW = 105; cardH = 160; cardPad = '10px 8px'; txStep = 30;
            fontSize = '11px'; fontSm = '9px'; fontXs = '8px'; iconSize = '28px'; badgeSz = '8px'; badgePad = '3px 10px';
            cardRadius = '12px'; cardBorder = '3px';
        } else if (vw < 768) {
            fanW = 340; fanH = 260; cardW = 140; cardH = 210; cardPad = '12px 10px'; txStep = 42;
            fontSize = '13px'; fontSm = '11px'; fontXs = '9px'; iconSize = '36px'; badgeSz = '9px'; badgePad = '3px 12px';
            cardRadius = '16px'; cardBorder = '3px';
        } else if (vw < 1280) {
            fanW = 500; fanH = 380; cardW = 200; cardH = 305; cardPad = '16px 14px'; txStep = 60;
            fontSize = '15px'; fontSm = '12px'; fontXs = '10px'; iconSize = '50px'; badgeSz = '11px'; badgePad = '4px 16px';
            cardRadius = '20px'; cardBorder = '4px';
        } else {
            fanW = 630; fanH = 480; cardW = 255; cardH = 390; cardPad = '20px 18px'; txStep = 75;
            fontSize = '19px'; fontSm = '14px'; fontXs = '11px'; iconSize = '63px'; badgeSz = '14px'; badgePad = '6px 20px';
            cardRadius = '24px'; cardBorder = '5px';
        }

        const overlay = document.createElement('div');
        overlay.id = 'cardFanOverlay';
        overlay.style.cssText = 'position:fixed;inset:0;z-index:75;display:flex;align-items:center;justify-content:center;pointer-events:none;';

        const backdrop = document.createElement('div');
        backdrop.style.cssText = 'position:absolute;inset:0;pointer-events:auto;background:rgba(0,0,0,0.35);backdrop-filter:blur(3px);';
        backdrop.addEventListener('click', closeDayPicker);
        overlay.appendChild(backdrop);

        const fan = document.createElement('div');
        fan.id = 'cardFan';
        fan.style.cssText = `position:relative;width:${fanW}px;height:${fanH}px;pointer-events:none;`;
        overlay.appendChild(fan);

        const stateMap = {
            completado: { color: '#10b981', light: '#d1fae5', badge: '#065f46', icon: 'thumb_up', label: 'Asistió', suit: '♠' },
            inasistencia: { color: '#ef4444', light: '#fee2e2', badge: '#991b1b', icon: 'block', label: 'Faltó', suit: '♥' },
            futuro: { color: '#3b82f6', light: '#dbeafe', badge: '#1e40af', icon: 'event', label: 'Próxima', suit: '♦' },
            bloqueado: { color: '#94a3b8', light: '#f1f5f9', badge: '#475569', icon: 'lock', label: 'Bloqueado', suit: '♣' }
        };

        const totalSpread = Math.min(n * 16, 72);
        const startAngle = -totalSpread / 2;

        pt.dias.forEach((dia, i) => {
            const s = stateMap[dia.estado] || stateMap.bloqueado;
            const tipoStr = (dia.tipo || '').toLowerCase();
            let resolvedTipo = 'otro';
            if (tipoStr.includes('psicolog') || tipoStr.includes('psicología')) resolvedTipo = 'psicologia';
            else if (tipoStr.includes('citaci') || tipoStr.includes('acudiente') || tipoStr.includes('apoderado')) resolvedTipo = 'citacion';
            else if (tipoStr.includes('extra')) resolvedTipo = 'extraescolar';
            else if (tipoStr.includes('clase') || tipoStr.includes('evaluaci') || tipoStr.includes('taller') || tipoStr.includes('escolar')) resolvedTipo = 'escolar';

            const typeInfo = {
                psicologia: { color: '#38bdf8', icon: 'psychology' },
                citacion: { color: '#64748b', icon: 'gavel' },
                escolar: { color: '#1e3a8a', icon: 'menu_book' },
                extraescolar: { color: '#f97316', icon: 'schedule' }
            };

            let cardColor = s.color;
            let cardIcon = s.icon;
            const tInfo = typeInfo[resolvedTipo];
            if (tInfo) {
                cardIcon = tInfo.icon;
                if (dia.estado !== 'inasistencia') cardColor = tInfo.color;
            }

            const ang = n > 1 ? startAngle + (totalSpread / (n - 1)) * i : 0;
            const tx = (i - (n - 1) / 2) * txStep;

            const card = document.createElement('div');
            card.className = 'fan-card';
            card.style.cssText = `
                position:absolute; top:50%; left:50%; width:${cardW}px; height:${cardH}px; margin-top:${Math.round(cardH * 0.04)}px;
                transform-origin:50% 100%; transform:translate(calc(-50% + ${tx}px), -50%) rotate(${ang}deg);
                transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.2s, width 0.4s, height 0.4s, padding 0.4s;
                border-radius:${cardRadius}; background:#ffffff; border:${cardBorder} solid ${cardColor};
                box-shadow:0 10px 36px rgba(0,0,0,0.15); cursor:pointer; pointer-events:auto;
                display:flex; flex-direction:column; padding:${cardPad}; user-select:none; z-index:${i};
            `;
            card.dataset.index = i;

            card.innerHTML = `
                <div style="font-size:${iconSize};font-weight:900;color:${cardColor} !important;line-height:1;text-shadow:0 1px 2px rgba(0,0,0,0.05);">${s.suit}</div>
                <div style="font-size:${fontSize};font-weight:900;color:#0f172a !important;margin-top:8px;line-height:1.35;text-align:center;">${dia.nombre}</div>
                <div style="font-size:${fontSm};font-weight:800;color:#64748b !important;margin-top:4px;text-align:center;">${dia.dia_semana} ${dia.fecha}</div>
                <div style="flex:1;"></div>
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <span class="material-symbols-outlined" style="font-size:${iconSize};color:${cardColor} !important;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.1));">${cardIcon}</span>
                    <span style="font-size:${badgeSz};font-weight:800;padding:${badgePad};border-radius:99px;background:${cardColor};color:#fff !important;letter-spacing:0.05em;box-shadow:0 2px 6px rgba(0,0,0,0.15);">${s.label}</span>
                </div>
                <div style="font-size:${iconSize};font-weight:900;color:${cardColor} !important;text-align:right;line-height:1;transform:rotate(180deg);margin-top:8px;">${s.suit}</div>
            `;

            card.addEventListener('mouseenter', () => {
                if (card.classList.contains('selected')) return;
                card.style.transform = `translate(calc(-50% + ${tx}px), -62%) rotate(${ang}deg) scale(1.06)`;
                card.style.zIndex = 99;
                card.style.boxShadow = `0 20px 48px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.6)`;
            });
            card.addEventListener('mouseleave', () => {
                if (!card.classList.contains('selected')) {
                    card.style.transform = `translate(calc(-50% + ${tx}px), -50%) rotate(${ang}deg)`;
                    card.style.zIndex = i;
                    card.style.boxShadow = `0 10px 36px rgba(0,0,0,0.28), inset 0 1px 0 rgba(255,255,255,0.6)`;
                }
            });

            card.addEventListener('click', (e) => {
                e.stopPropagation();
                if (card.classList.contains('expanded')) return;

                let stackIdx = 0;
                document.querySelectorAll('.fan-card').forEach((c, ci) => {
                    if (c === card) return;
                    const t = (stackIdx - (n - 2) / 2) * 12;
                    c.style.transition = 'transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.4s, box-shadow 0.2s';
                    c.style.transform = `translate(calc(-50% + ${t}px), -50%) rotate(0deg) scale(0.82)`;
                    c.style.opacity = '0.4';
                    c.style.zIndex = ci;
                    c.style.pointerEvents = 'none';
                    c.classList.remove('selected');
                    stackIdx++;
                });

                card.classList.add('selected', 'expanded');
                card.innerHTML = `
                    <div style="height:140px;background:#ffffff;border-bottom:3px dashed ${cardColor}40;border-radius:20px 20px 0 0;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                        <span style="font-size:90px;opacity:0.05;position:absolute;color:#000 !important;">${s.suit}</span>
                        <span class="material-symbols-outlined" style="font-size:64px;color:${cardColor} !important;z-index:1;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.1));">${cardIcon}</span>
                        <button id="cardBackBtn" style="position:absolute;top:12px;left:12px;width:36px;height:36px;border-radius:50%;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="color:#475569 !important;font-size:22px;">arrow_back</span>
                        </button>
                        <span style="position:absolute;bottom:12px;left:14px;background:${s.color};color:#fff !important;font-size:11px;font-weight:900;padding:4px 12px;border-radius:99px;letter-spacing:0.06em;text-transform:uppercase;display:flex;align-items:center;gap:4px;box-shadow:0 2px 10px rgba(0,0,0,0.15);">
                            <span class="material-symbols-outlined" style="font-size:14px;color:#fff !important;">${s.icon}</span>${s.label}
                        </span>
                        <span style="position:absolute;top:12px;right:14px;font-size:28px;font-weight:900;color:${cardColor} !important;opacity:0.7;">${s.suit}</span>
                    </div>
                    <div style="padding:22px 20px 26px;background:#ffffff;border-radius:0 0 20px 20px;overflow-y:auto;flex:1;">
                        <h2 style="font-size:20px;font-weight:900;color:#0f172a !important;margin:0 0 8px;line-height:1.2;">${dia.nombre}</h2>
                        <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:18px;">
                            <div style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:800;color:#475569 !important;">
                                <span class="material-symbols-outlined" style="font-size:18px;color:${cardColor} !important;">event</span>
                                <span>${dia.dia_semana} ${dia.fecha}</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:800;color:#475569 !important;">
                                <span class="material-symbols-outlined" style="font-size:18px;color:${cardColor} !important;">schedule</span>
                                <span>${dia.hora || '—'}</span>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
                            <div style="background:#f8fafc;border-radius:14px;padding:12px 14px;border:1px solid #e2e8f0;">
                                <div style="font-size:10px;font-weight:900;color:${cardColor} !important;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Sede</div>
                                <div style="font-size:14px;font-weight:900;color:#1e293b !important;">${dia.sede || '—'}</div>
                            </div>
                            <div style="background:#f8fafc;border-radius:14px;padding:12px 14px;border:1px solid #e2e8f0;">
                                <div style="font-size:10px;font-weight:900;color:${cardColor} !important;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:4px;">Tipo</div>
                                <div style="font-size:14px;font-weight:900;color:#1e293b !important;">${dia.tipo || '—'}</div>
                            </div>
                        </div>
                        <div style="background:#f1f5f9;border-radius:14px;padding:14px;border-left:4px solid ${cardColor};">
                            <div style="font-size:10px;font-weight:900;color:#64748b !important;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Descripción</div>
                            <p style="font-size:13px;color:#334155 !important;font-weight:600;line-height:1.6;margin:0;">${dia.descripcion || 'Sin descripción disponible.'}</p>
                        </div>
                    </div>
                `;

                card.style.transition = 'all 0.42s cubic-bezier(0.34,1.15,0.64,1)';
                card.style.transform = `translate(-50%, -50%) rotate(0deg) scale(1)`;
                card.style.width = 'min(340px, 90vw)';
                card.style.height = 'auto';
                card.style.maxHeight = '82vh';
                card.style.borderRadius = '24px';
                card.style.padding = '0';
                card.style.overflowY = 'auto';
                card.style.overflowX = 'hidden';
                card.style.zIndex = 200;
                card.style.boxShadow = `0 32px 80px rgba(0,0,0,0.5), 0 0 0 2.5px ${s.color}`;

                document.getElementById('cardBackBtn')?.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    card.classList.remove('expanded');
                    closeDayPicker();
                });
            });

            fan.appendChild(card);
        });

        document.body.appendChild(overlay);

        fan.style.opacity = '0';
        fan.style.transform = 'translateY(80px) scale(0.8)';
        fan.style.transition = 'opacity 0.38s ease, transform 0.45s cubic-bezier(0.34,1.2,0.64,1)';
        requestAnimationFrame(() => {
            fan.style.opacity = '1';
            fan.style.transform = 'translateY(0) scale(1)';
        });
    }

    // Inicializar listeners de los puntos
    document.querySelectorAll('.pico-wp').forEach(el => {
        el.addEventListener('click', (e) => {
            const sem = parseInt(el.getAttribute('data-semana'));
            if (sem === 1) {
                window.location.href = '<?= URLROOT ?>/padres/camino';
                return;
            }
            const pt = etapasPico[sem - 1];
            if (pt) {
                handleWeekClick(e, pt, el);
            }
        });
    });

    // Inicializar
    syncPano();
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
