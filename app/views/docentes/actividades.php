<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white docked full-width top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">
                Edusaft
            </div>
            <div class="hidden md:block text-on-surface-variant font-label-md">
                Gestión de Actividades
            </div>
            <div class="flex items-center gap-4">
                <button class="bg-primary text-on-primary py-2 px-4 rounded-lg font-label-md text-label-md shadow-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add</span>
                    Nueva Actividad
                </button>
                <div class="h-8 w-[1px] bg-outline-variant mx-2"></div>
                <img alt="Perfil" class="w-10 h-10 rounded-full border-2 border-primary-fixed object-cover" src="<?php echo URLROOT; ?>/assets/img/logo.png"/>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <!-- Calendar Card -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                <!-- Calendar Header -->
                <div class="bg-secondary text-on-secondary p-4 flex items-center justify-between">
                    <button class="p-2 hover:bg-white/10 rounded-full transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <h2 class="font-headline-md text-headline-md uppercase tracking-wider">MARZO 2024</h2>
                    <button class="p-2 hover:bg-white/10 rounded-full transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
                
                <!-- Calendar Grid -->
                <div class="p-4 flex-1 flex flex-col">
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Lun</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Mar</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Mié</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Jue</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Vie</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Sáb</div>
                        <div class="text-center font-label-md text-label-md text-on-surface-variant py-2">Dom</div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 flex-1 auto-rows-fr">
                        <!-- Dates placeholder -->
                        <?php for($i=1; $i<=31; $i++): ?>
                            <div class="min-h-[100px] p-2 border border-outline-variant rounded-lg hover:border-primary transition-colors cursor-pointer relative bg-surface group">
                                <span class="font-label-md text-label-md"><?php echo $i; ?></span>
                                <?php if($i == 8): ?>
                                    <div class="mt-1 p-1 text-[10px] bg-primary-container text-on-primary-container rounded border border-primary/20 truncate">
                                        Entrega Tarea
                                    </div>
                                <?php endif; ?>
                                <?php if($i == 15): ?>
                                    <div class="mt-1 p-1 text-[10px] bg-secondary-container text-on-secondary-container rounded border border-secondary/20 truncate">
                                        Examen Final
                                    </div>
                                <?php endif; ?>
                                <button class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 text-primary transition-opacity">
                                    <span class="material-symbols-outlined text-sm">add_circle</span>
                                </button>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-surface border border-outline-variant rounded-xl p-6">
                    <h3 class="text-headline-md text-primary mb-4">Próximas Actividades</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary">assignment</span>
                                </div>
                                <div>
                                    <p class="font-bold">Taller de Lectura</p>
                                    <p class="text-sm text-on-surface-variant">25 de Oct, 2023 - 9°2</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container rounded-full text-xs font-bold">Activo</span>
                        </div>
                    </div>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-6">
                    <h3 class="text-headline-md text-primary mb-4">Resumen</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4 p-4 bg-primary-container/10 rounded-lg">
                            <span class="material-symbols-outlined text-primary text-3xl">event_upcoming</span>
                            <div>
                                <p class="text-2xl font-bold">12</p>
                                <p class="text-xs text-on-surface-variant uppercase tracking-wider">Programadas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-tertiary-container/10 rounded-lg">
                            <span class="material-symbols-outlined text-tertiary text-3xl">task_alt</span>
                            <div>
                                <p class="text-2xl font-bold">45</p>
                                <p class="text-xs text-on-surface-variant uppercase tracking-wider">Completadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
