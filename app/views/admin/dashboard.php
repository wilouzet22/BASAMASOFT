<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleMobileSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>

</header>

<div class="flex">
    <!-- Sidebar reusable admin -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Panel de Administración</h2>
                    <p class="text-sm text-on-surface-variant">
                        Bienvenido, <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Administrador'); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Administrador del Sistema</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full">

            <!-- Hero Banner Admin -->
            <div class="relative bg-primary rounded-[2.5rem] p-8 md:p-12 text-on-primary overflow-hidden shadow-2xl shadow-primary/30 group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                            Portal Administrativo — EduSaft
                        </span>
                        <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">
                            Gestión Global de la Institución
                        </h3>
                        <p class="text-on-primary/80 mb-8 max-w-md">
                            Administra usuarios, profesores, familias, sedes, grupos y supervisa las actividades institucionales en un solo lugar.
                        </p>
                        <button onclick="document.getElementById('modal-crear-actividad').showModal()"
                            class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 transition-all flex items-center gap-2 cursor-pointer w-fit">
                            <span class="material-symbols-outlined">add_circle</span>
                            Crear Nueva Actividad
                        </button>
                    </div>

                    <!-- Stat Summary Card Glass (Gestión Institucional) -->
                    <div class="w-full md:w-1/3 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20 text-center md:text-left">
                        <p class="text-xs font-bold uppercase tracking-widest text-on-primary/70 mb-3">Resumen de Gestión</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">event</span>
                                <p class="text-2xl font-black"><?php echo formatCompactNumber($data['total_actividades']); ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Actividades</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">school</span>
                                <p class="text-2xl font-black"><?php echo formatCompactNumber($data['total_profesores']); ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Profesores</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">apartment</span>
                                <p class="text-2xl font-black"><?php echo formatCompactNumber($data['total_sedes']); ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Sedes</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">category</span>
                                <p class="text-2xl font-black"><?php echo count($data['grupos']); ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Grupos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid 6 columnas -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">

                <!-- Profesores -->
                <a href="<?php echo URLROOT; ?>/admin/profesores" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-primary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">school</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo formatCompactNumber($data['total_profesores']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-primary transition-colors">Profesores</p>
                </a>

                <!-- Familias -->
                <a href="<?php echo URLROOT; ?>/admin/familias" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">family_restroom</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo formatCompactNumber($data['total_familias']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-secondary transition-colors">Familias</p>
                </a>

                <!-- Estudiantes -->
                <a href="<?php echo URLROOT; ?>/admin/estudiantes" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-tertiary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-tertiary/10 rounded-2xl flex items-center justify-center text-tertiary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo formatCompactNumber($data['total_estudiantes']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-tertiary transition-colors">Estudiantes</p>
                </a>

                <!-- Sedes -->
                <a href="<?php echo URLROOT; ?>/admin/sedes" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-primary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-primary-container/30 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">apartment</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo formatCompactNumber($data['total_sedes']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-primary transition-colors">Sedes</p>
                </a>

                <!-- Actividades -->
                <a href="<?php echo URLROOT; ?>/admin/actividades" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-secondary-container/30 rounded-2xl flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">event</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo formatCompactNumber($data['total_actividades']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-secondary transition-colors">Actividades</p>
                </a>

                <!-- Grupos -->
                <a href="<?php echo URLROOT; ?>/admin/grupos" 
                   class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hover:shadow-md hover:border-tertiary/50 transition-all flex flex-col items-center text-center gap-3 group cursor-pointer">
                    <div class="w-14 h-14 bg-tertiary-container/30 rounded-2xl flex items-center justify-center text-tertiary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">category</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo count($data['grupos']); ?></p>
                    <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider group-hover:text-tertiary transition-colors">Grupos</p>
                </a>
            </div>

            <!-- Auditoría -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-3xl">history</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-on-surface text-lg">Auditoría del Sistema</h3>
                        <p class="text-sm text-on-surface-variant mt-0.5">Consulta el registro de actividad reciente de todos los usuarios e historial de cambios.</p>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/auditoria"
                   class="bg-primary text-on-primary font-bold px-6 py-3 rounded-2xl text-sm shadow hover:scale-105 transition-all inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                    Ver Auditoría Completa
                </a>
            </div>

        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

<!-- Modal Crear Actividad Centrado y Organizado -->
<dialog id="modal-crear-actividad" class="fixed inset-0 z-[100] m-auto w-[92%] max-w-2xl max-h-[88vh] bg-surface rounded-3xl shadow-2xl p-0 backdrop:bg-black/60 backdrop:backdrop-blur-sm border border-outline-variant/60 outline-none overflow-hidden open:animate-in open:fade-in open:zoom-in-95">
    <div class="flex flex-col h-full max-h-[88vh]">
        <!-- Header del Modal -->
        <div class="flex justify-between items-center px-6 py-4.5 border-b border-outline-variant bg-surface-container/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined text-2xl">event_available</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-on-surface tracking-tight">Crear Nueva Actividad</h3>
                    <p class="text-xs text-on-surface-variant">Programa una nueva actividad o evento institucional</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modal-crear-actividad').close()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-surface-variant text-on-surface-variant hover:text-on-surface transition-all active:scale-95" title="Cerrar">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        <!-- Cuerpo del Formulario -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1 custom-scrollbar">
            <form id="form-crear-actividad" action="<?php echo URLROOT; ?>/admin/crear_actividad" method="POST" class="flex flex-col gap-5">
                
                <!-- 1. Nombre y Descripción -->
                <div class="space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label for="nombre_actividad" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-sm">edit_note</span>
                            Nombre de la Actividad <span class="text-error">*</span>
                        </label>
                        <input type="text" id="nombre_actividad" name="nombre_actividad" required placeholder="Ej: Entrega de Informes Segundo Periodo" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:bg-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm font-medium">
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label for="descripcion" class="text-xs font-bold text-on-surface uppercase tracking-wider flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-sm">description</span>
                            Descripción (Opcional)
                        </label>
                        <textarea id="descripcion" name="descripcion" rows="2" placeholder="Detalles, objetivos o instrucciones para la comunidad escolar..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:bg-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all text-sm font-medium resize-none"></textarea>
                    </div>
                </div>

                <!-- 2. Fechas y Horarios -->
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

                <!-- 3. Tipo de Actividad y Sede -->
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
                            Sede Asignada <span class="text-error">*</span>
                        </label>
                        <select id="id_sede_fk" name="id_sede_fk" required class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                            <option value="">Seleccione una sede...</option>
                            <?php foreach($data['sedes'] as $sede): ?>
                                <option value="<?php echo $sede->id_sede; ?>"><?php echo htmlspecialchars($sede->nombre_sede); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- 4. Alcance y Selección de Grupos -->
                <div class="p-4 rounded-2xl bg-surface-container/30 border border-outline-variant/60 space-y-3">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">groups</span>
                        Alcance de la Actividad
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/80 bg-surface hover:border-primary cursor-pointer transition-all">
                            <input type="radio" name="tipo_alcance" value="general" checked onchange="toggleGruposSection(this.value)" class="w-4 h-4 text-primary focus:ring-primary border-outline">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-on-surface">Actividad General</span>
                                <span class="text-[11px] text-on-surface-variant">Aplica a toda la sede seleccionada</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/80 bg-surface hover:border-primary cursor-pointer transition-all">
                            <input type="radio" name="tipo_alcance" value="grupo" onchange="toggleGruposSection(this.value)" class="w-4 h-4 text-primary focus:ring-primary border-outline">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-on-surface">Por Grupos Específicos</span>
                                <span class="text-[11px] text-on-surface-variant">Seleccionar grupos participantes</span>
                            </div>
                        </label>
                    </div>

                    <!-- Lista de Grupos (oculta por defecto) -->
                    <div id="seccion-grupos" class="hidden space-y-2 pt-2 border-t border-outline-variant/40">
                        <p class="text-xs font-semibold text-on-surface">Selecciona los grupos participantes:</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-3 border border-outline-variant rounded-xl bg-surface max-h-44 overflow-y-auto custom-scrollbar">
                            <?php foreach($data['grupos'] as $grupo): ?>
                            <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-container transition-colors cursor-pointer text-xs text-on-surface">
                                <input type="checkbox" name="grupos[]" value="<?php echo $grupo->id_grupo; ?>" class="rounded text-primary focus:ring-primary border-outline">
                                <span class="font-medium truncate"><?php echo htmlspecialchars($grupo->nombre_grupo); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <!-- Footer con Botones de Acción -->
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
    const modalAct = document.getElementById('modal-crear-actividad');
    if (modalAct) {
        modalAct.addEventListener('click', (e) => {
            const rect = modalAct.getBoundingClientRect();
            if (e.clientX < rect.left || e.clientX > rect.right || e.clientY < rect.top || e.clientY > rect.bottom) {
                modalAct.close();
            }
        });
    }

    const fechaInicio = document.getElementById('fecha_hora_inicio');
    const fechaFin = document.getElementById('fecha_hora_fin');
    
    if (fechaInicio && fechaFin) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        fechaInicio.min = now.toISOString().slice(0, 16);

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
