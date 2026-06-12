<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-surface-container-lowest text-on-background font-lexend min-h-screen">
    <header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-10 w-10 rounded-full" alt="Logo">
            <span class="font-bold text-primary">EduSaft</span>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <nav class="hidden md:flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-40">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-8 w-8" alt="Logo">
                    </div>
                    <span class="text-2xl font-bold text-primary tracking-tight">EduSaft</span>
                </div>
                <p class="text-xs text-outline uppercase tracking-widest font-bold ml-1">Portal de Padres</p>
            </div>
            <div class="flex-grow px-4 space-y-2">
                <a href="<?php echo URLROOT; ?>/padres/dashboard"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="font-medium">Panel Principal</span>
                </a>
                <a href="<?php echo URLROOT; ?>/padres/camino"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl bg-primary text-on-primary shadow-lg shadow-primary/20 transition-all group">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">history</span>
                    <span class="font-medium">Historial Asistencias</span>
                </a>
                <a href="<?php echo URLROOT; ?>/padres/puntos"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined">workspace_premium</span>
                    <span class="font-medium">Mis Puntos</span>
                </a>
            </div>
            <div class="p-4 mt-auto">
                <a href="<?php echo URLROOT; ?>/auth/logout"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-error hover:bg-error/10 transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-medium">Cerrar Sesión</span>
                </a>
            </div>
        </nav>

        <!-- Main -->
        <main class="flex-1 md:ml-72 min-h-screen bg-surface-container-lowest p-6 md:p-10">
            <div class="max-w-5xl mx-auto space-y-8">

                <div>
                    <h1 class="text-3xl font-black text-primary tracking-tight">Historial de Asistencias</h1>
                    <p class="text-on-surface-variant mt-1">Registro completo de asistencia de sus hijos a actividades institucionales.</p>
                </div>

                <!-- Resumen por hijo -->
                <?php if (!empty($data['estudiantes'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($data['estudiantes'] as $est):
                        $stat = $data['estadisticas'][$est->id_estudiante] ?? (object)['porcentaje'=>0,'presentes'=>0,'ausentes'=>0,'total'=>0];
                    ?>
                    <div class="bg-white rounded-2xl border border-outline-variant p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">face</span>
                            </div>
                            <div>
                                <p class="font-bold text-on-surface"><?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?></p>
                                <p class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($est->nombre_grupo . ' — ' . $est->nombre_grado); ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-center">
                            <div class="bg-green-50 rounded-xl p-3">
                                <p class="text-xl font-black text-green-700"><?php echo $stat->presentes; ?></p>
                                <p class="text-[10px] text-green-600 font-bold uppercase">Presentes</p>
                            </div>
                            <div class="bg-red-50 rounded-xl p-3">
                                <p class="text-xl font-black text-red-700"><?php echo $stat->ausentes; ?></p>
                                <p class="text-[10px] text-red-600 font-bold uppercase">Ausentes</p>
                            </div>
                            <div class="bg-primary/5 rounded-xl p-3">
                                <p class="text-xl font-black text-primary"><?php echo $stat->porcentaje; ?>%</p>
                                <p class="text-[10px] text-primary font-bold uppercase">Asistencia</p>
                            </div>
                        </div>
                        <div class="mt-3 w-full bg-surface-container h-2 rounded-full overflow-hidden">
                            <div class="bg-primary h-full" style="width: <?php echo $stat->porcentaje; ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Tabla historial completo -->
                <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-x-auto">
                    <div class="p-6 border-b border-outline-variant">
                        <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">list_alt</span>
                            Todos los Registros
                        </h2>
                    </div>
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-outline-variant">
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Fecha</th>
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Hijo/a</th>
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Actividad</th>
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Tipo</th>
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Sede</th>
                                <th class="px-4 py-3 text-left text-on-surface-variant font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['asistencias'])): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-4xl block mb-2">event_busy</span>
                                        No hay registros de asistencia aún.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['asistencias'] as $asi): ?>
                                <tr class="border-b border-outline-variant/50 hover:bg-surface-container transition-colors">
                                    <td class="px-4 py-3 text-xs text-on-surface-variant">
                                        <?php echo date('d/m/Y', strtotime($asi->fecha_registro)); ?><br>
                                        <span class="text-[10px]"><?php echo date('H:i', strtotime($asi->fecha_registro)); ?></span>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-on-surface">
                                        <?php echo htmlspecialchars($asi->estudiante_nombre); ?><br>
                                        <span class="text-xs text-on-surface-variant"><?php echo htmlspecialchars($asi->nombre_grupo); ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-on-surface">
                                        <?php echo htmlspecialchars($asi->nombre_actividad); ?><br>
                                        <span class="text-xs text-on-surface-variant">
                                            <?php echo date('d/m/Y H:i', strtotime($asi->fecha_hora_inicio)); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-medium">
                                            <?php echo htmlspecialchars($asi->nombre_tipo); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-on-surface-variant">
                                        <?php echo htmlspecialchars($asi->nombre_sede); ?>
                                    </td>
                                    <td class="px-4 py-3">
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>