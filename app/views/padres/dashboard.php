<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-background text-on-background font-body-md graph-paper-bg min-h-screen flex flex-col md:flex-row">
    <!-- SideNavBar -->
    <nav class="hidden md:flex flex-col fixed left-0 top-0 h-full py-4 docked w-64 border-r border-outline-variant bg-white z-40">
        <div class="px-6 pb-6">
            <h1 class="text-primary font-headline-md text-headline-md">Edusaft Portal</h1>
            <p class="text-on-surface-variant font-body-md text-body-md">Gestión Educativa</p>
        </div>
        
        <div class="flex-grow flex flex-col gap-1">
            <a class="bg-primary-container text-on-primary-container font-semibold rounded-lg mx-2 px-4 py-3 flex items-center gap-3" href="<?php echo URLROOT; ?>/padres/dashboard">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                Panel Principal
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/padres/camino">
                <span class="material-symbols-outlined">map</span>
                Tu Camino
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="#">
                <span class="material-symbols-outlined">event_available</span>
                Asistencia
            </a>
            <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="#">
                <span class="material-symbols-outlined">family_restroom</span>
                Hijos
            </a>
        </div>

        <div class="mt-auto border-t border-outline-variant pt-4 flex flex-col gap-1">
            <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="#">
                <span class="material-symbols-outlined">settings</span>
                Configuración
            </a>
            <a class="text-error px-4 py-3 mx-2 hover:bg-error-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/auth/logout">
                <span class="material-symbols-outlined">logout</span>
                Cerrar Sesión
            </a>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar -->
        <header class="flex justify-between items-center h-16 px-6 w-full bg-white docked full-width top-0 z-50 border-b border-outline-variant shadow-sm">
            <div class="text-2xl font-extrabold tracking-tight text-primary font-headline-md md:hidden">
                Edusaft
            </div>
            <div class="hidden md:block text-on-surface-variant font-label-md">
                Bienvenido, Familia <?php echo $_SESSION['username']; ?>
            </div>
            <div class="flex items-center gap-4">
                <button class="hover:bg-surface-container transition-all p-2 rounded-full active:scale-95 duration-150 relative">
                    <span class="material-symbols-outlined text-primary">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                </button>
                <img alt="Perfil" class="w-10 h-10 rounded-full border-2 border-primary-fixed object-cover" src="<?php echo URLROOT; ?>/assets/img/logo.png"/>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 md:p-8 max-w-[1280px] mx-auto w-full flex flex-col gap-6">
            <!-- Gamification Path Card -->
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
                <div class="bg-primary text-on-primary p-6">
                    <h2 class="font-headline-md text-headline-md">Tu Camino al Éxito</h2>
                    <p class="text-on-primary/80">Sigue el progreso de tu familia y desbloquea recompensas.</p>
                </div>
                <div class="p-8 flex flex-col items-center justify-center min-h-[200px] border-b border-outline-variant/30">
                    <div class="w-full max-w-2xl bg-surface-container-high h-4 rounded-full relative overflow-hidden">
                        <div class="absolute left-0 top-0 h-full bg-tertiary w-1/3 shadow-[0_0_10px_rgba(63,210,152,0.5)]"></div>
                    </div>
                    <div class="flex justify-between w-full max-w-2xl mt-4 px-2">
                        <span class="font-label-sm text-primary">Inicio</span>
                        <span class="font-label-sm text-tertiary font-bold">Nivel 3: Exploradores</span>
                        <span class="font-label-sm text-outline">Meta</span>
                    </div>
                    <div class="mt-8">
                        <a href="<?php echo URLROOT; ?>/padres/camino" class="bg-secondary text-on-secondary px-6 py-2 rounded-full font-label-md shadow-sm hover:opacity-90 transition-opacity">
                            Ver Mapa Interactivo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Notifications Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-headline-md text-headline-md text-primary">Notificaciones</h3>
                        <span class="material-symbols-outlined text-outline">notifications_active</span>
                    </div>
                    <div class="space-y-4">
                        <div class="flex gap-4 p-4 bg-surface-container-low rounded-lg border-l-4 border-primary">
                            <span class="material-symbols-outlined text-primary">info</span>
                            <div>
                                <p class="font-label-md">Reunión de Padres</p>
                                <p class="text-sm text-on-surface-variant">Mañana a las 8:00 AM en el auditorio principal.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 p-4 bg-surface-container-low rounded-lg border-l-4 border-tertiary">
                            <span class="material-symbols-outlined text-tertiary">check_circle</span>
                            <div>
                                <p class="font-label-md">Tarea Completada</p>
                                <p class="text-sm text-on-surface-variant">Celeste ha entregado la actividad de Matemáticas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Children Status Card -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-headline-md text-headline-md text-primary">Estado de Hijos</h3>
                        <span class="material-symbols-outlined text-outline">face</span>
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        <button class="flex flex-col items-center gap-2 p-3 rounded-xl bg-tertiary-container text-on-tertiary-container min-w-[100px]">
                            <span class="material-symbols-outlined text-3xl">face</span>
                            <span class="font-label-sm">Celeste</span>
                            <span class="text-[10px] bg-tertiary text-on-tertiary px-2 py-0.5 rounded-full">9°2</span>
                        </button>
                        <button class="flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-container-high hover:bg-surface-container transition-colors min-w-[100px]">
                            <span class="material-symbols-outlined text-3xl">face_3</span>
                            <span class="font-label-sm">Emily</span>
                            <span class="text-[10px] bg-outline text-white px-2 py-0.5 rounded-full">5°3</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
