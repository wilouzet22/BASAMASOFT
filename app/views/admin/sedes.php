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
                    <h2 class="text-xl font-bold text-on-surface">Sedes Institucionales</h2>
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
                        <span class="material-symbols-outlined text-primary text-3xl md:text-4xl">apartment</span>
                        Sedes Institucionales
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Directorio de sedes y plantas físicas de la institución.</p>
                </div>
            </div>

            <?php if (empty($data['sedes'])): ?>
                <div class="bg-white rounded-3xl border border-outline-variant p-12 text-center shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-outline mb-4 opacity-50">apartment</span>
                    <h3 class="text-lg font-bold text-on-surface">No hay sedes registradas</h3>
                    <p class="text-sm text-on-surface-variant mt-2 max-w-sm mx-auto">No se encontraron sedes institucionales en el sistema.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['sedes'] as $sede): ?>
                    <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm hover:shadow-md transition-all">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-5">
                            <span class="material-symbols-outlined text-3xl">apartment</span>
                        </div>
                        <h3 class="text-xl font-bold text-on-surface mb-4">
                            <?php echo htmlspecialchars($sede->nombre_sede); ?>
                        </h3>
                        <?php if (!empty($sede->direccion_sede)): ?>
                        <div class="flex items-start gap-2.5 text-xs text-on-surface-variant mb-2">
                            <span class="material-symbols-outlined text-base text-primary shrink-0">location_on</span>
                            <span class="font-medium"><?php echo htmlspecialchars($sede->direccion_sede); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($sede->telefono_sede)): ?>
                        <div class="flex items-center gap-2.5 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-base text-primary shrink-0">phone</span>
                            <span class="font-medium"><?php echo htmlspecialchars($sede->telefono_sede); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="mt-6 pt-4 border-t border-outline-variant/30 flex justify-between items-center text-xs">
                            <span class="text-on-surface-variant font-medium">ID Sede:</span>
                            <span class="bg-surface-container text-on-surface font-bold px-3 py-1 rounded-full">#<?php echo $sede->id_sede; ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

</body>
</html>
