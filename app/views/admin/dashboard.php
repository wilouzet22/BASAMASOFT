<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white docked full-width top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">
                Edusaft
            </div>
            <div class="hidden md:block text-on-surface-variant font-label-md">
                Panel de Administración
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-on-surface-variant hidden md:inline">
                    <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
                </span>
                <a href="<?php echo URLROOT; ?>/auth/logout" title="Cerrar Sesión"
                   class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95 duration-150">
                    <span class="material-symbols-outlined text-error">logout</span>
                </a>
                <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <h2 class="text-headline-lg text-primary font-headline-lg">Resumen General</h2>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                <!-- Profesores -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">school</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo $data['total_profesores']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Profesores</p>
                </div>

                <!-- Familias -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary">family_restroom</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo $data['total_familias']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Familias</p>
                </div>

                <!-- Estudiantes -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-tertiary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary">groups</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo $data['total_estudiantes']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Estudiantes</p>
                </div>

                <!-- Sedes -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-primary-fixed/60 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">apartment</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo $data['total_sedes']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Sedes</p>
                </div>

                <!-- Actividades -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-secondary-container/30 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary">event</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo $data['total_actividades']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Actividades</p>
                </div>

                <!-- Asistencias -->
                <div class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center gap-2">
                    <div class="w-12 h-12 bg-tertiary-fixed/40 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary">how_to_reg</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo $data['total_asistencias']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Asistencias</p>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Usuarios Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-primary">group</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Usuarios</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">
                        <?php echo $data['total_profesores']; ?> prof. · <?php echo $data['total_familias']; ?> familias · <?php echo $data['total_estudiantes']; ?> estudiantes
                    </p>
                    <a href="<?php echo URLROOT; ?>/admin/usuarios" class="text-primary font-semibold hover:underline">Gestionar Usuarios &rarr;</a>
                </div>

                <!-- Sedes Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-tertiary">apartment</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Sedes</h3>
                    <p class="text-body-md text-on-surface-variant mb-4"><?php echo $data['total_sedes']; ?> sedes registradas en el sistema.</p>
                    <a href="<?php echo URLROOT; ?>/admin/sedes" class="text-tertiary font-semibold hover:underline">Ver Sedes &rarr;</a>
                </div>

                <!-- Asistencias Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">how_to_reg</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Asistencias</h3>
                    <p class="text-body-md text-on-surface-variant mb-4"><?php echo $data['total_asistencias']; ?> registros de asistencia totales.</p>
                    <a href="<?php echo URLROOT; ?>/admin/asistencias" class="text-secondary font-semibold hover:underline">Ver Asistencias &rarr;</a>
                </div>
            </div>

            <!-- Log Reciente -->
            <?php if (!empty($data['logs_recientes'])): ?>
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="text-headline-md text-primary font-headline-md mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">history</span>
                    Actividad Reciente del Sistema
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="text-left px-3 py-2 text-on-surface-variant font-semibold">Rol</th>
                                <th class="text-left px-3 py-2 text-on-surface-variant font-semibold">Usuario</th>
                                <th class="text-left px-3 py-2 text-on-surface-variant font-semibold">Acción</th>
                                <th class="text-left px-3 py-2 text-on-surface-variant font-semibold">Fecha</th>
                                <th class="text-left px-3 py-2 text-on-surface-variant font-semibold">IP</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['logs_recientes'] as $log): ?>
                            <?php
                                // Determinar el usuario según el rol del log
                                if (!empty($log->admin_nombre)) {
                                    $usuario = $log->admin_nombre . ' ' . $log->admin_apellidos;
                                } elseif (!empty($log->prof_nombre)) {
                                    $usuario = $log->prof_nombre . ' ' . $log->prof_apellidos;
                                } elseif (!empty($log->familia_nombre)) {
                                    $usuario = $log->familia_nombre;
                                } else {
                                    $usuario = 'Sistema';
                                }
                            ?>
                            <tr class="border-b border-outline-variant/50 hover:bg-surface-container transition-colors">
                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        <?php if ($log->rol_nombre === 'Administrador') echo 'bg-primary/10 text-primary';
                                              elseif ($log->rol_nombre === 'Profesor') echo 'bg-tertiary/10 text-tertiary';
                                              else echo 'bg-secondary/10 text-secondary'; ?>">
                                        <?php echo htmlspecialchars($log->rol_nombre); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3 font-medium text-on-surface"><?php echo htmlspecialchars($usuario); ?></td>
                                <td class="px-3 py-3 text-on-surface-variant"><?php echo htmlspecialchars($log->accion_realizada); ?></td>
                                <td class="px-3 py-3 text-on-surface-variant text-xs"><?php echo date('d/m/Y H:i', strtotime($log->timestamp)); ?></td>
                                <td class="px-3 py-3 text-on-surface-variant font-mono text-xs"><?php echo htmlspecialchars($log->ip_direccion); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
