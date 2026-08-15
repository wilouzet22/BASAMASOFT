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
    <button id="mobile-menu-toggle" class="p-2 text-on-surface-variant">
        <span class="material-symbols-outlined">menu</span>
    </button>
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
                    <p class="text-sm text-on-surface-variant mt-1">Historial de consultas y respuestas recibidas.</p>
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
                <p class="text-sm text-on-surface-variant mt-2 max-w-sm mx-auto">Cuando envíes un mensaje de contacto a maestros o directivas, aparecerá aquí junto con su respuesta.</p>
                <button type="button" onclick="openModal('contactosModal')" class="mt-6 inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-2.5 rounded-full font-bold shadow hover:scale-105 transition-all">
                    <span class="material-symbols-outlined text-sm">edit</span> Nuevo Mensaje
                </button>
            </div>
            <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($data['mensajes'] as $msg): ?>
                <?php 
                    // Estado para el padre: si hay respuesta y no está leída, es 'nueva'.
                    $esNuevaRespuesta = !empty($msg->respuesta) && !(bool)$msg->leido_familia; 
                    
                    // Identificar el remitente
                    if ($msg->destinatario_tipo === 'profesor') {
                        $destNombre = 'Prof. ' . htmlspecialchars($msg->prof_nombres . ' ' . $msg->prof_apellidos);
                        $destIcon = 'school';
                    } else {
                        $destNombre = 'Dir. ' . htmlspecialchars($msg->admin_nombres . ' ' . $msg->admin_apellidos);
                        $destIcon = 'domain';
                    }
                ?>
                <div class="bg-white rounded-3xl border <?php echo $esNuevaRespuesta ? 'border-primary shadow-md shadow-primary/10' : 'border-outline-variant shadow-sm'; ?> p-6 transition-all relative overflow-hidden group">
                    
                    <?php if ($esNuevaRespuesta): ?>
                    <div class="absolute top-0 right-0 bg-primary text-white text-[10px] font-bold px-4 py-1 rounded-bl-xl tracking-wider uppercase">
                        Nueva Respuesta
                    </div>
                    <?php endif; ?>

                    <!-- Tu Mensaje Original -->
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-xl">person</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <span class="font-bold text-on-surface text-sm block">Tú</span>
                                    <span class="text-xs text-on-surface-variant">
                                        Para: <?php echo $destNombre; ?> 
                                        <span class="material-symbols-outlined text-[10px] align-middle text-outline"><?php echo $destIcon; ?></span>
                                    </span>
                                </div>
                                <span class="text-xs text-on-surface-variant shrink-0 text-right">
                                    <?php echo (new DateTime($msg->fecha_envio))->format('d M Y, h:i A'); ?>
                                </span>
                            </div>
                            
                            <h4 class="font-bold text-sm text-on-surface mt-2"><?php echo htmlspecialchars($msg->titulo); ?></h4>
                            <?php if (!empty($msg->asunto)): ?>
                            <span class="text-[10px] font-bold bg-surface-container-high text-on-surface-variant px-2 py-0.5 rounded-full uppercase tracking-wider mb-2 inline-block"><?php echo htmlspecialchars($msg->asunto); ?></span>
                            <?php endif; ?>
                            
                            <p class="text-sm text-on-surface-variant leading-relaxed mt-1 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($msg->mensaje)); ?></p>
                        </div>
                    </div>

                    <!-- Respuesta de la institución -->
                    <?php if (!empty($msg->respuesta)): ?>
                    <div class="mt-5 ml-4 md:ml-12 pl-4 border-l-2 <?php echo $esNuevaRespuesta ? 'border-primary' : 'border-outline-variant'; ?>">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full <?php echo $esNuevaRespuesta ? 'bg-primary/10 text-primary' : 'bg-surface-container-high text-on-surface-variant'; ?> flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-sm"><?php echo $destIcon; ?></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-bold text-on-surface text-sm"><?php echo $destNombre; ?></span>
                                    <span class="text-[10px] text-on-surface-variant text-right">
                                        <?php echo (new DateTime($msg->fecha_respuesta))->format('d M Y, h:i A'); ?>
                                    </span>
                                </div>
                                <div class="bg-surface-container-lowest rounded-2xl p-4 text-sm text-on-surface leading-relaxed border border-outline-variant/30 shadow-sm whitespace-pre-line">
                                    <?php echo nl2br(htmlspecialchars($msg->respuesta)); ?>
                                </div>
                                
                                <?php if ($esNuevaRespuesta): ?>
                                <div class="mt-3 text-right">
                                    <a href="<?php echo URLROOT; ?>/padres/mensajes?leer=<?php echo $msg->id_mensaje; ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary/80 transition-colors">
                                        <span class="material-symbols-outlined text-sm">done_all</span> Marcar como visto
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="mt-4 ml-4 md:ml-14 flex items-center gap-2 text-xs text-outline font-medium italic">
                        <span class="material-symbols-outlined text-sm animate-pulse">schedule</span>
                        Esperando respuesta...
                    </div>
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
