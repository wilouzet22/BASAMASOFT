<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-surface-container-lowest text-on-background font-lexend min-h-screen">
    <!-- Mobile Header -->
    <header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-10 w-10 rounded-full" alt="Logo">
            <span class="font-bold text-primary">EduSaft</span>
        </div>
        <button id="mobile-menu-toggle" class="p-2 text-on-surface-variant">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </header>

    <div class="flex">
        <!-- Premium Sidebar -->
        <nav class="hidden md:flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-40 transition-all duration-300">
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
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl bg-primary text-on-primary shadow-lg shadow-primary/20 transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span class="font-medium">Panel Principal</span>
                </a>
                <a href="<?php echo URLROOT; ?>/padres/camino"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110">map</span>
                    <span class="font-medium">Historial Asistencias</span>
                </a>
                <a href="<?php echo URLROOT; ?>/padres/puntos"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110">workspace_premium</span>
                    <span class="font-medium">Mis Puntos</span>
                </a>
            </div>

            <div class="p-4 mt-auto space-y-2">
                <a href="<?php echo URLROOT; ?>/auth/logout"
                   class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-error hover:bg-error/10 transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-medium">Cerrar Sesión</span>
                </a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 md:ml-72 min-h-screen bg-surface-container-lowest flex flex-col">
            <!-- Top Bar -->
            <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Panel de Control</h2>
                    <p class="text-sm text-on-surface-variant">
                        Bienvenido, <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                        <div class="text-right">
                            <p class="text-sm font-bold text-on-surface">Acudiente</p>
                            <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Portal Familiar</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden">
                            <span class="material-symbols-outlined text-primary">person</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full">

                <!-- Hero Card con info de próximas actividades -->
                <?php
                    $total_asistencias_familia = 0;
                    $total_presentes = 0;
                    foreach ($data['estadisticas'] as $est_stat) {
                        $total_asistencias_familia += $est_stat->total;
                        $total_presentes += $est_stat->presentes;
                    }
                    $porcentaje_global = ($total_asistencias_familia > 0) ? round(($total_presentes / $total_asistencias_familia) * 100) : 0;
                    $barra_width = min($porcentaje_global, 100);
                ?>
                <div class="relative bg-primary rounded-[2.5rem] p-8 md:p-12 text-on-primary overflow-hidden shadow-2xl shadow-primary/30 group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                        <div class="flex-1 text-center md:text-left">
                            <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                                Portal Familiar — EduSaft
                            </span>
                            <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">
                                Tu historial de asistencias
                            </h3>
                            <p class="text-on-primary/80 mb-8 max-w-md">
                                Consulta la asistencia de tus hijos y las próximas actividades de su institución.
                            </p>
                            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                                <a href="<?php echo URLROOT; ?>/padres/camino"
                                   class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                                    Ver Historial Completo
                                    <span class="material-symbols-outlined">history</span>
                                </a>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-sm font-bold">Asistencia Global</span>
                                <span class="text-2xl font-black"><?php echo $porcentaje_global; ?>%</span>
                            </div>
                            <div class="w-full bg-white/20 h-4 rounded-full overflow-hidden">
                                <div class="bg-secondary h-full transition-all duration-1000"
                                     style="width: <?php echo $barra_width; ?>%"></div>
                            </div>
                            <p class="text-[10px] mt-4 opacity-70 text-center italic">
                                "<?php echo $total_presentes; ?> de <?php echo $total_asistencias_familia; ?> asistencias marcadas como presente"
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mis Hijos -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xl font-bold text-on-surface">Mis Hijos</h4>
                    </div>

                    <?php if (empty($data['estudiantes'])): ?>
                        <div class="bg-white p-8 rounded-3xl border border-outline-variant text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2">person_off</span>
                            <p>No hay estudiantes vinculados a su cuenta.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <?php foreach ($data['estudiantes'] as $est):
                                $stat = $data['estadisticas'][$est->id_estudiante] ?? (object)['porcentaje'=>0,'presentes'=>0,'ausentes'=>0,'total'=>0];
                                $colors = ['primary','secondary','tertiary'];
                                static $colorIdx = 0;
                                $color = $colors[$colorIdx % count($colors)];
                                $colorIdx++;
                            ?>
                            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-16 h-16 rounded-2xl bg-<?php echo $color; ?>/10 flex items-center justify-center text-<?php echo $color; ?>">
                                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">face</span>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-on-surface">
                                            <?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?>
                                        </h5>
                                        <p class="text-xs text-outline font-bold uppercase tracking-tighter">
                                            <?php echo htmlspecialchars($est->nombre_grado); ?> — <?php echo htmlspecialchars($est->nombre_grupo); ?>
                                        </p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">
                                            <?php echo htmlspecialchars($est->nombre_sede); ?> · Parentesco: <?php echo htmlspecialchars($est->parentesco ?? '—'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-on-surface-variant">Asistencia</span>
                                        <span class="font-bold text-<?php echo $color; ?>"><?php echo $stat->porcentaje; ?>%</span>
                                    </div>
                                    <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                                        <div class="bg-<?php echo $color; ?> h-full transition-all duration-700"
                                             style="width: <?php echo $stat->porcentaje; ?>%"></div>
                                    </div>
                                    <div class="flex gap-4 text-xs text-on-surface-variant pt-1">
                                        <span class="text-green-700 font-medium">✓ <?php echo $stat->presentes; ?> presentes</span>
                                        <span class="text-red-600 font-medium">✗ <?php echo $stat->ausentes; ?> ausentes</span>
                                        <span>de <?php echo $stat->total; ?> total</span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Próximas Actividades -->
                <?php if (!empty($data['proximas_actividades'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                    <h4 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">event_upcoming</span>
                        Próximas Actividades
                    </h4>
                    <div class="space-y-3">
                        <?php foreach ($data['proximas_actividades'] as $act): ?>
                        <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-surface-container transition-colors">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary flex-shrink-0">
                                <span class="material-symbols-outlined">event</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-on-surface text-sm"><?php echo htmlspecialchars($act->nombre_actividad); ?></p>
                                <p class="text-xs text-on-surface-variant">
                                    <?php echo date('d/m/Y H:i', strtotime($act->fecha_hora_inicio)); ?> —
                                    <?php echo htmlspecialchars($act->nombre_tipo); ?> · <?php echo htmlspecialchars($act->nombre_sede); ?>
                                </p>
                            </div>
                            <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">
                                <?php echo htmlspecialchars($act->grupos); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Últimas asistencias -->
                <?php if (!empty($data['asistencias_recientes'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                    <h4 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">history</span>
                        Últimos Registros de Asistencia
                    </h4>
                    <div class="space-y-2">
                        <?php foreach ($data['asistencias_recientes'] as $asi): ?>
                        <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-surface-container transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 <?php echo $asi->presente ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                <span class="material-symbols-outlined text-sm"><?php echo $asi->presente ? 'check' : 'close'; ?></span>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-on-surface text-sm">
                                    <?php echo htmlspecialchars($asi->estudiante_nombre); ?>
                                </p>
                                <p class="text-xs text-on-surface-variant">
                                    <?php echo htmlspecialchars($asi->nombre_actividad); ?> · <?php echo date('d/m/Y', strtotime($asi->fecha_registro)); ?>
                                </p>
                            </div>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full <?php echo $asi->presente ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $asi->presente ? 'Presente' : 'Ausente'; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo URLROOT; ?>/padres/camino" class="text-primary text-sm font-bold hover:underline">
                            Ver historial completo &rarr;
                        </a>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <?php require APPROOT . '/views/inc/footer.php'; ?>
        </main>
    </div>
</body>
</html>
