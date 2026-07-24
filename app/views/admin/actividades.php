<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen w-full overflow-x-hidden" style="margin-left:16rem">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">
                Edusaft
            </div>
            <div class="hidden md:block text-on-surface-variant font-label-md">
                Gestión de Actividades
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

        <!-- Main Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h2 class="text-headline-lg text-primary font-headline-lg">Actividades y Eventos</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Listado general de actividades institucionales programadas.</p>
                </div>
            </div>

            <?php if (empty($data['actividades'])): ?>
                <div class="bg-surface border border-outline-variant rounded-xl p-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl block mb-3">event_busy</span>
                    <p class="text-lg font-medium mb-2">No hay actividades registradas</p>
                    <p class="text-sm">Las actividades creadas aparecerán en esta lista.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($data['actividades'] as $act): ?>
                    <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-secondary">
                                        <?php echo ($act->nombre_tipo === 'Reunión de Padres') ? 'groups' : (($act->nombre_tipo === 'Evento Cultural/Deportivo') ? 'sports' : 'event'); ?>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-on-surface text-lg"><?php echo htmlspecialchars($act->nombre_actividad); ?></h3>
                                    <?php if (!empty($act->descripcion)): ?>
                                        <p class="text-sm text-on-surface-variant mt-1"><?php echo htmlspecialchars($act->descripcion); ?></p>
                                    <?php endif; ?>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs bg-primary/10 text-primary font-medium">
                                            <span class="material-symbols-outlined" style="font-size:14px">label</span>
                                            <?php echo htmlspecialchars($act->nombre_tipo); ?>
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs bg-tertiary/10 text-tertiary font-medium">
                                            <span class="material-symbols-outlined" style="font-size:14px">apartment</span>
                                            <?php echo htmlspecialchars($act->nombre_sede); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-on-surface">
                                    <?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?>
                                </p>
                                <p class="text-xs text-on-surface-variant">
                                    <?php echo date('H:i', strtotime($act->fecha_hora_inicio)); ?>
                                    <?php if (!empty($act->fecha_hora_fin)): ?>
                                        — <?php echo date('H:i', strtotime($act->fecha_hora_fin)); ?>
                                    <?php endif; ?>
                                </p>
                                <?php
                                    $ahora = new DateTime();
                                    $inicio = new DateTime($act->fecha_hora_inicio);
                                    $fin    = !empty($act->fecha_hora_fin) ? new DateTime($act->fecha_hora_fin) : null;
                                    if ($ahora < $inicio) {
                                        $estado = ['label' => 'Próxima', 'class' => 'bg-blue-100 text-blue-800'];
                                    } elseif ($fin && $ahora > $fin) {
                                        $estado = ['label' => 'Finalizada', 'class' => 'bg-gray-100 text-gray-600'];
                                    } else {
                                        $estado = ['label' => 'En Curso', 'class' => 'bg-green-100 text-green-800'];
                                    }
                                ?>
                                <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-bold <?php echo $estado['class']; ?>">
                                    <?php echo $estado['label']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
