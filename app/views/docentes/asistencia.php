<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">Edusaft</div>
            <div class="hidden md:block text-on-surface-variant font-label-md">Registro de Asistencia</div>
            <div class="flex items-center gap-4">
                <a href="<?php echo URLROOT; ?>/auth/logout"
                   class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95 duration-150">
                    <span class="material-symbols-outlined text-error">logout</span>
                </a>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <h2 class="text-headline-lg text-primary font-headline-lg">Asistencia Registrada</h2>

            <!-- Stat -->
            <div class="bg-surface border border-outline-variant rounded-xl p-5 flex items-center gap-4 w-fit">
                <span class="material-symbols-outlined text-secondary text-3xl">how_to_reg</span>
                <div>
                    <p class="text-2xl font-black text-secondary"><?php echo $data['total_asistencias']; ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Asistencias registradas por usted</p>
                </div>
            </div>

            <!-- Estudiantes de mis grupos -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6">
                <h3 class="text-headline-md text-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">groups</span>
                    Estudiantes en mis grupos
                </h3>
                <?php if (empty($data['estudiantes'])): ?>
                    <p class="text-on-surface-variant text-sm">No hay estudiantes asignados a sus grupos.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant">
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Nombre</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Documento</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Grupo</th>
                                    <th class="px-3 py-2 text-left text-on-surface-variant font-semibold">Grado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['estudiantes'] as $est): ?>
                                <tr class="border-b border-outline-variant/50 hover:bg-surface-container transition-colors">
                                    <td class="px-3 py-3 font-medium text-on-surface">
                                        <?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?>
                                    </td>
                                    <td class="px-3 py-3 font-mono text-xs text-on-surface-variant">
                                        <?php echo htmlspecialchars($est->documento_identidad ?? '—'); ?>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="bg-primary/10 text-primary px-2 py-0.5 rounded text-xs font-medium">
                                            <?php echo htmlspecialchars($est->nombre_grupo); ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-xs text-on-surface-variant">
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
            <div class="bg-surface border border-outline-variant rounded-xl p-6">
                <h3 class="text-headline-md text-primary mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined">history</span>
                    Historial de Asistencias Registradas
                </h3>
                <?php if (empty($data['asistencias'])): ?>
                    <p class="text-on-surface-variant text-sm">No ha registrado asistencias todavía.</p>
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
                                <?php foreach ($data['asistencias'] as $asi): ?>
                                <tr class="border-b border-outline-variant/50 hover:bg-surface-container transition-colors">
                                    <td class="px-3 py-3 text-xs text-on-surface-variant">
                                        <?php echo date('d/m/Y H:i', strtotime($asi->fecha_registro)); ?>
                                    </td>
                                    <td class="px-3 py-3 font-medium"><?php echo htmlspecialchars($asi->estudiante_nombre); ?></td>
                                    <td class="px-3 py-3 text-xs text-on-surface-variant"><?php echo htmlspecialchars($asi->nombre_grupo); ?></td>
                                    <td class="px-3 py-3 text-xs text-on-surface-variant"><?php echo htmlspecialchars($asi->nombre_actividad); ?></td>
                                    <td class="px-3 py-3">
                                        <?php if ($asi->presente): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800 font-medium">
                                                <span class="material-symbols-outlined" style="font-size:12px">check</span>Presente
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-800 font-medium">
                                                <span class="material-symbols-outlined" style="font-size:12px">close</span>Ausente
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
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
