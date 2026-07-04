<?php
// Datos genéricos de prueba para simular el progreso en la montaña
$etapas_prueba = [
    [
        'id' => 1,
        'nombre' => 'Inicio del Sendero',
        'estado' => 'completado',
        'cx' => 260,
        'cy' => 780,
    ],
    [
        'id' => 2,
        'nombre' => 'Bosque de los Ecos',
        'estado' => 'completado',
        'cx' => 340,
        'cy' => 650,
    ],
    [
        'id' => 3,
        'nombre' => 'Puente Colgante',
        'estado' => 'completado',
        'cx' => 300,
        'cy' => 520,
    ],
    [
        'id' => 4,
        'nombre' => 'Refugio Rocoso',
        'estado' => 'actual',
        'cx' => 260,
        'cy' => 400,
    ],
    [
        'id' => 5,
        'nombre' => 'Cueva de Cristal',
        'estado' => 'bloqueado',
        'cx' => 285,
        'cy' => 300,
    ],
    [
        'id' => 6,
        'nombre' => 'Paso Nublado',
        'estado' => 'bloqueado',
        'cx' => 295,
        'cy' => 210,
    ],
    [
        'id' => 7,
        'nombre' => 'Cima Zenith',
        'estado' => 'bloqueado',
        'cx' => 300,
        'cy' => 120,
        'is_peak' => true
    ]
];

// Cálculo del termómetro de asistencia
$actividadesProgramadas = 0;
$actividadesAsistidas = 0;
if (isset($estadisticas) && is_array($estadisticas)) {
    foreach ($estadisticas as $est) {
        $actividadesProgramadas += $est->total;
        $actividadesAsistidas += $est->presentes;
    }
}
$porcentajeTermometro = $actividadesProgramadas > 0 ? ($actividadesAsistidas / $actividadesProgramadas) * 100 : 0;
$sliderTop = 100 - $porcentajeTermometro; // 0% arriba, 100% abajo

// Determinar el humor basado en el porcentaje
$activeMoodIndex = 3;
if ($porcentajeTermometro >= 75) {
    $activeMoodIndex = 0; // Alegre
} elseif ($porcentajeTermometro >= 50) {
    $activeMoodIndex = 1; // Neutral
} elseif ($porcentajeTermometro >= 25) {
    $activeMoodIndex = 2; // Serio
}
?>
<!DOCTYPE html>

<html class="light" lang="es"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Mi Camino - Zenith Path</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700&family=Manrope:wght@400;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@100..900&family=Plus+Jakarta+Sans:wght@100..900&display=swap" rel="stylesheet"/>
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
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.02em", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["28px", {"lineHeight": "36px", "fontWeight": "700"}],
                        "headline-sm-mobile": ["18px", {"lineHeight": "24px", "fontWeight": "600"}]
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
            to { stroke-dashoffset: 0; }
        }
        .floating {
            animation: floating 4s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
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
<input class="w-full pl-12 pr-4 py-2 bg-surface-container-low border-none rounded-full text-label-md font-label-md focus:ring-2 focus:ring-primary transition-all text-center" placeholder="Buscar" type="text"/>
</div>
</div>
<div class="flex items-center gap-margin-sm">
<span class="hidden md:block font-headline-sm text-secondary">Zenith Path</span>
<a href="<?php echo URLROOT; ?>/auth/logout" onclick="return confirm('¿Seguro que deseas salir de tu cuenta?');" class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center overflow-hidden border-2 border-primary-fixed cursor-pointer block hover:ring-2 hover:ring-primary transition-all shadow-md" title="Cerrar sesión">
<img alt="User Profile" class="w-full h-full object-cover" data-alt="A professional studio headshot of a smiling user against a soft, blurred mountain landscape background. The lighting is bright and warm, reflecting a sense of progress and wellness. High-end portrait photography style with crisp detail and natural skin tones." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC4-sZziL98gyg-93o6NhBHrP9O1Mjg_PrtJ-VzMuxDcwNbPGr5nxHChNA__Afx1axDdlsUMxN0xhHaIfyQ4BJfSa1VKn5BjHv8Hso4JGk4t_9P9ByngNDbUCc2P7c1f4pRZM6NBUD-aFvlmReMobzBGytlvFkVx0doS8C7fu7znh8lOkuwi3f_zoHfXtkbgbMl8I_rcZhDiqgDqlXFzj8xwpAy8gYUn9ysa3z36Snvz1Y8nZVPo8VBtjuCETR-kIr1O9lPZ0BJzoC3"/>
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
<main class="pt-24 pb-24 px-container-padding max-w-7xl mx-auto flex flex-col items-center relative h-[calc(100vh-120px)]">
<!-- Background Elements -->
<div class="absolute inset-0 -z-10 overflow-hidden">
<!-- Floating Clouds -->
<div class="absolute top-20 left-10 floating opacity-60 flex flex-col items-center" style="animation-delay: 0s;">
<span class="material-symbols-outlined text-[64px] text-outline-variant">cloud</span>
<span class="material-symbols-outlined text-secondary -mt-6">bolt</span>
</div>
<div class="absolute top-40 right-20 floating opacity-40 flex flex-col items-center" style="animation-delay: 1s;">
<span class="material-symbols-outlined text-[80px] text-outline-variant">cloud</span>
</div>
<div class="absolute top-80 left-1/4 floating opacity-50 flex flex-col items-center" style="animation-delay: 2s;">
<span class="material-symbols-outlined text-[72px] text-outline-variant">cloud</span>
<span class="material-symbols-outlined text-secondary -mt-6">bolt</span>
</div>
</div>
<!-- Main Content Area (Mountain & Vidas) -->
<div class="relative w-full h-full flex flex-col md:flex-row items-center justify-center gap-gutter">
<!-- Central Mountain Component -->
<div class="relative w-[80vw] max-w-[1200px] h-full flex flex-col items-center justify-center">
    <svg class="w-full h-full drop-shadow-2xl" viewBox="0 0 600 800" preserveAspectRatio="xMidYMid meet">
        <defs>
            <linearGradient id="mntMain" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#64748b" /> <!-- Slate top -->
                <stop offset="30%" stop-color="#334155" />
                <stop offset="60%" stop-color="#166534" /> <!-- Green mid -->
                <stop offset="100%" stop-color="#14532d" /> <!-- Dark green base -->
            </linearGradient>
            <linearGradient id="mntHighlight" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="rgba(255,255,255,0.15)" />
                <stop offset="50%" stop-color="rgba(0,0,0,0)" />
                <stop offset="100%" stop-color="rgba(0,0,0,0.4)" />
            </linearGradient>
            <linearGradient id="snow" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#ffffff" />
                <stop offset="100%" stop-color="#e2e8f0" />
            </linearGradient>
            <filter id="softClouds" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="35" />
            </filter>
            <filter id="lightGlow" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="5" />
            </filter>
        </defs>

        <!-- Distant Mountains -->
        <path d="M -200,800 L 120,500 L 400,800 Z" fill="#0f301d" opacity="0.3"/>
        <path d="M 200,800 L 480,450 L 800,800 Z" fill="#0f301d" opacity="0.2"/>

        <!-- Main Mountain Base Shape -->
        <path d="M -150,800 L -20,680 L 30,650 L 100,580 L 150,500 L 190,450 L 220,380 L 260,300 L 280,220 L 290,150 L 300,100 L 310,180 L 320,250 L 340,350 L 380,450 L 420,520 L 480,600 L 580,680 L 750,800 Z" fill="url(#mntMain)" />
        <path d="M -150,800 L -20,680 L 30,650 L 100,580 L 150,500 L 190,450 L 220,380 L 260,300 L 280,220 L 290,150 L 300,100 L 310,180 L 320,250 L 340,350 L 380,450 L 420,520 L 480,600 L 580,680 L 750,800 Z" fill="url(#mntHighlight)" />
        
        <!-- Mountain Ridges / Details -->
        <path d="M 300,100 L 294,150 L 288,220 L 276,300 L 252,400 L 216,520 L 156,650 L 120,800 L 300,800 Z" fill="#064e3b" opacity="0.3"/>
        <path d="M 300,100 L 312,180 L 318,250 L 330,350 L 372,480 L 420,620 L 480,750 L 540,800 L 300,800 Z" fill="#022c22" opacity="0.4"/>

        <!-- Snow Cap -->
        <path d="M 300,100 L 294,150 L 288,220 L 282,260 Q 288,270 300,250 Q 312,260 318,250 L 312,180 Z" fill="url(#snow)" />

        <!-- Path Base Glow -->
        <path d="M 260,780 Q 360,700 340,650 Q 320,600 300,520 Q 250,460 260,400 Q 270,350 285,300 Q 295,250 295,210 Q 295,160 300,120" fill="none" stroke="#a1d494" stroke-linecap="round" stroke-width="12" opacity="0.3" filter="url(#lightGlow)"></path>

        <!-- The Interactive Path -->
        <path class="mountain-path" d="M 260,780 Q 360,700 340,650 Q 320,600 300,520 Q 250,460 260,400 Q 270,350 285,300 Q 295,250 295,210 Q 295,160 300,120" fill="none" id="mountainPath" stroke="#a1d494" stroke-linecap="round" stroke-width="6" stroke-dasharray="12,8"></path>

        <!-- Solid path overlay -->
        <path class="mountain-path" d="M 260,780 Q 360,700 340,650 Q 320,600 300,520 Q 250,460 260,400 Q 270,350 285,300 Q 295,250 295,210 Q 295,160 300,120" fill="none" stroke="#ffffff" stroke-linecap="round" stroke-width="2" opacity="0.8"></path>

        <!-- Waypoints (Generados con datos de prueba) -->
        <?php foreach($etapas_prueba as $etapa): ?>
            <g class="cursor-pointer hover:scale-125 transition-transform group" style="transform-origin: <?= $etapa['cx'] ?>px <?= $etapa['cy'] ?>px;">
                <title><?= htmlspecialchars($etapa['nombre']) ?> - <?= ucfirst($etapa['estado']) ?></title>
                
                <?php if (isset($etapa['is_peak']) && $etapa['is_peak']): ?>
                    <!-- Cima -->
                    <circle cx="<?= $etapa['cx'] ?>" cy="<?= $etapa['cy'] ?>" r="14" fill="<?= $etapa['estado'] === 'completado' ? '#fcd34d' : '#ffffff' ?>" stroke="#fcd34d" stroke-width="4" filter="url(#lightGlow)"></circle>
                    <circle cx="<?= $etapa['cx'] ?>" cy="<?= $etapa['cy'] ?>" r="6" fill="<?= $etapa['estado'] === 'completado' ? '#ffffff' : '#fcd34d' ?>"></circle>
                <?php else: ?>
                    <!-- Puntos normales -->
                    <?php 
                        $fillColor = '#154212';
                        $strokeColor = '#a1d494';
                        
                        if ($etapa['estado'] === 'completado') {
                            $fillColor = '#a1d494';
                            $strokeColor = '#ffffff';
                        } elseif ($etapa['estado'] === 'actual') {
                            $fillColor = '#fcd34d';
                            $strokeColor = '#ffffff';
                        } else {
                            // bloqueado
                            $fillColor = '#154212';
                            $strokeColor = '#4a7c59';
                        }
                    ?>
                    <circle cx="<?= $etapa['cx'] ?>" cy="<?= $etapa['cy'] ?>" r="10" fill="<?= $fillColor ?>" stroke="<?= $strokeColor ?>" stroke-width="3"></circle>
                    
                    <?php if ($etapa['estado'] === 'actual'): ?>
                        <circle cx="<?= $etapa['cx'] ?>" cy="<?= $etapa['cy'] ?>" r="4" fill="#154212" class="animate-pulse"></circle>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Tooltip visible on hover en SVG -->
                <text x="<?= $etapa['cx'] + 15 ?>" y="<?= $etapa['cy'] + 5 ?>" fill="#ffffff" font-size="14" font-weight="bold" class="opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-md pointer-events-none" filter="url(#lightGlow)">
                    <?= htmlspecialchars($etapa['nombre']) ?>
                </text>
            </g>
        <?php endforeach; ?>

        <!-- Clouds Obscuring the Peak and 60% of the top -->
        <g filter="url(#softClouds)" opacity="1" pointer-events="none">
            <rect x="0" y="0" width="600" height="400" fill="#f8fafc" />
            <ellipse cx="300" cy="400" rx="350" ry="100" fill="#f8fafc" />
            <ellipse cx="150" cy="450" rx="250" ry="120" fill="#ffffff" />
            <ellipse cx="450" cy="450" rx="250" ry="120" fill="#f1f5f9" />
            <ellipse cx="300" cy="500" rx="300" ry="80" fill="#ffffff" opacity="0.9"/>
            <ellipse cx="200" cy="550" rx="200" ry="60" fill="#f8fafc" opacity="0.7"/>
            <ellipse cx="400" cy="550" rx="200" ry="60" fill="#f1f5f9" opacity="0.6"/>
        </g>

        <!-- Text over clouds -->
        <text x="300" y="240" font-family="'Algerian', 'Georgia', serif" font-size="52" fill="#FFD700" text-anchor="middle" font-weight="bold" filter="drop-shadow(3px 5px 6px rgba(0,0,0,0.6))" style="letter-spacing: 2px;">
            ¡Confiamos en ti!
        </text>
    </svg>

    <!-- Characters at Base -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-4 z-10 drop-shadow-md">
        <div class="flex flex-col items-center">
            <span class="material-symbols-outlined text-[48px] text-primary-container">person</span>
            <div class="h-1.5 w-6 bg-black/30 rounded-full blur-[2px] mt-1"></div>
        </div>
        <div class="flex flex-col items-center">
            <span class="material-symbols-outlined text-[48px] text-primary">person_outline</span>
            <div class="h-1.5 w-6 bg-black/30 rounded-full blur-[2px] mt-1"></div>
        </div>
    </div>
</div>
<!-- Vidas / Mood Sidebar (Right) -->
<div class="flex flex-col items-center gap-margin-md md:absolute md:right-0 md:top-1/2 md:-translate-y-1/2 md:h-2/3 bg-surface-container-low/50 p-margin-sm rounded-full border border-outline-variant glass-panel">
<span class="text-label-md font-bold text-primary vertical-text md:-rotate-180 md:writing-mode-vertical">VIDAS</span>
<div class="flex md:flex-col items-center justify-around flex-1 w-full gap-4 py-4">
<div class="mood-btn transition-all <?= $activeMoodIndex === 0 ? '' : 'opacity-50' ?>">
<span class="material-symbols-outlined text-[32px] text-primary-container">sentiment_very_satisfied</span>
</div>
<div class="mood-btn transition-all <?= $activeMoodIndex === 1 ? '' : 'opacity-50' ?>">
<span class="material-symbols-outlined text-[32px] text-on-surface-variant">sentiment_neutral</span>
</div>
<div class="mood-btn transition-all <?= $activeMoodIndex === 2 ? '' : 'opacity-50' ?>">
<span class="material-symbols-outlined text-[32px] text-on-surface-variant">sentiment_dissatisfied</span>
</div>
<div class="mood-btn transition-all <?= $activeMoodIndex === 3 ? '' : 'opacity-50' ?>">
<span class="material-symbols-outlined text-[32px] text-outline">sentiment_very_dissatisfied</span>
</div>
<!-- Vertical Slider Visualization -->
<div class="hidden md:block w-1 bg-outline-variant rounded-full h-full relative pointer-events-none">
<div class="absolute left-1/2 -translate-x-1/2 w-4 h-4 bg-primary rounded-full shadow-md transition-all pointer-events-none" id="moodSlider" style="top: <?= $sliderTop ?>%;"></div>
</div>
</div>
</div>
</div>
</main>
<!-- BottomNavBar / Social Media -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-container-padding pb-4 pt-2 bg-surface shadow-md rounded-t-xl border-t border-secondary-container">
<div class="flex items-center gap-margin-lg">
<span class="text-label-md font-bold text-secondary">Redes sociales</span>
<div class="flex items-center gap-4">
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-90 duration-200" href="#">
<span class="material-symbols-outlined p-2 bg-secondary-container/20 rounded-full">public</span>
<span class="text-[10px] font-label-md mt-1">Mundo</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-90 duration-200" href="#">
<span class="material-symbols-outlined p-2 bg-secondary-container/20 rounded-full">share</span>
<span class="text-[10px] font-label-md mt-1">Compartir</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors active:scale-90 duration-200" href="#">
<span class="material-symbols-outlined p-2 bg-secondary-container/20 rounded-full">settings</span>
<span class="text-[10px] font-label-md mt-1">Ajustes</span>
</a>
</div>
</div>
</nav>
<script>
        // Sidebar Toggle & Tooltip Logic
        const menuBtn = document.getElementById('menuToggleBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('userSidebar');
        const tooltip = document.getElementById('sidebarTooltip');

        // Show tooltip briefly on load if on desktop
        if (window.innerWidth >= 1024 && tooltip) {
            setTimeout(() => {
                tooltip.classList.remove('opacity-0');
            }, 1000);
        }

        if (menuBtn && sidebar) {
            menuBtn.addEventListener('click', () => {
                sidebar.classList.add('force-open');
                if(tooltip) tooltip.classList.add('opacity-0'); // Hide tooltip permanently once clicked
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
            if(modal) {
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
            if(modal) {
                modal.children[0].classList.add('opacity-0');
                modal.children[1].classList.add('scale-95', 'opacity-0');
                modal.children[1].classList.remove('scale-100', 'opacity-100');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }

        // Initialize Path Animation
        document.addEventListener('DOMContentLoaded', () => {
            const paths = document.querySelectorAll('.mountain-path');
            paths.forEach(path => {
                const length = path.getTotalLength();
                path.style.strokeDasharray = length;
                path.style.strokeDashoffset = length;
            });
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