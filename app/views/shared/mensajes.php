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
                            Mensajes
                        </h1>
                        <p class="text-sm text-on-surface-variant mt-1">
                            Historial de mensajes enviados y recibidos.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php if ($data['no_leidos'] > 0): ?>
                            <span class="bg-error text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">
                                <?php echo $data['no_leidos']; ?> sin leer
                            </span>
                        <?php endif; ?>
                        <button type="button" onclick="document.getElementById('modal-nuevo-mensaje').classList.remove('hidden')"
                            class="bg-primary text-on-primary font-bold px-4 py-2 rounded-lg shadow hover:bg-primary/90 transition-colors flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-sm">add_comment</span> Nuevo Mensaje
                        </button>
                    </div>
                </div>

                <!-- Lista de mensajes -->
                <?php if (empty($data['mensajes'])): ?>
                    <div class="flex flex-col items-center justify-center py-24 text-on-surface-variant gap-4">
                        <span class="material-symbols-outlined text-6xl opacity-30">mark_email_read</span>
                        <p class="text-lg font-semibold">No hay mensajes aún.</p>
                        <p class="text-sm">
                            Cuando envíes o recibas un mensaje, aparecerá aquí.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col gap-4">
                        <?php foreach ($data['mensajes'] as $msg): ?>
                            <?php 
                                $esEnviado = ($msg->remitente_tipo !== 'familia');
                                $leido = (bool)$msg->leido; 
                            ?>
                            <div class="relative bg-surface rounded-2xl shadow-sm border <?php echo (!$esEnviado && !$leido) ? 'border-primary/40 shadow-primary/10 shadow-md' : 'border-outline-variant'; ?> p-4 transition-all">

                                <div class="flex gap-3 items-start">
                                    <!-- Avatar -->
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 <?php echo $esEnviado ? 'bg-secondary-container text-on-secondary-container' : 'bg-primary-container text-on-primary-container'; ?>">
                                        <span class="material-symbols-outlined text-xl"><?php echo $esEnviado ? 'send' : 'family_restroom'; ?></span>
                                    </div>

                                    <!-- Contenido Principal -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-on-surface text-sm">
                                                    <?php if ($esEnviado): ?>
                                                        Para: Familia <?php echo htmlspecialchars($msg->nombre_principal_acudiente . ' ' . $msg->apellidos_principal_acudiente); ?>
                                                    <?php else: ?>
                                                        De: Familia <?php echo htmlspecialchars($msg->nombre_principal_acudiente . ' ' . $msg->apellidos_principal_acudiente); ?>
                                                    <?php endif; ?>
                                                </span>
                                                <?php if (!$esEnviado && !$leido): ?>
                                                    <span class="text-[10px] bg-primary text-white rounded-full px-2 py-0.5 font-bold ml-1 uppercase">Nuevo</span>
                                                <?php endif; ?>
                                                <?php if ($esEnviado): ?>
                                                    <span class="text-[10px] bg-secondary/10 text-secondary rounded-full px-2 py-0.5 font-bold ml-1 uppercase">Enviado</span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-xs text-on-surface-variant shrink-0 text-right">
                                                <?php
                                                $fecha = new DateTime($msg->fecha_envio);
                                                echo $fecha->format('d M Y, H:i');
                                                ?>
                                            </span>
                                        </div>

                                        <div class="mb-2">
                                            <h3 class="font-bold text-sm text-on-surface mb-0.5"><?php echo htmlspecialchars($msg->titulo); ?></h3>
                                            <?php if (!empty($msg->asunto)): ?>
                                                <p class="text-[10px] font-bold text-primary/70 uppercase tracking-wider">Asunto: <?php echo htmlspecialchars($msg->asunto); ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl p-3 text-sm text-on-surface leading-normal whitespace-pre-line break-words" style="height: auto !important; min-height: 0 !important;">
                                            <?php echo nl2br(htmlspecialchars(trim($msg->mensaje))); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="mt-4 ml-16 flex flex-wrap gap-2 justify-end">
                                    <?php if (!$esEnviado && !$leido): ?>
                                        <a href="<?php echo URLROOT . ($isAdmin ? '/admin' : '/docentes'); ?>/mensajes?leer=<?php echo $msg->id_mensaje; ?>"
                                            class="inline-flex items-center gap-2 text-xs font-bold text-primary hover:text-white hover:bg-primary px-4 py-2 rounded-lg transition-colors border border-primary/30 hover:border-transparent cursor-pointer">
                                            <span class="material-symbols-outlined text-sm">done_all</span> Marcar como visto
                                        </a>
                                    <?php endif; ?>

                                    <form action="<?php echo URLROOT . ($isAdmin ? '/admin' : '/docentes'); ?>/eliminar_mensaje/<?php echo $msg->id_mensaje; ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este mensaje? Esta acción no se puede deshacer.');" class="inline-block">
                                        <button type="submit" class="inline-flex items-center gap-2 text-xs font-bold text-error hover:text-white hover:bg-error px-4 py-2 rounded-lg transition-colors border border-error/30 hover:border-transparent cursor-pointer">
                                            <span class="material-symbols-outlined text-sm">delete</span> Eliminar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <!-- Modal Nuevo Mensaje -->
    <div id="modal-nuevo-mensaje" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4 bg-scrim/50 backdrop-blur-sm">
        <div class="bg-surface rounded-2xl shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low shrink-0">
                <h3 class="text-xl font-bold text-on-surface">Nuevo Mensaje</h3>
                <button type="button" onclick="document.getElementById('modal-nuevo-mensaje').classList.add('hidden')" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-surface-variant transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form action="<?php echo URLROOT . ($isAdmin ? '/admin' : '/docentes'); ?>/mensajes" method="POST" class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Destinatario (Familia)</label>
                        <select name="id_familia" required class="w-full bg-white border border-outline-variant rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Seleccione una familia...</option>
                            <?php if(!empty($data['familias'])): ?>
                                <?php foreach($data['familias'] as $fam): ?>
                                    <option value="<?php echo $fam->id_familia; ?>">Familia <?php echo htmlspecialchars($fam->nombre_principal_acudiente . ' ' . $fam->apellidos_principal_acudiente); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Título</label>
                        <input type="text" name="titulo" required class="w-full bg-white border border-outline-variant rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Ej. Rendimiento del estudiante">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Asunto (Opcional)</label>
                        <input type="text" name="asunto" class="w-full bg-white border border-outline-variant rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Ej. Matemáticas">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Mensaje</label>
                        <textarea name="mensaje" rows="4" required class="w-full bg-white border border-outline-variant rounded-lg p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none" placeholder="Escribe aquí tu mensaje..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 mt-2">
                        <button type="button" onclick="document.getElementById('modal-nuevo-mensaje').classList.add('hidden')" class="px-5 py-2.5 font-bold text-sm rounded-lg text-on-surface-variant hover:bg-surface-variant transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 font-bold text-sm rounded-lg bg-primary text-on-primary shadow hover:bg-primary/90 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">send</span> Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>

</html>