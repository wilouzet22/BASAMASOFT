<?php
$data        = $data ?? [];
$actividades = $data['actividades_camino'] ?? [];
$now         = new DateTime();

// ── Agrupar actividades por número de semana del año ──
$actsByWeek = []; // [weekNum => [act, ...]]
foreach ($actividades as $act) {
    $fechaInicio = new DateTime($act->fecha_hora_inicio);
    $semana = (int)$fechaInicio->format('W'); // ISO week number
    if (!isset($actsByWeek[$semana])) {
        $actsByWeek[$semana] = [];
    }
    $actsByWeek[$semana][] = $act;
}

// ── Siempre generar exactamente 15 semanas fijas ──
// La semana de referencia es la primera semana que tenga actividad,
// o la semana actual si no hay actividades.
$totalSemanas = 15;
$etapas = [];

if (!empty($actsByWeek)) {
    $allWeekNums = array_keys($actsByWeek);
    sort($allWeekNums);
    $startWeek = $allWeekNums[0]; // semana inicial del programa
} else {
    $startWeek = (int)(new DateTime())->format('W');
}

$actual_assigned = false;
for ($s = 1; $s <= $totalSemanas; $s++) {
    $weekNum = $startWeek + ($s - 1);
    $acts    = $actsByWeek[$weekNum] ?? [];
    $count   = count($acts);

    // Determinar estado de la semana
    if ($count === 0) {
        // Sin actividades → bloqueado si es futuro, inasistencia si es pasado
        // Calculamos la fecha de esa semana (lunes)
        $weekDate = new DateTime();
        $weekDate->setISODate((int)(new DateTime())->format('Y'), $weekNum);
        $estado = ($weekDate > $now) ? 'bloqueado' : 'inasistencia';
    } else {
        // Calcular estado basado en actividades de la semana
        $completadas  = 0;
        $inasistencias = 0;
        $futuras      = 0;
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
            } else $estado = 'bloqueado';
        } elseif ($completadas > 0 && $inasistencias === 0) {
            $estado = 'completado';
        } elseif ($inasistencias > 0 && $completadas === 0) {
            $estado = 'inasistencia';
        } else {
            $estado = 'mixto'; // tiene completadas e inasistencias
        }
    }

    // Preparar sub-actividades (máx 5 días) para mostrar en popup
    $dias = [];
    $dayNames = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'];
    foreach (array_slice($acts, 0, 5) as $act) {
        $fd = new DateTime($act->fecha_hora_inicio);
        $actEstado = 'bloqueado';
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
        'semana'       => $s,
        'nombre'       => 'Semana ' . $s,
        'estado'       => $estado,
        'is_peak'      => ($s === $totalSemanas),
        'multiple'     => ($count > 2), // azul si >2 actividades
        'dias'         => $dias,
        'total_acts'   => $count,
    ];
}

$totalEtapas  = count($etapas); // always 40
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
    $offsetCorrX = 0; // desplazamiento horizontal global
    $offsetCorrY = 0; // desplazamiento vertical global

    $refPoints = [
        1  => ['x' => 1217.00 + $offsetCorrX, 'y' => 3223.00 + $offsetCorrY], // Semana 1  – Inicio carretera (antes Semana 3)
        2  => ['x' => 1524.00 + $offsetCorrX, 'y' => 3069.00 + $offsetCorrY], // Semana 2
        3  => ['x' => 1861.00 + $offsetCorrX, 'y' => 3115.00 + $offsetCorrY], // Semana 3
        4  => ['x' => 2203.00 + $offsetCorrX, 'y' => 3013.00 + $offsetCorrY], // Semana 4
        5  => ['x' => 2550.00 + $offsetCorrX, 'y' => 2922.00 + $offsetCorrY], // Semana 5
        6  => ['x' => 2748.00 + $offsetCorrX, 'y' => 2632.00 + $offsetCorrY], // Semana 6  – curva derecha
        7  => ['x' => 2506.00 + $offsetCorrX, 'y' => 2415.00 + $offsetCorrY], // Semana 7
        8  => ['x' => 2151.00 + $offsetCorrX, 'y' => 2334.00 + $offsetCorrY], // Semana 8  – lazo junto al claro
        9  => ['x' => 2091.00 + $offsetCorrX, 'y' => 2064.00 + $offsetCorrY], // Semana 9
        10 => ['x' => 2427.00 + $offsetCorrX, 'y' => 1948.00 + $offsetCorrY], // Semana 10
        11 => ['x' => 2758.00 + $offsetCorrX, 'y' => 1816.00 + $offsetCorrY], // Semana 11
        12 => ['x' => 2762.00 + $offsetCorrX, 'y' => 1502.00 + $offsetCorrY], // Semana 12 – segunda horquilla
    ];

    $imgRefW = 4600;
    $imgRefH = 3800;

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

        $is_peak = ($i === $totalEtapas - 1);
        $puntos[] = array_merge($etapa, ['cx' => $cx_pt, 'cy' => $cy_pt, 'is_peak' => $is_peak]);
    }
    return $puntos;
}


// Cálculo del termómetro de asistencia
$actividadesProgramadas = $totalEtapas;
$actividadesAsistidas = 0;
foreach ($etapas as $e) {
    if ($e['estado'] === 'completado') $actividadesAsistidas++;
}
if (isset($estadisticas) && is_array($estadisticas)) {
    $actividadesProgramadas = 0;
    $actividadesAsistidas = 0;
    foreach ($estadisticas as $est) {
        $actividadesProgramadas += $est->total;
        $actividadesAsistidas += $est->presentes;
    }
}
$porcentajeTermometro = $actividadesProgramadas > 0 ? round(($actividadesAsistidas / $actividadesProgramadas) * 100) : 0;

$activeMoodIndex = 3;
if ($porcentajeTermometro >= 75) $activeMoodIndex = 0;
elseif ($porcentajeTermometro >= 50) $activeMoodIndex = 1;
elseif ($porcentajeTermometro >= 25) $activeMoodIndex = 2;
?>
<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen overflow-x-hidden select-none';
$extraStyles = '
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
            height: auto;
            display: block;
            /* Minimum visual height so the mountain is not too flat on wide screens */
            min-height: 60vw;
        }
        @media (min-width: 768px) {
            #mountainSVG { min-height: 40vw; }
        }
        @media (min-width: 1024px) {
            #mountainSVG { min-height: 120vh; }
        }

        /* Thermometer: bottom-right on mobile, mid-right on desktop */
        #thermometerWidget {
            position: fixed;
            right: 0.75rem;
            bottom: 1rem;
            top: auto;
            transform: none;
            z-index: 40;
        }
        @media (min-width: 768px) {
            #thermometerWidget {
                top: 50%;
                bottom: auto;
                transform: translateY(-50%);
                right: 1rem;
            }
        }
        /* Shrink thermometer tube on very small screens */
        @media (max-width: 479px) {
            #thermometerWidget { display: none; }
            #thermometerMini   { display: flex !important; }
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
    </style>
';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <span class="font-bold text-primary text-lg">Zenith Path</span>
    </div>
    <button id="menuToggleBtn" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors active:scale-95">
        <span class="material-symbols-outlined">menu</span>
    </button>
</header>

<div class="flex">
    <!-- Sidebar -->
    <nav id="userSidebar" class="flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-50 transition-all duration-300 -translate-x-full lg:translate-x-0 overflow-hidden">
        <button id="closeSidebarBtn" class="lg:hidden absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>

        <!-- Botones de control desktop: Colapsar u Ocultar -->
        <div class="hidden lg:flex absolute top-3 left-3 right-3 justify-between items-center z-10">
            <button id="collapseSidebarBtn" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95 cursor-pointer" title="Colapsar a iconos">
                <span class="material-symbols-outlined transition-transform duration-300">menu_open</span>
            </button>
            <button id="hideSidebarBtn" class="material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95 cursor-pointer" title="Ocultar menú completamente">
                <span class="material-symbols-outlined">visibility_off</span>
            </button>
        </div>
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
                    <a class="submenu-item sidebar-item-link bg-primary text-on-primary shadow-sm rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino" style="animation-delay:0ms">
                        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings:'FILL' 1;">mountain_flag</span>
                        <span class="font-medium text-sm sidebar-text">Camino de Montaña</span>
                    </a>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/puntos" style="animation-delay:80ms">
                        <span class="material-symbols-outlined flex-shrink-0">workspace_premium</span>
                        <span class="font-medium text-sm sidebar-text">Mis Puntos</span>
                    </a>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/cueva" style="animation-delay:120ms">
                        <span class="material-symbols-outlined flex-shrink-0">cave</span>
                        <span class="font-medium text-sm sidebar-text">Camino de Cueva</span>
                    </a>
                    <button class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all cursor-pointer" onclick="openModal('contactosModal')" style="animation-delay:160ms">
                        <span class="material-symbols-outlined flex-shrink-0">group</span>
                        <span class="font-medium text-sm sidebar-text">Contáctanos</span>
                    </button>
                    <button class="submenu-item sidebar-item-link w-full text-left text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all cursor-pointer" onclick="openModal('opinionModal')" style="animation-delay:240ms">
                        <span class="material-symbols-outlined flex-shrink-0">chat_bubble</span>
                        <span class="font-medium text-sm sidebar-text">Opinión</span>
                    </button>
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

    <!-- Botón flotante para restaurar sidebar cuando está oculto -->
    <button id="showSidebarFloatingBtn"
        class="fixed top-4 left-4 z-50 hidden items-center justify-center p-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md rounded-full shadow-lg border border-outline-variant text-on-surface hover:scale-105 active:scale-95 transition-all cursor-pointer"
        title="Mostrar menú de navegación">
        <span class="material-symbols-outlined">side_navigation</span>
    </button>

    <main id="mainScrollContainer" class="flex-1 lg:ml-72 pt-0 pb-4 px-0 flex flex-col items-center relative w-full bg-gradient-to-b from-sky-100 via-blue-50 to-slate-100 scroll-smooth transition-all duration-300">

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
            <svg id="mountainSVG" class="w-full h-auto" preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 <?= $viewBoxW ?> <?= $totalHeight ?>"
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
                    href="<?= URLROOT ?>/public/assets/img/aro de genaro.jpeg"
                    x="0"
                    y="0"
                    width="<?= $viewBoxW ?>"
                    height="<?= $totalHeight ?>"
                    preserveAspectRatio="xMidYMid slice" />

                <!-- ======= HUD GROUP: waypoints ======= -->
                <g id="hudGroup">
                    <!-- Waypoints -->
                    <g id="waypointsGroup"></g>
                </g><!-- end hudGroup -->
            </svg>
        </div><!-- end mountainViewport -->

        <!-- Filler para tapar el espacio en blanco hasta el footer en móviles -->
        <div class="w-full flex-1 bg-slate-300 md:hidden block min-h-[100px]"></div>

        <!-- ====== THERMOMETER (responsive — hidden on xs, fixed on sm+) ====== -->
        <!-- Mini pill shown only on xs (< 480px) -->
        <div id="thermometerMini" class="fixed bottom-4 right-4 z-40 hidden items-center gap-2 bg-white/90 backdrop-blur-md px-3 py-2 rounded-full border border-outline-variant shadow-xl">
            <span class="material-symbols-outlined text-base" style="color:<?php
                                                                            if ($porcentajeTermometro >= 75) echo '#22c55e';
                                                                            elseif ($porcentajeTermometro >= 50) echo '#eab308';
                                                                            elseif ($porcentajeTermometro >= 25) echo '#f97316';
                                                                            else echo '#ef4444';
                                                                            ?>;"><?php
                                                                                    if ($porcentajeTermometro >= 75) echo 'sentiment_very_satisfied';
                                                                                    elseif ($porcentajeTermometro >= 50) echo 'sentiment_neutral';
                                                                                    elseif ($porcentajeTermometro >= 25) echo 'sentiment_dissatisfied';
                                                                                    else echo 'sentiment_very_dissatisfied';
                                                                                    ?></span>
            <span class="text-xs font-black text-primary"><?= $porcentajeTermometro ?>%</span>
        </div>

        <!-- Full thermometer for sm+ -->
        <div id="thermometerWidget" class="flex flex-col items-center bg-white/90 backdrop-blur-md p-3 rounded-[48px] border border-outline-variant shadow-2xl gap-2">
            <!-- Tube + Faces inside -->
            <div class="relative flex flex-col items-center" style="height:180px; width:36px;">
                <!-- Tube background -->
                <div class="absolute inset-x-2 top-0 bottom-0 rounded-t-full bg-slate-200/70 border border-slate-300 shadow-inner overflow-hidden">
                    <!-- Liquid fill -->
                    <div id="thermLiquid" class="absolute bottom-0 left-0 right-0 rounded-t-full transition-all duration-1000 ease-in-out"
                        style="height:<?= $porcentajeTermometro ?>%; background:linear-gradient(to top,#ef4444,#f97316,#eab308,#22c55e);"></div>
                </div>
                <!-- Faces overlaid inside the tube at 25%, 50%, 75%, 100% positions -->
                <?php
                $faces = [
                    ['icon' => 'sentiment_very_dissatisfied', 'pct' => 0,  'color' => '#ef4444'],
                    ['icon' => 'sentiment_dissatisfied',      'pct' => 33, 'color' => '#f97316'],
                    ['icon' => 'sentiment_neutral',           'pct' => 58, 'color' => '#eab308'],
                    ['icon' => 'sentiment_very_satisfied',    'pct' => 83, 'color' => '#22c55e'],
                ];
                foreach ($faces as $face):
                    $topPct = 100 - $face['pct'] - 12;
                    $covered = $porcentajeTermometro >= ($face['pct'] + 10);
                ?>
                    <div class="absolute left-0 right-0 flex justify-center" style="top:<?= $topPct ?>%;">
                        <span class="material-symbols-outlined transition-all duration-700"
                            style="font-size:16px; color:<?= $covered ? '#ffffff' : $face['color'] ?>;">
                            <?= $face['icon'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Bulb -->
            <div class="w-9 h-9 rounded-full -mt-1 z-10 flex items-center justify-center border-2 border-white shadow-md"
                style="background:<?= $porcentajeTermometro > 0 ? '#ef4444' : '#94a3b8' ?>;">
                <div class="w-3 h-3 bg-white/40 rounded-full"></div>
            </div>
            <!-- % label -->
            <span class="text-[11px] font-bold text-primary mt-1"><?= $porcentajeTermometro ?>%</span>
        </div>

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

                    // ── Color logic ──
                    let fillColor, strokeColor, outerColor;
                    if (pt.multiple) {
                        // Blue = week with >2 activities
                        fillColor = '#3b82f6';
                        strokeColor = '#1d4ed8';
                        outerColor = '#93c5fd';
                    } else if (pt.is_peak) {
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
                            inasistencia: {
                                fill: '#ef4444',
                                stroke: '#991b1b',
                                outer: '#f87171'
                            },
                            mixto: {
                                fill: '#a855f7',
                                stroke: '#7e22ce',
                                outer: '#d8b4fe'
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
                        outer.setAttribute('r', '32');
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
                        outer.setAttribute('r', '22');
                        outer.setAttribute('fill', outerColor);
                        outer.setAttribute('opacity', '0.55');
                        if (pt.estado !== 'bloqueado') outer.setAttribute('filter', 'url(#glow)');
                        group.appendChild(outer);

                        // Main dot para puntos normales
                        const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        dot.setAttribute('cx', pt.cx);
                        dot.setAttribute('cy', pt.cy);
                        dot.setAttribute('r', '16');
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
                        pulse.setAttribute('r', '12');
                        pulse.setAttribute('fill', 'none');
                        pulse.setAttribute('stroke', '#fbbf24');
                        pulse.setAttribute('stroke-width', '2');
                        pulse.setAttribute('opacity', '0.8');
                        const aR = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
                        aR.setAttribute('attributeName', 'r');
                        aR.setAttribute('values', '12;18;12;24;12;12');
                        aR.setAttribute('keyTimes', '0;0.15;0.3;0.45;0.7;1');
                        aR.setAttribute('dur', '2.5s');
                        aR.setAttribute('repeatCount', 'indefinite');
                        pulse.appendChild(aR);
                        group.appendChild(pulse);
                    }

                    // No label text — tooltips shown on click via popup

                    // Click: zoom + show day picker
                    group.addEventListener('click', (e) => handleWeekClick(e, pt, group));
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
            const origVBH = totalH;
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

            // ── Week click: zoom into point then show popup ──
            function handleWeekClick(e, pt, groupEl) {
                e.stopPropagation(); // Prevent SVG background click
                if (pt.is_peak) {
                    window.location.href = '<?= URLROOT ?>/padres/cueva';
                    return;
                }
                if (zoomedPoint === pt.semana) {
                    resetCurrentZoom();
                    return;
                }
                resetCurrentZoom(0); // Reset previous point if any, immediately
                zoomedPoint = pt.semana;
                zoomedGroupEl = groupEl;

                // 1. Animate SVG viewBox zoom to clicked point (reducido 30%: 3.5 -> 2.45)
                zoomToPoint(pt.cx, pt.cy, 2.45, 480, () => {
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

                // ── Build fan overlay ──
                const overlay = document.createElement('div');
                overlay.id = 'cardFanOverlay';
                overlay.style.cssText = 'position:fixed;inset:0;z-index:75;display:flex;align-items:center;justify-content:center;pointer-events:none;';

                // Dismiss backdrop
                const backdrop = document.createElement('div');
                backdrop.style.cssText = 'position:absolute;inset:0;pointer-events:auto;background:rgba(0,0,0,0.35);backdrop-filter:blur(3px);';
                backdrop.addEventListener('click', closeDayPicker);
                overlay.appendChild(backdrop);

                // Fan container — centrado en pantalla
                const fan = document.createElement('div');
                fan.id = 'cardFan';
                fan.style.cssText = 'position:relative;width:420px;height:320px;pointer-events:none;';
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
                    const tx = (i - (n - 1) / 2) * 50; // wider horizontal spread

                    const card = document.createElement('div');
                    card.className = 'fan-card';
                    card.style.cssText = `
                        position:absolute;
                        top:50%;
                        left:50%;
                        width:170px;
                        height:260px;
                        margin-top:10px;
                        transform-origin:50% 100%;
                        transform:translate(calc(-50% + ${tx}px), -50%) rotate(${ang}deg);
                        transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.2s, width 0.4s, height 0.4s, padding 0.4s;
                        border-radius:18px;
                        background:#ffffff;
                        border:4px solid ${cardColor};
                        box-shadow:0 10px 36px rgba(0,0,0,0.15);
                        cursor:pointer;
                        pointer-events:auto;
                        display:flex;
                        flex-direction:column;
                        padding:14px 12px;
                        user-select:none;
                        z-index:${i};
                    `;
                    card.dataset.index = i;

                    card.innerHTML = `
                        <div style="font-size:28px;font-weight:900;color:${cardColor} !important;line-height:1;text-shadow:0 1px 2px rgba(0,0,0,0.05);">${s.suit}</div>
                        <div style="font-size:13px;font-weight:900;color:#0f172a !important;margin-top:8px;line-height:1.35;text-align:center;">${dia.nombre}</div>
                        <div style="font-size:10px;font-weight:800;color:#64748b !important;margin-top:4px;text-align:center;">${dia.dia_semana} ${dia.fecha}</div>
                        <div style="flex:1;"></div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                            <span class="material-symbols-outlined" style="font-size:42px;color:${cardColor} !important;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.1));">${cardIcon}</span>
                            <span style="font-size:10px;font-weight:800;padding:4px 14px;border-radius:99px;background:${cardColor};color:#fff !important;letter-spacing:0.05em;box-shadow:0 2px 6px rgba(0,0,0,0.15);">${s.label}</span>
                        </div>
                        <div style="font-size:28px;font-weight:900;color:${cardColor} !important;text-align:right;line-height:1;transform:rotate(180deg);margin-top:10px;">${s.suit}</div>
                    `;

                    // Hover: raise
                    card.addEventListener('mouseenter', () => {
                        if (card.classList.contains('selected')) return;
                        card.style.transform = `translate(calc(-50% + ${tx}px), -60%) rotate(${ang}deg) scale(1.06)`;
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
                            closeDayPicker();
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

            // Sidebar Toggle & Collapse/Hide Logic
            const menuBtn = document.getElementById('menuToggleBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
            const hideSidebarBtn = document.getElementById('hideSidebarBtn');
            const showSidebarFloatingBtn = document.getElementById('showSidebarFloatingBtn');
            const sidebar = document.getElementById('userSidebar');

            if (collapseSidebarBtn) {
                collapseSidebarBtn.addEventListener('click', () => {
                    document.body.classList.remove('sidebar-hidden');
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
            if (hideSidebarBtn) {
                hideSidebarBtn.addEventListener('click', () => {
                    document.body.classList.remove('sidebar-collapsed');
                    document.body.classList.add('sidebar-hidden');
                });
            }
            if (showSidebarFloatingBtn) {
                showSidebarFloatingBtn.addEventListener('click', () => {
                    document.body.classList.remove('sidebar-hidden');
                });
            }
            if (menuBtn && sidebar) menuBtn.addEventListener('click', () => sidebar.classList.toggle('force-open'));
            if (closeSidebarBtn && sidebar) closeSidebarBtn.addEventListener('click', () => sidebar.classList.remove('force-open'));

            // Dropdown Historial
            const dropBtn = document.getElementById('asistenciaDropdownBtn');
            const submenu = document.getElementById('asistenciaSubmenu');
            const chevron = document.getElementById('asistenciaDropdownChevron');
            if (submenu) {
                submenu.classList.remove('hidden');
                submenu.offsetHeight;
                submenu.classList.add('open');
            }
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            if (dropBtn && submenu) {
                dropBtn.addEventListener('click', () => {
                    if (submenu.classList.contains('open')) {
                        submenu.classList.remove('open');
                        chevron && (chevron.style.transform = 'rotate(0deg)');
                        dropBtn.classList.remove('text-primary', 'bg-primary/5');
                    } else {
                        submenu.classList.remove('hidden');
                        submenu.offsetHeight;
                        submenu.classList.add('open');
                        chevron && (chevron.style.transform = 'rotate(180deg)');
                        dropBtn.classList.add('text-primary', 'bg-primary/5');
                    }
                });
            }

            const hash = window.location.hash;
            if (hash === '#contactos') setTimeout(() => openModal('contactosModal'), 400);
            if (hash === '#opinion') setTimeout(() => openModal('opinionModal'), 400);

            function openModal(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.children[0].classList.remove('opacity-0');
                    modal.children[1].classList.remove('scale-95', 'opacity-0');
                    modal.children[1].classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (!modal) return;
                modal.children[0].classList.add('opacity-0');
                modal.children[1].classList.add('scale-95', 'opacity-0');
                modal.children[1].classList.remove('scale-100', 'opacity-100');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            document.addEventListener('DOMContentLoaded', () => renderAll());
        </script>

        <!-- Day Picker (fan cards) – container injected dynamically by JS -->

        <!-- Floating Action Buttons -->
        <div class="fixed bottom-6 right-6 flex flex-col gap-4 z-40">
            <!-- Floating Action Buttons -->
            <div class="fixed bottom-6 right-6 flex flex-col gap-4 z-40">
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

            <!-- Modal Detalles de Actividad -->
            <div id="actividadModal" class="fixed inset-0 z-[80] hidden">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('actividadModal')"></div>
                <div class="modal-card absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-lg bg-surface text-on-surface rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 transform scale-95 opacity-0">
                    <!-- Imagen cabecera representativa -->
                    <div class="h-40 bg-gradient-to-r from-blue-500 to-indigo-600 relative flex items-center justify-center">
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
                        <h2 id="actTitle" class="text-2xl font-black text-primary mb-2">Nombre de Actividad</h2>

                        <div class="flex items-center gap-4 text-sm text-on-surface-variant mb-4 pb-4 border-b border-outline-variant">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">event</span>
                                <span id="actDate">Fecha</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                                <span id="actSede">Sede</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">category</span>
                                <span id="actType">Tipo</span>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-on-surface mb-1">Resumen de la Actividad</h4>
                            <p id="actDesc" class="text-sm text-on-surface-variant leading-relaxed">
                                Descripción detallada de la actividad aquí.
                            </p>
                        </div>
                    </div>
                    <div class="bg-surface-container p-4 flex justify-end">
                        <button onclick="closeModal('actividadModal')" class="px-6 py-2 bg-primary text-on-primary font-bold rounded-full hover:bg-primary-hover transition-colors shadow-sm">Entendido</button>
                    </div>
                </div>
            </div>

            <!-- Contactos Modal -->
            <div id="contactosModal" class="fixed inset-0 z-[60] hidden">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('contactosModal')"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-md bg-surface text-on-surface rounded-2xl shadow-2xl p-6 transition-all duration-300 transform scale-95 opacity-0">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-primary flex items-center gap-2"><span class="material-symbols-outlined">group</span> Contactos</h3>
                        <button onclick="closeModal('contactosModal')" class="text-outline hover:text-on-surface transition-colors p-1 rounded-full hover:bg-surface-variant"><span class="material-symbols-outlined">close</span></button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                            <div class="w-12 h-12 rounded-full bg-secondary-fixed flex items-center justify-center text-on-secondary-fixed"><span class="material-symbols-outlined">person</span></div>
                            <div>
                                <span class="text-label-md font-bold block">Guía Maestro</span>
                                <span class="text-xs text-on-surface-variant">guia@edusaft.edu</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-surface-container-low rounded-xl border border-outline-variant">
                            <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed"><span class="material-symbols-outlined">group</span></div>
                            <div>
                                <span class="text-label-md font-bold block">Compañeros (Grupo A)</span>
                                <span class="text-xs text-on-surface-variant">12 estudiantes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opinión Modal -->
            <div id="opinionModal" class="fixed inset-0 z-[60] hidden">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('opinionModal')"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 max-w-md bg-surface text-on-surface rounded-2xl shadow-2xl p-6 transition-all duration-300 transform scale-95 opacity-0">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-secondary flex items-center gap-2"><span class="material-symbols-outlined">chat_bubble</span> Danos tu Opinión</h3>
                        <button onclick="closeModal('opinionModal')" class="text-outline hover:text-on-surface transition-colors p-1 rounded-full hover:bg-surface-variant"><span class="material-symbols-outlined">close</span></button>
                    </div>
                    <p class="text-sm italic text-on-surface-variant mb-4">"El camino es tan importante como la cima."</p>
                    <form class="space-y-4" onsubmit="event.preventDefault(); alert('Opinión enviada. ¡Gracias!'); closeModal('opinionModal');">
                        <div>
                            <label class="block text-sm font-bold mb-1">¿Cómo podemos mejorar?</label>
                            <textarea rows="4" class="w-full rounded-xl border border-outline-variant bg-surface-container-low p-3 text-sm focus:ring-2 focus:ring-secondary focus:outline-none" placeholder="Escribe tus comentarios..." required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-secondary text-on-secondary font-bold rounded-xl py-3 shadow-md hover:opacity-90 transition-opacity">Enviar Opinión</button>
                    </form>
                </div>
            </div>

    </main>
</div><!-- end flex -->

<?php require APPROOT . '/views/inc/footer.php'; ?>
</body>

</html>