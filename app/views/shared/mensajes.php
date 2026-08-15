<?php 
$data = $data ?? []; 
require APPROOT . '/views/inc/header.php'; 

$isAdmin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador');
?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <?php 
    if ($isAdmin) {
        require APPROOT . '/views/admin/sidebar.php'; 
    } else {
        require APPROOT . '/views/docentes/sidebar.php'; 
    }
    ?>

    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen" style="margin-left:16rem">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-3">
                <?php if ($isAdmin): ?>
                <button type="button" onclick="toggleMobileSidebar()"
                        class="md:hidden flex w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <?php else: ?>
                <button type="button" onclick="toggleDocentesCollapse()"
                        class="hidden md:flex w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <?php endif; ?>
                
                <div class="text-xl font-extrabold tracking-tight text-primary md:hidden">Edusaft</div>
                <div class="hidden md:block text-on-surface-variant text-sm">
                    <?php echo $isAdmin ? 'Administración' : 'Panel Docente'; ?>
                </div>
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
            <div class="max-w-4xl mx-auto">

                <!-- Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-3xl">mail</span>
                            Mensajes de Familias
                        </h1>
                        <p class="text-sm text-on-surface-variant mt-1">
                            <?php echo $isAdmin ? 'Consultas y mensajes de contacto dirigidos a las directivas.' : 'Consultas y mensajes de contacto enviados por los acudientes.'; ?>
                        </p>
                    </div>
                    <?php if ($data['no_leidos'] > 0): ?>
                    <span class="bg-error text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">
                        <?php echo $data['no_leidos']; ?> sin leer
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Lista de mensajes -->
                <?php if (empty($data['mensajes'])): ?>
                <div class="flex flex-col items-center justify-center py-24 text-on-surface-variant gap-4">
                    <span class="material-symbols-outlined text-6xl opacity-30">mark_email_read</span>
                    <p class="text-lg font-semibold">No hay mensajes aún.</p>
                    <p class="text-sm">
                        <?php echo $isAdmin ? 'Cuando una familia envíe un mensaje a las directivas, aparecerá aquí.' : 'Cuando una familia envíe un mensaje de contacto, aparecerá aquí.'; ?>
                    </p>
                </div>
                <?php else: ?>
                <div class="flex flex-col gap-4">
                    <?php foreach ($data['mensajes'] as $msg): ?>
                    <?php $leido = (bool)$msg->leido; ?>
                    <div class="relative bg-surface rounded-2xl shadow-sm border <?php echo $leido ? 'border-outline-variant' : 'border-primary/40 shadow-primary/10 shadow-md'; ?> p-5 transition-all">
                        
                        <div class="flex gap-4 items-start">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 <?php echo $leido ? 'bg-surface-container text-on-surface-variant' : 'bg-primary-container text-on-primary-container'; ?>">
                                <span class="material-symbols-outlined text-2xl">family_restroom</span>
                            </div>

                            <!-- Contenido Principal -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-on-surface text-base">
                                            Familia <?php echo htmlspecialchars($msg->nombre_principal_acudiente . ' ' . $msg->apellidos_principal_acudiente); ?>
                                        </span>
                                        <?php if (!empty($msg->email_contacto)): ?>
                                        <span class="text-xs text-on-surface-variant">· <?php echo htmlspecialchars($msg->email_contacto); ?></span>
                                        <?php endif; ?>
                                        <?php if (!$leido): ?>
                                        <span class="text-xs bg-primary text-white rounded-full px-2 py-0.5 font-semibold ml-2">Nuevo</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs text-on-surface-variant shrink-0 text-right">
                                        <?php
                                            $fecha = new DateTime($msg->fecha_envio);
                                            echo $fecha->format('d M Y, H:i');
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <h3 class="font-bold text-sm text-on-surface mb-0.5"><?php echo htmlspecialchars($msg->titulo); ?></h3>
                                    <?php if (!empty($msg->asunto)): ?>
                                    <p class="text-xs font-semibold text-primary/70 uppercase tracking-wider">Asunto: <?php echo htmlspecialchars($msg->asunto); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="bg-surface-container-low border border-outline-variant/50 rounded-xl p-4 text-sm text-on-surface leading-relaxed whitespace-pre-line">
                                    <?php echo nl2br(htmlspecialchars($msg->mensaje)); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Respuesta del profesor/admin -->
                        <?php if (!empty($msg->respuesta)): ?>
                        <div class="mt-4 ml-16 bg-primary/5 border border-primary/20 rounded-xl p-4 text-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-primary text-sm">reply</span>
                                <span class="font-bold text-primary">Tu respuesta</span>
                                <span class="text-xs text-on-surface-variant ml-auto">
                                    <?php echo (new DateTime($msg->fecha_respuesta))->format('d M Y, H:i'); ?>
                                </span>
                            </div>
                            <div class="text-on-surface leading-relaxed whitespace-pre-line">
                                <?php echo nl2br(htmlspecialchars($msg->respuesta)); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Acciones -->
                        <div class="mt-4 ml-16 flex flex-wrap gap-2 justify-end">
                            <?php if (empty($msg->respuesta)): ?>
                            <button type="button" onclick="document.getElementById('reply-form-<?php echo $msg->id_mensaje; ?>').classList.toggle('hidden')"
                               class="inline-flex items-center gap-2 text-xs font-bold text-secondary hover:text-white hover:bg-secondary px-4 py-2 rounded-lg transition-colors border border-secondary/30 hover:border-transparent cursor-pointer">
                                <span class="material-symbols-outlined text-sm">reply</span> Responder
                            </button>
                            <?php endif; ?>

                            <?php if (!$leido): ?>
                            <a href="<?php echo URLROOT . ($isAdmin ? '/admin' : '/docentes'); ?>/mensajes?leer=<?php echo $msg->id_mensaje; ?>"
                               class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-on-primary hover:bg-primary px-4 py-2 rounded-lg transition-colors border border-primary/30 hover:border-transparent">
                                <span class="material-symbols-outlined text-sm">done_all</span> Marcar como leído
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Formulario de respuesta (oculto) -->
                        <?php if (empty($msg->respuesta)): ?>
                        <div id="reply-form-<?php echo $msg->id_mensaje; ?>" class="hidden mt-4 ml-16">
                            <form action="<?php echo URLROOT . ($isAdmin ? '/admin' : '/docentes'); ?>/mensajes" method="POST" class="bg-surface-container rounded-xl p-4 border border-outline-variant">
                                <input type="hidden" name="id_mensaje" value="<?php echo $msg->id_mensaje; ?>">
                                <label class="block text-sm font-bold text-on-surface mb-2">Escribe tu respuesta:</label>
                                <textarea name="respuesta" rows="3" required
                                          class="w-full bg-white border border-outline-variant rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                          placeholder="Hola familia..."></textarea>
                                <div class="mt-3 flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('reply-form-<?php echo $msg->id_mensaje; ?>').classList.add('hidden')"
                                            class="px-4 py-2 text-xs font-bold text-on-surface-variant hover:bg-surface-variant rounded-lg transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 text-xs font-bold bg-primary text-on-primary rounded-lg shadow hover:bg-primary/90 transition-colors flex items-center gap-2">
                                        <span class="material-symbols-outlined text-sm">send</span> Enviar Respuesta
                                    </button>
                                </div>
                            </form>
                        </div>
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
