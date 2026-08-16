<?php
/**
 * Vista compartida: Actividades de la Institución
 * Usada por Admin (/admin/actividades_proximas) y Docentes (/docentes/actividades_proximas).
 * Solo muestra información — sin puntaje ni gamificación.
 */
$data = $data ?? [];
$rol  = $_SESSION['rol'] ?? '';
$isAdmin = ($rol === 'administrador');

$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="<?php echo $isAdmin ? 'toggleMobileSidebar()' : 'toggleDocentesMobileSidebar()'; ?>" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>

</header>

<div class="flex">
    <!-- Sidebar según rol -->
    <?php if ($isAdmin): ?>
        <?php require APPROOT . '/views/admin/sidebar.php'; ?>
    <?php else: ?>
        <?php require APPROOT . '/views/docentes/sidebar.php'; ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">

        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Actividades Institucionales</h2>
                    <p class="text-sm text-on-surface-variant">
                        <?php echo $isAdmin ? 'Administración' : 'Portal Docente'; ?> ·
                        <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                <div class="text-right">
                    <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></p>
                    <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">
                        <?php echo $isAdmin ? 'Administrador del Sistema' : 'Portal Docente'; ?>
                    </p>
                </div>
                <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();"
                   class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center hover:bg-primary/20 transition-all cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-primary">
                        <?php echo $isAdmin ? 'admin_panel_settings' : 'school'; ?>
                    </span>
                </a>
            </div>
        </header>

        <div class="p-6 md:p-10 space-y-10 max-w-7xl mx-auto w-full flex-1">

            <!-- Encabezado de página -->
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-4xl">event_note</span>
                    Actividades de la Institución
                </h1>
                <p class="text-sm text-on-surface-variant mt-1">
                    Consulta el calendario de actividades registradas. Esta vista es informativa — no incluye puntaje.
                </p>
            </div>

            <!-- Próximas actividades (destacadas) -->
            <?php if (!empty($data['proximas'])): ?>
            <div>
                <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-xl">schedule</span>
                    Próximas actividades
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($data['proximas'] as $prox):
                        $esFutura = strtotime($prox->fecha_hora_inicio) >= time();
                    ?>
                        <div class="relative bg-white rounded-3xl border <?php echo $esFutura ? 'border-secondary/40 shadow-md shadow-secondary/10' : 'border-outline-variant'; ?> p-6 overflow-hidden group hover:shadow-lg transition-all">
                            <?php if ($esFutura): ?>
                                <span class="absolute top-4 right-4 bg-secondary text-on-secondary text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full">Próxima</span>
                            <?php else: ?>
                                <span class="absolute top-4 right-4 bg-surface-container text-on-surface-variant text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full">Pasada</span>
                            <?php endif; ?>

                            <div class="w-12 h-12 rounded-2xl <?php echo $esFutura ? 'bg-secondary/10 text-secondary' : 'bg-surface-container text-on-surface-variant'; ?> flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-2xl">event</span>
                            </div>

                            <h3 class="font-bold text-on-surface text-base mb-1 pr-16"><?php echo htmlspecialchars($prox->nombre_actividad); ?></h3>
                            <p class="text-xs text-on-surface-variant font-medium mb-3"><?php echo htmlspecialchars($prox->nombre_tipo); ?></p>

                            <div class="space-y-1.5 text-xs text-on-surface-variant">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-primary">calendar_today</span>
                                    <?php echo date('d M Y, H:i', strtotime($prox->fecha_hora_inicio)); ?>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm text-primary">apartment</span>
                                    <?php echo htmlspecialchars($prox->nombre_sede); ?>
                                </div>
                                <?php if (!empty($prox->descripcion)): ?>
                                <div class="flex items-start gap-2 mt-2 pt-2 border-t border-outline-variant/30">
                                    <span class="material-symbols-outlined text-sm text-primary shrink-0">info</span>
                                    <span class="leading-relaxed"><?php echo htmlspecialchars($prox->descripcion); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Todas las actividades -->
            <div>
                <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">list_alt</span>
                    Todas las actividades
                </h2>

                <?php if (empty($data['actividades'])): ?>
                    <div class="bg-white rounded-3xl border border-outline-variant p-12 text-center shadow-sm">
                        <span class="material-symbols-outlined text-6xl text-outline opacity-50 block mb-4">event_busy</span>
                        <h3 class="text-lg font-bold text-on-surface">Sin actividades registradas</h3>
                        <p class="text-sm text-on-surface-variant mt-2 max-w-xs mx-auto">
                            Todavía no hay actividades institucionales registradas en el sistema.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-3xl border border-outline-variant shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead>
                                    <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider bg-surface-container-low">
                                        <th class="py-4 px-6">Actividad</th>
                                        <th class="py-4 px-6">Tipo</th>
                                        <th class="py-4 px-6">Fecha Inicio</th>
                                        <th class="py-4 px-6">Sede</th>
                                        <th class="py-4 px-6 text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/20">
                                    <?php foreach ($data['actividades'] as $act):
                                        $ahora = time();
                                        $inicio = strtotime($act->fecha_hora_inicio);
                                        $fin    = !empty($act->fecha_hora_fin) ? strtotime($act->fecha_hora_fin) : null;

                                        if ($inicio > $ahora) {
                                            $estado = 'proxima';
                                            $estadoLabel = 'Próxima';
                                            $estadoClasses = 'bg-secondary/10 text-secondary';
                                            $iconoEstado = 'schedule';
                                        } elseif ($fin && $ahora > $fin) {
                                            $estado = 'finalizada';
                                            $estadoLabel = 'Finalizada';
                                            $estadoClasses = 'bg-surface-container text-on-surface-variant';
                                            $iconoEstado = 'check_circle';
                                        } else {
                                            $estado = 'en_curso';
                                            $estadoLabel = 'En Curso';
                                            $estadoClasses = 'bg-green-100 text-green-800';
                                            $iconoEstado = 'play_circle';
                                        }
                                    ?>
                                    <tr class="hover:bg-surface-container-low/40 transition-colors">
                                        <td class="py-4 px-6">
                                            <p class="font-bold text-on-surface"><?php echo htmlspecialchars($act->nombre_actividad); ?></p>
                                            <?php if (!empty($act->descripcion)): ?>
                                                <p class="text-xs text-on-surface-variant mt-0.5 line-clamp-1"><?php echo htmlspecialchars($act->descripcion); ?></p>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="bg-primary/10 text-primary font-bold text-xs px-3 py-1 rounded-full">
                                                <?php echo htmlspecialchars($act->nombre_tipo); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-xs text-on-surface-variant font-medium">
                                            <?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?>
                                            <br><span class="text-[11px] text-outline"><?php echo date('H:i', strtotime($act->fecha_hora_inicio)); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-xs text-on-surface-variant font-medium">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-sm text-primary">apartment</span>
                                                <?php echo htmlspecialchars($act->nombre_sede); ?>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full <?php echo $estadoClasses; ?>">
                                                <span class="material-symbols-outlined text-sm"><?php echo $iconoEstado; ?></span>
                                                <?php echo $estadoLabel; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

</body>
</html>
