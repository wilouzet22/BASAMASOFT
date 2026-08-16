<?php
$data        = $data ?? [];
$actividades = $data['actividades_camino'] ?? [];
$now         = new DateTime();

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

$hasCueva = false;
for ($i = 23; $i <= 37; $i++) {
    if (!empty($actsByWeek[$i])) {
        $hasCueva = true;
        break;
    }
}

// ── Los primeros 22 slots del programa = Montaña ──
$totalSemanas = 22;
$etapas = [];
$actual_assigned = false;

for ($s = 1; $s <= $totalSemanas; $s++) {
    $weekKey = $allWeekKeys[$s - 1] ?? null; // null = sin actividades ese slot
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

    // Preparar sub-actividades (máx 5 días) para mostrar en popup
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

    $etapas[] = [
        'semana'     => $s,
        'nombre'     => 'Semana ' . $s . ($weekKey ? " ($weekKey)" : ''),
        'estado'     => $estado,
        'is_peak'    => false,
        'multiple'   => ($count > 2),
        'dias'       => $dias,
        'total_acts' => $count,
    ];
}

$etapas[] = [
    'semana'     => 99,
    'nombre'     => 'Portal a La Cueva',
    'estado'     => $hasCueva ? 'completado' : 'bloqueado',
    'is_peak'    => true,
    'multiple'   => false,
    'dias'       => [],
    'is_portal'  => true,
    'target_url' => URLROOT . '/padres/cueva',
];

$totalEtapas  = count($etapas); // always 23
$totalSections = 10;
$perPage       = 4;
$totalPages    = max($totalSections, (int)ceil($totalEtapas / $perPage));

// Ancho dinámico de la montaña basado en cant. de actividades (min 900, max 2000)
// Escala desde 1 hasta 50 actividades. Siempre gruesa.
$maxActividades = 50;
$escalaAncho = max(0.3, min(1.0, $totalEtapas / $maxActividades));
$viewBoxW = 900 + (int)($escalaAncho * 1100); // 900 a 2000
$viewBoxH = 1400;
$centroX = (int)($viewBoxW / 2);
$baseHalfW = (int)($viewBoxW * 0.75); // La base siempre muy ancha

// Constante global de secciones (altura)
$TOTAL_SECTIONS = 10; // Mantenemos 10 solo por referencia
$totalHeight = 1400; // Altura lógica más corta para evitar estiramiento y zoom masivo

/**
 * Genera TODOS los waypoints distribuidos en la altura total de la montaña.
 */
function generarTodosLosWaypoints(array $etapas, int $totalEtapas, int $vbW, int $totalH): array
{
    // ========================================================================
    // COORDENADAS X,Y DE LAS 15 SEMANAS EN LA IMAGEN (Resolución 4600 x 3800)
    // ========================================================================
    // Para editar la ubicación de un punto, simplemente cambia el valor de 'x' (horizontal) y 'y' (vertical).
    // Nota: El punto 1 es la base (inicio del camino) y el punto 15 es la cueva (destino final).
    // ── OFFSET para centrar los puntos en el camino visible ──
    // Ajusta $offsetCorrX para mover todos los puntos horizontalmente (+= derecha, -= izquierda)
    // Ajusta $offsetCorrY para mover todos los puntos verticalmente   (+= abajo,  -= arriba)
    $offsetCorrX = -40; // desplazamiento horizontal global
    $offsetCorrY = 0; // desplazamiento vertical global

    $refPoints = [
        1  => ['x' => 500.00 + $offsetCorrX, 'y' => 830.00 + $offsetCorrY], // Inicio (original)
        2  => ['x' => 455.50 + $offsetCorrX, 'y' => 810.00 + $offsetCorrY], // intermedio 1-2
        3  => ['x' => 411.00 + $offsetCorrX, 'y' => 790.00 + $offsetCorrY], // original 2
        4  => ['x' => 305.50 + $offsetCorrX, 'y' => 755.00 + $offsetCorrY], // intermedio 2-3
        5  => ['x' => 200.00 + $offsetCorrX, 'y' => 720.00 + $offsetCorrY], // original 3
        6  => ['x' => 222.50 + $offsetCorrX, 'y' => 698.00 + $offsetCorrY], // intermedio 3-4
        7  => ['x' => 245.00 + $offsetCorrX, 'y' => 676.00 + $offsetCorrY], // original 4
        8  => ['x' => 237.50 + $offsetCorrX, 'y' => 676.00 + $offsetCorrY], // intermedio 4-5
        9  => ['x' => 230.00 + $offsetCorrX, 'y' => 676.00 + $offsetCorrY], // original 5
        10 => ['x' => 275.00 + $offsetCorrX, 'y' => 658.00 + $offsetCorrY], // intermedio 5-6
        11 => ['x' => 320.00 + $offsetCorrX, 'y' => 640.00 + $offsetCorrY], // original 6
        12 => ['x' => 410.00 + $offsetCorrX, 'y' => 623.00 + $offsetCorrY], // intermedio 6-7
        13 => ['x' => 500.00 + $offsetCorrX, 'y' => 606.00 + $offsetCorrY], // original 7
        14 => ['x' => 525.00 + $offsetCorrX, 'y' => 583.00 + $offsetCorrY], // intermedio 7-8
        15 => ['x' => 550.00 + $offsetCorrX, 'y' => 560.00 + $offsetCorrY], // original 8
        16 => ['x' => 428.50 + $offsetCorrX, 'y' => 530.00 + $offsetCorrY], // intermedio 8-9
        17 => ['x' => 307.00 + $offsetCorrX, 'y' => 500.00 + $offsetCorrY], // original 9
        18 => ['x' => 378.50 + $offsetCorrX, 'y' => 471.00 + $offsetCorrY], // intermedio 9-10
        19 => ['x' => 450.00 + $offsetCorrX, 'y' => 442.00 + $offsetCorrY], // original 10
        20 => ['x' => 440.00 + $offsetCorrX, 'y' => 453.50 + $offsetCorrY], // intermedio 10-11
        22 => ['x' => 500.50 + $offsetCorrX, 'y' => 400.50 + $offsetCorrY], // intermedio 11-12
        23 => ['x' => 469.00 + $offsetCorrX, 'y' => 380.00 + $offsetCorrY], // original 12 — entrada a la cueva
    ];

    $imgRefW = 723;
    $imgRefH = 1024;

    // Convert reference points to relative [0..1] coordinates
    $relPoints = array_map(function ($pt) use ($imgRefW, $imgRefH) {
        return ['rx' => $pt['x'] / $imgRefW, 'ry' => $pt['y'] / $imgRefH];
    }, array_values($refPoints));

    // Calc total length of relative polyline
    $segments = [];
    $totalLen = 0;
    for ($i = 0; $i < count($relPoints) - 1; $i++) {
        $p1 = $relPoints[$i];
        $p2 = $relPoints[$i + 1];
        $dx = $p2['rx'] - $p1['rx'];
        $dy = $p2['ry'] - $p1['ry'];
        $dist = sqrt($dx * $dx + $dy * $dy);
        $segments[] = ['p1' => $p1, 'p2' => $p2, 'dist' => $dist];
        $totalLen += $dist;
    }

    // SVG Target Box for `<image>` tag (vista alejada 50%)
    $targetW = $vbW * 1.0;
    $targetH = $totalH * 1.0;
    $targetX = 0;
    $targetY = 0;

    // Simulate `preserveAspectRatio="xMidYMid slice"`
    // source image aro de genaro.jpeg aspect ratio is 4600:3800 (~1.21:1)
    $imgRatio = $imgRefW / $imgRefH;
    $targetRatio = $targetW / $targetH;

    if ($targetRatio > $imgRatio) {
        // Scaled by width
        $scale = $targetW / $imgRefW;
        $scaledW = $targetW;
        $scaledH = $imgRefH * $scale;
        $offsetX = 0;
        $offsetY = ($targetH - $scaledH) / 2;
    } else {
        // Scaled by height
        $scale = $targetH / $imgRefH;
        $scaledW = $imgRefW * $scale;
        $scaledH = $targetH;
        $offsetX = ($targetW - $scaledW) / 2;
        $offsetY = 0;
    }

    $puntos = [];
    for ($i = 0; $i < $totalEtapas; $i++) {
        $etapa = $etapas[$i];
        $t = $totalEtapas > 1 ? $i / ($totalEtapas - 1) : 0.5;

        $targetDist = $t * $totalLen;
        $currDist = 0;
        $rx = 0.5;
        $ry = 0.5; // fallback

        // Find segment
        foreach ($segments as $idx => $seg) {
            if ($currDist + $seg['dist'] >= $targetDist - 0.0001 || $idx === count($segments) - 1) {
                $segT = $seg['dist'] > 0 ? ($targetDist - $currDist) / $seg['dist'] : 0;
                $segT = max(0, min(1, $segT));
                $rx = $seg['p1']['rx'] + ($seg['p2']['rx'] - $seg['p1']['rx']) * $segT;
                $ry = $seg['p1']['ry'] + ($seg['p2']['ry'] - $seg['p1']['ry']) * $segT;
                break;
            }
            $currDist += $seg['dist'];
        }

        // Map to SVG user space
        $mappedX = $offsetX + $rx * $scaledW;
        $mappedY = $offsetY + $ry * $scaledH;
        $cx_pt = $targetX + $mappedX;
        $cy_pt = $targetY + $mappedY;

        $is_peak = !empty($etapa['is_peak']);
        $puntos[] = array_merge($etapa, ['cx' => $cx_pt, 'cy' => $cy_pt, 'is_peak' => $is_peak]);
    }
    return $puntos;
}


$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen overflow-x-hidden select-none';
$extraStyles = <<<'EOT'
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        @keyframes dash { to { stroke-dashoffset: 0; } }
        .floating { animation: floating 4s ease-in-out infinite; }
        @keyframes floating {
            0%   { transform: translateY(0px); }
            50%  { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .force-close { transform: translateX(-100%) !important; }
        .force-open  { transform: translateX(0%)    !important; }

        /* Animacion escalera helicoptero para items del submenu */
        @keyframes submenu-drop {
            0%   { opacity: 0; transform: translateY(-18px); }
            60%  { opacity: 1; transform: translateY(4px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        #asistenciaSubmenu.open .submenu-item {
            animation: submenu-drop 0.32s cubic-bezier(0.34,1.56,0.64,1) both;
        }

        /* Animación para la flecha del último punto apuntando 25º a la izquierda con movimiento */
        @keyframes peak-arrow-bounce {
            0%, 100% {
                transform: rotate(-25deg) translate(0px, 0px);
            }
            50% {
                transform: rotate(-25deg) translate(0px, -15px);
            }
        }
        .peak-arrow-animated {
            animation: peak-arrow-bounce 1.4s ease-in-out infinite;
            transform-origin: 0px 0px;
        }

        /* ── Responsive mountain layout ── */
        body { min-height: 100dvh; overflow-x: hidden; }

        /* Main scroll container fills full height minus the mobile top-bar */
        #mainScrollContainer {
            height: calc(100dvh - 65px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        @media (min-width: 1024px) {
            #mainScrollContainer { height: 100dvh; }
        }

        /* SVG always fills its container width; height driven by viewBox ratio */
        #mountainSVG {
            width: 100%;
            height: calc(100dvh - 65px); /* Ajusta al alto de la pantalla en móvil */
            display: block;
        }
        @media (min-width: 768px) {
            #mountainSVG { min-height: 40vw; }
        }
        @media (min-width: 1024px) {
            #mountainSVG { min-height: 120vh; }
        }



        /* Sidebar collapse */
        @media (min-width: 1024px) {
            body.sidebar-collapsed #userSidebar { width: 5.5rem; }
            body.sidebar-collapsed #mainScrollContainer { margin-left: 5.5rem; }
            body.sidebar-collapsed .sidebar-text { display: none !important; }
            body.sidebar-collapsed .sidebar-search-container { display: none !important; }
            body.sidebar-collapsed .sidebar-profile-info { display: none !important; }
            body.sidebar-collapsed .sidebar-header { padding-left: 0.75rem; padding-right: 0.75rem; padding-top: 4rem; }
            body.sidebar-collapsed .sidebar-logo-container { flex-direction: column; gap: 0.25rem; }
            body.sidebar-collapsed .sidebar-item-link { padding-left: 0; padding-right: 0; justify-content: center; }
            body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }

            /* Sidebar desaparecible (completamente oculto) */
            body.sidebar-hidden #userSidebar {
                transform: translateX(-100%) !important;
            }
            body.sidebar-hidden #mainScrollContainer {
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

        /* Activity detail modal — mobile first */
        #actividadModal {
            padding: 0.5rem;
        }
        #actividadModal .modal-card {
            max-height: 90dvh;
            overflow-y: auto;
            width: 100%;
            max-width: 32rem;
            border-radius: 1.5rem;
        }
        @media (min-width: 640px) {
            #actividadModal { padding: 1.5rem; }
            #actividadModal .modal-card { border-radius: 2rem; }
        }
        @media (max-width: 1023px) {
            #global-theme-selector { display: none !important; }
            /* Ocultar mini pill flotante en camino — se muestra en el header */
            #thermometerMini { display: none !important; }
        }
    </style>
EOT;
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center px-4 py-3 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-2">
        <span class="font-bold text-primary text-lg">Zenith Path</span>
        <?php
        /* Mini pill de asistencia inline en el header */
        $pct = $porcentajeTermometro ?? 0;
        if ($pct >= 75)     { $pctColor = '#22c55e'; $pctIcon = 'sentiment_very_satisfied'; }
        elseif ($pct >= 50) { $pctColor = '#eab308'; $pctIcon = 'sentiment_neutral'; }
        elseif ($pct >= 25) { $pctColor = '#f97316'; $pctIcon = 'sentiment_dissatisfied'; }
        else                { $pctColor = '#ef4444'; $pctIcon = 'sentiment_very_dissatisfied'; }
        ?>
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-black border" style="background:<?= $pctColor ?>22; color:<?= $pctColor ?>; border-color:<?= $pctColor ?>44;">
            <span class="material-symbols-outlined" style="font-size:14px;color:<?= $pctColor ?>"><?= $pctIcon ?></span>
            <?= $pct ?>%
        </span>
    </div>
    <div class="flex items-center gap-1.5">
        <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
        <button onclick="openModal('contactosModal')" class="p-1.5 text-primary hover:bg-primary/10 rounded-full transition-colors active:scale-95" title="Contactos">
            <span class="material-symbols-outlined text-[1.3rem]">group</span>
        </button>
        <button onclick="openModal('opinionModal')" class="p-1.5 text-secondary hover:bg-secondary/10 rounded-full transition-colors active:scale-95" title="Opinión">
            <span class="material-symbols-outlined text-[1.3rem]">chat_bubble</span>
        </button>
    </div>
</header>
<script>
// Conectar el botón de tema del header al sistema global de temas
(function() {
    function bindHeaderTheme() {
        var btn = document.getElementById('headerThemeToggle');
        var mainBtn = document.getElementById('theme-main-toggle');
        if (!btn) return;
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (mainBtn) {
                mainBtn.click();
            }
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindHeaderTheme);
    } else {
        bindHeaderTheme();
    }
})();
</script>

<div class="flex">
    <!-- Sidebar reusable -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <main id="mainScrollContainer" class="flex-1 pt-0 pb-4 px-0 flex flex-col items-center relative w-full bg-gradient-to-b from-sky-100 via-blue-50 to-slate-100 scroll-smooth transition-all duration-300">

        <!-- ====== MOUNTAIN VIEWPORT (Native scroll) ====== -->
        <div class="relative w-full" id="mountainViewport">
            <?php
            // Generar todos los puntos
            $allPoints = generarTodosLosWaypoints($etapas, $totalEtapas, $viewBoxW, $totalHeight);

            // Determinar colores según la hora del día
            $hora = (int)date('H');
            $isNight = ($hora >= 19 || $hora < 6);
            $isTwilight = ($hora == 6 || $hora == 18);

            if ($isNight) {
                // Noche
                $skyStops = ['#020617', '#0f172a', '#1e1b4b', '#312e81'];
                $mntStops = ['#475569', '#334155', '#1e293b', '#0f172a']; // Silvery moonlight slate
                $distMnt = ['#1e1b4b', '#0f172a', '#020617'];
                $orb = ['fill' => '#e2e8f0', 'glow' => '#f1f5f9', 'r1' => 80, 'r2' => 60, 'opacity' => 0.4]; // Luna
                $showStars = true;
            } elseif ($isTwilight) {
                // Amanecer / Atardecer
                $skyStops = ['#1e1b4b', '#4c1d95', '#be185d', '#f59e0b'];
                $mntStops = ['#475569', '#334155', '#166534', '#14532d'];
                $distMnt = ['#4c1d95', '#312e81', '#1e1b4b'];
                $orb = ['fill' => '#fffbeb', 'glow' => '#fcd34d', 'r1' => 120, 'r2' => 90, 'opacity' => 0.6]; // Sol cálido
                $showStars = true;
            } else {
                // Día
                $skyStops = ['#38bdf8', '#7dd3fc', '#bae6fd', '#e0f2fe'];
                $mntStops = ['#22c55e', '#16a34a', '#15803d', '#166534'];
                $distMnt = ['#0ea5e9', '#0284c7', '#0369a1'];
                $orb = ['fill' => '#fef08a', 'glow' => '#fde047', 'r1' => 100, 'r2' => 80, 'opacity' => 0.5]; // Sol intenso
                $showStars = false;
            }
            ?>

            <!-- SVG Container -->
            <svg id="mountainSVG" class="w-full" preserveAspectRatio="xMidYMid slice"
                viewBox="0 0 <?= $viewBoxW ?> <?= $totalHeight + 150 ?>"
                style="overflow: visible;"
                data-vbw="<?= $viewBoxW ?>" data-totalh="<?= $totalHeight ?>"
                data-centrox="<?= $centroX ?>" data-basehalf="<?= $baseHalfW ?>">
                <defs>
                    <!-- Rock Face Gradients -->
                    <linearGradient id="mntBody" x1="15%" y1="0%" x2="85%" y2="100%">
                        <stop offset="0%" stop-color="<?= $mntStops[0] ?>" />
                        <stop offset="40%" stop-color="<?= $mntStops[1] ?>" />
                        <stop offset="100%" stop-color="<?= $mntStops[3] ?>" />
                    </linearGradient>
                    <linearGradient id="mntShadowL" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(0,0,0,0.55)" />
                        <stop offset="60%" stop-color="rgba(0,0,0,0)" />
                    </linearGradient>
                    <linearGradient id="mntShadowR" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="40%" stop-color="rgba(0,0,0,0)" />
                        <stop offset="100%" stop-color="rgba(0,0,0,0.45)" />
                    </linearGradient>
                    <linearGradient id="mntLit" x1="30%" y1="0%" x2="60%" y2="100%">
                        <stop offset="0%" stop-color="rgba(255,255,255,0.30)" />
                        <stop offset="100%" stop-color="rgba(255,255,255,0)" />
                    </linearGradient>
                    <linearGradient id="rockFace1" x1="20%" y1="0%" x2="80%" y2="100%">
                        <stop offset="0%" stop-color="<?= $mntStops[1] ?>" />
                        <stop offset="100%" stop-color="<?= $mntStops[2] ?>" />
                    </linearGradient>
                    <linearGradient id="rockFace2" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="<?= $mntStops[0] ?>" stop-opacity="0.9" />
                        <stop offset="100%" stop-color="<?= $mntStops[3] ?>" />
                    </linearGradient>
                    <linearGradient id="vegGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#4ade80" />
                        <stop offset="100%" stop-color="#15803d" />
                    </linearGradient>
                    <linearGradient id="rockGray" x1="20%" y1="0%" x2="80%" y2="100%">
                        <stop offset="0%" stop-color="#94a3b8" />
                        <stop offset="100%" stop-color="#475569" />
                    </linearGradient>
                    <linearGradient id="rockDark" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#334155" />
                        <stop offset="100%" stop-color="#1e293b" />
                    </linearGradient>
                    <linearGradient id="rockOcre" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#a16207" stop-opacity="0.5" />
                        <stop offset="100%" stop-color="#78350f" stop-opacity="0.3" />
                    </linearGradient>
                    <linearGradient id="meadow" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#86efac" />
                        <stop offset="100%" stop-color="#16a34a" />
                    </linearGradient>
                    <linearGradient id="snowGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                    <filter id="glow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="6" />
                    </filter>
                    <filter id="cloudBlur" x="-60%" y="-60%" width="220%" height="220%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="35" />
                    </filter>
                    <linearGradient id="skyGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="<?= $skyStops[0] ?>" />
                        <stop offset="35%" stop-color="<?= $skyStops[1] ?>" />
                        <stop offset="65%" stop-color="<?= $skyStops[2] ?>" />
                        <stop offset="100%" stop-color="<?= $skyStops[3] ?>" />
                    </linearGradient>
                    <!-- Filtro para textura de roca orgánica y detalles de superficie hiper-realistas -->
                    <filter id="organicRock" x="-20%" y="-20%" width="140%" height="140%">
                        <!-- 1. Generar deformación natural de bordes -->
                        <feTurbulence type="fractalNoise" baseFrequency="0.008 0.015" numOctaves="4" result="edgeNoise" />
                        <feDisplacementMap in="SourceGraphic" in2="edgeNoise" scale="40" xChannelSelector="R" yChannelSelector="G" result="displaced" />

                        <!-- 2. Generar textura interna porosa/rocosa (alta frecuencia) -->
                        <feTurbulence type="fractalNoise" baseFrequency="0.12" numOctaves="5" result="surfaceNoise" />

                        <!-- 3. Convertir el ruido en sombras sutiles (20% opacidad negra) -->
                        <feColorMatrix type="matrix" values="0 0 0 0 0   0 0 0 0 0   0 0 0 0 0   0 0 0 0.20 0" in="surfaceNoise" result="coloredNoise" />

                        <!-- 4. Recortar la textura para que no se salga de la montaña deformada -->
                        <feComposite operator="in" in="coloredNoise" in2="displaced" result="texture" />

                        <!-- 5. Fusionar la textura rocosa sobre los colores volumétricos de la montaña -->
                        <feBlend mode="multiply" in="texture" in2="displaced" />
                    </filter>
                    <!-- Filtro para el sendero (erosión e irregularidad) -->
                    <filter id="pathErosion" x="-20%" y="-20%" width="140%" height="140%">
                        <feTurbulence type="fractalNoise" baseFrequency="0.08" numOctaves="3" result="noise" />
                        <feDisplacementMap in="SourceGraphic" in2="noise" scale="12" xChannelSelector="R" yChannelSelector="G" result="displaced" />
                    </filter>
                </defs>

                <!-- Background sky gradient -->
                <rect x="0" y="0" width="<?= $viewBoxW ?>" height="<?= $viewBoxH ?>" fill="url(#skyGrad)" opacity="1" />

                <!-- Stars -->
                <?php if ($showStars): ?>
                    <g fill="#ffffff" opacity="0.8">
                        <circle cx="<?= $centroX - 400 ?>" cy="100" r="1.5" />
                        <circle cx="<?= $centroX + 500 ?>" cy="150" r="2" />
                        <circle cx="<?= $centroX - 200 ?>" cy="250" r="1" />
                        <circle cx="<?= $centroX + 100 ?>" cy="50" r="2.5" />
                        <circle cx="<?= $centroX - 600 ?>" cy="300" r="1.5" />
                        <circle cx="<?= $centroX + 700 ?>" cy="200" r="2" />
                        <circle cx="<?= $centroX - 800 ?>" cy="180" r="2" />
                        <circle cx="<?= $centroX + 300 ?>" cy="280" r="1" />
                    </g>
                <?php endif; ?>

                <!-- Glowing orb (Sun/Moon) -->
                <circle cx="<?= $centroX + 350 ?>" cy="450" r="<?= $orb['r1'] ?>" fill="<?= $orb['glow'] ?>" filter="url(#glow)" opacity="<?= $orb['opacity'] ?>" />
                <circle cx="<?= $centroX + 350 ?>" cy="450" r="<?= $orb['r2'] ?>" fill="<?= $orb['fill'] ?>" opacity="0.9" />

                <!-- Distant mountains for depth -->
                <path d="M <?= -$baseHalfW ?>,<?= $viewBoxH ?> L <?= $centroX - 500 ?>,600 L <?= $centroX - 100 ?>,400 L <?= $centroX + 400 ?>,700 L <?= $viewBoxW + $baseHalfW ?>,<?= $viewBoxH ?> Z" fill="<?= $distMnt[0] ?>" opacity="0.4" />
                <path d="M <?= -$baseHalfW ?>,<?= $viewBoxH ?> L <?= $centroX - 300 ?>,700 L <?= $centroX + 150 ?>,500 L <?= $centroX + 600 ?>,800 L <?= $viewBoxW + $baseHalfW ?>,<?= $viewBoxH ?> Z" fill="<?= $distMnt[1] ?>" opacity="0.5" />
                <path d="M <?= $centroX - 400 ?>,<?= $viewBoxH ?> L <?= $centroX + 300 ?>,650 L <?= $viewBoxW + $baseHalfW ?>,<?= $viewBoxH ?> Z" fill="<?= $distMnt[2] ?>" opacity="0.6" />

                <image
                    id="mountainImg"
                    href="<?= URLROOT ?>/public/assets/img/montañaña.jpeg"
                    x="0"
                    y="0"
                    width="<?= $viewBoxW ?>"
                    height="<?= $totalHeight ?>"
                    preserveAspectRatio="xMidYMid slice" />

                <!-- Nubes falsas integradas en el SVG (se mueven con el zoom) -->
                <?php
                if ($isNight) {
                    $cloud1 = '#334155';
                    $cloud2 = '#1e293b';
                    $cloud3 = '#0f172a';
                } elseif ($isTwilight) {
                    $cloud1 = '#4c1d95';
                    $cloud2 = '#312e81';
                    $cloud3 = '#1e1b4b';
                } else {
                    $cloud1 = '#ffffff';
                    $cloud2 = '#f8fafc';
                    $cloud3 = '#f1f5f9';
                }
                ?>
                <svg y="<?= $totalHeight - 200 ?>" width="<?= $viewBoxW ?>" height="350" viewBox="0 0 1440 250" preserveAspectRatio="none" style="pointer-events: none;">
                    <path fill="<?= $cloud1 ?>" opacity="0.6" d="M0,90 C300,210 600,-30 900,90 C1200,210 1350,30 1440,90 L1440,300 L0,300 Z" />
                    <path fill="<?= $cloud2 ?>" opacity="0.8" d="M0,130 C400,10 700,250 1000,130 C1200,50 1300,190 1440,130 L1440,300 L0,300 Z" />
                    <path fill="<?= $cloud3 ?>" d="M0,180 C450,280 850,80 1200,180 C1300,200 1370,160 1440,180 L1440,300 L0,300 Z" />
                </svg>

                <!-- ======= HUD GROUP: waypoints ======= -->
                <g id="hudGroup">
                    <!-- Waypoints -->
                    <g id="waypointsGroup"></g>
                </g><!-- end hudGroup -->
            </svg>
        </div><!-- end mountainViewport -->





        <script>
            // ====== MOUNTAIN RENDER – WEEK MODE ======
            const pts = <?= json_encode($allPoints) ?>;
            const vbW = <?= $viewBoxW ?>;
            const totalH = <?= $totalHeight ?>;

            // ── Zoom state ──
            let zoomedPoint = null;

            function buildSVGPath(pts) {
                if (pts.length === 0) return '';
                let d = `M ${pts[0].cx},${pts[0].cy}`;
                for (let i = 1; i < pts.length; i++) {
                    const prev = pts[i - 1],
                        cur = pts[i];
                    const mx = (prev.cx + cur.cx) / 2,
                        my = (prev.cy + cur.cy) / 2;
                    d += ` Q ${prev.cx},${prev.cy} ${mx},${my}`;
                }
                const last = pts[pts.length - 1];
                d += ` Q ${last.cx},${last.cy} ${last.cx},${last.cy}`;
                return d;
            }

            function renderAll() {
                const pBase = document.getElementById('pathBase');
                const pDirt = document.getElementById('pathDirt');
                if (pBase) pBase.setAttribute('d', buildSVGPath(pts));
                if (pDirt) pDirt.setAttribute('d', buildSVGPath(pts));

                const g = document.getElementById('waypointsGroup');
                if (!g) return;
                g.innerHTML = '';

                let activeY = totalH;

                pts.forEach((pt, idx) => {
                    if (pt.estado === 'actual') activeY = pt.cy;

                    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                    group.setAttribute('style', `transform-origin:${pt.cx}px ${pt.cy}px; cursor:pointer;`);

                    // ── Color logic — attendance-based only ──
                    let fillColor, strokeColor, outerColor;
                    if (pt.is_peak) {
                        fillColor = '#fcd34d';
                        strokeColor = '#f59e0b';
                        outerColor = '#fde68a';
                    } else {
                        const colorMap = {
                            completado: {
                                fill: '#10b981',
                                stroke: '#047857',
                                outer: '#34d399'
                            },
                            actual: {
                                fill: '#f59e0b',
                                stroke: '#b45309',
                                outer: '#fbbf24'
                            },
                            futuro: {
                                fill: '#3b82f6',
                                stroke: '#1d4ed8',
                                outer: '#93c5fd'
                            },
                            inasistencia: {
                                fill: '#ef4444',
                                stroke: '#991b1b',
                                outer: '#f87171'
                            },
                            mixto: {
                                fill: '#f97316',
                                stroke: '#c2410c',
                                outer: '#fed7aa'
                            },
                            bloqueado: {
                                fill: '#94a3b8',
                                stroke: '#475569',
                                outer: '#cbd5e1'
                            },
                        };
                        const c = colorMap[pt.estado] || colorMap.bloqueado;
                        fillColor = c.fill;
                        strokeColor = c.stroke;
                        outerColor = c.outer;
                    }


                    if (pt.is_peak) {
                        // Halo de luz exterior para la flecha de la cima
                        const outer = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        outer.setAttribute('cx', pt.cx);
                        outer.setAttribute('cy', pt.cy);
                        outer.setAttribute('r', '20');
                        outer.setAttribute('fill', outerColor);
                        outer.setAttribute('opacity', '0.5');
                        outer.setAttribute('filter', 'url(#glow)');
                        group.appendChild(outer);

                        // Grupo contenedor posicionado en las coordenadas (pt.cx, pt.cy)
                        const arrowGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                        arrowGroup.setAttribute('transform', `translate(${pt.cx}, ${pt.cy})`);

                        // Grupo interno animado con rotación de -25º y movimiento de vaivén
                        const arrowInner = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                        arrowInner.setAttribute('class', 'peak-arrow-animated');

                        // Trazo principal de la flecha
                        const arrowPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        arrowPath.setAttribute('d', 'M 0,-36 L 20,6 L 8,2 L 8,26 C 8,29 -8,29 -8,26 L -8,2 L -20,6 Z');
                        arrowPath.setAttribute('fill', fillColor);
                        arrowPath.setAttribute('stroke', strokeColor);
                        arrowPath.setAttribute('stroke-width', '3');
                        arrowPath.setAttribute('stroke-linejoin', 'round');

                        // Detalle metálico/brillante interior
                        const arrowDetail = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        arrowDetail.setAttribute('d', 'M 0,-25 L 10,4 L 3,1 L 3,19 L -3,19 L -3,1 L -10,4 Z');
                        arrowDetail.setAttribute('fill', '#ffffff');
                        arrowDetail.setAttribute('opacity', '0.7');

                        arrowInner.appendChild(arrowPath);
                        arrowInner.appendChild(arrowDetail);
                        arrowGroup.appendChild(arrowInner);
                        group.appendChild(arrowGroup);
                    } else {
                        // Outer glow ring para puntos normales
                        const outer = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        outer.setAttribute('cx', pt.cx);
                        outer.setAttribute('cy', pt.cy);
                        outer.setAttribute('r', '14');
                        outer.setAttribute('fill', outerColor);
                        outer.setAttribute('opacity', '0.55');
                        if (pt.estado !== 'bloqueado') outer.setAttribute('filter', 'url(#glow)');
                        group.appendChild(outer);

                        // Main dot para puntos normales
                        const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        dot.setAttribute('cx', pt.cx);
                        dot.setAttribute('cy', pt.cy);
                        dot.setAttribute('r', '10');
                        dot.setAttribute('fill', fillColor);
                        dot.setAttribute('stroke', strokeColor);
                        dot.setAttribute('stroke-width', '2.5');
                        group.appendChild(dot);
                    }

                    // Heartbeat pulse for "actual" week
                    if (pt.estado === 'actual' && !pt.multiple) {
                        const pulse = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        pulse.setAttribute('cx', pt.cx);
                        pulse.setAttribute('cy', pt.cy);
                        pulse.setAttribute('r', '8');
                        pulse.setAttribute('fill', 'none');
                        pulse.setAttribute('stroke', '#fbbf24');
                        pulse.setAttribute('stroke-width', '2');
                        pulse.setAttribute('opacity', '0.8');
                        const aR = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
                        aR.setAttribute('attributeName', 'r');
                        aR.setAttribute('values', '8;13;8;18;8;8');
                        aR.setAttribute('keyTimes', '0;0.15;0.3;0.45;0.7;1');
                        aR.setAttribute('dur', '2.5s');
                        aR.setAttribute('repeatCount', 'indefinite');
                        pulse.appendChild(aR);
                        group.appendChild(pulse);
                    }

                    // No label text — tooltips shown on click via popup

                    // Click: zoom + show day picker
                    group.addEventListener('click', (e) => handlePointClick(e, pt));
                    g.appendChild(group);
                });

                // Scroll to active week
                setTimeout(() => {
                    const sc = document.getElementById('mainScrollContainer');
                    const svg = document.getElementById('mountainSVG');
                    if (!svg || !sc) return;
                    const ratio = svg.getBoundingClientRect().height / totalH;
                    window.ignoreScroll = true;
                    sc.scrollTo({
                        top: Math.max(0, activeY * ratio - sc.clientHeight * 0.55),
                        behavior: 'smooth'
                    });
                    setTimeout(() => window.ignoreScroll = false, 800);
                }, 100);
            }

            // ── SVG viewBox zoom helpers ──
            const svgEl = document.getElementById('mountainSVG');
            const origVBW = vbW;
            const origVBH = totalH + 150;
            let vbAnimReq = null;

            function animateViewBox(fromVB, toVB, durationMs, onDone) {
                if (vbAnimReq) cancelAnimationFrame(vbAnimReq);
                const start = performance.now();

                function ease(t) {
                    return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
                }

                function step(now) {
                    const t = Math.min(1, (now - start) / durationMs);
                    const e = ease(t);
                    const vb = fromVB.map((v, i) => v + (toVB[i] - v) * e);
                    svgEl.setAttribute('viewBox', vb.join(' '));
                    if (t < 1) {
                        vbAnimReq = requestAnimationFrame(step);
                    } else if (onDone) onDone();
                }
                vbAnimReq = requestAnimationFrame(step);
            }

            function zoomToPoint(cx, cy, zoomFactor, durationMs, onDone) {
                const curVB = svgEl.getAttribute('viewBox').split(' ').map(Number);
                const zW = origVBW / zoomFactor;
                const zH = origVBH / zoomFactor;
                const zX = cx - zW / 2;
                const zY = cy - zH / 2;
                animateViewBox(curVB, [zX, zY, zW, zH], durationMs, onDone);
            }

            function resetZoom(durationMs) {
                const curVB = svgEl.getAttribute('viewBox').split(' ').map(Number);
                animateViewBox(curVB, [0, 0, origVBW, origVBH], durationMs);
            }

            // ── Zoom state ──
            let zoomedGroupEl = null;

            function resetCurrentZoom(durationMs = 500) {
                if (zoomedPoint !== null) {
                    zoomedPoint = null;
                    if (zoomedGroupEl) {
                        zoomedGroupEl.style.transform = 'scale(1)';
                        zoomedGroupEl = null;
                    }
                    closeDayPicker();
                    resetZoom(durationMs);
                }
            }

            // ── SVG background click to reset zoom ──
            svgEl.addEventListener('click', () => {
                resetCurrentZoom();
            });

            // ── Scroll to reset zoom ──
            const mainScroller = document.getElementById('mainScrollContainer');
            if (mainScroller) {
                mainScroller.addEventListener('scroll', () => {
                    if (window.ignoreScroll) return; // Prevent auto-scroll from closing modal
                    closeModal('actividadModal'); // Close activity detail modal if open
                    if (zoomedPoint !== null) {
                        resetCurrentZoom();
                    }
                }, {
                    passive: true
                });
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

            // ── Week click: zoom into point then show popup ──
            function handleWeekClick(e, pt, groupEl) {
                e.stopPropagation(); // Prevent SVG background click
                if (!pt.dias || pt.dias.length === 0) {
                    return; // No zoom if there are no activities
                }
                if (zoomedPoint === pt.semana) {
                    resetCurrentZoom();
                    return;
                }
                resetCurrentZoom(0); // Reset previous point if any, immediately
                zoomedPoint = pt.semana;
                zoomedGroupEl = groupEl;

                // 1. Animate SVG viewBox zoom to clicked point (incrementado 30%: 2.45 -> 3.2)
                zoomToPoint(pt.cx, pt.cy, 3.2, 480, () => {
                    // 2. After zoom, show popup; dot pulses
                    if (zoomedGroupEl) {
                        zoomedGroupEl.style.transition = 'transform 0.3s cubic-bezier(0.34,1.56,0.64,1)';
                        zoomedGroupEl.style.transform = 'scale(1.5)';
                        setTimeout(() => {
                            if (zoomedGroupEl) zoomedGroupEl.style.transform = 'scale(1.25)';
                        }, 300);
                    }

                    if (pt.dias && pt.dias.length > 0) showDayPicker(pt);
                    else showWeekInfo(pt);
                });
            }

            function showDayPicker(pt) {
                closeDayPicker();
                const n = pt.dias.length;

                // ── Responsive sizing ──
                const vw = window.innerWidth;
                let fanW, fanH, cardW, cardH, cardPad, txStep, fontSize, fontSm, fontXs, iconSize, badgeSz, badgePad, cardRadius, cardBorder;
                if (vw < 480) {
                    // Móvil pequeño
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
                    // Móvil estándar
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
                    // Tablet / laptop pequeño
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
                    // Desktop
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

                // ── Build fan overlay ──
                const overlay = document.createElement('div');
                overlay.id = 'cardFanOverlay';
                overlay.style.cssText = 'position:fixed;inset:0;z-index:75;display:flex;align-items:center;justify-content:center;pointer-events:none;';

                // Dismiss backdrop
                const backdrop = document.createElement('div');
                backdrop.style.cssText = 'position:absolute;inset:0;pointer-events:auto;background:rgba(0,0,0,0.35);backdrop-filter:blur(3px);';
                backdrop.addEventListener('click', () => resetCurrentZoom());
                overlay.appendChild(backdrop);

                // Fan container — centrado en pantalla
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
                    },
                };

                // Fan spread params
                const totalSpread = Math.min(n * 16, 72); // degrees
                const startAngle = -totalSpread / 2;

                pt.dias.forEach((dia, i) => {
                    const s = stateMap[dia.estado] || stateMap.bloqueado;

                    // -- Logic for Icon and Border Color based on Tipo --
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
                        }, // Azul claro
                        citacion: {
                            color: '#64748b',
                            icon: 'gavel'
                        }, // Gris (gavel = martillo/justicia, similar a esposas)
                        escolar: {
                            color: '#1e3a8a',
                            icon: 'menu_book'
                        }, // Azul oscuro
                        extraescolar: {
                            color: '#f97316',
                            icon: 'schedule'
                        } // Naranja
                    };

                    let cardColor = s.color;
                    let cardIcon = s.icon;

                    const tInfo = typeInfo[resolvedTipo];
                    if (tInfo) {
                        cardIcon = tInfo.icon; // Prioritize type icon
                        if (dia.estado !== 'inasistencia') {
                            cardColor = tInfo.color; // Prioritize type color UNLESS it's a missed activity
                        }
                    }

                    const ang = n > 1 ? startAngle + (totalSpread / (n - 1)) * i : 0;
                    const tx = (i - (n - 1) / 2) * txStep; // wider horizontal spread

                    const card = document.createElement('div');
                    card.className = 'fan-card';
                    card.style.cssText = `
                        position:absolute;
                        top:50%;
                        left:50%;
                        width:${cardW}px;
                        height:${cardH}px;
                        margin-top:${Math.round(cardH * 0.04)}px;
                        transform-origin:50% 100%;
                        transform:translate(calc(-50% + ${tx}px), -50%) rotate(${ang}deg);
                        transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.2s, width 0.4s, height 0.4s, padding 0.4s;
                        border-radius:${cardRadius};
                        background:#ffffff;
                        border:${cardBorder} solid ${cardColor};
                        box-shadow:0 10px 36px rgba(0,0,0,0.15);
                        cursor:pointer;
                        pointer-events:auto;
                        display:flex;
                        flex-direction:column;
                        padding:${cardPad};
                        user-select:none;
                        z-index:${i};
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

                    // Hover: raise
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

                    // Click: extract from fan, expand with full detail
                    card.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (card.classList.contains('expanded')) return;

                        // 1. Collapse other cards into a perfectly centered stack behind
                        let stackIdx = 0;
                        document.querySelectorAll('.fan-card').forEach((c, ci) => {
                            if (c === card) return;
                            const t = (stackIdx - (n - 2) / 2) * 12; // centered relative to remaining cards
                            c.style.transition = 'transform 0.4s cubic-bezier(0.4,0,0.2,1), opacity 0.4s, box-shadow 0.2s';
                            c.style.transform = `translate(calc(-50% + ${t}px), -50%) rotate(0deg) scale(0.82)`;
                            c.style.opacity = '0.4';
                            c.style.zIndex = ci;
                            c.style.pointerEvents = 'none';
                            c.classList.remove('selected');
                            stackIdx++;
                        });

                        // 2. Move card directly to center, straight, expand immediately
                        card.classList.add('selected', 'expanded');

                        // Replace content immediately before moving
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

                        // Animate card to center, straight, expanded
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
                            resetCurrentZoom();
                        });
                    });

                    fan.appendChild(card);
                });

                document.body.appendChild(overlay);

                // Animate in
                fan.style.opacity = '0';
                fan.style.transform = 'translateY(80px) scale(0.8)';
                fan.style.transition = 'opacity 0.38s ease, transform 0.45s cubic-bezier(0.34,1.2,0.64,1)';
                requestAnimationFrame(() => {
                    fan.style.opacity = '1';
                    fan.style.transform = 'translateY(0) scale(1)';
                });
            }

            function showWeekInfo(pt) {
                const overlay = document.getElementById('dayPickerOverlay');
                const container = document.getElementById('dayPickerCards');
                const title = document.getElementById('dayPickerTitle');
                if (!overlay) return;
                title.textContent = pt.nombre + ' — Sin actividades programadas';
                container.innerHTML = `<div class="col-span-5 text-center text-slate-400 py-4">
                    <span class="material-symbols-outlined text-4xl block mb-2">event_busy</span>
                    <p class="text-sm">No hay actividades esta semana</p></div>`;
                overlay.classList.remove('hidden');
                requestAnimationFrame(() => {
                    overlay.children[0].classList.remove('opacity-0', 'translate-y-4');
                });
            }

            function closeDayPicker() {
                const overlay = document.getElementById('cardFanOverlay');
                if (!overlay) return;
                const fan = document.getElementById('cardFan');
                if (fan) {
                    fan.style.opacity = '0';
                    fan.style.transform = 'translateY(60px) scale(0.85)';
                }
                setTimeout(() => overlay && overlay.remove(), 320);
            }

            function showActivityDetail(dia) {
                const stateMap = {
                    completado: {
                        badge: 'bg-emerald-100 text-emerald-700',
                        icon: 'check_circle',
                        label: 'Asistió'
                    },
                    inasistencia: {
                        badge: 'bg-red-100 text-red-700',
                        icon: 'cancel',
                        label: 'Faltó'
                    },
                    futuro: {
                        badge: 'bg-blue-100 text-blue-700',
                        icon: 'schedule',
                        label: 'Próxima'
                    },
                    bloqueado: {
                        badge: 'bg-slate-100 text-slate-600',
                        icon: 'lock',
                        label: 'Bloqueado'
                    },
                };
                const s = stateMap[dia.estado] || stateMap.bloqueado;
                document.getElementById('actTitle').textContent = dia.nombre;
                document.getElementById('actDate').textContent = `${dia.dia_semana} ${dia.fecha} ${dia.hora}`;
                document.getElementById('actSede').textContent = dia.sede || '—';
                document.getElementById('actType').textContent = dia.tipo || '—';
                document.getElementById('actDesc').textContent = dia.descripcion || 'Sin descripción.';
                const st = document.getElementById('actStatus');
                st.className = `px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider ${s.badge}`;
                st.innerHTML = `<span class="material-symbols-outlined text-sm align-middle mr-1">${s.icon}</span>${s.label}`;
                openModal('actividadModal');
            }


            const hash = window.location.hash;
            if (hash === '#contactos') setTimeout(() => openModal('contactosModal'), 400);
            if (hash === '#opinion') setTimeout(() => openModal('opinionModal'), 400);


            document.addEventListener('DOMContentLoaded', () => renderAll());
        </script>

        <!-- Day Picker (fan cards) – container injected dynamically by JS -->

        <!-- Floating Action Buttons -->
        <div class="fixed bottom-6 right-6 hidden lg:flex flex-col gap-4 z-40">
            <button onclick="openModal('contactosModal')" class="w-14 h-14 bg-primary text-on-primary rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform active:scale-95 floating" style="animation-delay: 0s;" title="Contactos">
                <span class="material-symbols-outlined">group</span>
            </button>
            <button onclick="openModal('opinionModal')" class="w-14 h-14 bg-secondary text-on-secondary rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform active:scale-95 floating" style="animation-delay: 1s;" title="Opinión">
                <span class="material-symbols-outlined">chat_bubble</span>
            </button>
            <button onclick="alert('Compartir próximamente')" class="w-14 h-14 bg-primary-container text-on-primary-container rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform active:scale-95 floating" style="animation-delay: 2s;" title="Compartir">
                <span class="material-symbols-outlined">share</span>
            </button>
        </div>

            <!-- Modals -->

            <!-- Modal Detalles de Actividad (Centrado) -->
            <div id="actividadModal" class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('actividadModal')"></div>
                <div class="modal-card relative z-10 w-full max-w-lg bg-surface text-on-surface rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0 border border-outline-variant/50">
                    <!-- Imagen cabecera representativa -->
                    <div class="h-36 bg-gradient-to-r from-blue-500 to-indigo-600 relative flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-6xl opacity-20 absolute">landscape</span>
                        <button onclick="closeModal('actividadModal')" class="absolute top-4 right-4 bg-black/20 hover:bg-black/40 text-white rounded-full w-8 h-8 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </button>
                        <!-- Status Badge sobre la imagen -->
                        <div id="actStatus" class="absolute bottom-4 left-6 bg-white shadow px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider">
                            Estado
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 id="actTitle" class="text-xl font-extrabold text-primary mb-2">Nombre de Actividad</h2>

                        <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-4 pb-4 border-b border-outline-variant/40">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-primary">event</span>
                                <span id="actDate">Fecha</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-secondary">location_on</span>
                                <span id="actSede">Sede</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">category</span>
                                <span id="actType">Tipo</span>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Resumen de la Actividad</h4>
                            <p id="actDesc" class="text-xs text-on-surface-variant leading-relaxed bg-surface-container/30 p-3.5 rounded-2xl border border-outline-variant/30">
                                Descripción detallada de la actividad aquí.
                            </p>
                        </div>
                    </div>
                    <div class="bg-surface-container/50 px-6 py-4 flex justify-end border-t border-outline-variant/30">
                        <button onclick="closeModal('actividadModal')" class="px-6 py-2.5 bg-primary text-on-primary font-bold text-xs rounded-xl shadow-md hover:bg-primary/90 transition-colors">Entendido</button>
                    </div>
                </div>
            </div>

    </main>
</div><!-- end flex -->

<?php
$etapasTermometro = $etapas;
require APPROOT . '/views/padres/termometro.php';
?>
<style>
    @media (max-width: 1023px) {
        /* Override termometro.php's display: flex !important */
        #thermometerMini { display: none !important; }
    }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
</body>

</html>