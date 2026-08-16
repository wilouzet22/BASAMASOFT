<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleDocentesMobileSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>

</header>

<div class="flex">
    <!-- Sidebar reusable docentes -->
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Registro de Asistencia</h2>
                    <p class="text-sm text-on-surface-variant">
                        Prof. <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Docente'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Portal Docente</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">school</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full flex-1">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">event_available</span>
                        Asistencia Registrada
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Control y seguimiento de asistencias tomadas.</p>
                </div>
            </div>

            <!-- Stat Banner Card -->
            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-5 w-fit">
                <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shrink-0">
                    <span class="material-symbols-outlined text-3xl">how_to_reg</span>
                </div>
                <div>
                    <p class="text-3xl font-black text-secondary"><?php echo $data['total_asistencias']; ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Asistencias registradas por usted</p>
                </div>
            </div>

            <!-- Estudiantes de mis grupos -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h3 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">groups</span>
                    Estudiantes en mis grupos
                </h3>
                <?php if (empty($data['estudiantes'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">person_off</span>
                        <p class="text-sm font-medium">No hay estudiantes asignados a sus grupos.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Nombre</th>
                                    <th class="py-3 px-4">Documento</th>
                                    <th class="py-3 px-4">Grupo</th>
                                    <th class="py-3 px-4">Grado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <?php foreach ($data['estudiantes'] as $est): ?>
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-on-surface">
                                            <?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs text-on-surface-variant">
                                            <?php echo htmlspecialchars($est->documento_identidad ?? '—'); ?>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="bg-primary/10 text-primary font-bold px-3 py-1 rounded-full text-xs">
                                                <?php echo htmlspecialchars($est->nombre_grupo); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium">
                                            <?php echo htmlspecialchars($est->nombre_grado); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Historial de asistencias -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h3 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-2xl">history</span>
                    Historial de Asistencias Registradas
                </h3>
                <?php if (empty($data['asistencias'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">event_busy</span>
                        <p class="text-sm font-medium">No ha registrado asistencias todavía.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Fecha</th>
                                    <th class="py-3 px-4">Estudiante</th>
                                    <th class="py-3 px-4">Grupo</th>
                                    <th class="py-3 px-4">Actividad</th>
                                    <th class="py-3 px-4 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <?php foreach ($data['asistencias'] as $asi): ?>
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant">
                                            <?php echo date('d/m/Y H:i', strtotime($asi->fecha_registro)); ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-bold text-on-surface">
                                            <?php echo htmlspecialchars($asi->estudiante_nombre); ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant">
                                            <span class="bg-primary/10 text-primary font-bold px-2.5 py-1 rounded-full text-[11px]">
                                                <?php echo htmlspecialchars($asi->nombre_grupo); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium">
                                            <?php echo htmlspecialchars($asi->nombre_actividad); ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <?php if ($asi->presente): ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                                    <span class="material-symbols-outlined text-sm">check</span> Presente
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold bg-red-100 text-red-800 px-3 py-1 rounded-full">
                                                    <span class="material-symbols-outlined text-sm">close</span> Ausente
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

</body>
</html>
