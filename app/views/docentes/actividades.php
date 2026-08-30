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
                <button onclick="document.getElementById('modal-crear-actividad').showModal()" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">add</span>
                    Crear Actividad
                </button>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'validation'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> Complete todos los campos obligatorios y seleccione al menos un grupo.
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized_group'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> No tiene permiso para crear actividades en ese grupo.
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> Ya existe una actividad con ese nombre, fecha y sede.
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'upload_permission'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> No tiene permiso para subir imagen a esta actividad o la actividad no ha finalizado.
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_image'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> Formato de imagen no válido. Use JPG, PNG, GIF o WEBP (máx. 5 MB).
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'upload_failed'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> Error al subir la imagen. Intente nuevamente.
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success'): ?>
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">check_circle</span> Imagen principal guardada correctamente.
            </div>
            <?php endif; ?>

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
                                    <?php if ($estado['label'] === 'Finalizada'): ?>
                                        <button onclick="abrirModalFoto(<?php echo $act->id_actividad; ?>, '<?php echo htmlspecialchars(addslashes($act->nombre_actividad)); ?>')" class="mt-2 text-[11px] font-bold text-primary hover:text-primary/80 flex items-center justify-end gap-1 w-full transition-colors">
                                            <span class="material-symbols-outlined text-[14px]">add_a_photo</span>
                                            Definir imagen principal
                                        </button>
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

    <!-- Modal Subir Foto -->
    <dialog id="modal-subir-foto" class="fixed inset-0 z-[100] m-auto w-[92%] max-w-sm max-h-[88vh] bg-surface rounded-3xl shadow-2xl p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm border border-outline-variant/60 outline-none overflow-hidden open:animate-in open:fade-in open:zoom-in-95">
        <div class="flex flex-col h-full">
            <div class="flex justify-between items-center px-6 py-4.5 border-b border-outline-variant bg-surface-container/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined text-2xl">add_a_photo</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-on-surface tracking-tight">Imagen principal</h3>
                        <p class="text-[10px] font-bold text-primary uppercase" id="foto-actividad-nombre">Cargando...</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-subir-foto').close()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-variant text-on-surface-variant hover:text-on-surface transition-all active:scale-95" title="Cerrar">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <div class="p-6">
                <form id="form-subir-foto" action="<?php echo URLROOT; ?>/docentes/subir_foto_actividad" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                    <input type="hidden" name="id_actividad" id="foto_id_actividad" value="">
                    <div class="flex flex-col gap-1.5">
                        <label for="foto_actividad" class="text-xs font-bold text-on-surface uppercase tracking-wider">
                            Seleccionar imagen (PNG, JPG, GIF o WEBP; máx. 5 MB) <span class="text-error">*</span>
                        </label>
                        <input type="file" id="foto_actividad" name="foto_actividad" accept="image/*" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface text-sm font-medium">
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant flex justify-end items-center gap-3 bg-surface-container/50">
                <button type="button" onclick="document.getElementById('modal-subir-foto').close()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-variant transition-colors active:scale-95">
                    Cancelar
                </button>
                <button type="submit" form="form-subir-foto" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-primary text-on-primary hover:bg-primary/90 shadow-md shadow-primary/20 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-lg">upload</span>
                    Guardar imagen principal
                </button>
            </div>
        </div>
    </dialog>
    
    <script>
    function abrirModalFoto(id, nombre) {
        document.getElementById('foto_id_actividad').value = id;
        document.getElementById('foto-actividad-nombre').textContent = nombre;
        document.getElementById('modal-subir-foto').showModal();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const fechaInicio = document.getElementById('fecha_hora_inicio');
        const fechaFin = document.getElementById('fecha_hora_fin');

        if (fechaInicio && fechaFin) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            fechaInicio.min = now.toISOString().slice(0, 16);

            function checkWeekend(inputEl) {
                if (!inputEl.value) return;
                const date = new Date(inputEl.value);
                const day = date.getDay();
                if (day === 0 || day === 6) {
                    alert('No se pueden seleccionar días sábados ni domingos.');
                    inputEl.value = '';
                }
            }

            fechaInicio.addEventListener('change', (e) => {
                checkWeekend(e.target);
                if (e.target.value) {
                    fechaFin.min = e.target.value;
                    if (fechaFin.value && fechaFin.value < e.target.value) {
                        fechaFin.value = '';
                    }
                }
            });

            fechaFin.addEventListener('change', (e) => {
                checkWeekend(e.target);
            });
        }
    });
    </script>

    <!-- Modal Crear Actividad -->
    <dialog id="modal-crear-actividad" class="fixed inset-0 z-[100] m-auto w-[92%] max-w-2xl max-h-[88vh] bg-surface rounded-3xl shadow-2xl p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm border border-outline-variant/60 outline-none overflow-hidden open:animate-in open:fade-in open:zoom-in-95">
        <div class="flex flex-col h-full max-h-[88vh]">
            <div class="flex justify-between items-center px-6 py-4.5 border-b border-outline-variant bg-surface-container/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shadow-sm">
                        <span class="material-symbols-outlined text-2xl">event_available</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-on-surface tracking-tight">Crear Nueva Actividad</h3>
                        <p class="text-xs text-on-surface-variant">Programa una nueva actividad para tus grupos</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-crear-actividad').close()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-variant text-on-surface-variant hover:text-on-surface transition-all active:scale-95" title="Cerrar">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            <div class="p-6 overflow-y-auto space-y-5 flex-1 custom-scrollbar">
                <form id="form-crear-actividad" action="<?php echo URLROOT; ?>/docentes/crear_actividad" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">

                    <div class="space-y-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="nombre_actividad" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-sm">edit_note</span>
                                Nombre de la Actividad <span class="text-error">*</span>
                            </label>
                            <input type="text" id="nombre_actividad" name="nombre_actividad" required placeholder="Ej: Clase de repaso Matemáticas" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:bg-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm font-medium">
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label for="descripcion" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-sm">description</span>
                                Descripción (Opcional)
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="2" placeholder="Detalles, objetivos o instrucciones..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:bg-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm font-medium resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="imagen_principal" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-sm">image</span>
                            Imagen principal (Opcional)
                        </label>
                        <input type="file" id="imagen_principal" name="imagen_principal" accept="image/png,image/jpeg,image/gif,image/webp" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface text-sm font-medium">
                        <p class="text-[11px] text-on-surface-variant">Máximo 5 MB. Se guardará como imagen de la actividad.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-surface-container/30 border border-outline-variant/60 space-y-3">
                        <span class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">schedule</span>
                            Horario y Programación
                        </span>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label for="fecha_hora_inicio" class="text-xs font-semibold text-on-surface">Fecha y Hora Inicio <span class="text-error">*</span></label>
                                <input type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label for="fecha_hora_fin" class="text-xs font-semibold text-on-surface">Fecha y Hora Fin (Opcional)</label>
                                <input type="datetime-local" id="fecha_hora_fin" name="fecha_hora_fin" class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="id_tipo_actividad_fk" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-sm">category</span>
                                Tipo de Actividad <span class="text-error">*</span>
                            </label>
                            <select id="id_tipo_actividad_fk" name="id_tipo_actividad_fk" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                                <option value="">Seleccione un tipo...</option>
                                <?php foreach($data['tipos_actividad'] as $tipo): ?>
                                    <option value="<?php echo $tipo->id_tipo_actividad; ?>"><?php echo htmlspecialchars($tipo->nombre_tipo); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="id_sede_fk" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-primary text-sm">apartment</span>
                                Sede <span class="text-error">*</span>
                            </label>
                            <select id="id_sede_fk" name="id_sede_fk" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                                <option value="">Seleccione una sede...</option>
                                <?php foreach($data['sedes'] as $sede): ?>
                                    <option value="<?php echo $sede->id_sede; ?>"><?php echo htmlspecialchars($sede->nombre_sede); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-surface-container/30 border border-outline-variant/60 space-y-3">
                        <span class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">groups</span>
                            Grupos Participantes <span class="text-error">*</span>
                        </span>
                        <p class="text-xs text-on-surface-variant">Selecciona uno o más de tus grupos asignados:</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3 border border-outline-variant rounded-xl bg-surface max-h-44 overflow-y-auto custom-scrollbar">
                            <?php foreach($data['grupos'] as $grupo): ?>
                            <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-container transition-colors cursor-pointer text-xs text-on-surface">
                                <input type="checkbox" name="grupos[]" value="<?php echo $grupo->id_grupo; ?>" class="rounded text-primary focus:ring-primary border-outline">
                                <span class="font-medium truncate"><?php echo htmlspecialchars($grupo->nombre_grupo); ?></span>
                                <span class="text-[10px] text-on-surface-variant"><?php echo htmlspecialchars($grupo->nombre_grado . ' - ' . $grupo->nombre_sede); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                            Requiere asistencia por hijo
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="requiere_asistencia_por_hijo" value="1" checked class="w-4 h-4 text-primary focus:ring-primary border-outline">
                            <span class="text-sm text-on-surface">Sí, registrar asistencia individual por estudiante</span>
                        </label>
                    </div>

                </form>
            </div>

            <div class="px-6 py-4 border-t border-outline-variant flex justify-end items-center gap-3 bg-surface-container/50">
                <button type="button" onclick="document.getElementById('modal-crear-actividad').close()" class="px-5 py-2.5 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-variant transition-colors active:scale-95">
                    Cancelar
                </button>
                <button type="submit" form="form-crear-actividad" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-primary text-on-primary hover:bg-primary/90 shadow-md shadow-primary/20 transition-all flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Guardar Actividad
                </button>
            </div>
        </div>
    </dialog>

</body>
</html>
