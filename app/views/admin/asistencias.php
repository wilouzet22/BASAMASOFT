<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleMobileSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>

</header>

<div class="flex">
    <!-- Sidebar reusable admin -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Gestión de Asistencias</h2>
                    <p class="text-sm text-on-surface-variant">
                        Administración · <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Administrador del Sistema</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full flex-1">

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">how_to_reg</span>
                        Gestión de Asistencias
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Consulado e historial global de asistencias tomadas.</p>
                </div>
            </div>

            <!-- Stats Tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-3xl">format_list_bulleted</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-primary"><?php echo $data['presentes'] + $data['ausentes']; ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Total Registros</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-700 shrink-0">
                        <span class="material-symbols-outlined text-3xl">check_circle</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-green-700"><?php echo $data['presentes']; ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Presentes</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-5">
                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center text-red-700 shrink-0">
                        <span class="material-symbols-outlined text-3xl">cancel</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-red-700"><?php echo $data['ausentes']; ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Ausentes</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <?php if (empty($data['asistencias'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">event_busy</span>
                        <p class="text-sm font-medium">No hay registros de asistencia aún.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Fecha Registro</th>
                                    <th class="py-3 px-4">Estudiante</th>
                                    <th class="py-3 px-4">Grupo / Grado</th>
                                    <th class="py-3 px-4">Actividad</th>
                                    <th class="py-3 px-4">Tipo</th>
                                    <th class="py-3 px-4">Sede</th>
                                    <th class="py-3 px-4">Profesor</th>
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
                                            <span class="bg-primary/10 text-primary font-bold px-2.5 py-1 rounded-full text-[11px] inline-block mb-1">
                                                <?php echo htmlspecialchars($asi->nombre_grupo); ?>
                                            </span><br>
                                            <span class="text-[10px] text-outline uppercase font-bold"><?php echo htmlspecialchars($asi->nombre_grado); ?></span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface font-medium">
                                            <?php echo htmlspecialchars($asi->nombre_actividad); ?><br>
                                            <span class="text-[10px] text-on-surface-variant">
                                                <?php echo date('d/m/Y H:i', strtotime($asi->fecha_hora_inicio)); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs">
                                            <span class="bg-tertiary/10 text-tertiary font-bold px-2.5 py-1 rounded-full text-[11px]">
                                                <?php echo htmlspecialchars($asi->nombre_tipo); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium">
                                            <?php echo htmlspecialchars($asi->nombre_sede); ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium">
                                            <?php echo htmlspecialchars($asi->profesor_nombre); ?>
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
