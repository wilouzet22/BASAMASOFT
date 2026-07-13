<?php
$data = $data ?? [];
$actividades = $data['actividades_camino'] ?? [];
$etapas = [];
$actual_assigned = false;
$now = new DateTime();

foreach ($actividades as $index => $act) {
    $fechaInicio = new DateTime($act->fecha_hora_inicio);
    $estado = 'bloqueado';
    
    if ($fechaInicio <= $now) {
        // Pasado
        if ($act->asistencia_registrada > 0) {
            $estado = 'completado';
        } else {
            $estado = 'inasistencia';
        }
    } else {
        // Futuro
        if (!$actual_assigned) {
            $estado = 'actual';
            $actual_assigned = true;
        } else {
            $estado = 'bloqueado';
        }
    }

    $is_peak = ($index === count($actividades) - 1);
    $fecha_fmt = $fechaInicio->format('d M Y, h:i A');

    $etapas[] = [
        'id' => $act->id_actividad,
        'nombre' => $act->nombre_actividad,
        'estado' => $estado,
        'is_peak' => $is_peak,
        'fecha' => $fecha_fmt,
        'descripcion' => $act->descripcion ?? 'Sin descripción',
        'tipo' => $act->nombre_tipo,
        'sede' => $act->nombre_sede
    ];
}

if (empty($etapas)) {
    $etapas[] = [
        'id' => 1, 'nombre' => 'Sin actividades asignadas', 'estado' => 'bloqueado', 'is_peak' => true,
        'fecha' => '', 'descripcion' => '', 'tipo' => '', 'sede' => ''
    ];
}

$totalEtapas = count($etapas);
$totalSections = 10;            // La imagen se divide en 10 secciones
$perPage      = 4;             // Máx 4 actividades por sección (40 total)
$totalPages   = max($totalSections, (int)ceil($totalEtapas / $perPage)); // siempre 10 páginas

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
    $cx = (int)($vbW / 2);
    $puntos = [];

    // El camino ahora arranca casi en la base real de la montaña
    $yStart = $totalH - 80;  // Base de la montaña (casi al fondo del SVG)
    $yEnd   = 200;            // Cima
    $rango  = $yStart - $yEnd;

    for ($i = 0; $i < $totalEtapas; $i++) {
        $etapa = $etapas[$i];
        $t = $totalEtapas > 1 ? $i / ($totalEtapas - 1) : 0.5;
        $cy = (int)($yStart - $t * $rango);

        // Curvas más pronunciadas: mayor frecuencia y mayor amplitud
        $frecuencia = 3.5;  // Más curvas a lo largo del sendero
        // Amplitud alta en la base, se reduce gradualmente hacia la cima
        $amplitud = 320 * (1 - ($t * 0.55));

        $offset = sin($t * M_PI * $frecuencia) * $amplitud;
        $cx_pt = max(120, min($vbW - 120, $cx + $offset));

        $is_peak = ($i === $totalEtapas - 1);
        if ($is_peak) {
            $cx_pt = $cx;
            $cy = $yEnd - 30; // Forzar cima un poco más arriba y al centro
        }

        $puntos[] = array_merge($etapa, ['cx' => $cx_pt, 'cy' => $cy, 'is_peak' => $is_peak]);
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
        <button id="collapseSidebarBtn" class="hidden lg:block absolute top-4 left-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95 z-10" title="Colapsar menú">
            <span class="material-symbols-outlined transition-transform duration-300">menu_open</span>
        </button>
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
                <rect x="-2000" y="-1000" width="<?= $viewBoxW + 4000 ?>" height="<?= $viewBoxH + 2000 ?>" fill="url(#skyGrad)" opacity="1" />

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

                <!-- ======= MOUNTAIN IMAGE (10x zoom, natively scrolled) ======= -->
                <image
                    id="mountainImg"
                    href="<?= URLROOT ?>/public/assets/img/ago.png"
                    x="<?= -$viewBoxW * 0.25 ?>"
                    y="<?= -$totalHeight * 0.25 ?>"
                    width="<?= $viewBoxW * 1.5 ?>"
                    height="<?= $totalHeight * 1.5 ?>"
                    preserveAspectRatio="xMidYMid slice" />

                <!-- ======= HUD GROUP: trail + waypoints + clouds + arrows + title =======
                     JS translates this entire group to the active section via transform -->
                <g id="hudGroup">

                    <!-- === ERODED MOUNTAIN TRAIL === -->
                    <filter id="pathShadow" x="-20%" y="-20%" width="140%" height="140%">
                        <feDropShadow dx="0" dy="4" stdDeviation="5" flood-color="#000000" flood-opacity="0.45" />
                    </filter>

                    <g filter="url(#pathShadow)">
                        <!-- Borde exterior (sombra/tierra oscura) -->
                        <path id="pathBase" fill="none" stroke="#291e12" stroke-width="26" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Camino de tierra principal -->
                        <path id="pathDirt" fill="none" stroke="#8b7355" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    </g>

                    <!-- Waypoints -->
                    <g id="waypointsGroup"></g>

                    <!-- NUBES SOLO EN LA CIMA (zona alta del SVG) -->
                    <g filter="url(#cloudBlur)" pointer-events="none" id="peakClouds">
                        <!-- Fondo base grisáceo -->
                        <rect x="-2000" y="-1000" width="<?= $viewBoxW + 4000 ?>" height="1080" fill="#f1f5f9" />
                        
                        <!-- Capa de nubes densas y oscuras (atrás) -->
                        <ellipse cx="<?= $centroX - 150 ?>" cy="220" rx="<?= (int)($viewBoxW * 1.5) ?>" ry="140" fill="#cbd5e1" opacity="0.8" />
                        <ellipse cx="<?= $centroX + 200 ?>" cy="230" rx="<?= (int)($viewBoxW * 1.3) ?>" ry="130" fill="#94a3b8" opacity="0.5" />
                        
                        <!-- Capa intermedia principal -->
                        <ellipse cx="<?= $centroX ?>" cy="260" rx="<?= (int)($viewBoxW * 1.68) ?>" ry="108" fill="#e2e8f0" opacity="0.9" />
                        <ellipse cx="<?= $centroX - 350 ?>" cy="285" rx="700" ry="120" fill="#f1f5f9" opacity="0.85" />
                        <ellipse cx="<?= $centroX + 320 ?>" cy="290" rx="650" ry="114" fill="#e2e8f0" opacity="0.95" />
                        
                        <!-- Capa frontal más dispersa y translúcida -->
                        <ellipse cx="<?= $centroX - 100 ?>" cy="330" rx="<?= (int)($viewBoxW * 1.2) ?>" ry="85" fill="#f8fafc" opacity="0.6" />
                        <ellipse cx="<?= $centroX + 150 ?>" cy="340" rx="<?= (int)($viewBoxW * 0.9) ?>" ry="75" fill="#cbd5e1" opacity="0.4" />
                        <ellipse cx="<?= $centroX ?>" cy="360" rx="<?= (int)($viewBoxW * 1.08) ?>" ry="90" fill="#e2e8f0" opacity="0.7" />
                        
                        <!-- Niebla baja -->
                        <ellipse cx="<?= $centroX - 400 ?>" cy="380" rx="800" ry="60" fill="#f1f5f9" opacity="0.3" />
                        <ellipse cx="<?= $centroX + 400 ?>" cy="385" rx="800" ry="60" fill="#cbd5e1" opacity="0.2" />
                    </g>

                    <!-- NUBES EN LA BASE (móviles muy pequeños) para tapar el final de la montaña -->
                    <g filter="url(#cloudBlur)" pointer-events="none" id="baseClouds" class="md:hidden">
                        <!-- Capas extra añadidas por encima de la base -->
                        <ellipse cx="<?= $centroX - 300 ?>" cy="<?= $totalHeight - 280 ?>" rx="600" ry="120" fill="#f8fafc" opacity="0.6" />
                        <ellipse cx="<?= $centroX + 250 ?>" cy="<?= $totalHeight - 260 ?>" rx="650" ry="110" fill="#f8fafc" opacity="0.5" />
                        <ellipse cx="<?= $centroX - 700 ?>" cy="<?= $totalHeight - 220 ?>" rx="700" ry="140" fill="#f1f5f9" opacity="0.7" />
                        <ellipse cx="<?= $centroX + 600 ?>" cy="<?= $totalHeight - 200 ?>" rx="750" ry="150" fill="#f1f5f9" opacity="0.75" />
                        <ellipse cx="<?= $centroX ?>" cy="<?= $totalHeight - 190 ?>" rx="800" ry="160" fill="#f8fafc" opacity="0.8" />

                        <!-- Capa trasera lejana -->
                        <ellipse cx="<?= $centroX - 600 ?>" cy="<?= $totalHeight - 150 ?>" rx="900" ry="150" fill="#f8fafc" opacity="0.8" />
                        <ellipse cx="<?= $centroX + 600 ?>" cy="<?= $totalHeight - 120 ?>" rx="950" ry="140" fill="#f8fafc" opacity="0.8" />
                        
                        <!-- Capa intermedia alta -->
                        <ellipse cx="<?= $centroX - 200 ?>" cy="<?= $totalHeight - 80 ?>" rx="700" ry="200" fill="#f1f5f9" opacity="0.9" />
                        <ellipse cx="<?= $centroX + 300 ?>" cy="<?= $totalHeight - 70 ?>" rx="750" ry="220" fill="#f1f5f9" opacity="0.9" />
                        
                        <!-- Capa principal central -->
                        <ellipse cx="<?= $centroX ?>" cy="<?= $totalHeight ?>" rx="<?= (int)($viewBoxW * 1.5) ?>" ry="300" fill="#e2e8f0" opacity="0.95" />
                        
                        <!-- Capas laterales densas -->
                        <ellipse cx="<?= $centroX - 500 ?>" cy="<?= $totalHeight + 20 ?>" rx="850" ry="280" fill="#e2e8f0" opacity="0.95" />
                        <ellipse cx="<?= $centroX + 450 ?>" cy="<?= $totalHeight + 30 ?>" rx="800" ry="260" fill="#e2e8f0" opacity="0.95" />
                        
                        <!-- Nubes oscuras de base (transición hacia el fondo sólido) -->
                        <ellipse cx="<?= $centroX - 800 ?>" cy="<?= $totalHeight + 80 ?>" rx="1000" ry="350" fill="#cbd5e1" opacity="0.9" />
                        <ellipse cx="<?= $centroX + 700 ?>" cy="<?= $totalHeight + 100 ?>" rx="1100" ry="380" fill="#cbd5e1" opacity="0.95" />
                        
                        <!-- Base sólida final -->
                        <ellipse cx="<?= $centroX ?>" cy="<?= $totalHeight + 150 ?>" rx="<?= (int)($viewBoxW * 1.8) ?>" ry="450" fill="#cbd5e1" opacity="1" />
                    </g>

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
            // ====== MOUNTAIN RENDER ======
            const pts = <?= json_encode($allPoints) ?>;
            const vbW = <?= $viewBoxW ?>;
            const totalH = <?= $totalHeight ?>;

            function buildSVGPath(pts) {
                if (pts.length === 0) return '';
                let d = `M ${pts[0].cx},${pts[0].cy}`;
                for (let i = 1; i < pts.length; i++) {
                    const prev = pts[i - 1],
                        cur = pts[i];
                    const mx = (prev.cx + cur.cx) / 2;
                    const my = (prev.cy + cur.cy) / 2;
                    d += ` Q ${prev.cx},${prev.cy} ${mx},${my}`;
                }
                const last = pts[pts.length - 1];
                d += ` Q ${last.cx},${last.cy} ${last.cx},${last.cy}`;
                return d;
            }

            function renderAll() {
                // Update path
                const pathD = buildSVGPath(pts);
                document.getElementById('pathBase').setAttribute('d', pathD);
                document.getElementById('pathDirt').setAttribute('d', pathD);

                // Update waypoints
                const g = document.getElementById('waypointsGroup');
                g.innerHTML = '';

                let activeY = totalH; // default to bottom

                pts.forEach(pt => {
                    if (pt.estado === 'actual') activeY = pt.cy; // Guardar Y del punto activo

                    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                    group.setAttribute('class', 'cursor-pointer');
                    group.setAttribute('style', `transform-origin:${pt.cx}px ${pt.cy}px`);

                    // Renderización de los nodos (puntos) con diseño premium
                    if (pt.is_peak) {
                        const c1 = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c1.setAttribute('cx', pt.cx);
                        c1.setAttribute('cy', pt.cy);
                        c1.setAttribute('r', '22');
                        c1.setAttribute('fill', pt.estado === 'completado' ? '#fcd34d' : '#ffffff');
                        c1.setAttribute('stroke', '#fbbf24');
                        c1.setAttribute('stroke-width', '4');
                        c1.setAttribute('filter', 'url(#glow)');

                        const c2 = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c2.setAttribute('cx', pt.cx);
                        c2.setAttribute('cy', pt.cy);
                        c2.setAttribute('r', '10');
                        c2.setAttribute('fill', pt.estado === 'completado' ? '#ffffff' : '#fcd34d');

                        group.appendChild(c1);
                        group.appendChild(c2);
                    } else {
                        const colors = {
                            completado: { fill: '#10b981', stroke: '#047857', outer: '#34d399' },
                            actual: { fill: '#f59e0b', stroke: '#b45309', outer: '#fbbf24' },
                            inasistencia: { fill: '#ef4444', stroke: '#991b1b', outer: '#f87171' },
                            bloqueado: { fill: '#94a3b8', stroke: '#475569', outer: '#cbd5e1' }
                        };
                        const c = colors[pt.estado] || colors.bloqueado;

                        // Interactive Group
                        const pointGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                        pointGroup.setAttribute('class', 'cursor-pointer transition-transform hover:scale-110');
                        pointGroup.style.transformOrigin = `${pt.cx}px ${pt.cy}px`;
                        pointGroup.addEventListener('click', () => {
                            abrirModalActividad(pt);
                        });

                        // Outer glowing ring
                        const outer = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        outer.setAttribute('cx', pt.cx);
                        outer.setAttribute('cy', pt.cy);
                        outer.setAttribute('r', '16');
                        outer.setAttribute('fill', c.outer);
                        outer.setAttribute('opacity', '0.6');
                        if (pt.estado !== 'bloqueado') outer.setAttribute('filter', 'url(#glow)');

                        // Main circle
                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('cx', pt.cx);
                        circle.setAttribute('cy', pt.cy);
                        circle.setAttribute('r', '10');
                        circle.setAttribute('fill', c.fill);
                        circle.setAttribute('stroke', c.stroke);
                        circle.setAttribute('stroke-width', '3');

                        pointGroup.appendChild(outer);
                        pointGroup.appendChild(circle);

                        // Animated heartbeat (latido lento) for current level
                        if (pt.estado === 'actual') {
                            const pulse = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                            pulse.setAttribute('cx', pt.cx);
                            pulse.setAttribute('cy', pt.cy);
                            pulse.setAttribute('r', '16');
                            pulse.setAttribute('fill', 'none');
                            pulse.setAttribute('stroke', '#fbbf24');
                            pulse.setAttribute('stroke-width', '3');
                            pulse.setAttribute('opacity', '0.8');

                            // Animación del radio (Latido: bum-bum... bum-bum...)
                            const animR = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
                            animR.setAttribute('attributeName', 'r');
                            animR.setAttribute('values', '16; 20; 16; 26; 16; 16');
                            animR.setAttribute('keyTimes', '0; 0.15; 0.3; 0.45; 0.7; 1');
                            animR.setAttribute('dur', '2.5s');
                            animR.setAttribute('repeatCount', 'indefinite');
                            pulse.appendChild(animR);

                            // Animación de la opacidad sincronizada
                            const animOpacity = document.createElementNS('http://www.w3.org/2000/svg', 'animate');
                            animOpacity.setAttribute('attributeName', 'opacity');
                            animOpacity.setAttribute('values', '0.8; 0.5; 0.8; 0; 0.8; 0.8');
                            animOpacity.setAttribute('keyTimes', '0; 0.15; 0.3; 0.45; 0.7; 1');
                            animOpacity.setAttribute('dur', '2.5s');
                            animOpacity.setAttribute('repeatCount', 'indefinite');
                            pulse.appendChild(animOpacity);
                            
                            pointGroup.appendChild(pulse);
                        }
                        
                        group.appendChild(pointGroup);
                    }

                    // Tooltip: background pill + text for readability on all screen sizes
                    const labelFontSize = Math.max(18, Math.round(vbW / 70));
                    const labelX = pt.cx + 22;
                    const labelY = pt.cy + Math.round(labelFontSize * 0.38);
                    const labelW = pt.nombre.length * labelFontSize * 0.55 + 20;
                    const labelH = labelFontSize + 14;

                    const pill = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    pill.setAttribute('x', labelX - 6);
                    pill.setAttribute('y', labelY - Math.round(labelFontSize * 0.82));
                    pill.setAttribute('width', labelW);
                    pill.setAttribute('height', labelH);
                    pill.setAttribute('rx', labelH / 2);
                    pill.setAttribute('fill', 'rgba(0,0,0,0.55)');
                    group.appendChild(pill);

                    const txt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    txt.setAttribute('x', labelX);
                    txt.setAttribute('y', labelY);
                    txt.setAttribute('fill', '#ffffff');
                    txt.setAttribute('font-size', labelFontSize);
                    txt.setAttribute('font-weight', 'bold');
                    txt.setAttribute('font-family', 'Manrope,sans-serif');
                    txt.textContent = pt.nombre;
                    group.appendChild(txt);
                    g.appendChild(group);
                });

                // Scroll to active position after rendering
                setTimeout(() => {
                    const scrollContainer = document.getElementById('mainScrollContainer');
                    const svg = document.getElementById('mountainSVG');
                    if (!svg) return;
                    const ratio = svg.getBoundingClientRect().height / totalH;
                    const scrollTarget = (activeY * ratio) - (scrollContainer.clientHeight * 0.55);
                    scrollContainer.scrollTo({
                        top: Math.max(0, scrollTarget),
                        behavior: 'smooth'
                    });
                }, 100);
            }

            // Sidebar Toggle Logic
            const menuBtn = document.getElementById('menuToggleBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
            const sidebar = document.getElementById('userSidebar');

            if (collapseSidebarBtn) {
                collapseSidebarBtn.addEventListener('click', () => {
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }

            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('force-open');
                });
            }
            if (closeSidebarBtn && sidebar) {
                closeSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.remove('force-open');
                });
            }

            // ── Historial Asistencias Dropdown ──
            const dropBtn  = document.getElementById('asistenciaDropdownBtn');
            const submenu  = document.getElementById('asistenciaSubmenu');
            const chevron  = document.getElementById('asistenciaDropdownChevron');

            // This page is a child — start opened
            if (submenu) {
                submenu.classList.remove('hidden');
                submenu.offsetHeight; // reflow
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

            // ── Auto-abrir modal por hash de URL ──
            // Permite que otros sidebars enlacen a camino#contactos o camino#opinion
            const hash = window.location.hash;
            if (hash === '#contactos') { setTimeout(() => openModal('contactosModal'), 400); }
            if (hash === '#opinion')   { setTimeout(() => openModal('opinionModal'),   400); }

            // Modal Logic
            function openModal(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.children[0].classList.remove('opacity-0');
                        modal.children[1].classList.remove('scale-95', 'opacity-0');
                        modal.children[1].classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            }

            function closeModal(id) {
                const modal = document.getElementById(id);
                if (modal) {
                    modal.children[0].classList.add('opacity-0');
                    modal.children[1].classList.add('scale-95', 'opacity-0');
                    modal.children[1].classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }
            }

            function abrirModalActividad(pt) {
                // Seleccionar los elementos del modal
                const modalTitle = document.getElementById('actTitle');
                const modalDate = document.getElementById('actDate');
                const modalDesc = document.getElementById('actDesc');
                const modalStatus = document.getElementById('actStatus');
                const modalType = document.getElementById('actType');
                const modalSede = document.getElementById('actSede');

                // Llenar datos
                modalTitle.textContent = pt.nombre;
                modalDate.textContent = pt.fecha;
                modalDesc.textContent = pt.descripcion;
                modalType.textContent = pt.tipo;
                modalSede.textContent = pt.sede;

                // Estilizar el badge de estado
                modalStatus.className = 'px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider';
                let icon = '';
                
                if (pt.estado === 'completado') {
                    modalStatus.classList.add('bg-emerald-100', 'text-emerald-700');
                    icon = '<span class="material-symbols-outlined text-sm align-middle mr-1">check_circle</span> Completado';
                } else if (pt.estado === 'inasistencia') {
                    modalStatus.classList.add('bg-red-100', 'text-red-700');
                    icon = '<span class="material-symbols-outlined text-sm align-middle mr-1">cancel</span> Inasistencia';
                } else if (pt.estado === 'actual') {
                    modalStatus.classList.add('bg-amber-100', 'text-amber-700');
                    icon = '<span class="material-symbols-outlined text-sm align-middle mr-1">star</span> Próxima';
                } else {
                    modalStatus.classList.add('bg-slate-100', 'text-slate-700');
                    icon = '<span class="material-symbols-outlined text-sm align-middle mr-1">lock</span> Bloqueado';
                }
                modalStatus.innerHTML = icon;

                // Abrir el modal
                openModal('actividadModal');
            }

            // Init
            document.addEventListener('DOMContentLoaded', () => {
                renderAll();
            });
        </script>

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
        <div id="actividadModal" class="fixed inset-0 z-[60] hidden">
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