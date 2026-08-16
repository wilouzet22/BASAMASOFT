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
                    <h2 class="text-xl font-bold text-on-surface">Gestión de Profesores</h2>
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

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">school</span>
                        Profesores (<?php echo count($data['profesores']); ?>)
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Listado del cuerpo docente registrado en la institución.</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm">
                <?php if (empty($data['profesores'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">school</span>
                        <p class="text-sm font-medium">No hay profesores registrados.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Nombre</th>
                                    <th class="py-3 px-4">Documento</th>
                                    <th class="py-3 px-4">Correo</th>
                                    <th class="py-3 px-4">Teléfono</th>
                                    <th class="py-3 px-4">Usuario</th>
                                    <th class="py-3 px-4">Grupos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20">
                                <?php foreach ($data['profesores'] as $prof): ?>
                                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-on-surface">
                                            <?php echo htmlspecialchars($prof->nombres . ' ' . $prof->apellidos); ?>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs text-on-surface-variant"><?php echo htmlspecialchars($prof->documento_identidad); ?></td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($prof->email); ?></td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($prof->telefono ?? '—'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <span class="bg-primary/10 text-primary font-bold px-2.5 py-1 rounded-full text-xs">
                                                <?php echo htmlspecialchars($prof->username); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($prof->grupos ?? '—'); ?></td>
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

</body>
</html>
