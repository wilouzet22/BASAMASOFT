<?php $data = $data ?? [];
require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen" style="margin-left:16rem">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleDocentesCollapse()"
                    class="hidden md:flex w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="text-xl font-extrabold tracking-tight text-primary md:hidden">Edusaft</div>
                <div class="hidden md:block text-on-surface-variant text-sm">Panel Docente</div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-on-surface-variant hidden md:inline"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold border border-outline text-on-surface-variant hover:bg-surface-container transition-colors">
                    Salir
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 md:p-8">
            <div class="max-w-3xl mx-auto">

                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-3xl">notifications</span>
                            Notificaciones
                        </h1>
                        <p class="text-sm text-on-surface-variant mt-1">Opiniones y comentarios enviados por las familias.</p>
                    </div>
                    <?php if ($data['no_leidas'] > 0): ?>
                        <span class="bg-error text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">
                            <?php echo $data['no_leidas']; ?> sin leer
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Lista de opiniones -->
                <?php if (empty($data['opiniones'])): ?>
                    <div class="flex flex-col items-center justify-center py-24 text-on-surface-variant gap-4">
                        <span class="material-symbols-outlined text-6xl opacity-30">mark_chat_read</span>
                        <p class="text-lg font-semibold">No hay notificaciones aún.</p>
                        <p class="text-sm">Cuando una familia envíe una opinión, aparecerá aquí.</p>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($data['opiniones'] as $op): ?>
                            <?php $leida = (bool)$op->leida; ?>
                            <div class="relative bg-surface rounded-2xl shadow-sm border <?php echo $leida ? 'border-outline-variant' : 'border-primary/40 shadow-primary/10 shadow-md'; ?> p-5 flex gap-4 items-start transition-all">
                                <!-- Indicador no leído -->
                                <?php if (!$leida): ?>
                                    <span class="absolute top-4 right-4 w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                                <?php endif; ?>

                                <!-- Avatar -->
                                <div class="w-11 h-11 rounded-full flex items-center justify-center shrink-0 <?php echo $leida ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container text-on-primary-container'; ?>">
                                    <span class="material-symbols-outlined text-2xl">person</span>
                                </div>

                                <!-- Contenido -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="font-bold text-on-surface text-sm">
                                            <?php echo htmlspecialchars($op->nombre_principal_acudiente . ' ' . $op->apellidos_principal_acudiente); ?>
                                        </span>
                                        <span class="text-xs text-on-surface-variant">·</span>
                                        <span class="text-xs text-on-surface-variant">
                                            <?php
                                            $fecha = new DateTime($op->fecha_creacion);
                                            echo $fecha->format('d M Y, H:i');
                                            ?>
                                        </span>
                                        <?php if (!$leida): ?>
                                            <span class="text-xs bg-primary text-white rounded-full px-2 py-0.5 font-semibold">Nueva</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm text-on-surface leading-relaxed whitespace-pre-line">
                                        <?php echo nl2br(htmlspecialchars($op->mensaje)); ?>
                                    </p>
                                </div>

                                <!-- Acción marcar leída -->
                                <?php if (!$leida): ?>
                                    <a href="<?php echo URLROOT; ?>/docentes/notificaciones?leer=<?php echo $op->id_opinion; ?>"
                                        title="Marcar como leída"
                                        class="shrink-0 w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-container transition-colors text-primary">
                                        <span class="material-symbols-outlined text-xl">done_all</span>
                                    </a>
                                <?php else: ?>
                                    <span class="shrink-0 w-9 h-9 flex items-center justify-center text-on-surface-variant opacity-40">
                                        <span class="material-symbols-outlined text-xl">done_all</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>

</html>