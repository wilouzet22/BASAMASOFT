<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITENAME : SITENAME; ?></title>

    <!-- Script Anti-Parpadeo Temas (Claro, Oscuro, Súper Oscuro) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'superdark') {
                document.documentElement.classList.add('dark', 'superdark');
            } else if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('superdark');
            } else {
                document.documentElement.classList.remove('dark', 'superdark');
            }
        })();
    </script>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-fixed": "#002113",
                        "inverse-surface": "#213145",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#d3e4fe",
                        "on-tertiary-container": "#3fd298",
                        "on-primary-container": "#a8b8ff",
                        "inverse-on-surface": "#eaf1ff",
                        "primary": "#00288e",
                        "on-secondary": "#ffffff",
                        "surface-container-high": "#dce9ff",
                        "on-surface-variant": "#444653",
                        "surface": "#f8f9ff",
                        "on-background": "#0b1c30",
                        "primary-fixed": "#dde1ff",
                        "inverse-primary": "#b8c4ff",
                        "outline-variant": "#c4c5d5",
                        "surface-variant": "#d3e4fe",
                        "on-primary": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "surface-dim": "#cbdbf5",
                        "surface-container": "#e5eeff",
                        "primary-container": "#1e40af",
                        "error": "#ba1a1a",
                        "secondary": "#a93349",
                        "surface-tint": "#3755c3",
                        "secondary-fixed-dim": "#ffb2b9",
                        "primary-fixed-dim": "#b8c4ff",
                        "tertiary-fixed-dim": "#4edea3",
                        "secondary-container": "#fe7488",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#0b1c30",
                        "on-secondary-fixed-variant": "#891933",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-primary-fixed": "#001453",
                        "on-secondary-container": "#730425",
                        "outline": "#757684",
                        "on-secondary-fixed": "#400010",
                        "surface-container-low": "#eff4ff",
                        "tertiary-container": "#00563a",
                        "tertiary": "#003d27",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#173bab",
                        "secondary-fixed": "#ffdadc",
                        "tertiary-fixed": "#6ffbbe",
                        "surface-bright": "#f8f9ff",
                        "error-container": "#ffdad6",
                        "background": "#f8f9ff"
                    },
                    "borderRadius": {
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "lexend": ["Lexend"],
                    }
                }
            }
        }
    </script>
    <style>
        /* ================================================================
           RESPONSIVE GLOBAL — Aplicado a todo el sitio
        ================================================================ */

        /* Viewport fix para evitar overflow horizontal en móvil */
        *, *::before, *::after { box-sizing: border-box; }
        html { overflow-x: hidden; }

        /* Tablas: scroll horizontal en móvil */
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table { min-width: 500px; }
        .overflow-x-auto table { min-width: 0; }

        /* Imágenes fluidas */
        img { max-width: 100%; height: auto; }

        /* Botones y links full-width en móvil cuando se usan en bloque */
        @media (max-width: 767px) {
            /* Sidebar: siempre oculto hasta activar por hamburguesa */
            #admin-sidebar, #docentes-sidebar {
                transform: translateX(-100%);
            }

            /* Contenido principal sin margen del sidebar en móvil */
            #main-content-wrap {
                margin-left: 0 !important;
                width: 100%;
            }

            /* Tablas: hacer que cada fila sea un bloque en móvil (card mode) para tablas simples */
            .mobile-card-table thead { display: none; }
            .mobile-card-table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                padding: 0.75rem;
                background: white;
            }
            .mobile-card-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem 0.25rem;
                border: none !important;
                font-size: 0.8125rem;
            }
            .mobile-card-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                margin-right: 0.5rem;
                flex-shrink: 0;
            }

            /* Grids: forzar 1 columna en móvil si no tienen clase específica */
            .auto-grid {
                grid-template-columns: 1fr !important;
            }

            /* Headers de página: texto más pequeño */
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.25rem !important; }

            /* Padding reducido en móvil */
            .mobile-p-reduced { padding: 1rem !important; }

            /* Flex que se apila en móvil */
            .mobile-stack {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            /* Ocultar texto de labels en navbars */
            .mobile-icon-only span:not(.material-symbols-outlined) { display: none; }

            /* Footer margen en móvil */
            footer { padding-bottom: 5rem; } /* espacio para bottom nav */
        }

        /* Breakpoint tablet (768px - 1024px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            #main-content-wrap {
                margin-left: 4rem; /* sidebar colapsado por defecto en tablet */
            }
        }

        /* Bottom navigation bar para móvil — solo visible en <768px */
        #mobile-bottom-nav {
            display: none;
        }
        @media (max-width: 767px) {
            #mobile-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 50;
                background: white;
                border-top: 1px solid #e2e8f0;
                padding: 0.5rem 0;
                padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
                box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
            }
        }
        .dark #mobile-bottom-nav { background: #1e293b !important; border-color: #334155 !important; }
        .superdark #mobile-bottom-nav { background: #0d0d0d !important; border-color: #262626 !important; }

        /* Responsive: cards en grid que se adaptan */
        @media (max-width: 479px) {
            .grid-cols-2 { grid-template-columns: 1fr; }
            .grid-cols-3 { grid-template-columns: 1fr; }
        }
        @media (min-width: 480px) and (max-width: 767px) {
            .grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        }

        /* Contenedores max-width fluid en móvil */
        @media (max-width: 767px) {
            .max-w-7xl, .max-w-\[1280px\] { padding-left: 1rem; padding-right: 1rem; }
        }

        body, .animated-bg, .graph-paper-bg, .bg-gray-50, .bg-background {
            font-family: "Lexend", sans-serif;
            transition: background-color 0.2s ease, color 0.2s ease;
            background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 25%, #e5eeff 50%, #dde1ff 75%, #eef1ff 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            position: relative;
            min-height: 100vh;
        }
        
        .graph-paper-bg {
            background-image: linear-gradient(#e5eeff 1px, transparent 1px),
                              linear-gradient(90deg, #e5eeff 1px, transparent 1px);
            background-size: 32px 32px;
        }

        body::before, .animated-bg::before, .graph-paper-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 250px 250px at 15% 20%, rgba(99,102,241,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 350px 200px at 80% 70%, rgba(169,51,73,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 300px 300px at 50% 50%, rgba(55,85,195,0.07) 0%, transparent 70%);
            animation: blobMove 18s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }
        body::after, .animated-bg::after, .graph-paper-bg::after {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 220px 220px at 75% 15%, rgba(99,102,241,0.09) 0%, transparent 70%),
                radial-gradient(ellipse 260px 160px at 25% 80%, rgba(169,51,73,0.07) 0%, transparent 70%);
            animation: blobMove 22s ease-in-out infinite alternate-reverse;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes blobMove {
            0%   { transform: translate(0px, 0px) scale(1); }
            33%  { transform: translate(30px, -20px) scale(1.05); }
            66%  { transform: translate(-20px, 15px) scale(0.97); }
            100% { transform: translate(10px, -10px) scale(1.02); }
        }

        /* 1. MODO OSCURO (Slate / Azul Oscuro) - Alto Contraste */
        .dark {
            color-scheme: dark;
        }
        .dark body, .dark .animated-bg, .dark .graph-paper-bg, .dark .bg-gray-50, .dark .bg-background {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 25%, #0f172a 50%, #1e293b 75%, #0f172a 100%) !important;
            background-size: 400% 400% !important;
            animation: gradientShift 12s ease infinite !important;
            color: #f8fafc !important;
        }
        .dark body::before, .dark .animated-bg::before, .dark .graph-paper-bg::before {
            background:
                radial-gradient(ellipse 250px 250px at 15% 20%, rgba(147,197,253,0.12) 0%, transparent 70%),
                radial-gradient(ellipse 350px 200px at 80% 70%, rgba(252,165,165,0.09) 0%, transparent 70%),
                radial-gradient(ellipse 300px 300px at 50% 50%, rgba(110,231,183,0.08) 0%, transparent 70%) !important;
        }
        .dark body::after, .dark .animated-bg::after, .dark .graph-paper-bg::after {
            background:
                radial-gradient(ellipse 220px 220px at 75% 15%, rgba(147,197,253,0.1) 0%, transparent 70%),
                radial-gradient(ellipse 260px 160px at 25% 80%, rgba(252,165,165,0.08) 0%, transparent 70%) !important;
        }
        .dark .graph-paper-bg {
            background-image: linear-gradient(#1e293b 1px, transparent 1px),
            linear-gradient(90deg, #1e293b 1px, transparent 1px) !important;
        }
        .dark nav, 
        .dark header, 
        .dark footer, 
        .dark .bg-white,
        .dark .bg-surface,
        .dark .docked {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border-color: #334155 !important;
        }

        /* Forzar texto claro en absolutamente todos los elementos para Modo Oscuro */
        .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6,
        .dark p, .dark div, .dark label, .dark td, .dark th,
        .dark a, .dark li, .dark strong, .dark b, .dark dt, .dark dd, .dark i {
            color: #f8fafc !important;
        }
        /* Spans genéricos sin clase de badge */
        .dark span:not([class*="bg-green-"]):not([class*="bg-red-"]):not([class*="bg-blue-"]):not([class*="bg-yellow-"]):not([class*="bg-orange-"]):not([class*="bg-purple-"]):not([class*="bg-indigo-"]):not([class*="bg-emerald-"]):not([class*="bg-amber-"]):not([class*="bg-teal-"]):not([class*="text-green-"]):not([class*="text-red-"]):not([class*="text-blue-"]) {
            color: #f8fafc !important;
        }
        .dark .text-on-surface-variant,
        .dark .text-gray-400,
        .dark .text-gray-500,
        .dark .text-gray-600,
        .dark .text-gray-700,
        .dark .text-[#444653],
        .dark .text-[#4c739a] {
            color: #cbd5e1 !important;
        }
        .dark .text-on-surface,
        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-[#0b1c30],
        .dark .text-[#0d141b] {
            color: #ffffff !important;
        }

        /* Colores de acento destacados y claros */
        .dark .text-primary { color: #93c5fd !important; }
        .dark .text-secondary { color: #fca5a5 !important; }
        .dark .text-tertiary { color: #6ee7b7 !important; }

        .dark table {
            color: #ffffff !important;
        }
        .dark th {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border-color: #334155 !important;
        }
        .dark td {
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark tr:hover {
            background-color: #334155 !important;
        }
        .dark input, .dark select, .dark textarea {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border-color: #475569 !important;
        }
        .dark input::placeholder, .dark textarea::placeholder {
            color: #94a3b8 !important;
        }
        .dark .border-outline-variant,
        .dark .border-[#cfdbe7],
        .dark .border-gray-200,
        .dark .border-gray-300 {
            border-color: #334155 !important;
        }
        .dark .bg-slate-50,
        .dark .bg-slate-100,
        .dark .bg-gray-50,
        .dark .bg-gray-100,
        .dark .bg-surface-container,
        .dark .bg-surface-container-low,
        .dark .bg-surface-container-high {
            background-color: #1e293b !important;
        }

        /* Proteger badges de color (chips/pills) en Modo Oscuro */
        .dark .bg-green-100 { background-color: #16a34a !important; }
        .dark .text-green-800, .dark .text-green-700, .dark .text-green-600 { color: #dcfce7 !important; }
        .dark .bg-red-100 { background-color: #dc2626 !important; }
        .dark .text-red-800, .dark .text-red-700, .dark .text-red-600 { color: #fee2e2 !important; }
        .dark .bg-blue-50, .dark .bg-blue-100 { background-color: #1d4ed8 !important; }
        .dark .text-blue-800, .dark .text-blue-700, .dark .text-blue-600 { color: #dbeafe !important; }
        .dark .bg-yellow-100 { background-color: #ca8a04 !important; }
        .dark .text-yellow-800, .dark .text-yellow-700 { color: #fef9c3 !important; }
        .dark .bg-orange-100 { background-color: #ea580c !important; }
        .dark .text-orange-800, .dark .text-orange-700 { color: #ffedd5 !important; }
        .dark .bg-purple-100 { background-color: #9333ea !important; }
        .dark .text-purple-800, .dark .text-purple-700 { color: #f3e8ff !important; }
        .dark .bg-emerald-100 { background-color: #059669 !important; }
        .dark .text-emerald-800, .dark .text-emerald-700 { color: #d1fae5 !important; }

        /* 2. MODO SÚPER OSCURO (Negro Puro AMOLED + Letras Blancas) */
        .superdark body, .superdark .animated-bg, .superdark .graph-paper-bg, .superdark .bg-gray-50, .superdark .bg-background {
            background: linear-gradient(135deg, #000000 0%, #080c14 30%, #000000 70%, #0d0614 100%) !important;
            background-size: 400% 400% !important;
            animation: gradientShift 12s ease infinite !important;
            color: #ffffff !important;
        }
        .superdark body::before, .superdark .animated-bg::before, .superdark .graph-paper-bg::before {
            background:
                radial-gradient(ellipse 200px 200px at 15% 20%, rgba(147,197,253,0.06) 0%, transparent 70%),
                radial-gradient(ellipse 300px 150px at 80% 70%, rgba(252,165,165,0.04) 0%, transparent 70%) !important;
        }
        .superdark body::after, .superdark .animated-bg::after, .superdark .graph-paper-bg::after {
            background:
                radial-gradient(ellipse 180px 180px at 75% 15%, rgba(147,197,253,0.05) 0%, transparent 70%),
                radial-gradient(ellipse 220px 120px at 25% 80%, rgba(252,165,165,0.03) 0%, transparent 70%) !important;
        }
        .superdark .graph-paper-bg {
            background-image: linear-gradient(#1a1a1a 1px, transparent 1px),
            linear-gradient(90deg, #1a1a1a 1px, transparent 1px) !important;
        }
        .superdark nav, 
        .superdark header, 
        .superdark footer, 
        .superdark .bg-white,
        .superdark .bg-surface,
        .superdark .docked {
            background-color: #0d0d0d !important;
            color: #ffffff !important;
            border-color: #262626 !important;
        }

        /* Forzar texto blanco brillante en absolutamente todos los elementos para Súper Oscuro */
        .superdark h1, .superdark h2, .superdark h3, .superdark h4, .superdark h5, .superdark h6,
        .superdark p, .superdark div, .superdark label, .superdark td, .superdark th,
        .superdark a, .superdark li, .superdark strong, .superdark b, .superdark dt, .superdark dd, .superdark i {
            color: #ffffff !important;
        }
        /* Spans genéricos sin clase de badge para Súper Oscuro */
        .superdark span:not([class*="bg-green-"]):not([class*="bg-red-"]):not([class*="bg-blue-"]):not([class*="bg-yellow-"]):not([class*="bg-orange-"]):not([class*="bg-purple-"]):not([class*="bg-indigo-"]):not([class*="bg-emerald-"]):not([class*="bg-amber-"]):not([class*="bg-teal-"]):not([class*="text-green-"]):not([class*="text-red-"]):not([class*="text-blue-"]) {
            color: #ffffff !important;
        }
        .superdark .text-on-surface-variant,
        .superdark .text-gray-400,
        .superdark .text-gray-500,
        .superdark .text-gray-600,
        .superdark .text-gray-700,
        .superdark .text-[#444653],
        .superdark .text-[#4c739a] {
            color: #e5e5e5 !important;
        }
        .superdark .text-on-surface,
        .superdark .text-gray-900,
        .superdark .text-gray-800,
        .superdark .text-[#0b1c30],
        .superdark .text-[#0d141b] {
            color: #ffffff !important;
        }

        /* Colores de acento ultra visibles para súper oscuro */
        .superdark .text-primary { color: #60a5fa !important; }
        .superdark .text-secondary { color: #f87171 !important; }
        .superdark .text-tertiary { color: #34d399 !important; }

        .superdark th {
            background-color: #000000 !important;
            color: #ffffff !important;
            border-color: #262626 !important;
        }
        .superdark td {
            border-color: #262626 !important;
            color: #ffffff !important;
        }
        .superdark tr:hover {
            background-color: #1a1a1a !important;
        }
        .superdark input, .superdark select, .superdark textarea {
            background-color: #0a0a0a !important;
            color: #ffffff !important;
            border-color: #333333 !important;
        }
        .superdark input::placeholder, .superdark textarea::placeholder {
            color: #a3a3a3 !important;
        }
        .superdark .border-outline-variant,
        .superdark .border-[#cfdbe7],
        .superdark .border-gray-200,
        .superdark .border-gray-300 {
            border-color: #262626 !important;
        }
        .superdark .bg-slate-50,
        .superdark .bg-slate-100,
        .superdark .bg-gray-50,
        .superdark .bg-gray-100,
        .superdark .bg-surface-container,
        .superdark .bg-surface-container-low,
        .superdark .bg-surface-container-high {
            background-color: #0d0d0d !important;
        }

        /* Proteger badges de color (chips/pills) en Modo Súper Oscuro */
        .superdark .bg-green-100 { background-color: #16a34a !important; }
        .superdark .text-green-800, .superdark .text-green-700, .superdark .text-green-600 { color: #dcfce7 !important; }
        .superdark .bg-red-100 { background-color: #dc2626 !important; }
        .superdark .text-red-800, .superdark .text-red-700, .superdark .text-red-600 { color: #fee2e2 !important; }
        .superdark .bg-blue-50, .superdark .bg-blue-100 { background-color: #1d4ed8 !important; }
        .superdark .text-blue-800, .superdark .text-blue-700, .superdark .text-blue-600 { color: #dbeafe !important; }
        .superdark .bg-yellow-100 { background-color: #ca8a04 !important; }
        .superdark .text-yellow-800, .superdark .text-yellow-700 { color: #fef9c3 !important; }
        .superdark .bg-orange-100 { background-color: #ea580c !important; }
        .superdark .text-orange-800, .superdark .text-orange-700 { color: #ffedd5 !important; }
        .superdark .bg-purple-100 { background-color: #9333ea !important; }
        .superdark .text-purple-800, .superdark .text-purple-700 { color: #f3e8ff !important; }
        .superdark .bg-emerald-100 { background-color: #059669 !important; }
        .superdark .text-emerald-800, .superdark .text-emerald-700 { color: #d1fae5 !important; }
    </style>
    <?php if (!empty($extraStyles)) echo $extraStyles; ?>
</head>
<?php $bodyClass = $bodyClass ?? 'bg-gray-50'; ?>
<body class="<?php echo $bodyClass; ?>">
