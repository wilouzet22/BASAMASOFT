<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-10 w-10 rounded-full" alt="Logo">
        <span class="font-bold text-primary">EduSaft</span>
    </div>
</header>

<div class="flex">
    <!-- Sidebar reusable -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Mis Mensajes</h2>
                    <p class="text-sm text-on-surface-variant">
                        Bienvenido, <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface">Acudiente</p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Portal Familiar</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-10 max-w-4xl mx-auto w-full flex-1">

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">mail</span>
                        Mis Mensajes
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Historial de mensajes enviados y recibidos.</p>
                </div>
                <?php if ($data['no_leidos'] > 0): ?>
                    <span class="bg-error text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                        <?php echo $data['no_leidos']; ?> nuevos
                    </span>
                <?php endif; ?>
            </div>

            <!-- Lista de mensajes -->
            <?php if (empty($data['mensajes'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-10 text-center shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-outline mb-4 opacity-50">mark_email_read</span>
                    <h3 class="text-lg font-bold text-on-surface">No tienes mensajes</h3>
                    <p class="text-sm text-on-surface-variant mt-2 max-w-sm mx-auto">Cuando envíes o recibas un mensaje, aparecerá aquí.</p>
                    <button type="button" onclick="openModal('contactosModal')" class="mt-6 inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-full font-bold shadow hover:scale-105 transition-all">
                        <span class="material-symbols-outlined text-sm">edit</span> Nuevo Mensaje
                    </button>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($data['mensajes'] as $msg): ?>
                        <?php
                        $esRecibido = ($msg->destinatario_tipo === 'familia');
                        $esNuevo = $esRecibido && !(bool)$msg->leido;

                        if ($msg->id_profesor_fk) {
                            $otroNombre = 'Prof. ' . htmlspecialchars($msg->prof_nombres . ' ' . $msg->prof_apellidos);
                            $otroIcon = 'school';
                        } else {
                            $otroNombre = 'Dir. ' . htmlspecialchars($msg->admin_nombres . ' ' . $msg->admin_apellidos);
                            $otroIcon = 'domain';
                        }
                        ?>
                        <div class="bg-white rounded-3xl border <?php echo $esNuevo ? 'border-primary shadow-md shadow-primary/10' : 'border-outline-variant shadow-sm'; ?> p-4 transition-all relative overflow-hidden group">

                            <?php if ($esNuevo): ?>
                                <div class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl tracking-wider uppercase">
                                    Nuevo
                                </div>
                            <?php endif; ?>

                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full <?php echo $esRecibido ? 'bg-primary/10 text-primary' : 'bg-surface-container text-on-surface-variant'; ?> flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-xl"><?php echo $esRecibido ? $otroIcon : 'person'; ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <div>
                                            <span class="font-bold text-on-surface text-sm block">
                                                <?php echo $esRecibido ? $otroNombre : 'Tú'; ?>
                                            </span>
                                            <span class="text-xs text-on-surface-variant">
                                                <?php if ($esRecibido): ?>
                                                    Para: Tú
                                                <?php else: ?>
                                                    Para: <?php echo $otroNombre; ?>
                                                    <span class="material-symbols-outlined text-[10px] align-middle text-outline"><?php echo $otroIcon; ?></span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <span class="text-xs text-on-surface-variant shrink-0 text-right">
                                            <?php echo (new DateTime($msg->fecha_envio))->format('d M Y, h:i A'); ?>
                                        </span>
                                    </div>

                                    <h4 class="font-bold text-sm text-on-surface mt-1"><?php echo htmlspecialchars($msg->titulo); ?></h4>
                                    <?php if (!empty($msg->asunto)): ?>
                                        <span class="text-[10px] font-bold bg-surface-container-high text-on-surface-variant px-2 py-0.5 rounded-full uppercase tracking-wider mb-2 inline-block"><?php echo htmlspecialchars($msg->asunto); ?></span>
                                    <?php endif; ?>

                                    <div class="bg-surface-container-lowest rounded-2xl p-3 text-sm text-on-surface leading-relaxed border border-outline-variant/30 shadow-sm mt-2 break-words">
                                        <?php echo nl2br(htmlspecialchars(trim($msg->mensaje))); ?>
                                    </div>

                                    <?php if ($esNuevo): ?>
                                        <div class="mt-3 text-right">
                                            <a href="<?php echo URLROOT; ?>/padres/mensajes?leer=<?php echo $msg->id_mensaje; ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                                                <span class="material-symbols-outlined text-sm">done_all</span> Marcar como visto
                                            </a>
                                        </div>
                                    <?php endif; ?>
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