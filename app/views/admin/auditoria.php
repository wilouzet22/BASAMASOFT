<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen" style="margin-left:16rem">
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebarCollapse()"
                        class="hidden md:flex w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="text-xl font-extrabold tracking-tight text-primary md:hidden">Edusaft</div>
                <div class="hidden md:block text-on-surface-variant text-sm">Auditoría del Sistema</div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-on-surface-variant hidden md:inline"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                <a href="<?php echo URLROOT; ?>/auth/logout" class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95">
                    <span class="material-symbols-outlined text-error">logout</span>
                </a>
                <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <div>
                <h2 class="text-headline-lg text-primary font-headline-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-3xl">history</span>
                    Auditoría del Sistema
                </h2>
                <p class="text-on-surface-variant text-sm mt-1">Registro completo de actividad reciente de todos los usuarios del sistema.</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface shadow-sm">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-surface-container/60 border-b border-outline-variant">
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Fecha</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Rol</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Usuario</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Acción</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['logs'])): ?>
                            <tr><td colspan="5" class="px-4 py-8 text-center text-on-surface-variant">No hay registros de auditoría.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['logs'] as $log):
                                if (!empty($log->admin_nombre)) {
                                    $usuario = $log->admin_nombre . ' ' . $log->admin_apellidos;
                                } elseif (!empty($log->prof_nombre)) {
                                    $usuario = $log->prof_nombre . ' ' . $log->prof_apellidos;
                                } elseif (!empty($log->familia_nombre)) {
                                    $usuario = $log->familia_nombre;
                                } else {
                                    $usuario = 'Sistema';
                                }
                            ?>
                            <tr class="border-b border-outline-variant/50 hover:bg-surface-container/40 transition-colors">
                                <td class="px-4 py-3.5 text-on-surface-variant text-xs font-mono">
                                    <?php echo date('d/m/Y H:i', strtotime($log->timestamp)); ?>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        <?php if ($log->rol_nombre === 'Administrador') echo 'bg-primary/10 text-primary';
                                              elseif ($log->rol_nombre === 'Profesor') echo 'bg-tertiary/10 text-tertiary';
                                              else echo 'bg-secondary/10 text-secondary'; ?>">
                                        <?php echo htmlspecialchars($log->rol_nombre); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 font-medium text-on-surface"><?php echo htmlspecialchars($usuario); ?></td>
                                <td class="px-4 py-3.5 text-on-surface-variant"><?php echo htmlspecialchars($log->accion_realizada); ?></td>
                                <td class="px-4 py-3.5 text-on-surface-variant font-mono text-xs"><?php echo htmlspecialchars($log->ip_direccion); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
