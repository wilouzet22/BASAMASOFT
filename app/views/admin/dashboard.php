<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white docked full-width top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">
                Edusaft
            </div>
            <div class="hidden md:block text-on-surface-variant font-label-md">
                Panel de Administración
            </div>
            <div class="flex items-center gap-4">
                <button class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95 duration-150">
                    <span class="material-symbols-outlined text-primary">notifications</span>
                </button>
                <img alt="Perfil" class="w-10 h-10 rounded-full border-2 border-primary-fixed object-cover" src="<?php echo URLROOT; ?>/assets/img/logo.png"/>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <h2 class="text-headline-lg text-primary font-headline-lg">Resumen General</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Usuarios Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-primary">group</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Usuarios</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">Gestionar padres, docentes y alumnos.</p>
                    <a href="<?php echo URLROOT; ?>/admin/usuarios" class="text-primary font-semibold hover:underline">Ir a Usuarios &rarr;</a>
                </div>

                <!-- Sedes Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-tertiary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-tertiary">apartment</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Sedes</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">Gestionar las sedes de la institución.</p>
                    <a href="<?php echo URLROOT; ?>/admin/sedes" class="text-tertiary font-semibold hover:underline">Ir a Sedes &rarr;</a>
                </div>

                <!-- Configuración Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-secondary-fixed rounded-lg flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-secondary">settings</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Configuración</h3>
                    <p class="text-body-md text-on-surface-variant mb-4">Ajustes generales del sistema.</p>
                    <a href="<?php echo URLROOT; ?>/admin/configuracion" class="text-secondary font-semibold hover:underline">Ir a Ajustes &rarr;</a>
                </div>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
