<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen" style="margin-left:16rem">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white docked full-width top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Botón colapso sidebar en desktop -->
                <button type="button" onclick="toggleSidebarCollapse()"
                        class="hidden md:flex w-9 h-9 items-center justify-center rounded-full hover:bg-surface-container transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <!-- Logo móvil -->
                <div class="text-xl font-extrabold tracking-tight text-primary md:hidden">Edusaft</div>
                <div class="hidden md:block text-on-surface-variant font-label-md text-sm">Panel de Administración</div>
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

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <h2 class="text-headline-lg text-primary font-headline-lg">Resumen General</h2>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

                <!-- Profesores -->
                <a href="<?php echo URLROOT; ?>/admin/profesores" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-primary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-primary">school</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo formatCompactNumber($data['total_profesores']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-primary transition-colors">Profesores</p>
                </a>

                <!-- Familias -->
                <a href="<?php echo URLROOT; ?>/admin/familias" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary">family_restroom</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo formatCompactNumber($data['total_familias']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-secondary transition-colors">Familias</p>
                </a>

                <!-- Estudiantes -->
                <a href="<?php echo URLROOT; ?>/admin/estudiantes" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-tertiary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer">
                    <div class="w-12 h-12 bg-tertiary/10 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-tertiary">groups</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo formatCompactNumber($data['total_estudiantes']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-tertiary transition-colors">Estudiantes</p>
                </a>

                <!-- Sedes -->
                <a href="<?php echo URLROOT; ?>/admin/sedes" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-primary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer">
                    <div class="w-12 h-12 bg-primary-fixed/60 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-primary">apartment</span>
                    </div>
                    <p class="text-3xl font-black text-primary"><?php echo formatCompactNumber($data['total_sedes']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-primary transition-colors">Sedes</p>
                </a>

                <!-- Actividades -->
                <a href="<?php echo URLROOT; ?>/admin/actividades" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer">
                    <div class="w-12 h-12 bg-secondary-container/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-secondary">event</span>
                    </div>
                    <p class="text-3xl font-black text-secondary"><?php echo formatCompactNumber($data['total_actividades']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-secondary transition-colors">Actividades</p>
                </a>

                <!-- Asistencias -->
                <a href="<?php echo URLROOT; ?>/admin/asistencias" 
                   class="bg-surface border border-outline-variant rounded-xl p-5 shadow-sm hover:shadow-md hover:border-tertiary/50 transition-all flex flex-col items-center text-center gap-2 group cursor-pointer"
                   title="Asistencias: Presentes / Total de Personas Evaluadas">
                    <div class="w-12 h-12 bg-tertiary-fixed/40 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-tertiary">how_to_reg</span>
                    </div>
                    <p class="text-3xl font-black text-tertiary"><?php echo formatCompactNumber($data['asistencias_presentes']); ?>/<?php echo formatCompactNumber($data['total_asistencias']); ?></p>
                    <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wide group-hover:text-tertiary transition-colors">Asistencias</p>
                </a>
            </div>

            <!-- Quick Access Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Usuarios Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-primary">group</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Usuarios</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">
                        <?php echo formatCompactNumber($data['total_profesores']); ?> prof. · <?php echo formatCompactNumber($data['total_familias']); ?> familias · <?php echo formatCompactNumber($data['total_estudiantes']); ?> estudiantes
                    </p>
                    <a href="<?php echo URLROOT; ?>/admin/profesores" class="text-primary font-semibold hover:underline">Gestionar Usuarios &rarr;</a>
                </div>

                <!-- Sedes Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-tertiary">apartment</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Sedes</h3>
                    <p class="text-body-md text-on-surface-variant mb-4"><?php echo formatCompactNumber($data['total_sedes']); ?> sedes registradas en el sistema.</p>
                    <a href="<?php echo URLROOT; ?>/admin/sedes" class="text-tertiary font-semibold hover:underline">Ver Sedes &rarr;</a>
                </div>

                <!-- Asistencias Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">how_to_reg</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Asistencias</h3>
                    <p class="text-body-md text-on-surface-variant mb-4"><?php echo formatCompactNumber($data['asistencias_presentes']); ?>/<?php echo formatCompactNumber($data['total_asistencias']); ?> de la lista total de personas asistieron.</p>
                    <a href="<?php echo URLROOT; ?>/admin/asistencias" class="text-secondary font-semibold hover:underline">Ver Asistencias &rarr;</a>
                </div>
            </div>

            <!-- Acceso rápido a Auditoría -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">history</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-on-surface">Auditoría del Sistema</h3>
                        <p class="text-sm text-on-surface-variant">Consulta el registro de actividad reciente de todos los usuarios.</p>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/admin/auditoria"
                   class="flex items-center gap-1 px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Ver Auditoría
                </a>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
