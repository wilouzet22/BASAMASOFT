<?php
$data = $data ?? [];
$actividades = $data['actividades_camino'] ?? [];
$now = new DateTime();
$normalizarRutaImagenActividad = static function ($ruta) {
    return is_string($ruta) ? str_replace('/assets/img/actividades/', '/public/assets/img/actividades/', $ruta) : $ruta;
};

// ── Determinar fecha de Inicio de Año ──
$inicioAnoDate = null;
foreach ($actividades as $act) {
    if (strtolower(trim($act->nombre_actividad)) === 'inicio de año') {
        $inicioAnoDate = new DateTime($act->fecha_hora_inicio);
        $inicioAnoDate->setTime(0, 0, 0);
        break;
    }
}
if (!$inicioAnoDate) {
    $inicioAnoDate = new DateTime(date('Y') . '-01-01');
}

// ── Agrupar actividades por Número de Semana real (1 a 41) ──
$actsByWeek = []; 
foreach ($actividades as $act) {
    $fd  = new DateTime($act->fecha_hora_inicio);
    $fdNorm = clone $fd;
    $fdNorm->setTime(0, 0, 0);
    
    $interval = $inicioAnoDate->diff($fdNorm);
    $days = $interval->days;
    $invert = $interval->invert;
    
    if ($invert) {
        $weekNum = 1; // Si es antes del inicio de año, semana 1
    } else {
        $weekNum = (int)floor($days / 7) + 1;
    }
    if ($weekNum > 41) $weekNum = 41; 
    
    $actsByWeek[$weekNum][] = $act;
}

$allWeekKeys = range(1, 41); // Claves explícitas 1 a 41

$hasCamino = count($actividades) > 0; // El camino siempre es accesible si hay alguna actividad
$hasPico = false;
for ($i = 38; $i <= 41; $i++) {
    if (!empty($actsByWeek[$i])) $hasPico = true;
}

$etapasProg = [];
$actual_assigned = false;
for ($s = 1; $s <= 41; $s++) {
    $weekKey = $allWeekKeys[$s - 1] ?? null;
    $acts    = $weekKey ? ($actsByWeek[$weekKey] ?? []) : [];
    $count   = count($acts);

    // Determinar estado de la semana
    if ($count === 0) {
        // Sin actividades → bloqueado (gris)
        $estado = 'bloqueado';
    } else {
        $completadas   = 0;
        $inasistencias = 0;
        $futuras       = 0;
        foreach ($acts as $act) {
            $inicio_act = new DateTime($act->fecha_hora_inicio);
            $fin_act    = !empty($act->fecha_hora_fin)
                          ? new DateTime($act->fecha_hora_fin)
                          : (clone $inicio_act)->modify('+2 hours');

            if ($fin_act <= $now) {
                // Actividad ya terminó
                if ($act->asistencia_registrada > 0) $completadas++;
                else $inasistencias++;
            } else {
                // Futura o en progreso
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
        $fd      = new DateTime($act->fecha_hora_inicio);
        $fin_act = !empty($act->fecha_hora_fin)
                   ? new DateTime($act->fecha_hora_fin)
                   : (clone $fd)->modify('+2 hours');

        if ($fin_act <= $now) {
            $actEstado = $act->asistencia_registrada > 0 ? 'completado' : 'inasistencia';
        } elseif ($fd <= $now) {
            $actEstado = 'actual'; // en progreso
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
            'imagen_principal' => $normalizarRutaImagenActividad($act->imagen_principal ?? null),
        ];
    }

    $etapasProg[] = [
        'semana'     => $s,
        'nombre'     => 'Semana ' . $s . ($weekKey ? " ($weekKey)" : ''),
        'estado'     => $estado,
        'is_peak'    => ($s === 41),
        'multiple'   => ($count > 2),
        'dias'       => $dias,
        'total_acts' => $count,
    ];
}

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
];

// Para cueva, tomamos de la 23 a la 37 (índices 22 a 36)
$etapasCueva = array_slice($etapasProg, 22, 15);

$etapasFinalCueva = [];
// Return portal
$etapasFinalCueva[] = [
    'semana' => 98,
    'nombre' => 'Volver al Camino',
    'estado' => $hasCamino ? 'completado' : 'bloqueado',
    'is_peak' => false,
    'is_return' => true,
    'is_portal' => true,
    'target_url' => URLROOT . '/padres/camino',
    'target_name' => 'El Camino',
    'cx' => 200,
    'cy' => 570
];

foreach ($etapasCueva as $i => $etapa) {
    $ptCoord = $cuevaPoints[$i + 1] ?? ['x' => 0, 'y' => 0];
    $etapa['cx'] = $ptCoord['x'];
    $etapa['cy'] = $ptCoord['y'];
    $etapa['is_peak'] = false;
    $etapasFinalCueva[] = $etapa;
}

// Forward portal
$etapasFinalCueva[] = [
    'semana' => 99,
    'nombre' => 'Portal al Pico',
    'estado' => $hasPico ? 'completado' : 'bloqueado',
    'is_peak' => true,
    'is_portal' => true,
    'target_url' => URLROOT . '/padres/pico_montana',
    'target_name' => 'Pico de la Montaña',
    'cx' => 1400,
    'cy' => 300
];

$bodyClass = 'antialiased min-h-screen';
// ── Porcentaje de asistencia para el header móvil ──
$_hPast = 0; $_hAsist = 0;
foreach ($actividades as $_a) {
    $_inicio = new DateTime($_a->fecha_hora_inicio);
    $_fin    = !empty($_a->fecha_hora_fin)
               ? new DateTime($_a->fecha_hora_fin)
               : (clone $_inicio)->modify('+2 hours');
    if ($_fin <= $now) {
        if ($_a->asistencia_registrada > 0) { $_hPast++; $_hAsist++; }
        else $_hPast++;
    }
}
$pctHeader = $_hPast > 0 ? round(($_hAsist / $_hPast) * 100) : 0;

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
        /* En móvil, subir para no tapar el navbar inferior */
        @media (max-width: 1023px) {
            #panoSliderBar {
                bottom: calc(80px + 0.75rem);
            }
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

        @media (max-width: 1023px) {
            #global-theme-selector { display: none !important; }
            #thermometerMini       { display: none !important; }
        }

        /* Scrollbar invisible por defecto, aparece al desplazarse */
        #mainContent {
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
            transition: scrollbar-color 0.3s;
        }
        #mainContent:hover,
        #mainContent.scrolling {
            scrollbar-color: rgba(255,255,255,0.35) transparent;
        }
        #mainContent::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        #mainContent::-webkit-scrollbar-track {
            background: transparent;
        }
        #mainContent::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 9999px;
            transition: background 0.3s;
        }
        #mainContent:hover::-webkit-scrollbar-thumb,
        #mainContent.scrolling::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.35);
        }
    </style>
';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center px-4 py-3 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-2">
        <span class="font-bold text-primary text-lg">Camino de Cueva</span>
        <?php
        if ($pctHeader >= 75)     { $hColor = '#22c55e'; $hIcon = 'sentiment_very_satisfied'; }
        elseif ($pctHeader >= 50) { $hColor = '#eab308'; $hIcon = 'sentiment_neutral'; }
        elseif ($pctHeader >= 25) { $hColor = '#f97316'; $hIcon = 'sentiment_dissatisfied'; }
        else                      { $hColor = '#ef4444'; $hIcon = 'sentiment_very_dissatisfied'; }
        ?>
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-black border" style="background:<?= $hColor ?>22; color:<?= $hColor ?>; border-color:<?= $hColor ?>44;">
            <span class="material-symbols-outlined" style="font-size:14px;color:<?= $hColor ?>"><?= $hIcon ?></span>
            <?= $pctHeader ?>%
        </span>
    </div>
    <div class="flex items-center gap-1.5">
        <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
    </div>
</header>

<div class="flex">
    <!-- Sidebar reusable -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <!-- Main content — fondo cueva unificado en SVG -->
    <main id="mainContent" class="flex-1 min-h-screen relative transition-all duration-300 bg-[#0d141f] overflow-hidden">

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
                // Mapa de colores igual que camino.php
                $colorMap = [
                    'completado'  => ['fill' => '#10b981', 'stroke' => '#047857', 'outer' => '#34d399'],
                    'actual'      => ['fill' => '#f59e0b', 'stroke' => '#b45309', 'outer' => '#fbbf24'],
                    'futuro'      => ['fill' => '#3b82f6', 'stroke' => '#1d4ed8', 'outer' => '#93c5fd'],
                    'inasistencia' => ['fill' => '#ef4444', 'stroke' => '#991b1b', 'outer' => '#f87171'],
                    'mixto'       => ['fill' => '#f97316', 'stroke' => '#c2410c', 'outer' => '#fed7aa'],
                    'bloqueado'   => ['fill' => '#94a3b8', 'stroke' => '#475569', 'outer' => '#cbd5e1'],
                ];

                foreach ($etapasFinalCueva as $idx => $etapaRender):
                    $estadoActual = $etapaRender['estado'] ?? 'bloqueado';
                    $esPortal     = !empty($etapaRender['is_portal']);
                    $esRetorno    = !empty($etapaRender['is_return']);
                    $c = $colorMap[$estadoActual] ?? $colorMap['bloqueado'];
                    $cx = $etapaRender['cx'] ?? 0;
                    $cy = $etapaRender['cy'] ?? 0;
                ?>
                    <!-- Etapa idx=<?= $idx ?> -->
                    <g style="transform-origin:<?= $cx ?>px <?= $cy ?>px; cursor:pointer; pointer-events:all;"
                        class="cueva-wp" data-idx="<?= $idx ?>">

                        <?php if ($esPortal): ?>
                            <!-- Portal: flecha independiente -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="30"
                                fill="<?= $c['outer'] ?>" opacity="0.45"
                                filter="url(#cuevaGlow)" />
                            <g transform="translate(<?= $cx ?>, <?= $cy ?>) scale(<?= $esRetorno ? '-1,1' : '1,1' ?>)">
                                <g class="<?= $esRetorno ? 'return-arrow-animated' : 'peak-arrow-animated' ?>">
                                    <path d="M 0,-36 L 20,6 L 8,2 L 8,26 C 8,29 -8,29 -8,26 L -8,2 L -20,6 Z"
                                        fill="<?= $c['fill'] ?>"
                                        stroke="<?= $c['stroke'] ?>"
                                        stroke-width="3" stroke-linejoin="round" />
                                    <path d="M 0,-25 L 10,4 L 3,1 L 3,19 L -3,19 L -3,1 L -10,4 Z"
                                        fill="#ffffff" opacity="0.7" />
                                </g>
                            </g>
                        <?php elseif ($estadoActual === 'actual'): ?>
                            <!-- Heartbeat pulse -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="8" fill="none" stroke="#fbbf24" stroke-width="2" opacity="0.8">
                                <animate attributeName="r" values="8;13;8;18;8;8" keyTimes="0;0.15;0.3;0.45;0.7;1" dur="2.5s" repeatCount="indefinite" />
                            </circle>
                            <!-- Anillo glow exterior -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="22"
                                fill="<?= $c['outer'] ?>" opacity="0.55"
                                filter="url(#cuevaGlow)" />
                            <!-- Círculo principal -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="16"
                                fill="<?= $c['fill'] ?>" stroke="<?= $c['stroke'] ?>" stroke-width="2.5" />
                        <?php else: ?>
                            <!-- Anillo glow exterior para puntos normales -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="22"
                                fill="<?= $c['outer'] ?>" opacity="0.55"
                                filter="url(#cuevaGlow)" />
                            <!-- Círculo principal normal -->
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="16"
                                fill="<?= $c['fill'] ?>" stroke="<?= $c['stroke'] ?>" stroke-width="2.5" />
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
    const etapasCueva = <?= json_encode(array_values($etapasFinalCueva)) ?>;

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

        // Mostrar scrollbar brevemente al desplazarse
        let scrollHideTimer;
        if (mainContent) {
            mainContent.addEventListener('scroll', () => {
                mainContent.classList.add('scrolling');
                clearTimeout(scrollHideTimer);
                scrollHideTimer = setTimeout(() => {
                    mainContent.classList.remove('scrolling');
                }, 1000);
            }, { passive: true });
        }

        // Touch panning
        let touchStartX = null;
        let startVal = null;
        document.addEventListener('touchstart', e => {
            if (e.target.closest('#panoSliderBar') || e.target.closest('#cardFanOverlay')) return;
            touchStartX = e.touches[0].clientX;
            startVal = parseFloat(panoSlider.value);
        }, {
            passive: true
        });

        document.addEventListener('touchmove', e => {
            if (touchStartX === null) return;
            const dx = e.touches[0].clientX - touchStartX;
            const winW = window.innerWidth;
            const deltaPct = -(dx / winW) * 100;
            const newVal = Math.min(100, Math.max(0, startVal + deltaPct));
            panoSlider.value = newVal;
            syncPano();
        }, {
            passive: true
        });

        document.addEventListener('touchend', () => {
            touchStartX = null;
        }, {
            passive: true
        });

        // Fade suave del slider
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

    // Usar ResizeObserver para que el paneo se recalcule 60fps durante la transición del sidebar
    if (mainContent) {
        const resizeObserver = new ResizeObserver(() => {
            syncPano();
        });
        resizeObserver.observe(mainContent);
    }

    // ── Click handler centralizado ──
    function handlePointClick(e, pt) {
        e.stopPropagation();
        if (pt.is_portal) {
            if (pt.estado === 'bloqueado') {
                showZoneAlert();
            } else {
                window.location.href = pt.target_url;
            }
            return;
        }
        handleWeekClick(e, pt, e.currentTarget);
    }

    function showZoneAlert() {
        // Usar modal en lugar de alert nativo
        const existing = document.getElementById('zoneAlertModal');
        if (existing) { existing.remove(); }
        const modal = document.createElement('div');
        modal.id = 'zoneAlertModal';
        modal.style.cssText = 'position:fixed;inset:0;z-index:200;display:flex;align-items:center;justify-content:center;';
        modal.innerHTML = `
            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);" onclick="this.parentElement.remove()"></div>
            <div style="position:relative;background:#1e293b;border:1px solid #475569;border-radius:1.5rem;padding:2.5rem 3rem;max-width:360px;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.5);">
                <div style="font-size:3rem;margin-bottom:1rem;">🔒</div>
                <h3 style="color:#f1f5f9;font-size:1.2rem;font-weight:700;margin-bottom:0.75rem;">Zona no disponible</h3>
                <p style="color:#94a3b8;font-size:0.95rem;margin-bottom:1.5rem;">Esta zona aun no tiene actividades pendientes</p>
                <button onclick="document.getElementById('zoneAlertModal').remove()"
                    style="background:#3b82f6;color:#fff;border:none;padding:0.6rem 2rem;border-radius:9999px;cursor:pointer;font-weight:600;">
                    Entendido
                </button>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function handleWeekClick(e, pt, groupEl) {
        e.stopPropagation();
        if (!pt.dias || pt.dias.length === 0) {
            return; // No zoom/pan if there are no activities
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
            fanW = 260;
            fanH = 200;
            cardW = 105;
            cardH = 160;
            cardPad = '10px 8px';
            txStep = 30;
            fontSize = '11px';
            fontSm = '9px';
            fontXs = '8px';
            iconSize = '28px';
            badgeSz = '8px';
            badgePad = '3px 10px';
            cardRadius = '12px';
            cardBorder = '3px';
        } else if (vw < 768) {
            fanW = 340;
            fanH = 260;
            cardW = 140;
            cardH = 210;
            cardPad = '12px 10px';
            txStep = 42;
            fontSize = '13px';
            fontSm = '11px';
            fontXs = '9px';
            iconSize = '36px';
            badgeSz = '9px';
            badgePad = '3px 12px';
            cardRadius = '16px';
            cardBorder = '3px';
        } else if (vw < 1280) {
            fanW = 500;
            fanH = 380;
            cardW = 200;
            cardH = 305;
            cardPad = '16px 14px';
            txStep = 60;
            fontSize = '15px';
            fontSm = '12px';
            fontXs = '10px';
            iconSize = '50px';
            badgeSz = '11px';
            badgePad = '4px 16px';
            cardRadius = '20px';
            cardBorder = '4px';
        } else {
            fanW = 630;
            fanH = 480;
            cardW = 255;
            cardH = 390;
            cardPad = '20px 18px';
            txStep = 75;
            fontSize = '19px';
            fontSm = '14px';
            fontXs = '11px';
            iconSize = '63px';
            badgeSz = '14px';
            badgePad = '6px 20px';
            cardRadius = '24px';
            cardBorder = '5px';
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
            completado: {
                color: '#10b981',
                light: '#d1fae5',
                badge: '#065f46',
                icon: 'thumb_up',
                label: 'Asistió',
                suit: '♠'
            },
            inasistencia: {
                color: '#ef4444',
                light: '#fee2e2',
                badge: '#991b1b',
                icon: 'block',
                label: 'Faltó',
                suit: '♥'
            },
            futuro: {
                color: '#3b82f6',
                light: '#dbeafe',
                badge: '#1e40af',
                icon: 'event',
                label: 'Próxima',
                suit: '♦'
            },
            bloqueado: {
                color: '#94a3b8',
                light: '#f1f5f9',
                badge: '#475569',
                icon: 'lock',
                label: 'Bloqueado',
                suit: '♣'
            }
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
                psicologia: {
                    color: '#38bdf8',
                    icon: 'psychology'
                },
                citacion: {
                    color: '#64748b',
                    icon: 'gavel'
                },
                escolar: {
                    color: '#1e3a8a',
                    icon: 'menu_book'
                },
                extraescolar: {
                    color: '#f97316',
                    icon: 'schedule'
                }
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
                
                // Determinar si hay foto disponible
                const fotoUrl = dia.imagen_principal || null;
                const headerIconStyle = fotoUrl ? 'display:none;' : '';
                
                card.innerHTML = `
                    <div style="height:140px;background:#ffffff;border-bottom:3px dashed ${cardColor}40;border-radius:20px 20px 0 0;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;">
                        ${fotoUrl ? `<img src="${fotoUrl}" alt="Imagen principal de ${dia.nombre}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" onerror="this.remove()">` : ''}
                        <span style="font-size:90px;opacity:0.05;position:absolute;color:#000 !important;${headerIconStyle}">${s.suit}</span>
                        <span class="material-symbols-outlined" style="font-size:64px;color:${cardColor} !important;z-index:1;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.1));${headerIconStyle}">${cardIcon}</span>
                        <button id="cardBackBtn" style="position:absolute;top:12px;left:12px;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,0.9);border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;">
                            <span class="material-symbols-outlined" style="color:#475569 !important;font-size:22px;">arrow_back</span>
                        </button>
                        <span style="position:absolute;bottom:12px;left:14px;background:${s.color};color:#fff !important;font-size:11px;font-weight:900;padding:4px 12px;border-radius:99px;letter-spacing:0.06em;text-transform:uppercase;display:flex;align-items:center;gap:4px;box-shadow:0 2px 10px rgba(0,0,0,0.15);z-index:2;">
                            <span class="material-symbols-outlined" style="font-size:14px;color:#fff !important;">${s.icon}</span>${s.label}
                        </span>
                        <span style="position:absolute;top:12px;right:14px;font-size:28px;font-weight:900;color:${fotoUrl ? '#ffffff' : cardColor} !important;opacity:0.85;z-index:2;text-shadow:0 1px 4px rgba(0,0,0,0.4);">${s.suit}</span>
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
    document.querySelectorAll('.cueva-wp').forEach(el => {
        el.addEventListener('click', (e) => {
            const idx = parseInt(el.getAttribute('data-idx'));
            const pt = etapasCueva[idx];
            if (pt) {
                handlePointClick(e, pt);
            }
        });
    });

    // Inicializar
    syncPano();
</script>

<?php
$etapasTermometro = $etapasProg;
require APPROOT . '/views/padres/termometro.php';
?>
<style>
    @media (max-width: 1023px) {
        /* Override termometro.php's display: flex !important */
        #thermometerMini { display: none !important; }
    }
</style>
<?php require APPROOT . '/views/inc/footer.php'; ?>
