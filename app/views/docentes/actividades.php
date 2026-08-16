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
                    <h2 class="text-xl font-bold text-on-surface">Mis Actividades</h2>
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

        <div class="p-6 md:p-10 max-w-7xl mx-auto w-full flex-1">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">assignment</span>
                        Mis Actividades
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Gestión de clases y eventos programados.</p>
                </div>
            </div>

            <?php if (empty($data['actividades'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-12 text-center shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-outline mb-4 opacity-50">event_busy</span>
                    <h3 class="text-lg font-bold text-on-surface">No tiene actividades creadas</h3>
                    <p class="text-sm text-on-surface-variant mt-2 max-w-sm mx-auto">Las actividades que programe para sus grupos aparecerán aquí.</p>
                </div>
            <?php else: ?>
                <!-- Lista de Actividades -->
                <div class="space-y-4">
                    <?php foreach ($data['actividades'] as $act): ?>
                        <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md transition-all">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                                        <span class="material-symbols-outlined text-3xl">
                                            <?php echo ($act->nombre_tipo === 'Reunión de Padres') ? 'groups' : (($act->nombre_tipo === 'Evento Cultural/Deportivo') ? 'sports' : 'assignment'); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-on-surface text-lg"><?php echo htmlspecialchars($act->nombre_actividad); ?></h3>
                                        <?php if (!empty($act->descripcion)): ?>
                                            <p class="text-sm text-on-surface-variant mt-1 leading-relaxed"><?php echo htmlspecialchars($act->descripcion); ?></p>
                                        <?php endif; ?>
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-primary/10 text-primary font-bold">
                                                <span class="material-symbols-outlined text-sm">label</span>
                                                <?php echo htmlspecialchars($act->nombre_tipo); ?>
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-tertiary/10 text-tertiary font-bold">
                                                <span class="material-symbols-outlined text-sm">apartment</span>
                                                <?php echo htmlspecialchars($act->nombre_sede); ?>
                                            </span>
                                            <?php if (!empty($act->grupos)): ?>
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-secondary/10 text-secondary font-bold">
                                                    <span class="material-symbols-outlined text-sm">group</span>
                                                    <?php echo htmlspecialchars($act->grupos); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0 border-t md:border-t-0 md:border-l border-outline-variant/30 pt-3 md:pt-0 md:pl-6">
                                    <p class="text-sm font-bold text-on-surface">
                                        <?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?>
                                    </p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        <?php echo date('H:i', strtotime($act->fecha_hora_inicio)); ?>
                                        <?php if (!empty($act->fecha_hora_fin)): ?>
                                            — <?php echo date('H:i', strtotime($act->fecha_hora_fin)); ?>
                                        <?php endif; ?>
                                    </p>
                                    <?php
                                        $ahora = new DateTime();
                                        $inicio = new DateTime($act->fecha_hora_inicio);
                                        $fin = !empty($act->fecha_hora_fin) ? new DateTime($act->fecha_hora_fin) : (clone $inicio)->setTime(23, 59, 59);
                                        
                                        if ($ahora < $inicio) {
                                            $estado = ['label' => 'Próxima', 'class' => 'bg-blue-100 text-blue-800'];
                                        } elseif ($ahora > $fin) {
                                            $estado = ['label' => 'Finalizada', 'class' => 'bg-surface-variant text-on-surface-variant font-extrabold shadow-sm'];
                                        } else {
                                            $estado = ['label' => 'En Curso', 'class' => 'bg-green-100 text-green-800'];
                                        }
                                    ?>
                                    <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-bold <?php echo $estado['class']; ?>">
                                        <?php echo $estado['label']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

</body>
</html>
