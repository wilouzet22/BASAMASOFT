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
                    <h2 class="text-xl font-bold text-on-surface">Panel de Control Docente</h2>
                    <p class="text-sm text-on-surface-variant">
                        Bienvenido, <span class="text-primary font-bold">Prof. <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
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

        <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full">

            <!-- Hero Banner Docente -->
            <div class="relative bg-primary rounded-[2.5rem] p-8 md:p-12 text-on-primary overflow-hidden shadow-2xl shadow-primary/30 group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                            Portal Docente — EduSaft
                        </span>
                        <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">
                            ¡Hola, Prof. <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>!
                        </h3>
                        <p class="text-on-primary/80 mb-8 max-w-md">
                            Gestiona las actividades de tus grupos, toma la asistencia y haz seguimiento en tiempo real del progreso de tus alumnos.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            <a href="<?php echo URLROOT; ?>/docentes/asistencia"
                                class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                                Registrar Asistencia
                                <span class="material-symbols-outlined">event_available</span>
                            </a>
                            <a href="<?php echo URLROOT; ?>/docentes/actividades"
                                class="bg-white/20 text-white hover:bg-white/30 border border-white/30 px-8 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-2">
                                Mis Actividades
                                <span class="material-symbols-outlined">assignment</span>
                            </a>
                        </div>
                    </div>

                    <!-- Glassmorphism Stat Box -->
                    <div class="w-full md:w-1/3 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20 text-center md:text-left">
                        <p class="text-xs font-bold uppercase tracking-widest text-on-primary/70 mb-3">Resumen de Gestión</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">assignment</span>
                                <p class="text-2xl font-black"><?php echo $data['total_actividades']; ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Actividades</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="material-symbols-outlined text-2xl mb-1 text-white">check_circle</span>
                                <p class="text-2xl font-black"><?php echo $data['total_asistencias']; ?></p>
                                <p class="text-[10px] text-on-primary/70 uppercase font-bold">Asistencias</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Tarjetas estilo Padres -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                        <span class="material-symbols-outlined text-3xl">event</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-primary"><?php echo $data['total_actividades']; ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Mis Actividades</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary shrink-0">
                        <span class="material-symbols-outlined text-3xl">how_to_reg</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-secondary"><?php echo $data['total_asistencias']; ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Asistencias Registradas</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-tertiary/10 flex items-center justify-center text-tertiary shrink-0">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-tertiary"><?php echo count($data['grupos']); ?></p>
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mt-0.5">Mis Grupos Asignados</p>
                    </div>
                </div>
            </div>

            <!-- Mis Grupos Asignados -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h4 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">class</span>
                    Mis Grupos Asignados
                </h4>
                <?php if (empty($data['grupos'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">group_off</span>
                        <p class="font-medium text-sm">No tiene grupos asignados actualmente.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['grupos'] as $grupo): ?>
                            <div class="p-6 bg-surface-container-lowest rounded-2xl border border-outline-variant/40 hover:border-primary/40 hover:shadow-md transition-all flex items-center gap-4 group">
                                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shrink-0">
                                    <span class="material-symbols-outlined text-2xl">group</span>
                                </div>
                                <div class="min-w-0">
                                    <h5 class="font-bold text-on-surface text-base truncate"><?php echo htmlspecialchars($grupo->nombre_grupo); ?></h5>
                                    <p class="text-xs text-outline font-bold uppercase tracking-tighter mt-0.5">
                                        <?php echo htmlspecialchars($grupo->nombre_grado); ?>
                                    </p>
                                    <p class="text-xs text-on-surface-variant truncate">
                                        <?php echo htmlspecialchars($grupo->nombre_sede); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Últimas asistencias registradas -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <h4 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-2xl">history</span>
                    Últimas Asistencias Registradas
                </h4>
                <?php if (empty($data['asistencias_recientes'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">event_busy</span>
                        <p class="font-medium text-sm">Aún no ha registrado asistencias.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Fecha</th>
                                    <th class="py-3 px-4">Estudiante</th>
                                    <th class="py-3 px-4">Grupo</th>
                                    <th class="py-3 px-4">Actividad</th>
                                    <th class="py-3 px-4 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <?php foreach ($data['asistencias_recientes'] as $asi): ?>
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="py-3 px-4 text-xs text-on-surface-variant">
                                            <?php echo date('d/m/Y H:i', strtotime($asi->fecha_registro)); ?>
                                        </td>
                                        <td class="py-3 px-4 font-bold text-on-surface">
                                            <?php echo htmlspecialchars($asi->estudiante_nombre); ?>
                                        </td>
                                        <td class="py-3 px-4 text-xs text-on-surface-variant">
                                            <span class="bg-primary/10 text-primary font-bold px-2.5 py-1 rounded-full text-[11px]">
                                                <?php echo htmlspecialchars($asi->nombre_grupo); ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-xs text-on-surface-variant">
                                            <?php echo htmlspecialchars($asi->nombre_actividad); ?>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <?php if ($asi->presente): ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                                    <span class="material-symbols-outlined text-sm">check</span> Presente
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 text-xs font-bold bg-red-100 text-red-800 px-3 py-1 rounded-full">
                                                    <span class="material-symbols-outlined text-sm">close</span> Ausente
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <a href="<?php echo URLROOT; ?>/docentes/asistencia" class="text-primary text-sm font-bold hover:underline inline-flex items-center gap-1">
                            Ver todas las asistencias <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Accesos Rápidos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="<?php echo URLROOT; ?>/docentes/actividades"
                   class="bg-white border border-outline-variant rounded-3xl p-6 hover:border-primary/40 hover:shadow-md transition-all flex items-center gap-5 group">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all shrink-0">
                        <span class="material-symbols-outlined text-3xl">assignment</span>
                    </div>
                    <div>
                        <h5 class="font-bold text-on-surface text-base">Mis Actividades</h5>
                        <p class="text-xs text-on-surface-variant mt-0.5">Crear, ver y gestionar actividades programadas</p>
                    </div>
                </a>
                <a href="<?php echo URLROOT; ?>/docentes/asistencia"
                   class="bg-white border border-outline-variant rounded-3xl p-6 hover:border-secondary/40 hover:shadow-md transition-all flex items-center gap-5 group">
                    <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all shrink-0">
                        <span class="material-symbols-outlined text-3xl">how_to_reg</span>
                    </div>
                    <div>
                        <h5 class="font-bold text-on-surface text-base">Registrar Asistencia</h5>
                        <p class="text-xs text-on-surface-variant mt-0.5">Tomar asistencia de tus alumnos por clase o evento</p>
                    </div>
                </a>
            </div>

        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

</body>
</html>
