<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">Edusaft</div>
            <div class="hidden md:block text-on-surface-variant font-label-md">Panel Docente</div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-on-surface-variant hidden md:inline">
                    <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
                </span>
                <a href="<?php echo URLROOT; ?>/auth/logout" title="Cerrar Sesión"
                   class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95 duration-150">
                    <span class="material-symbols-outlined text-error">logout</span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <h2 class="text-headline-lg text-primary font-headline-lg">
                Bienvenido, Prof. <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
            </h2>

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-surface border border-outline-variant rounded-xl p-5 flex flex-col items-center text-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl">event</span>
                    <p class="text-3xl font-black text-primary"><?php echo $data['total_actividades']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Mis Actividades</p>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-5 flex flex-col items-center text-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-3xl">how_to_reg</span>
                    <p class="text-3xl font-black text-secondary"><?php echo $data['total_asistencias']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Asistencias Registradas</p>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-5 flex flex-col items-center text-center gap-2">
                    <span class="material-symbols-outlined text-tertiary text-3xl">groups</span>
                    <p class="text-3xl font-black text-tertiary"><?php echo count($data['grupos']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Mis Grupos</p>
                </div>
            </div>

            <!-- Mis Grupos -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6">
                <h3 class="text-headline-md text-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">class</span>
                    Mis Grupos Asignados
                </h3>
                <?php if (empty($data['grupos'])): ?>
                    <p class="text-on-surface-variant text-sm">No tiene grupos asignados actualmente.</p>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($data['grupos'] as $grupo): ?>
                        <div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">group</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface"><?php echo htmlspecialchars($grupo->nombre_grupo); ?></p>
                                <p class="text-xs text-on-surface-variant">
                                    <?php echo htmlspecialchars($grupo->nombre_grado); ?> — <?php echo htmlspecialchars($grupo->nombre_sede); ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Asistencias Recientes -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6">
                <h3 class="text-headline-md text-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">history</span>
                    Últimas Asistencias Registradas
                </h3>
                <?php if (empty($data['asistencias_recientes'])): ?>
                    <p class="text-on-surface-variant text-sm">Aún no ha registrado asistencias.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant">
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Fecha</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Estudiante</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Grupo</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Actividad</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['asistencias_recientes'] as $asi): ?>
                                <tr class="border-b border-outline-variant/50 hover:bg-surface-container transition-colors">
                                    <td class="px-3 py-3 text-xs text-on-surface-variant">
                                        <?php echo date('d/m/Y H:i', strtotime($asi->fecha_registro)); ?>
                                    </td>
                                    <td class="px-3 py-3 font-medium"><?php echo htmlspecialchars($asi->estudiante_nombre); ?></td>
                                    <td class="px-3 py-3 text-xs text-on-surface-variant"><?php echo htmlspecialchars($asi->nombre_grupo); ?></td>
                                    <td class="px-3 py-3 text-xs text-on-surface-variant"><?php echo htmlspecialchars($asi->nombre_actividad); ?></td>
                                    <td class="px-3 py-3">
                                        <?php if ($asi->presente): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800 font-medium">Presente</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800 font-medium">Ausente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo URLROOT; ?>/docentes/asistencia" class="text-primary text-sm font-semibold hover:underline">
                            Ver todas las asistencias &rarr;
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="<?php echo URLROOT; ?>/docentes/actividades"
                   class="bg-primary/5 border border-primary/20 rounded-xl p-6 hover:bg-primary/10 transition-colors flex items-center gap-4 group">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                        <span class="material-symbols-outlined text-primary">calendar_today</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface">Mis Actividades</p>
                        <p class="text-sm text-on-surface-variant">Ver y gestionar actividades programadas</p>
                    </div>
                </a>
                <a href="<?php echo URLROOT; ?>/docentes/asistencia"
                   class="bg-secondary/5 border border-secondary/20 rounded-xl p-6 hover:bg-secondary/10 transition-colors flex items-center gap-4 group">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center group-hover:bg-secondary/20 transition-colors">
                        <span class="material-symbols-outlined text-secondary">how_to_reg</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface">Registrar Asistencia</p>
                        <p class="text-sm text-on-surface-variant">Tomar asistencia de sus grupos</p>
                    </div>
                </a>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
