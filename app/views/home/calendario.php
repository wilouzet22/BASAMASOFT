<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$jsActividades = [];
$normalizarRutaImagenActividad = static function ($ruta) {
    if (!is_string($ruta)) return $ruta;
    return strpos($ruta, '/public/assets/img/actividades/') === false 
        ? str_replace('/assets/img/actividades/', '/public/assets/img/actividades/', $ruta) 
        : $ruta;
};
foreach ($data['actividades'] as $act) {
    $fecha = new DateTime($act->fecha_hora_inicio);
    $jsActividades[] = [
        'id' => $act->id_actividad,
        'nombre' => htmlspecialchars($act->nombre_actividad),
        'descripcion' => htmlspecialchars($act->descripcion ?? 'Sin descripción disponible.'),
        'tipo' => htmlspecialchars($act->nombre_tipo),
        'sede' => htmlspecialchars($act->nombre_sede),
        'fecha_raw' => $fecha->format('Y-m-d'),
        'fecha' => $fecha->format('d/m/Y'),
        'hora' => $fecha->format('H:i') . (!empty($act->fecha_hora_fin) ? ' — ' . date('H:i', strtotime($act->fecha_hora_fin)) : ''),
        // La imagen se obtiene exclusivamente del registro de esta actividad.
        'imagen_principal' => $normalizarRutaImagenActividad($act->imagen_principal ?? null)
    ];
}
?>

<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header / Navigation -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
            <div>
                <a href="<?php echo URLROOT; ?>" class="inline-flex items-center gap-2 text-primary font-bold text-sm hover:underline mb-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al inicio
                </a>
                <h1 class="text-headline-lg font-black text-on-surface flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-4xl">calendar_month</span>
                    Calendario de Actividades
                </h1>
                <p class="text-body-md text-on-surface-variant">Explora todos los eventos y actividades de nuestra institución.</p>
            </div>
            
            <!-- Controles / Info -->
            <div class="flex items-center gap-4 self-start md:self-auto">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                
                <!-- Selector de Vista / Info -->
                <div class="flex items-center gap-3 bg-white border border-outline-variant p-2 rounded-2xl shadow-sm">
                    <span class="flex h-3 w-3 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Modo Visitante Activo</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar: List of all activities -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                    <h3 class="font-bold text-title-md text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">list_alt</span>
                        Todas las Actividades
                    </h3>
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php if (empty($data['actividades'])): ?>
                            <p class="text-xs text-on-surface-variant text-center py-4">No hay actividades registradas.</p>
                        <?php else: ?>
                            <?php foreach ($jsActividades as $act): ?>
                                <div onclick="abrirComicModal(<?php echo htmlspecialchars(json_encode($act), ENT_QUOTES, 'UTF-8'); ?>)" 
                                     class="p-3 rounded-2xl border border-slate-100 hover:border-primary/30 hover:bg-primary/5 transition-all cursor-pointer group">
                                    <span class="inline-block text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 mb-1.5">
                                        <?php echo $act['tipo']; ?>
                                    </span>
                                    <h4 class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors line-clamp-1">
                                        <?php echo $act['nombre']; ?>
                                    </h4>
                                    <p class="text-xs text-on-surface-variant mt-1"><?php echo $act['fecha']; ?> - <?php echo $act['hora']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Decorative comic card -->
                <div class="comic-card comic-border bg-yellow-300 border-[4px] border-slate-900 rounded-[24px] p-6 shadow-[5px_5px_0px_0px_#0b1c30] rotate-[-1deg]">
                    <h4 class="font-black text-slate-900 uppercase text-lg mb-2">¡Hey! 👋</h4>
                    <p class="text-xs font-bold text-slate-800 leading-relaxed">Haz clic en cualquier actividad en el calendario o en la lista para ver los detalles completos y sus fotos en un globo de diálogo de cómic.</p>
                </div>
            </div>

            <!-- Main Calendar Grid -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-outline-variant p-6 md:p-8 shadow-sm">
                    <!-- Calendar Controls -->
                    <div class="flex items-center justify-between mb-8">
                        <button onclick="changeMonth(-1)" class="p-2 border-2 border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <h2 id="calendarMonthYear" class="text-xl md:text-2xl font-black text-on-surface uppercase tracking-tight">
                            Mes Año
                        </h2>
                        <button onclick="changeMonth(1)" class="p-2 border-2 border-outline-variant rounded-xl hover:bg-surface-container transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 gap-2 mb-4 text-center">
                        <div class="text-xs font-black uppercase text-outline py-2">Lun</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Mar</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Mié</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Jue</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Vie</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Sáb</div>
                        <div class="text-xs font-black uppercase text-outline py-2">Dom</div>
                    </div>

                    <!-- Calendar Cells -->
                    <div id="calendarCells" class="grid grid-cols-7 gap-2 min-h-[400px]">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Globo de Diálogo de Cómic -->
<div id="comicModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/45 backdrop-blur-[4px] transition-opacity duration-300 opacity-0" onclick="cerrarComicModal()"></div>
    
    <!-- Comic Bubble Wrapper -->
    <div class="relative w-full max-w-lg transition-all duration-300 transform scale-95 opacity-0 z-10">
        
        <!-- The Comic Speech Bubble -->
        <div class="comic-border-lg bg-white border-[5px] border-slate-900 rounded-[32px] p-6 md:p-8 shadow-[8px_8px_0px_0px_#0b1c30] relative overflow-visible">
            
            <!-- Close Button -->
            <button onclick="cerrarComicModal()" class="comic-border absolute -top-3 -right-3 bg-secondary text-white border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[3px_3px_0px_0px_#0b1c30] z-30 font-extrabold text-lg">
                ✕
            </button>
            
            <!-- Activity Category Badge -->
            <div class="comic-card comic-border absolute -top-6 left-8 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 px-4 py-1.5 rounded-lg rotate-[-3deg] shadow-[3px_3px_0px_0px_#0b1c30] font-black uppercase text-xs tracking-wider z-20">
                <span id="comicType">ACTIVIDAD</span>
            </div>

            <!-- Carousel -->
            <div class="comic-border relative h-60 w-full rounded-2xl border-[4px] border-slate-900 overflow-hidden mb-6 bg-slate-100 shadow-inner group">
                <div id="carouselSlides" class="absolute inset-0 flex transition-transform duration-500 ease-in-out">
                    <!-- Slides inserted dynamically -->
                </div>
                
                <button id="carouselPrevBtn" onclick="moverCarousel(-1)" class="comic-card comic-border absolute left-3 top-1/2 -translate-y-1/2 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[2px_2px_0px_0px_#0b1c30] font-black text-xl z-20 opacity-0 group-hover:opacity-100">
                    &lt;
                </button>
                <button id="carouselNextBtn" onclick="moverCarousel(1)" class="comic-card comic-border absolute right-3 top-1/2 -translate-y-1/2 bg-yellow-300 text-slate-900 border-[3px] border-slate-900 rounded-full w-10 h-10 flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-[2px_2px_0px_0px_#0b1c30] font-black text-xl z-20 opacity-0 group-hover:opacity-100">
                    &gt;
                </button>

                <div id="carouselDots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-20 bg-slate-900/60 px-3 py-1 rounded-full">
                    <!-- Dots inserted dynamically -->
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-4">
                <h3 id="comicTitle" class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight leading-none uppercase border-b-4 border-dashed border-slate-200 pb-3">
                    Nombre de Actividad
                </h3>
                
                <!-- Event info table -->
                <div class="grid grid-cols-2 gap-3 bg-yellow-50/50 border-2 border-slate-900 rounded-xl p-3 text-xs font-bold text-slate-800">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">event</span>
                        <span id="comicDate">Fecha</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">schedule</span>
                        <span id="comicTime">Hora</span>
                    </div>
                    <div class="col-span-2 flex items-center gap-2 border-t border-slate-200 pt-2">
                        <span class="material-symbols-outlined text-[16px] text-secondary">location_on</span>
                        <span id="comicSede">Sede</span>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs uppercase font-extrabold tracking-wider text-slate-400 mb-1">Sobre la actividad:</h4>
                    <p id="comicDesc" class="text-sm text-slate-700 leading-relaxed font-medium line-clamp-4">
                        Descripción...
                    </p>
                </div>
            </div>

            <!-- Ingresar Button -->
            <div class="mt-6 pt-4 border-t-4 border-dashed border-slate-200 flex justify-center">
                <a href="<?php echo URLROOT; ?>/auth/login" class="comic-border w-full text-center py-4 bg-primary text-on-primary border-[4px] border-slate-900 rounded-2xl font-black uppercase text-sm tracking-wider shadow-[4px_4px_0px_0px_#0b1c30] hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_#0b1c30] transition-all duration-150 active:scale-98">
                    ¡Ingresar al Portal!
                </a>
            </div>
        </div>
        
        <!-- Speech Bubble Tail -->
        <div class="comic-bubble-tail"></div>
    </div>
</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c4c5d5;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #757684;
}

/* Comic Book Speech Bubble Tail */
.comic-bubble-tail {
    position: absolute;
    bottom: -22px;
    left: 80px;
    width: 0;
    height: 0;
    border-width: 25px 25px 0 0;
    border-style: solid;
    border-color: #0b1c30 transparent transparent transparent;
    z-index: 5;
}
.comic-bubble-tail::after {
    content: '';
    position: absolute;
    top: -29px;
    left: 4px;
    width: 0;
    height: 0;
    border-width: 20px 20px 0 0;
    border-style: solid;
    border-color: #ffffff transparent transparent transparent;
    z-index: 6;
}

/* -------------------------------------------------------------
   CORRECCIONES PARA MODOS OSCUROS (DARK Y SÚPER OSCURO)
------------------------------------------------------------- */
/* 1. Proteger las tarjetas estilo cómic de las reglas globales (Ambos modos) */
.dark .comic-card,
.dark .comic-card h1, .dark .comic-card h2, .dark .comic-card h3, .dark .comic-card h4,
.dark .comic-card p, .dark .comic-card div, .dark .comic-card span {
    color: #0f172a !important; /* slate-900 */
}

/* (Eliminado bloque 2 anterior, reemplazado por la lógica global de abajo) */
/* 3. Bordes de comic más visibles (Ambos modos) */
.dark .comic-border {
    border-color: #94a3b8 !important; /* slate-400 */
    box-shadow: 5px 5px 0px 0px #94a3b8 !important;
}
.dark .comic-border-lg {
    border-color: #94a3b8 !important;
    box-shadow: 8px 8px 0px 0px #94a3b8 !important;
}
.dark .comic-bubble-tail {
    border-color: #94a3b8 transparent transparent transparent !important;
}

/* 4. Celdas del calendario genéricas (Diferencias específicas) */

/* ---> MODO OSCURO NORMAL <--- */
html.dark:not(.superdark) .calendar-cell:not([class*="cell-"]) {
    background-color: #1e293b !important; /* slate-800 */
    border-color: #475569 !important; /* slate-600 */
}
html.dark:not(.superdark) .calendar-cell:not([class*="cell-"]):hover {
    background-color: #334155 !important; /* slate-700 */
    border-color: #60a5fa !important; 
}
html.dark:not(.superdark) .calendar-cell-today:not([class*="cell-"]) {
    background-color: rgba(59, 130, 246, 0.15) !important; 
    border-color: #3b82f6 !important; 
}
html.dark:not(.superdark) .comic-bubble-tail::after {
    border-color: #1e293b transparent transparent transparent !important;
}

/* 5. Celdas coloreadas según evento (TODOS LOS TEMAS - Light, Dark, Superdark) */
.calendar-cell.cell-taller { background-color: #3b82f6 !important; border-color: #2563eb !important; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4) !important; }
.calendar-cell.cell-taller:hover { background-color: #2563eb !important; border-color: #1d4ed8 !important; }

.calendar-cell.cell-feria { background-color: #a855f7 !important; border-color: #9333ea !important; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(168, 85, 247, 0.4) !important; }
.calendar-cell.cell-feria:hover { background-color: #9333ea !important; border-color: #7e22ce !important; }

.calendar-cell.cell-reunion { background-color: #f97316 !important; border-color: #ea580c !important; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.4) !important; }
.calendar-cell.cell-reunion:hover { background-color: #ea580c !important; border-color: #c2410c !important; }

.calendar-cell.cell-convivencia { background-color: #22c55e !important; border-color: #16a34a !important; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.4) !important; }
.calendar-cell.cell-convivencia:hover { background-color: #16a34a !important; border-color: #15803d !important; }

.calendar-cell.cell-default { background-color: #64748b !important; border-color: #475569 !important; color: #ffffff !important; box-shadow: 0 4px 6px -1px rgba(100, 116, 139, 0.4) !important; }
.calendar-cell.cell-default:hover { background-color: #475569 !important; border-color: #334155 !important; }

/* 6. Asegurar que el número del día sea blanco en todas estas celdas y la tarjeta contrasta */
.calendar-cell.cell-taller span.day-number-text, 
.calendar-cell.cell-feria span.day-number-text, 
.calendar-cell.cell-reunion span.day-number-text, 
.calendar-cell.cell-convivencia span.day-number-text, 
.calendar-cell.cell-default span.day-number-text {
    color: #ffffff !important;
}

/* 7. Forzar colores de la micro-tarjeta para que resalten como botones blancos con texto de color sobre los cuadros vivos */
.event-badge { background-color: #ffffff !important; font-weight: 900 !important; }
.event-badge:hover { background-color: #f8fafc !important; transform: scale(1.05); }
.event-badge.taller { color: #1e40af !important; border-color: #bfdbfe !important; } /* blue-800 */
.event-badge.feria { color: #6b21a8 !important; border-color: #e9d5ff !important; } /* purple-800 */
.event-badge.reunion { color: #9a3412 !important; border-color: #fed7aa !important; } /* orange-800 */
.event-badge.convivencia { color: #166534 !important; border-color: #bbf7d0 !important; } /* green-800 */
.event-badge.default { color: #334155 !important; border-color: #e2e8f0 !important; } /* slate-800 */

/* ---> MODO SÚPER OSCURO <--- */
.superdark .calendar-cell:not([class*="cell-"]) {
    background-color: #111827 !important; /* slate-900 */
    border-color: #374151 !important; /* slate-700 */
}
.superdark .calendar-cell:not([class*="cell-"]):hover {
    background-color: #1f2937 !important; /* slate-800 */
    border-color: #60a5fa !important; /* blue-400 */
}
.superdark .calendar-cell-today:not([class*="cell-"]) {
    background-color: rgba(59, 130, 246, 0.1) !important; /* blue-500 con opacidad */
    border-color: #3b82f6 !important; /* blue-500 */
}
.superdark .comic-bubble-tail::after {
    border-color: #0d0d0d transparent transparent transparent !important;
}
</style>

<script>
// Load events from PHP
const allEvents = <?php echo json_encode($jsActividades); ?>;

// Calendar state
let currentDate = new Date();
// If we have events, default to the month of the first event so it's not empty!
if (allEvents.length > 0) {
    currentDate = new Date(allEvents[0].fecha_raw + 'T00:00:00');
}
let currentYear = currentDate.getFullYear();
let currentMonth = currentDate.getMonth();

const monthsSpanish = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
];

const categoryColorMap = {
    'Taller': { 
        bg: 'event-badge taller',
        cellBg: 'cell-taller'
    },
    'Feria': { 
        bg: 'event-badge feria',
        cellBg: 'cell-feria'
    },
    'Reunión': { 
        bg: 'event-badge reunion',
        cellBg: 'cell-reunion'
    },
    'Convivencia': { 
        bg: 'event-badge convivencia',
        cellBg: 'cell-convivencia'
    },
    'default': { 
        bg: 'event-badge default',
        cellBg: 'cell-default'
    }
};

function getBadgeColors(tipo) {
    for (const key in categoryColorMap) {
        if (tipo.toLowerCase().includes(key.toLowerCase())) {
            return categoryColorMap[key];
        }
    }
    return categoryColorMap['default'];
}

function renderCalendar() {
    const cellsContainer = document.getElementById('calendarCells');
    const monthYearTitle = document.getElementById('calendarMonthYear');
    cellsContainer.innerHTML = '';
    
    // Set title
    monthYearTitle.textContent = `${monthsSpanish[currentMonth]} ${currentYear}`;
    
    // First day of the month
    const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
    // In JS getDay() starts on Sunday (0). We want Monday to be index 0:
    // Sunday (0) -> 6
    // Monday (1) -> 0
    // Tuesday (2) -> 1 ...
    const adjustedFirstDayIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;
    
    // Total days in month
    const totalDays = new Date(currentYear, currentMonth + 1, 0).getDate();
    // Total days in previous month
    const prevMonthTotalDays = new Date(currentYear, currentMonth, 0).getDate();
    
    // 1. Render empty days from previous month
    for (let i = adjustedFirstDayIndex; i > 0; i--) {
        const dayNum = prevMonthTotalDays - i + 1;
        const cell = document.createElement('div');
        cell.className = 'calendar-cell bg-slate-50/50 border border-slate-100 text-slate-300 rounded-xl p-2 min-h-[90px] flex flex-col justify-between opacity-50 select-none';
        cell.innerHTML = `<span class="text-xs font-bold">${dayNum}</span>`;
        cellsContainer.appendChild(cell);
    }
    
    // 2. Render actual days of current month
    const today = new Date();
    const isCurrentMonthYear = today.getFullYear() === currentYear && today.getMonth() === currentMonth;
    
    for (let day = 1; day <= totalDays; day++) {
        const cell = document.createElement('div');
        
        // Format date string for search: YYYY-MM-DD
        const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = allEvents.filter(e => e.fecha_raw === formattedDate);
        
        let cellClass = 'calendar-cell bg-white border border-outline-variant rounded-xl p-2 min-h-[90px] flex flex-col justify-between hover:shadow-sm transition-all group';
        if (isCurrentMonthYear && today.getDate() === day) {
            cellClass = 'calendar-cell calendar-cell-today bg-primary/5 border-2 border-primary rounded-xl p-2 min-h-[90px] flex flex-col justify-between hover:shadow-md transition-all group';
        }
        
        let dayTextColorClass = (isCurrentMonthYear && today.getDate() === day) ? 'text-primary' : 'text-on-surface';
        
        if (dayEvents.length > 0) {
            const mainEventColors = getBadgeColors(dayEvents[0].tipo);
            cellClass = cellClass.replace('bg-white', '').replace('bg-primary/5', '').replace('border-outline-variant', '');
            cellClass += ` cursor-pointer ${mainEventColors.cellBg}`;
            cell.onclick = () => abrirComicModal(dayEvents[0]);
            
            dayTextColorClass = 'day-number-text';
        }
        
        cell.className = cellClass;
        
        // Day number structure
        let html = `<span class="text-xs font-black ${dayTextColorClass}">${day}</span>`;
        
        // Plot events
        if (dayEvents.length > 0) {
            html += `<div class="flex flex-col gap-1.5 mt-2 overflow-y-auto max-h-[60px] custom-scrollbar">`;
            dayEvents.forEach(evt => {
                const colors = getBadgeColors(evt.tipo);
                html += `
                    <div onclick="event.stopPropagation(); abrirComicModal(${JSON.stringify(evt).replace(/"/g, '&quot;')})" 
                         class="text-[9px] md:text-[10px] px-1.5 py-0.5 rounded border-l-2 font-black truncate cursor-pointer transition-all hover:scale-[1.02] ${colors.bg}">
                        ${evt.nombre}
                    </div>
                `;
            });
            html += `</div>`;
        }
        
        cell.innerHTML = html;
        cellsContainer.appendChild(cell);
    }
    
    // 3. Render empty days from next month to fill grid (usually 42 grid cells total)
    const totalRendered = adjustedFirstDayIndex + totalDays;
    const remainingCells = 42 - totalRendered;
    for (let day = 1; day <= remainingCells; day++) {
        const cell = document.createElement('div');
        cell.className = 'calendar-cell bg-slate-50/50 border border-slate-100 text-slate-300 rounded-xl p-2 min-h-[90px] flex flex-col justify-between opacity-50 select-none';
        cell.innerHTML = `<span class="text-xs font-bold">${day}</span>`;
        cellsContainer.appendChild(cell);
    }
}

function changeMonth(dir) {
    currentMonth += dir;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear -= 1;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear += 1;
    }
    renderCalendar();
}

// Initial render
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
});

// Modal and Carousel Logic (identical to landing view)
let currentSlideIndex = 0;
let modalSlides = [];

function abrirComicModal(act) {
    document.getElementById('comicTitle').textContent = act.nombre;
    document.getElementById('comicDesc').textContent = act.descripcion;
    document.getElementById('comicType').textContent = act.tipo;
    document.getElementById('comicSede').textContent = act.sede;
    document.getElementById('comicDate').textContent = act.fecha;
    document.getElementById('comicTime').textContent = act.hora;

    const slidesContainer = document.getElementById('carouselSlides');
    const dotsContainer = document.getElementById('carouselDots');
    slidesContainer.innerHTML = '';
    dotsContainer.innerHTML = '';
    
    const fotos = act.imagen_principal ? [act.imagen_principal] : [];
    
    modalSlides = fotos;
    currentSlideIndex = 0;

    if (fotos.length === 0) {
        slidesContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-center px-6 text-slate-500 font-bold text-sm">Esta actividad aún no tiene una imagen principal registrada.</div>';
    }

    fotos.forEach((foto, i) => {
        const slide = document.createElement('div');
        slide.className = 'w-full h-full flex-shrink-0';
        slide.innerHTML = `<img src="${foto}" class="w-full h-full object-contain" alt="Foto ${i+1}">`;
        slidesContainer.appendChild(slide);

        const dot = document.createElement('button');
        dot.className = `w-2 h-2 rounded-full transition-all ${i === 0 ? 'bg-yellow-300 scale-125' : 'bg-slate-400'}`;
        dot.onclick = () => irASlide(i);
        dotsContainer.appendChild(dot);
    });

    const prevBtn = document.getElementById('carouselPrevBtn');
    const nextBtn = document.getElementById('carouselNextBtn');
    if (fotos.length <= 1) {
        prevBtn.classList.add('hidden');
        nextBtn.classList.add('hidden');
        dotsContainer.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
        nextBtn.classList.remove('hidden');
        dotsContainer.classList.remove('hidden');
    }

    actualizarCarousel();

    const modal = document.getElementById('comicModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-black\\/45').classList.remove('opacity-0');
        modal.querySelector('.bg-black\\/45').classList.add('opacity-100');
        modal.querySelector('.relative.max-w-lg').classList.remove('scale-95', 'opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function cerrarComicModal() {
    const modal = document.getElementById('comicModal');
    if (modal) {
        modal.querySelector('.bg-black\\/45').classList.add('opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.add('scale-95', 'opacity-0');
        modal.querySelector('.relative.max-w-lg').classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

function irASlide(index) {
    currentSlideIndex = index;
    actualizarCarousel();
}

function moverCarousel(dir) {
    currentSlideIndex = (currentSlideIndex + dir + modalSlides.length) % modalSlides.length;
    actualizarCarousel();
}

function actualizarCarousel() {
    const slidesContainer = document.getElementById('carouselSlides');
    slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;

    const dots = document.getElementById('carouselDots').children;
    for (let i = 0; i < dots.length; i++) {
        if (i === currentSlideIndex) {
            dots[i].className = 'w-2.5 h-2.5 rounded-full transition-all bg-yellow-300 scale-125';
        } else {
            dots[i].className = 'w-2 h-2 rounded-full transition-all bg-slate-400';
        }
    }
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
