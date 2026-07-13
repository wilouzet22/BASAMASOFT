<?php
$data       = $data ?? [];
$globales   = $data['globales']  ?? (object)['total'=>0,'asistidas'=>0,'inasistencias'=>0,'porcentaje'=>0];
$por_tipo   = $data['por_tipo']  ?? [];
$por_mes    = $data['por_mes']   ?? [];
$racha      = $data['racha']     ?? 0;
$asistencias= $data['asistencias'] ?? [];
$estudiantes= $data['estudiantes'] ?? [];

$bodyClass   = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen overflow-x-hidden';
$extraStyles = '
<style>
    .floating { animation: floating 4s ease-in-out infinite; }
    @keyframes floating {
        0%   { transform: translateY(0px); }
        50%  { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }
    .force-open { transform: translateX(0%) !important; }
    @media (min-width: 1024px) {
        body.sidebar-collapsed #userSidebar         { width: 5.5rem; }
        body.sidebar-collapsed #mainContent         { margin-left: 5.5rem; }
        body.sidebar-collapsed .sidebar-text        { display: none !important; }
        body.sidebar-collapsed .sidebar-search-container { display: none !important; }
        body.sidebar-collapsed .sidebar-profile-info{ display: none !important; }
        body.sidebar-collapsed .sidebar-header      { padding-left: 0.75rem; padding-right: 0.75rem; padding-top: 4rem; }
        body.sidebar-collapsed .sidebar-logo-container { flex-direction: column; gap: 0.25rem; }
        body.sidebar-collapsed .sidebar-item-link   { padding-left: 0; padding-right: 0; justify-content: center; }
        body.sidebar-collapsed #collapseSidebarBtn span { transform: rotate(180deg); }
    }
    .stat-card { transition: transform .2s, box-shadow .2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .progress-bar { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
    .badge-fire { background: linear-gradient(135deg, #f97316, #ef4444); }
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
    /* Animacion escalera helicoptero para items del submenu */
    @keyframes submenu-drop {
        0%   { opacity: 0; transform: translateY(-18px); }
        60%  { opacity: 1; transform: translateY(4px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    #asistenciaSubmenu.open .submenu-item {
        animation: submenu-drop 0.32s cubic-bezier(0.34,1.56,0.64,1) both;
    }
</style>
';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="lg:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <span class="font-bold text-primary text-lg">Mis Puntos</span>
    <button id="menuToggleBtn" class="p-2 text-on-surface-variant hover:bg-surface-container-low rounded-full transition-colors">
        <span class="material-symbols-outlined">menu</span>
    </button>
</header>

<div class="flex">
    <!-- ====== SIDEBAR ====== -->
    <nav id="userSidebar" class="flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-40 transition-all duration-300 -translate-x-full lg:translate-x-0">

        <!-- Collapse Button -->
        <button id="collapseSidebarBtn" class="absolute top-4 left-4 z-50 p-1.5 rounded-xl bg-surface-container-low hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-all duration-200 shadow-sm" title="Colapsar menú">
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
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino" style="animation-delay:0ms">
                        <span class="material-symbols-outlined flex-shrink-0">mountain_flag</span>
                        <span class="font-medium text-sm sidebar-text">Camino de Montaña</span>
                    </a>
                    <a class="submenu-item sidebar-item-link bg-primary text-on-primary shadow-sm rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/puntos" style="animation-delay:80ms">
                        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings:'FILL' 1;">workspace_premium</span>
                        <span class="font-medium text-sm sidebar-text">Mis Puntos</span>
                    </a>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino#contactos" style="animation-delay:160ms">
                        <span class="material-symbols-outlined flex-shrink-0">group</span>
                        <span class="font-medium text-sm sidebar-text">Contáctanos</span>
                    </a>
                    <a class="submenu-item sidebar-item-link text-on-surface-variant hover:bg-primary/5 hover:text-primary rounded-2xl px-4 py-3 flex items-center gap-3 transition-all" href="<?php echo URLROOT; ?>/padres/camino#opinion" style="animation-delay:240ms">
                        <span class="material-symbols-outlined flex-shrink-0">chat_bubble</span>
                        <span class="font-medium text-sm sidebar-text">Opinión</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <div class="p-4 border-t border-outline-variant flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary">person</span>
            </div>
            <div class="sidebar-profile-info overflow-hidden">
                <p class="text-sm font-bold text-on-surface truncate"><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Familia'); ?></p>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="return confirm('¿Seguro que deseas salir?');" class="text-xs text-error hover:underline">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <!-- ====== MAIN CONTENT ====== -->
    <main id="mainContent" class="flex-1 lg:ml-72 transition-all duration-300 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

            <!-- Page Header -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings:'FILL' 1;">workspace_premium</span>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-on-surface">Mis Puntos</h1>
                    <p class="text-on-surface-variant text-sm">Resumen de tu progreso y participación</p>
                </div>
            </div>

            <!-- ===== KPI CARDS ===== -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Porcentaje global -->
                <div class="stat-card bg-white rounded-2xl border border-outline-variant p-5 flex flex-col gap-2 col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Asistencia Global</span>
                        <span class="material-symbols-outlined text-emerald-500">verified</span>
                    </div>
                    <p class="text-5xl font-black text-emerald-600"><?= $globales->porcentaje ?>%</p>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="progress-bar h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full" style="width:0%" data-target="<?= $globales->porcentaje ?>"></div>
                    </div>
                    <p class="text-xs text-on-surface-variant"><?= $globales->asistidas ?> de <?= $globales->total ?> actividades</p>
                </div>

                <!-- Racha -->
                <div class="stat-card bg-white rounded-2xl border border-outline-variant p-5 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Racha Actual</span>
                        <span class="material-symbols-outlined text-orange-500">local_fire_department</span>
                    </div>
                    <p class="text-5xl font-black text-orange-500"><?= $racha ?></p>
                    <p class="text-xs text-on-surface-variant">días consecutivos</p>
                </div>

                <!-- Asistidas -->
                <div class="stat-card bg-white rounded-2xl border border-outline-variant p-5 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Asistidas</span>
                        <span class="material-symbols-outlined text-blue-500">check_circle</span>
                    </div>
                    <p class="text-5xl font-black text-blue-600"><?= $globales->asistidas ?></p>
                    <p class="text-xs text-on-surface-variant">actividades completadas</p>
                </div>

                <!-- Inasistencias -->
                <div class="stat-card bg-white rounded-2xl border border-outline-variant p-5 flex flex-col gap-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Inasistencias</span>
                        <span class="material-symbols-outlined text-red-400">cancel</span>
                    </div>
                    <p class="text-5xl font-black text-red-500"><?= $globales->inasistencias ?></p>
                    <p class="text-xs text-on-surface-variant">actividades perdidas</p>
                </div>
            </div>

            <!-- ===== CHARTS ROW ===== -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Gráfica: Asistencia por Tipo (Donut) -->
                <div class="bg-white rounded-2xl border border-outline-variant p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">donut_large</span>
                        <h2 class="font-bold text-on-surface">Tipos de Actividad</h2>
                    </div>
                    <?php if (empty($por_tipo)): ?>
                        <div class="flex flex-col items-center justify-center h-48 text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2">bar_chart_off</span>
                            <p class="text-sm">Sin datos de asistencia aún</p>
                        </div>
                    <?php else: ?>
                        <div class="relative h-52 flex items-center justify-center">
                            <canvas id="chartTipo"></canvas>
                        </div>
                        <!-- Leyenda manual -->
                        <div class="mt-4 space-y-2">
                            <?php
                            $chartColors = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4'];
                            foreach ($por_tipo as $i => $t):
                                $pct = $t->total > 0 ? round(($t->asistidas/$t->total)*100) : 0;
                                $color = $chartColors[$i % count($chartColors)];
                            ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background:<?= $color ?>"></span>
                                    <span class="text-sm text-on-surface truncate max-w-[160px]"><?= htmlspecialchars($t->nombre_tipo) ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                                    </div>
                                    <span class="text-xs font-bold text-on-surface-variant w-8 text-right"><?= $pct ?>%</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Gráfica: Progreso mes a mes (barras) -->
                <div class="bg-white rounded-2xl border border-outline-variant p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">bar_chart</span>
                        <h2 class="font-bold text-on-surface">Progreso Mensual</h2>
                        <span class="ml-auto text-xs text-on-surface-variant">Últimos 6 meses</span>
                    </div>
                    <?php if (empty($por_mes)): ?>
                        <div class="flex flex-col items-center justify-center h-48 text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2">bar_chart_off</span>
                            <p class="text-sm">Sin datos en los últimos 6 meses</p>
                        </div>
                    <?php else: ?>
                        <div class="relative h-52">
                            <canvas id="chartMes"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ===== DESGLOSE POR TIPO (tabla detallada) ===== -->
            <?php if (!empty($por_tipo)): ?>
            <div class="bg-white rounded-2xl border border-outline-variant p-6">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-primary">category</span>
                    <h2 class="font-bold text-on-surface">Desglose por Tipo de Actividad</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-on-surface-variant uppercase tracking-wider border-b border-outline-variant">
                                <th class="text-left py-2 pb-3">Tipo</th>
                                <th class="text-center py-2 pb-3">Total</th>
                                <th class="text-center py-2 pb-3">Asistidas</th>
                                <th class="text-center py-2 pb-3">Perdidas</th>
                                <th class="text-left py-2 pb-3 w-32">Cumplimiento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50">
                            <?php foreach ($por_tipo as $i => $t):
                                $pct = $t->total > 0 ? round(($t->asistidas/$t->total)*100) : 0;
                                $color = $chartColors[$i % count($chartColors)];
                                $perdidas = $t->total - $t->asistidas;
                            ?>
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-3 font-medium text-on-surface flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:<?= $color ?>"></span>
                                    <?= htmlspecialchars($t->nombre_tipo) ?>
                                </td>
                                <td class="py-3 text-center font-bold text-on-surface"><?= $t->total ?></td>
                                <td class="py-3 text-center">
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold"><?= $t->asistidas ?></span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="px-2 py-0.5 <?= $perdidas > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400' ?> rounded-full text-xs font-bold"><?= $perdidas ?></span>
                                </td>
                                <td class="py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                                        </div>
                                        <span class="text-xs font-bold text-on-surface-variant w-8"><?= $pct ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- ===== ÚLTIMAS ASISTENCIAS ===== -->
            <?php if (!empty($asistencias)): ?>
            <div class="bg-white rounded-2xl border border-outline-variant p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">history</span>
                        <h2 class="font-bold text-on-surface">Últimas Actividades</h2>
                    </div>
                    <a href="<?= URLROOT ?>/padres/asistencias" class="text-xs text-primary hover:underline font-medium">Ver todo →</a>
                </div>
                <div class="space-y-3">
                    <?php foreach ($asistencias as $a): ?>
                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 <?= $a->presente ? 'bg-emerald-50' : 'bg-red-50' ?>">
                            <span class="material-symbols-outlined text-sm <?= $a->presente ? 'text-emerald-600' : 'text-red-500' ?>" style="font-variation-settings:'FILL' 1;"><?= $a->presente ? 'check_circle' : 'cancel' ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-on-surface truncate"><?= htmlspecialchars($a->nombre_actividad) ?></p>
                            <p class="text-xs text-on-surface-variant"><?= htmlspecialchars($a->nombre_tipo) ?> · <?= date('d M Y', strtotime($a->fecha_hora_inicio)) ?></p>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0 <?= $a->presente ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' ?>">
                            <?= $a->presente ? 'Asistió' : 'No asistió' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /max-w -->
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
    // ====== Datos PHP → JS ======
    const dataTipo = <?= json_encode(array_values($por_tipo)) ?>;
    const dataMes  = <?= json_encode(array_values($por_mes)) ?>;
    const chartColors = ['#10b981','#3b82f6','#f59e0b','#8b5cf6','#ef4444','#06b6d4'];

    // ====== Donut: por tipo ======
    if (dataTipo.length && document.getElementById('chartTipo')) {
        new Chart(document.getElementById('chartTipo'), {
            type: 'doughnut',
            data: {
                labels: dataTipo.map(d => d.nombre_tipo),
                datasets: [{
                    data: dataTipo.map(d => d.asistidas),
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                cutout: '65%',
                plugins: { legend: { display: false }, tooltip: {
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} asistidas` }
                }},
                animation: { animateRotate: true, duration: 900 }
            }
        });
    }

    // ====== Barras: por mes ======
    if (dataMes.length && document.getElementById('chartMes')) {
        new Chart(document.getElementById('chartMes'), {
            type: 'bar',
            data: {
                labels: dataMes.map(d => d.mes_label),
                datasets: [
                    {
                        label: 'Asistidas',
                        data: dataMes.map(d => d.asistidas),
                        backgroundColor: '#10b981cc',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Perdidas',
                        data: dataMes.map(d => d.total - d.asistidas),
                        backgroundColor: '#ef444460',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' }, border: { display: false } }
                },
                animation: { duration: 900 }
            }
        });
    }

    // ====== Progress bars animadas ======
    document.querySelectorAll('.progress-bar[data-target]').forEach(el => {
        setTimeout(() => { el.style.width = el.dataset.target + '%'; }, 200);
    });

    // ====== Sidebar collapse ======
    const collapseSidebarBtn = document.getElementById('collapseSidebarBtn');
    const menuBtn = document.getElementById('menuToggleBtn');
    const sidebar = document.getElementById('userSidebar');

    if (collapseSidebarBtn) {
        collapseSidebarBtn.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }
    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', () => sidebar.classList.toggle('force-open'));
    }

    // ── Historial Asistencias Dropdown ──
    const dropBtn  = document.getElementById('asistenciaDropdownBtn');
    const submenu  = document.getElementById('asistenciaSubmenu');
    const chevron  = document.getElementById('asistenciaDropdownChevron');

    // This page is a child — start opened
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
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>