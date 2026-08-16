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
                    <h2 class="text-xl font-bold text-on-surface">Notificaciones</h2>
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

        <div class="p-6 md:p-10 max-w-4xl mx-auto w-full flex-1">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">notifications</span>
                        Notificaciones
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Opiniones y comentarios enviados por las familias.</p>
                </div>
                <?php if ($data['no_leidas'] > 0): ?>
                    <span class="bg-error text-white text-xs font-bold px-3.5 py-1.5 rounded-full shadow-sm">
                        <?php echo $data['no_leidas']; ?> sin leer
                    </span>
                <?php endif; ?>
            </div>

            <!-- Lista de opiniones -->
            <?php if (empty($data['opiniones'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-12 text-center shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-outline mb-4 opacity-50">mark_chat_read</span>
                    <h3 class="text-lg font-bold text-on-surface">No hay notificaciones aún</h3>
                    <p class="text-sm text-on-surface-variant mt-2 max-w-sm mx-auto">Cuando una familia envíe una opinión o comentario, aparecerá aquí.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($data['opiniones'] as $op): ?>
                        <?php $leida = (bool)$op->leida; ?>
                        <div class="relative bg-white rounded-3xl shadow-sm border <?php echo $leida ? 'border-outline-variant' : 'border-primary shadow-md shadow-primary/10'; ?> p-5 flex gap-4 items-start transition-all overflow-hidden group">
                            
                            <!-- Indicador no leído -->
                            <?php if (!$leida): ?>
                                <span class="absolute top-4 right-4 w-3 h-3 rounded-full bg-primary animate-pulse"></span>
                            <?php endif; ?>

                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 <?php echo $leida ? 'bg-surface-container text-on-surface-variant' : 'bg-primary/10 text-primary'; ?>">
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
                                        <span class="text-[10px] bg-primary text-white rounded-full px-2 py-0.5 font-bold uppercase tracking-wider">Nueva</span>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-3.5 text-sm text-on-surface leading-relaxed mt-2 break-words" style="height: auto !important; min-height: 0 !important;">
                                    <?php echo nl2br(htmlspecialchars(trim($op->mensaje))); ?>
                                </div>
                            </div>

                            <!-- Acción marcar leída -->
                            <?php if (!$leida): ?>
                                <a href="<?php echo URLROOT; ?>/docentes/notificaciones?leer=<?php echo $op->id_opinion; ?>"
                                    title="Marcar como leída"
                                    class="shrink-0 w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary/10 transition-colors text-primary border border-primary/20">
                                    <span class="material-symbols-outlined text-xl">done_all</span>
                                </a>
                            <?php else: ?>
                                <span class="shrink-0 w-10 h-10 flex items-center justify-center text-on-surface-variant opacity-40">
                                    <span class="material-symbols-outlined text-xl">done_all</span>
                                </span>
                            <?php endif; ?>
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