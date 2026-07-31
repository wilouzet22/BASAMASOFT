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
                Gestión de Profesores
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
                    <h2 class="text-headline-lg text-primary font-headline-lg flex items-center gap-2">
                        <span class="material-symbols-outlined text-3xl">school</span>
                        Profesores (<?php echo count($data['profesores']); ?>)
                    </h2>
                    <p class="text-on-surface-variant text-sm mt-1">Listado del cuerpo docente registrado en la institución.</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface shadow-sm">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-surface-container/60 border-b border-outline-variant">
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Nombre</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Documento</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Correo</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Teléfono</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Username</th>
                            <th class="px-4 py-3.5 text-on-surface font-semibold">Grupos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['profesores'])): ?>
                            <tr><td colspan="6" class="px-4 py-8 text-center text-on-surface-variant">No hay profesores registrados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['profesores'] as $prof): ?>
                            <tr class="border-b border-outline-variant/50 hover:bg-surface-container/40 transition-colors">
                                <td class="px-4 py-3.5 font-medium text-on-surface">
                                    <?php echo htmlspecialchars($prof->nombres . ' ' . $prof->apellidos); ?>
                                </td>
                                <td class="px-4 py-3.5 text-on-surface-variant font-mono text-xs"><?php echo htmlspecialchars($prof->documento_identidad); ?></td>
                                <td class="px-4 py-3.5 text-on-surface-variant"><?php echo htmlspecialchars($prof->email); ?></td>
                                <td class="px-4 py-3.5 text-on-surface-variant"><?php echo htmlspecialchars($prof->telefono ?? '—'); ?></td>
                                <td class="px-4 py-3.5">
                                    <code class="bg-primary/10 text-primary px-2 py-0.5 rounded text-xs font-semibold"><?php echo htmlspecialchars($prof->username); ?></code>
                                </td>
                                <td class="px-4 py-3.5 text-on-surface-variant text-xs"><?php echo htmlspecialchars($prof->grupos ?? '—'); ?></td>
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
