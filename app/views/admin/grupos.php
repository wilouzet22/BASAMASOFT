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
                    <h2 class="text-xl font-bold text-on-surface">Gestión de Grupos</h2>
                    <p class="text-sm text-on-surface-variant">
                        Administración · <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
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

        <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto w-full flex-1">

            <!-- Encabezado -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">category</span>
                        Gestión de Grupos
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Administra los grupos asignados a sedes y grados.</p>
                </div>
                <?php if (date('m-d') === '01-01'): ?>
                    <button onclick="document.getElementById('modalCrearGrupo').classList.remove('hidden')" class="bg-primary text-on-primary font-bold px-6 py-3 rounded-2xl shadow hover:scale-105 transition-all flex items-center gap-2 text-sm w-fit cursor-pointer">
                        <span class="material-symbols-outlined text-sm">add</span> Nuevo Grupo
                    </button>
                <?php else: ?>
                    <button onclick="alert('La creación de grupos está restringida únicamente al inicio de año (1 de enero) para garantizar la consistencia de los ciclos escolares.')" class="bg-surface-container text-on-surface-variant font-bold px-6 py-3 rounded-2xl shadow-sm flex items-center gap-2 text-sm w-fit opacity-80 cursor-not-allowed" title="Solo disponible el 1 de enero">
                        <span class="material-symbols-outlined text-sm">lock</span> Nuevo Grupo
                    </button>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'not_jan1'): ?>
                <div class="bg-error/10 text-error border border-error/30 px-5 py-3 rounded-2xl text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined">warning</span> No se pueden crear grupos fuera del 1 de enero.
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <?php if (empty($data['grupos'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">category</span>
                        <p class="text-sm font-medium">No hay grupos registrados.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Nombre del Grupo</th>
                                    <th class="py-3 px-4">Grado</th>
                                    <th class="py-3 px-4">Sede</th>
                                    <th class="py-3 px-4">Estudiantes</th>
                                    <th class="py-3 px-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <?php foreach ($data['grupos'] as $grupo): ?>
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-on-surface-variant">#<?php echo $grupo->id_grupo; ?></td>
                                        <td class="py-3.5 px-4 font-bold text-on-surface flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-sm">group</span>
                                            </div>
                                            <?php echo htmlspecialchars($grupo->nombre_grupo); ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($grupo->nombre_grado ?? 'N/A'); ?></td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($grupo->nombre_sede ?? 'N/A'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <span class="bg-tertiary/10 text-tertiary font-bold px-3 py-1 rounded-full text-xs">
                                                <?php echo $grupo->total_estudiantes; ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <form action="<?php echo URLROOT; ?>/admin/eliminar_grupo/<?php echo $grupo->id_grupo; ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este grupo? (Atención: esto podría afectar a estudiantes asignados a él)');">
                                                <button type="submit" title="Eliminar Grupo" class="w-9 h-9 rounded-full hover:bg-error/10 text-error inline-flex items-center justify-center transition-colors">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

<!-- Modal Crear Grupo -->
<?php if (date('m-d') === '01-01'): ?>
<div id="modalCrearGrupo" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full border border-outline-variant/50">
        <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
            <h3 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span> Crear Nuevo Grupo
            </h3>
            <button type="button" onclick="document.getElementById('modalCrearGrupo').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-surface-variant flex items-center justify-center text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <form action="<?php echo URLROOT; ?>/admin/crear_grupo" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Nombre del Grupo *</label>
                    <input type="text" name="nombre_grupo" required placeholder="Ej: Grupo Alpha" 
                           class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Grado *</label>
                    <select name="id_grado_fk" required class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all cursor-pointer">
                        <option value="">Seleccione un grado...</option>
                        <?php foreach ($data['grados'] as $g): ?>
                            <option value="<?php echo $g->id_grado; ?>"><?php echo htmlspecialchars($g->nombre_grado); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Sede *</label>
                    <select name="id_sede_fk" required class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all cursor-pointer">
                        <option value="">Seleccione una sede...</option>
                        <?php foreach ($data['sedes'] as $s): ?>
                            <option value="<?php echo $s->id_sede; ?>"><?php echo htmlspecialchars($s->nombre_sede); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-outline-variant">
                    <button type="button" onclick="document.getElementById('modalCrearGrupo').classList.add('hidden')" class="px-5 py-2 rounded-xl font-bold text-on-surface-variant hover:bg-surface-variant transition-colors text-sm">Cancelar</button>
                    <button type="submit" class="px-6 py-2 rounded-xl font-bold bg-primary text-on-primary shadow hover:bg-primary/90 transition-all text-sm">Crear Grupo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>
