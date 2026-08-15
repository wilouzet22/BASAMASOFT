<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen" style="margin-left:16rem">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleMobileSidebar()" class="md:hidden w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant flex">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="text-xl font-extrabold tracking-tight text-primary md:hidden">Edusaft</div>
                <div class="hidden md:block text-on-surface-variant text-sm">Administración</div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold hidden md:inline"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" class="px-4 py-1.5 rounded-full text-sm font-semibold border border-outline text-on-surface-variant hover:bg-surface-container transition-colors">Salir</a>
            </div>
        </header>

        <!-- Contenido principal -->
        <main class="flex-1 p-6 md:p-8">
            <div class="max-w-6xl mx-auto space-y-6">
                <!-- Encabezado -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Gestión de Grupos</h1>
                        <p class="text-sm text-on-surface-variant mt-1">Administra los grupos asignados a sedes y grados.</p>
                    </div>
                    <?php if (date('m-d') === '01-01'): ?>
                    <button onclick="document.getElementById('modalCrearGrupo').classList.remove('hidden')" class="bg-primary text-white font-semibold py-2.5 px-5 rounded-full shadow hover:bg-primary-hover hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm w-fit">
                        <span class="material-symbols-outlined text-[18px]">add</span> Nuevo Grupo
                    </button>
                    <?php else: ?>
                    <button onclick="alert('La creación de grupos está restringida únicamente al inicio de año (1 de enero) para garantizar la consistencia de los ciclos escolares.')" class="bg-surface-variant text-on-surface-variant font-semibold py-2.5 px-5 rounded-full shadow-sm flex items-center gap-2 text-sm w-fit opacity-80 cursor-not-allowed" title="Solo disponible el 1 de enero">
                        <span class="material-symbols-outlined text-[18px]">lock</span> Nuevo Grupo
                    </button>
                    <?php endif; ?>
                </div>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'not_jan1'): ?>
                <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in">
                    <span class="material-symbols-outlined">warning</span> No se puede crear grupos fuera del 1 de enero.
                </div>
                <?php endif; ?>

                <!-- Data Table / Grid -->
                <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Nombre del Grupo</th>
                                    <th class="px-6 py-4">Grado</th>
                                    <th class="px-6 py-4">Sede</th>
                                    <th class="px-6 py-4">Estudiantes</th>
                                    <th class="px-6 py-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/50">
                                <?php if (!empty($data['grupos'])): ?>
                                    <?php foreach ($data['grupos'] as $grupo): ?>
                                    <tr class="hover:bg-surface-container-lowest transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs text-on-surface-variant">#<?php echo $grupo->id_grupo; ?></td>
                                        <td class="px-6 py-4 font-bold text-on-surface flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-sm">group</span>
                                            </div>
                                            <?php echo htmlspecialchars($grupo->nombre_grupo); ?>
                                        </td>
                                        <td class="px-6 py-4 text-on-surface-variant"><?php echo htmlspecialchars($grupo->nombre_grado ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-on-surface-variant"><?php echo htmlspecialchars($grupo->nombre_sede ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-on-surface-variant">
                                            <span class="bg-tertiary-container text-on-tertiary-container px-2.5 py-1 rounded-full text-xs font-bold">
                                                <?php echo $grupo->total_estudiantes; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="<?php echo URLROOT; ?>/admin/eliminar_grupo/<?php echo $grupo->id_grupo; ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este grupo? (Atención: esto podría afectar a estudiantes asignados a él)');">
                                                <button type="submit" title="Eliminar Grupo" class="w-8 h-8 rounded-full hover:bg-error-container text-error inline-flex items-center justify-center transition-colors">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="px-6 py-12 text-center text-on-surface-variant italic">No hay grupos registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Crear Grupo -->
    <?php if (date('m-d') === '01-01'): ?>
    <div id="modalCrearGrupo" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
        <div class="bg-surface w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-full">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
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
                        <label class="block text-sm font-bold text-on-surface mb-1">Nombre del Grupo</label>
                        <input type="text" name="nombre_grupo" required placeholder="Ej: Grupo Alpha" 
                               class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Grado</label>
                        <select name="id_grado_fk" required class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Seleccione un grado...</option>
                            <?php foreach ($data['grados'] as $g): ?>
                                <option value="<?php echo $g->id_grado; ?>"><?php echo htmlspecialchars($g->nombre_grado); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-1">Sede</label>
                        <select name="id_sede_fk" required class="w-full rounded-xl border border-outline-variant bg-surface-container-low px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Seleccione una sede...</option>
                            <?php foreach ($data['sedes'] as $s): ?>
                                <option value="<?php echo $s->id_sede; ?>"><?php echo htmlspecialchars($s->nombre_sede); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-outline-variant">
                        <button type="button" onclick="document.getElementById('modalCrearGrupo').classList.add('hidden')" class="px-5 py-2 rounded-full font-semibold text-on-surface-variant hover:bg-surface-variant transition-colors text-sm">Cancelar</button>
                        <button type="submit" class="px-6 py-2 rounded-full font-bold bg-primary text-on-primary shadow-sm hover:bg-primary-hover hover:-translate-y-0.5 transition-all text-sm">Crear Grupo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
