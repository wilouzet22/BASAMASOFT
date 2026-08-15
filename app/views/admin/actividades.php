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
                <button onclick="document.getElementById('modal-crear-actividad').showModal()" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-base">add</span>
                    Crear Actividad
                </button>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'not_jan1'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined">warning</span> La actividad "Inicio de año" solo puede crearse el 1 de enero.
            </div>
            <?php endif; ?>

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
                                    // Si no tiene fecha de fin, se asume que termina al final del día de inicio
                                    $fin = !empty($act->fecha_hora_fin) ? new DateTime($act->fecha_hora_fin) : (clone $inicio)->setTime(23, 59, 59);
                                    
                                    if ($ahora < $inicio) {
                                        $estado = ['label' => 'Próxima', 'class' => 'bg-blue-100 text-blue-800'];
                                    } elseif ($ahora > $fin) {
                                        $estado = ['label' => 'Finalizada', 'class' => 'bg-surface-variant text-on-surface-variant font-extrabold shadow-sm'];
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
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>

    <!-- Modal Crear Actividad -->
    <dialog id="modal-crear-actividad" class="bg-surface rounded-2xl shadow-xl w-full max-w-2xl p-0 backdrop:bg-black/50 open:animate-in open:fade-in open:zoom-in-95">
        <div class="flex flex-col h-full max-h-[90vh]">
            <div class="flex justify-between items-center p-6 border-b border-outline-variant">
                <h3 class="text-xl font-bold text-primary">Crear Nueva Actividad</h3>
                <button type="button" onclick="document.getElementById('modal-crear-actividad').close()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-surface-container text-on-surface-variant transition-colors">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form id="form-crear-actividad" action="<?php echo URLROOT; ?>/admin/crear_actividad" method="POST" class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label for="nombre_actividad" class="text-sm font-semibold text-on-surface">Nombre de la Actividad</label>
                        <input type="text" id="nombre_actividad" name="nombre_actividad" required class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label for="descripcion" class="text-sm font-semibold text-on-surface">Descripción (Opcional)</label>
                        <textarea id="descripcion" name="descripcion" rows="3" class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label for="fecha_hora_inicio" class="text-sm font-semibold text-on-surface">Fecha y Hora de Inicio</label>
                            <input type="datetime-local" id="fecha_hora_inicio" name="fecha_hora_inicio" required class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="fecha_hora_fin" class="text-sm font-semibold text-on-surface">Fecha y Hora de Fin (Opcional)</label>
                            <input type="datetime-local" id="fecha_hora_fin" name="fecha_hora_fin" class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label for="id_tipo_actividad_fk" class="text-sm font-semibold text-on-surface">Tipo de Actividad</label>
                            <select id="id_tipo_actividad_fk" name="id_tipo_actividad_fk" required class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="">Seleccione un tipo</option>
                                <?php foreach($data['tipos_actividad'] as $tipo): ?>
                                    <option value="<?php echo $tipo->id_tipo_actividad; ?>"><?php echo htmlspecialchars($tipo->nombre_tipo); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="id_sede_fk" class="text-sm font-semibold text-on-surface">Sede</label>
                            <select id="id_sede_fk" name="id_sede_fk" required class="px-4 py-2.5 rounded-lg border border-outline bg-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all">
                                <option value="">Seleccione una sede</option>
                                <?php foreach($data['sedes'] as $sede): ?>
                                    <option value="<?php echo $sede->id_sede; ?>"><?php echo htmlspecialchars($sede->nombre_sede); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 p-4 bg-surface-container/50 rounded-xl border border-outline-variant">
                        <span class="text-sm font-semibold text-on-surface">Alcance de la Actividad</span>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="tipo_alcance" value="general" checked onchange="toggleGruposSection(this.value)" class="w-4 h-4 text-primary focus:ring-primary border-outline">
                                <span class="text-sm text-on-surface-variant">Actividad General (Toda la sede)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="tipo_alcance" value="grupo" onchange="toggleGruposSection(this.value)" class="w-4 h-4 text-primary focus:ring-primary border-outline">
                                <span class="text-sm text-on-surface-variant">Actividad de Grupo</span>
                            </label>
                        </div>
                    </div>

                    <div id="seccion-grupos" class="flex flex-col gap-1.5 hidden">
                        <label class="text-sm font-semibold text-on-surface">Seleccionar Grupos</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 border border-outline-variant rounded-xl bg-surface">
                            <?php foreach($data['grupos'] as $grupo): ?>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="grupos[]" value="<?php echo $grupo->id_grupo; ?>" class="rounded text-primary focus:ring-primary border-outline">
                                <span class="text-sm text-on-surface-variant"><?php echo htmlspecialchars($grupo->nombre_grupo); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-1">Selecciona los grupos que participarán en esta actividad.</p>
                    </div>

                </form>
            </div>
            <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-container/30">
                <button type="button" onclick="document.getElementById('modal-crear-actividad').close()" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition-colors">Cancelar</button>
                <button type="submit" form="form-crear-actividad" class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-primary text-white hover:bg-primary/90 transition-colors shadow-sm">Guardar Actividad</button>
            </div>
        </div>
    </dialog>
    
    <script>
    function toggleGruposSection(value) {
        const seccionGrupos = document.getElementById('seccion-grupos');
        if (value === 'grupo') {
            seccionGrupos.classList.remove('hidden');
        } else {
            seccionGrupos.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const fechaInicio = document.getElementById('fecha_hora_inicio');
        const fechaFin = document.getElementById('fecha_hora_fin');
        
        if (fechaInicio && fechaFin) {
            // 1. Establecer 'min' en inicio para no permitir fechas pasadas
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const nowFormatted = now.toISOString().slice(0, 16);
            fechaInicio.min = nowFormatted;

            function checkWeekend(inputEl) {
                if (!inputEl.value) return;
                const date = new Date(inputEl.value);
                const day = date.getDay(); // 0 = Sunday, 6 = Saturday
                if (day === 0 || day === 6) {
                    alert('No se pueden seleccionar días sábados ni domingos.');
                    inputEl.value = '';
                }
            }

            fechaInicio.addEventListener('change', (e) => {
                checkWeekend(e.target);
                if (e.target.value) {
                    // 2. Establecer 'min' en fin para que no sea anterior a inicio
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

</body>
</html>
