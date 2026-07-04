<?php
// Datos genéricos de prueba - 10 actividades
$etapas_prueba = [
    ['id' => 1, 'nombre' => 'Inicio del Sendero', 'estado' => 'completado'],
    ['id' => 2, 'nombre' => 'Bosque de los Ecos', 'estado' => 'completado'],
    ['id' => 3, 'nombre' => 'Puente Colgante', 'estado' => 'completado'],
    ['id' => 4, 'nombre' => 'Refugio Rocoso', 'estado' => 'completado'],
    ['id' => 5, 'nombre' => 'Valle Perdido', 'estado' => 'completado'],
    ['id' => 6, 'nombre' => 'Mirador del Águila', 'estado' => 'actual'],
    ['id' => 7, 'nombre' => 'Cueva de Cristal', 'estado' => 'bloqueado'],
    ['id' => 8, 'nombre' => 'Paso Nublado', 'estado' => 'bloqueado'],
    ['id' => 9, 'nombre' => 'Cumbre Helada', 'estado' => 'bloqueado'],
    ['id' => 10, 'nombre' => 'Cima Zenith', 'estado' => 'bloqueado', 'is_peak' => true]
];

$totalEtapas = count($etapas_prueba);
$perPage = 6;
$totalPages = (int)ceil($totalEtapas / $perPage);

// Ancho dinámico de la montaña basado en cant. de actividades (min 900, max 2000)
// Escala desde 1 hasta 50 actividades. Siempre gruesa.
$maxActividades = 50;
$escalaAncho = max(0.3, min(1.0, $totalEtapas / $maxActividades));
$viewBoxW = 900 + (int)($escalaAncho * 1100); // 900 a 2000
$viewBoxH = 1400;
$centroX = (int)($viewBoxW / 2);
$baseHalfW = (int)($viewBoxW * 0.75); // La base siempre muy ancha

// SVG path serpentino dinámico (6 puntos por página en viewBox de 1400 alto)
function generarWaypointsPagina(array $etapas, int $pagina, int $perPage, int $totalEtapas, int $vbW, int $vbH): array
{
    $cx = (int)($vbW / 2);
    $inicio = $pagina * $perPage;
    $fin = min($inicio + $perPage, $totalEtapas);
    $puntos = [];
    $count = $fin - $inicio;
    $esUltimaPagina = ($fin >= $totalEtapas);

    // Distribuir entre y=1200 (base visible) y y=180 (cima / borde superior)
    $yStart = 1200;
    $yEnd = $esUltimaPagina ? 100 : 250;
    $rango = $yStart - $yEnd;

    for ($i = 0; $i < $count; $i++) {
        $etapa = $etapas[$inicio + $i];
        $t = $count > 1 ? $i / ($count - 1) : 0;
        $cy = (int)($yStart - $t * $rango);
        // Serpenteo: alterna izquierda/derecha
        $offset = ($i % 2 === 0) ? -80 : 80;
        $cx_pt = $cx + $offset;
        // Asegurar dentro de la montaña
        $cx_pt = max(180, min($vbW - 180, $cx_pt));
        $puntos[] = array_merge($etapa, ['cx' => $cx_pt, 'cy' => $cy]);
    }
    // Si es la última página, el último punto es la cima
    if ($esUltimaPagina && $count > 0) {
        $puntos[count($puntos) - 1]['cx'] = $cx;
        $puntos[count($puntos) - 1]['cy'] = 120;
        $puntos[count($puntos) - 1]['is_peak'] = true;
    }
    return $puntos;
}

// Cálculo del termómetro de asistencia
$actividadesProgramadas = $totalEtapas;
$actividadesAsistidas = 0;
foreach ($etapas_prueba as $e) {
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
<!DOCTYPE html>

<html class="light" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Mi Camino - Zenith Path</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700&family=Manrope:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@100..900&family=Plus+Jakarta+Sans:wght@100..900&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error-container": "#ffdad6",
                        "secondary-container": "#68abff",
                        "primary-fixed-dim": "#a1d494",
                        "inverse-on-surface": "#eef1ef",
                        "primary": "#154212",
                        "primary-container": "#2d5a27",
                        "on-primary-container": "#9dd090",
                        "on-surface-variant": "#42493e",
                        "tertiary-fixed": "#ffdcc3",
                        "on-background": "#181c1b",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed-variant": "#693b10",
                        "primary-fixed": "#bcf0ae",
                        "outline": "#72796e",
                        "surface-variant": "#e0e3e1",
                        "surface-container": "#ebefed",
                        "on-tertiary": "#ffffff",
                        "surface-dim": "#d7dbd9",
                        "outline-variant": "#c2c9bb",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#a1d494",
                        "on-secondary-container": "#003e73",
                        "secondary-fixed": "#d4e3ff",
                        "secondary": "#0060ac",
                        "on-surface": "#181c1b",
                        "on-primary-fixed": "#002201",
                        "inverse-surface": "#2d3130",
                        "error": "#ba1a1a",
                        "tertiary-container": "#744419",
                        "on-tertiary-container": "#f8b47f",
                        "on-primary-fixed-variant": "#23501e",
                        "surface-bright": "#f7faf8",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e6e9e7",
                        "background": "#f7faf8",
                        "on-error-container": "#93000a",
                        "secondary-fixed-dim": "#a4c9ff",
                        "surface-container-low": "#f1f4f2",
                        "on-secondary-fixed": "#001c39",
                        "tertiary": "#592e03",
                        "surface": "#f7faf8",
                        "on-secondary-fixed-variant": "#004883",
                        "surface-container-highest": "#e0e3e1",
                        "on-tertiary-fixed": "#2f1500",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#fcb882",
                        "surface-tint": "#3b6934"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-padding": "20px",
                        "margin-sm": "12px",
                        "gutter": "16px",
                        "margin-lg": "40px",
                        "unit": "8px",
                        "margin-md": "24px"
                    },
                    "fontFamily": {
                        "label-md": ["Manrope"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "body-lg": ["Manrope"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "body-md": ["Manrope"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "headline-sm-mobile": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "label-md": ["14px", {
                            "lineHeight": "20px",
                            "letterSpacing": "0.02em",
                            "fontWeight": "600"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "40px",
                            "fontWeight": "700"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "28px",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "600"
                        }],
                        "headline-sm": ["20px", {
                            "lineHeight": "28px",
                            "fontWeight": "600"
                        }],
                        "body-md": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "headline-lg-mobile": ["28px", {
                            "lineHeight": "36px",
                            "fontWeight": "700"
                        }],
                        "headline-sm-mobile": ["18px", {
                            "lineHeight": "24px",
                            "fontWeight": "600"
                        }]
                    }
                },
            },
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .mountain-path {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 3s linear forwards;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: 0;
            }
        }

        .floating {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .force-close {
            transform: translateX(-100%) !important;
        }

        .force-open {
            transform: translateX(0%) !important;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background text-on-surface font-body-md min-h-screen overflow-x-hidden select-none">
    <!-- TopAppBar -->
    <header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-container-padding py-unit bg-surface shadow-sm rounded-b-xl border-b border-outline-variant max-w-7xl mx-auto">
        <div class="flex items-center gap-margin-sm">
            <div class="relative flex">
                <button id="menuToggleBtn" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div id="sidebarTooltip" class="absolute top-12 left-0 w-48 bg-primary text-on-primary text-label-md p-3 rounded-lg shadow-lg opacity-0 pointer-events-none transition-opacity duration-500 delay-500 z-50">
                    <div class="absolute -top-2 left-4 w-4 h-4 bg-primary rotate-45"></div>
                    Click aquí para hacer pequeña la barra lateral
                </div>
            </div>
            <div class="relative flex-1 min-w-[200px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">search</span>
                <input class="w-full pl-12 pr-4 py-2 bg-surface-container-low border-none rounded-full text-label-md font-label-md focus:ring-2 focus:ring-primary transition-all text-center" placeholder="Buscar" type="text" />
            </div>
        </div>
        <div class="flex items-center gap-margin-sm">
            <span class="hidden md:block font-headline-sm text-secondary">Zenith Path</span>
            <a href="<?php echo URLROOT; ?>/auth/logout" onclick="return confirm('¿Seguro que deseas salir de tu cuenta?');" class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center overflow-hidden border-2 border-primary-fixed cursor-pointer block hover:ring-2 hover:ring-primary transition-all shadow-md" title="Cerrar sesión">
                <img alt="User Profile" class="w-full h-full object-cover" data-alt="A professional studio headshot of a smiling user against a soft, blurred mountain landscape background. The lighting is bright and warm, reflecting a sense of progress and wellness. High-end portrait photography style with crisp detail and natural skin tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4-sZziL98gyg-93o6NhBHrP9O1Mjg_PrtJ-VzMuxDcwNbPGr5nxHChNA__Afx1axDdlsUMxN0xhHaIfyQ4BJfSa1VKn5BjHv8Hso4JGk4t_9P9ByngNDbUCc2P7c1f4pRZM6NBUD-aFvlmReMobzBGytlvFkVx0doS8C7fu7znh8lOkuwi3f_zoHfXtkbgbMl8I_rcZhDiqgDqlXFzj8xwpAy8gYUn9ysa3z36Snvz1Y8nZVPo8VBtjuCETR-kIr1O9lPZ0BJzoC3" />
            </a>
        </div>
    </header>
    <!-- Side Navigation Drawer -->
    <aside id="userSidebar" class="fixed left-0 top-0 h-full w-80 bg-surface-container-low flex flex-col z-50 shadow-lg transition-transform duration-300 rounded-r-xl pt-24 -translate-x-full">
        <button id="closeSidebarBtn" class="absolute top-6 right-4 material-symbols-outlined text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors active:scale-95" title="Cerrar menú">close</button>
        <div class="px-6 mb-8 text-left">
            <h2 class="text-headline-md font-headline-md text-primary">Mi Camino</h2>
            <p class="text-body-md text-on-surface-variant">Nivel: Base de Montaña</p>
        </div>
        <nav class="flex flex-col gap-1">
            <a class="text-on-surface-variant hover:bg-surface-variant rounded-full mx-2 my-1 px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/dashboard">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-label-md">Panel Principal</span>
            </a>
            <a class="bg-primary-container text-on-primary-container hover:bg-surface-variant rounded-full px-4 py-3 mx-2 my-1 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino">
                <span class="material-symbols-outlined">mountain_flag</span>
                <span class="font-label-md">Camino de Progreso</span>
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 my-1 flex items-center gap-3 hover:bg-surface-variant rounded-full transition-all" href="<?php echo URLROOT; ?>/padres/asistencias">
                <span class="material-symbols-outlined">history</span>
                <span class="font-label-md">Historial Asistencia</span>
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 my-1 flex items-center gap-3 hover:bg-surface-variant rounded-full transition-all" href="<?php echo URLROOT; ?>/padres/puntos">
                <span class="material-symbols-outlined">workspace_premium</span>
                <span class="font-label-md">Mis Puntos</span>
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 my-1 flex items-center gap-3 hover:bg-surface-variant rounded-full transition-all cursor-pointer" onclick="openModal('contactosModal')">
                <span class="material-symbols-outlined">group</span>
                <span class="font-label-md">Contactos</span>
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 my-1 flex items-center gap-3 hover:bg-surface-variant rounded-full transition-all cursor-pointer" onclick="openModal('opinionModal')">
                <span class="material-symbols-outlined">chat_bubble</span>
                <span class="font-label-md">Opinión</span>
            </a>
        </nav>
    </aside>
    <main class="pt-0 pb-4 px-0 mx-auto flex flex-col items-center relative h-screen w-full overflow-hidden bg-gradient-to-b from-sky-100 via-blue-50 to-slate-100">

        <!-- ====== MOUNTAIN VIEWPORT (single screen, paginated) ====== -->
        <div class="relative w-full flex-1 overflow-hidden" id="mountainViewport">
            <?php
            // Pre-generate all pages JSON for JS
            $allPagesData = [];
            for ($p = 0; $p < $totalPages; $p++) {
                $pts = generarWaypointsPagina($etapas_prueba, $p, $perPage, $totalEtapas, $viewBoxW, $viewBoxH);
                $allPagesData[] = $pts;
            }
            ?>

            <!-- SVG Container - page 0 shown by default -->
            <svg id="mountainSVG" class="w-full h-full overflow-visible" preserveAspectRatio="xMidYMid meet"
                viewBox="0 0 <?= $viewBoxW ?> <?= $viewBoxH ?>"
                data-vbw="<?= $viewBoxW ?>" data-vbh="<?= $viewBoxH ?>"
                data-centrox="<?= $centroX ?>" data-basehalf="<?= $baseHalfW ?>">
                <defs>
                    <linearGradient id="mntMain" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#475569" />
                        <stop offset="35%" stop-color="#334155" />
                        <stop offset="65%" stop-color="#166534" />
                        <stop offset="100%" stop-color="#14532d" />
                    </linearGradient>
                    <linearGradient id="mntHighlight" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(255,255,255,0.18)" />
                        <stop offset="50%" stop-color="rgba(0,0,0,0)" />
                        <stop offset="100%" stop-color="rgba(0,0,0,0.45)" />
                    </linearGradient>
                    <linearGradient id="snowGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#ffffff" />
                        <stop offset="100%" stop-color="#cbd5e1" />
                    </linearGradient>
                    <filter id="glow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="6" />
                    </filter>
                    <filter id="cloudBlur" x="-60%" y="-60%" width="220%" height="220%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="40" />
                    </filter>
                </defs>

                <!-- Background sky gradient -->
                <rect x="0" y="0" width="<?= $viewBoxW ?>" height="<?= $viewBoxH ?>" fill="url(#skyGrad)" opacity="0.3" />

                <!-- Distant mountains for depth -->
                <path d="M <?= -$baseHalfW ?>,<?= $viewBoxH ?> L <?= $centroX - 300 ?>,500 L <?= $centroX + 200 ?>,<?= $viewBoxH ?> Z" fill="#0f2d1a" opacity="0.2" />
                <path d="M <?= $centroX - 100 ?>,<?= $viewBoxH ?> L <?= $centroX + 350 ?>,550 L <?= $viewBoxW + 200 ?>,<?= $viewBoxH ?> Z" fill="#0a2215" opacity="0.15" />

                <!-- MAIN MOUNTAIN - dynamic width, steep & narrow -->
                <path id="mntPath" d="
        M <?= -$baseHalfW ?>,<?= $viewBoxH ?>
        L <?= $centroX - (int)($baseHalfW * 0.85) ?>,1100
        L <?= $centroX - (int)($baseHalfW * 0.65) ?>,850
        L <?= $centroX - (int)($baseHalfW * 0.40) ?>,550
        L <?= $centroX - (int)($baseHalfW * 0.25) ?>,300
        L <?= $centroX - (int)($baseHalfW * 0.15) ?>,-50
        L <?= $centroX + (int)($baseHalfW * 0.15) ?>,-50
        L <?= $centroX + (int)($baseHalfW * 0.25) ?>,300
        L <?= $centroX + (int)($baseHalfW * 0.40) ?>,550
        L <?= $centroX + (int)($baseHalfW * 0.65) ?>,850
        L <?= $centroX + (int)($baseHalfW * 0.85) ?>,1100
        L <?= $viewBoxW + $baseHalfW ?>,<?= $viewBoxH ?> Z"
                    fill="url(#mntMain)" />
                <path d="
        M <?= -$baseHalfW ?>,<?= $viewBoxH ?>
        L <?= $centroX - (int)($baseHalfW * 0.85) ?>,1100
        L <?= $centroX - (int)($baseHalfW * 0.65) ?>,850
        L <?= $centroX - (int)($baseHalfW * 0.40) ?>,550
        L <?= $centroX - (int)($baseHalfW * 0.25) ?>,300
        L <?= $centroX - (int)($baseHalfW * 0.15) ?>,-50
        L <?= $centroX + (int)($baseHalfW * 0.15) ?>,-50
        L <?= $centroX + (int)($baseHalfW * 0.25) ?>,300
        L <?= $centroX + (int)($baseHalfW * 0.40) ?>,550
        L <?= $centroX + (int)($baseHalfW * 0.65) ?>,850
        L <?= $centroX + (int)($baseHalfW * 0.85) ?>,1100
        L <?= $viewBoxW + $baseHalfW ?>,<?= $viewBoxH ?> Z"
                    fill="url(#mntHighlight)" opacity="0.6" />

                <!-- Mountain ridge shadows -->
                <path d="M <?= $centroX ?>,-50 L <?= $centroX - (int)($baseHalfW * 0.10) ?>,300 L <?= $centroX - (int)($baseHalfW * 0.25) ?>,600 L <?= $centroX - (int)($baseHalfW * 0.65) ?>,<?= $viewBoxH ?> L <?= $centroX ?>,<?= $viewBoxH ?> Z" fill="#064e3b" opacity="0.3" />
                <path d="M <?= $centroX ?>,-50 L <?= $centroX + (int)($baseHalfW * 0.10) ?>,300 L <?= $centroX + (int)($baseHalfW * 0.25) ?>,600 L <?= $centroX + (int)($baseHalfW * 0.65) ?>,<?= $viewBoxH ?> L <?= $centroX ?>,<?= $viewBoxH ?> Z" fill="#022c22" opacity="0.35" />

                <!-- Snow cap -->
                <path d="M <?= $centroX - (int)($baseHalfW * 0.15) ?>,-50 L <?= $centroX - (int)($baseHalfW * 0.12) ?>,250 Q <?= $centroX ?>,300 <?= $centroX + (int)($baseHalfW * 0.12) ?>,250 L <?= $centroX + (int)($baseHalfW * 0.15) ?>,-50 Z" fill="url(#snowGrad)" />

                <!-- Path glow (drawn by JS) -->
                <path id="pathGlow" fill="none" stroke="#a1d494" stroke-width="18" opacity="0.25" stroke-linecap="round" filter="url(#glow)" />
                <!-- Main path -->
                <path id="pathMain" class="mountain-path" fill="none" stroke="#a1d494" stroke-width="7" stroke-dasharray="14,9" stroke-linecap="round" />
                <!-- White overlay -->
                <path id="pathWhite" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" opacity="0.75" />

                <!-- Waypoints container -->
                <g id="waypointsGroup"></g>

                <!-- TOP CLOUD BANK (hides upper transition / peak) -->
                <g filter="url(#cloudBlur)" pointer-events="none" id="topClouds">
                    <rect x="-2000" y="-1000" width="<?= $viewBoxW + 4000 ?>" height="1195" fill="#f1f5f9" />
                    <ellipse cx="<?= $centroX ?>" cy="195" rx="<?= (int)($viewBoxW * 1.5) ?>" ry="105" fill="#f8fafc" />
                    <ellipse cx="<?= $centroX - 300 ?>" cy="235" rx="600" ry="115" fill="#ffffff" />
                    <ellipse cx="<?= $centroX + 280 ?>" cy="245" rx="550" ry="130" fill="#f1f5f9" />
                    <ellipse cx="<?= $centroX ?>" cy="285" rx="<?= (int)($viewBoxW * 1.0) ?>" ry="80" fill="#ffffff" opacity="0.85" />
                    <ellipse cx="<?= $centroX ?>" cy="340" rx="<?= (int)($viewBoxW * 0.8) ?>" ry="50" fill="#f8fafc" opacity="0.6" />
                </g>

                <!-- BOTTOM CLOUD BANK (hides lower transition between pages) -->
                <g filter="url(#cloudBlur)" pointer-events="none" id="bottomClouds">
                    <rect x="-2000" y="<?= $viewBoxH - 190 ?>" width="<?= $viewBoxW + 4000 ?>" height="200" fill="#f1f5f9" />
                    <ellipse cx="<?= $centroX ?>" cy="<?= $viewBoxH - 190 ?>" rx="<?= (int)($viewBoxW * 1.5) ?>" ry="110" fill="#f8fafc" />
                    <ellipse cx="<?= $centroX - 450 ?>" cy="<?= $viewBoxH - 150 ?>" rx="800" ry="120" fill="#ffffff" />
                    <ellipse cx="<?= $centroX + 400 ?>" cy="<?= $viewBoxH - 160 ?>" rx="750" ry="90" fill="#f1f5f9" />
                    <ellipse cx="<?= $centroX ?>" cy="<?= $viewBoxH - 110 ?>" rx="<?= (int)($viewBoxW * 1.2) ?>" ry="80" fill="#ffffff" opacity="0.8" />
                </g>

                <!-- Title over top clouds -->
                <text id="topCloudTitle" x="<?= $centroX ?>" y="200" font-family="'Algerian','Georgia',serif" font-size="<?= max(36, (int)($viewBoxW * 0.042)) ?>" fill="#FFD700" text-anchor="middle" font-weight="bold" style="filter:drop-shadow(2px 4px 5px rgba(0,0,0,0.55)); letter-spacing:2px;">¡Confiamos en ti!</text>

                <!-- Bouncing UP arrow in top cloud (go to previous page) -->
                <g id="arrowUp" class="cursor-pointer" onclick="mountainNav(-1)" style="display:none;">
                    <circle cx="<?= $centroX ?>" cy="290" r="28" fill="#154212" opacity="0.85" />
                    <text x="<?= $centroX ?>" y="300" text-anchor="middle" font-size="32" fill="#ffffff">&#8679;</text>
                </g>

                <!-- Bouncing DOWN arrow in bottom cloud (go to next page) -->
                <g id="arrowDown" class="cursor-pointer" onclick="mountainNav(1)">
                    <circle cx="<?= $centroX ?>" cy="<?= $viewBoxH - 135 ?>" r="28" fill="#154212" opacity="0.85" />
                    <text x="<?= $centroX ?>" y="<?= $viewBoxH - 123 ?>" text-anchor="middle" font-size="32" fill="#ffffff">&#8681;</text>
                </g>

                <!-- Page indicator -->
                <text id="pageIndicator" x="<?= $centroX ?>" y="<?= $viewBoxH - 20 ?>" text-anchor="middle" font-family="Manrope,sans-serif" font-size="22" fill="#64748b" opacity="0.7">Tramo 1 de <?= $totalPages ?></text>
            </svg>
        </div><!-- end mountainViewport -->

        <!-- ====== THERMOMETER (fixed right) ====== -->
        <div class="fixed right-4 top-1/2 -translate-y-1/2 flex flex-col items-center z-40 bg-white/90 backdrop-blur-md p-3 rounded-[48px] border border-outline-variant shadow-2xl gap-2">
            <!-- Tube + Faces inside -->
            <div class="relative flex flex-col items-center" style="height:200px; width:40px;">
                <!-- Tube background -->
                <div class="absolute inset-x-2 top-0 bottom-0 rounded-t-full bg-slate-200/70 border border-slate-300 shadow-inner overflow-hidden">
                    <!-- Liquid fill -->
                    <div id="thermLiquid" class="absolute bottom-0 left-0 right-0 rounded-t-full transition-all duration-1000 ease-in-out"
                        style="height:<?= $porcentajeTermometro ?>%; background:linear-gradient(to top,#ef4444,#f97316,#eab308,#22c55e);"></div>
                </div>
                <!-- Faces overlaid inside the tube at 25%, 50%, 75%, 100% positions -->
                <?php
                $faces = [
                    ['icon' => 'sentiment_very_dissatisfied', 'pct' => 0, 'color' => '#ef4444'],
                    ['icon' => 'sentiment_dissatisfied', 'pct' => 33, 'color' => '#f97316'],
                    ['icon' => 'sentiment_neutral', 'pct' => 58, 'color' => '#eab308'],
                    ['icon' => 'sentiment_very_satisfied', 'pct' => 83, 'color' => '#22c55e'],
                ];
                foreach ($faces as $face):
                    // bottom-% = face pct; top = 100 - pct - small offset
                    $topPct = 100 - $face['pct'] - 12;
                    $covered = $porcentajeTermometro >= ($face['pct'] + 10);
                ?>
                    <div class="absolute left-0 right-0 flex justify-center" style="top:<?= $topPct ?>%;">
                        <span class="material-symbols-outlined transition-all duration-700"
                            style="font-size:18px; color:<?= $covered ? '#ffffff' : $face['color'] ?>;">
                            <?= $face['icon'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Bulb -->
            <div class="w-10 h-10 rounded-full -mt-1 z-10 flex items-center justify-center border-2 border-white shadow-md"
                style="background:<?= $porcentajeTermometro > 0 ? '#ef4444' : '#94a3b8' ?>;">
                <div class="w-3 h-3 bg-white/40 rounded-full"></div>
            </div>
            <!-- % label -->
            <span class="text-[11px] font-bold text-primary mt-1"><?= $porcentajeTermometro ?>%</span>
        </div>

        <!-- Inline styles for bouncing arrow animation -->
        <style>
            @keyframes bounce-y {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(8px);
                }
            }

            #arrowDown {
                animation: bounce-y 1.4s ease-in-out infinite;
                transform-origin: center;
            }

            @keyframes bounce-y-up {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-8px);
                }
            }

            #arrowUp {
                animation: bounce-y-up 1.4s ease-in-out infinite;
                transform-origin: center;
            }
        </style>


        <script>
            // ====== MOUNTAIN PAGINATION ======
            const allPages = <?= json_encode($allPagesData) ?>;
            const totalPages = <?= $totalPages ?>;
            const vbW = <?= $viewBoxW ?>;
            const vbH = <?= $viewBoxH ?>;
            const centroX = <?= $centroX ?>;
            const baseHalfW = <?= $baseHalfW ?>;
            let currentPage = 0;

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

            function renderPage(page) {
                const pts = allPages[page];
                const svg = document.getElementById('mountainSVG');

                // Update path
                const pathD = buildSVGPath(pts);
                document.getElementById('pathGlow').setAttribute('d', pathD);
                document.getElementById('pathMain').setAttribute('d', pathD);
                document.getElementById('pathWhite').setAttribute('d', pathD);

                // Update waypoints
                const g = document.getElementById('waypointsGroup');
                g.innerHTML = '';
                pts.forEach(pt => {
                    const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                    group.setAttribute('class', 'cursor-pointer');
                    group.setAttribute('style', `transform-origin:${pt.cx}px ${pt.cy}px`);

                    if (pt.is_peak) {
                        const c1 = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c1.setAttribute('cx', pt.cx);
                        c1.setAttribute('cy', pt.cy);
                        c1.setAttribute('r', '16');
                        c1.setAttribute('fill', pt.estado === 'completado' ? '#fcd34d' : '#ffffff');
                        c1.setAttribute('stroke', '#fcd34d');
                        c1.setAttribute('stroke-width', '4');
                        c1.setAttribute('filter', 'url(#glow)');
                        const c2 = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        c2.setAttribute('cx', pt.cx);
                        c2.setAttribute('cy', pt.cy);
                        c2.setAttribute('r', '7');
                        c2.setAttribute('fill', pt.estado === 'completado' ? '#ffffff' : '#fcd34d');
                        group.appendChild(c1);
                        group.appendChild(c2);
                    } else {
                        const colors = {
                            completado: {
                                fill: '#a1d494',
                                stroke: '#ffffff'
                            },
                            actual: {
                                fill: '#fcd34d',
                                stroke: '#ffffff'
                            },
                            bloqueado: {
                                fill: '#154212',
                                stroke: '#4a7c59'
                            }
                        };
                        const c = colors[pt.estado] || colors.bloqueado;
                        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                        circle.setAttribute('cx', pt.cx);
                        circle.setAttribute('cy', pt.cy);
                        circle.setAttribute('r', '12');
                        circle.setAttribute('fill', c.fill);
                        circle.setAttribute('stroke', c.stroke);
                        circle.setAttribute('stroke-width', '3');
                        group.appendChild(circle);
                        if (pt.estado === 'actual') {
                            const pulse = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                            pulse.setAttribute('cx', pt.cx);
                            pulse.setAttribute('cy', pt.cy);
                            pulse.setAttribute('r', '5');
                            pulse.setAttribute('fill', '#154212');
                            pulse.setAttribute('class', 'animate-pulse');
                            group.appendChild(pulse);
                        }
                    }
                    // Tooltip text
                    const txt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    txt.setAttribute('x', pt.cx + 18);
                    txt.setAttribute('y', pt.cy + 5);
                    txt.setAttribute('fill', '#ffffff');
                    txt.setAttribute('font-size', '15');
                    txt.setAttribute('font-weight', 'bold');
                    txt.setAttribute('font-family', 'Manrope,sans-serif');
                    txt.setAttribute('class', 'opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none');
                    txt.setAttribute('filter', 'url(#glow)');
                    txt.textContent = pt.nombre;
                    group.appendChild(txt);
                    g.appendChild(group);
                });

                // Arrow visibility
                document.getElementById('arrowUp').style.display = page > 0 ? '' : 'none';
                const isLast = page >= totalPages - 1;
                document.getElementById('arrowDown').style.display = isLast ? 'none' : '';

                // Title on last page
                const title = document.getElementById('topCloudTitle');
                title.style.display = isLast ? '' : '';

                // Page indicator
                document.getElementById('pageIndicator').textContent = `Tramo ${page+1} de ${totalPages}`;

                currentPage = page;
            }

            function mountainNav(dir) {
                const next = currentPage + dir;
                if (next < 0 || next >= totalPages) return;
                // Fade out, switch, fade in
                const vp = document.getElementById('mountainViewport');
                vp.style.transition = 'opacity 0.35s';
                vp.style.opacity = '0';
                setTimeout(() => {
                    renderPage(next);
                    vp.style.opacity = '1';
                }, 350);
            }

            // Sidebar Toggle & Tooltip Logic
            const menuBtn = document.getElementById('menuToggleBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const sidebar = document.getElementById('userSidebar');
            const tooltip = document.getElementById('sidebarTooltip');

            if (window.innerWidth >= 1024 && tooltip) {
                setTimeout(() => {
                    tooltip.classList.remove('opacity-0');
                }, 1000);
            }
            if (menuBtn && sidebar) {
                menuBtn.addEventListener('click', () => {
                    sidebar.classList.add('force-open');
                    if (tooltip) tooltip.classList.add('opacity-0');
                });
            }
            if (closeSidebarBtn && sidebar) {
                closeSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.remove('force-open');
                });
            }

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

            // Init
            document.addEventListener('DOMContentLoaded', () => {
                renderPage(0);
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
</body>

</html>