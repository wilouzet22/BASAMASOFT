<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-surface-container-lowest text-on-background font-lexend min-h-screen">
    <!-- Mobile Header -->
    <header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-10 w-10 rounded-full" alt="Logo">
            <span class="font-bold text-primary">EduSaft</span>
        </div>
        <button id="mobile-menu-toggle" class="p-2 text-on-surface-variant">
            <span class="material-symbols-outlined">menu</span>
        </button>
    </header>

    <div class="flex">
        <!-- Premium Sidebar -->
        <nav class="hidden md:flex flex-col fixed left-0 top-0 h-full w-72 bg-white border-r border-outline-variant z-40 transition-all duration-300">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary/10 rounded-xl">
                        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-8 w-8" alt="Logo">
                    </div>
                    <span class="text-2xl font-bold text-primary tracking-tight">EduSaft</span>
                </div>
                <p class="text-xs text-outline uppercase tracking-widest font-bold ml-1">Portal de Padres</p>
            </div>
            
            <div class="flex-grow px-4 space-y-2">
                <a href="<?php echo URLROOT; ?>/padres/dashboard" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl bg-primary text-on-primary shadow-lg shadow-primary/20 transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span class="font-medium">Panel Principal</span>
                </a>
                <a href="<?php echo URLROOT; ?>/padres/camino" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110">map</span>
                    <span class="font-medium">Camino de Éxito</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110">library_books</span>
                    <span class="font-medium">Contenidos</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all group">
                    <span class="material-symbols-outlined transition-transform group-hover:scale-110">event_available</span>
                    <span class="font-medium">Asistencias</span>
                </a>
            </div>

            <div class="p-4 mt-auto space-y-2">
                <div class="bg-surface-container rounded-2xl p-4 mb-4 border border-outline-variant/50">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-secondary">workspace_premium</span>
                        <span class="text-sm font-bold text-on-surface">Puntos Totales</span>
                    </div>
                    <div class="text-2xl font-black text-secondary">245 <span class="text-xs font-normal text-outline">pts</span></div>
                </div>
                <a href="<?php echo URLROOT; ?>/auth/logout" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl text-error hover:bg-error/10 transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-medium">Cerrar Sesión</span>
                </a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 md:ml-72 min-h-screen bg-surface-container-lowest flex flex-col">
            <!-- Top Bar -->
            <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Panel de Control</h2>
                    <p class="text-sm text-on-surface-variant">Bienvenido, Familia <span class="text-primary font-bold"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitada'; ?></span></p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="relative group cursor-pointer p-2 rounded-full hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-primary">notifications</span>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full ring-2 ring-white"></span>
                    </div>
                    <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                        <div class="text-right">
                            <p class="text-sm font-bold text-on-surface">Sede Principal</p>
                            <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Acudiente</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden">
                            <span class="material-symbols-outlined text-primary">person</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 md:p-10 space-y-8 max-w-7xl mx-auto">
                <!-- Gamification Preview Card -->
                <div class="relative bg-primary rounded-[2.5rem] p-8 md:p-12 text-on-primary overflow-hidden shadow-2xl shadow-primary/30 group">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-secondary/20 rounded-full -ml-10 -mb-10 blur-2xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                        <div class="flex-1 text-center md:text-left">
                            <span class="inline-block px-4 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Nivel 3: Exploradores</span>
                            <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">Tu camino al éxito escolar</h3>
                            <p class="text-on-primary/80 mb-8 max-w-md">¡Faltan solo 55 puntos para desbloquear la siguiente estación en tu mapa!</p>
                            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                                <a href="<?php echo URLROOT; ?>/padres/camino" class="bg-white text-primary px-8 py-3.5 rounded-2xl font-bold text-sm shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                                    Ver Mapa Interactivo
                                    <span class="material-symbols-outlined">map</span>
                                </a>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-white/20">
                            <div class="flex justify-between items-end mb-4">
                                <span class="text-sm font-bold">Progreso Actual</span>
                                <span class="text-2xl font-black">245 <span class="text-xs opacity-60">/ 300</span></span>
                            </div>
                            <div class="w-full bg-white/20 h-4 rounded-full overflow-hidden">
                                <div class="bg-secondary h-full w-[82%] shadow-[0_0_15px_rgba(169,51,73,0.5)] transition-all duration-1000"></div>
                            </div>
                            <p class="text-[10px] mt-4 opacity-70 text-center italic">"Cada asistencia cuenta para el futuro de tus hijos"</p>
                        </div>
                    </div>
                </div>

                <!-- Stats and Hijos Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Hijos Section -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xl font-bold text-on-surface">Mis Hijos</h4>
                            <a href="#" class="text-primary text-sm font-bold hover:underline">Ver detalles</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-16 h-16 rounded-2xl bg-tertiary-fixed flex items-center justify-center text-tertiary">
                                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">face</span>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-on-surface">Celeste Bedoya</h5>
                                        <p class="text-xs text-outline font-bold uppercase tracking-tighter">Grado 9°2 - Jornada Mañana</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-on-surface-variant">Asistencia Escolar</span>
                                        <span class="font-bold text-tertiary">95%</span>
                                    </div>
                                    <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                                        <div class="bg-tertiary h-full w-[95%]"></div>
                                    </div>
                                </div>
                                <button class="w-full mt-6 py-3 bg-surface-container-high rounded-xl text-xs font-bold text-on-surface group-hover:bg-primary group-hover:text-on-primary transition-all flex items-center justify-center gap-2">
                                    Ver Notas
                                    <span class="material-symbols-outlined text-sm">trending_up</span>
                                </button>
                            </div>

                            <div class="bg-white p-6 rounded-3xl border border-outline-variant shadow-sm hover:shadow-md transition-all group">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-16 h-16 rounded-2xl bg-secondary-fixed flex items-center justify-center text-secondary">
                                        <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">face_3</span>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-on-surface">Emily Bedoya</h5>
                                        <p class="text-xs text-outline font-bold uppercase tracking-tighter">Grado 5°3 - Jornada Tarde</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-on-surface-variant">Asistencia Escolar</span>
                                        <span class="font-bold text-secondary">88%</span>
                                    </div>
                                    <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                                        <div class="bg-secondary h-full w-[88%]"></div>
                                    </div>
                                </div>
                                <button class="w-full mt-6 py-3 bg-surface-container-high rounded-xl text-xs font-bold text-on-surface group-hover:bg-primary group-hover:text-on-primary transition-all flex items-center justify-center gap-2">
                                    Ver Notas
                                    <span class="material-symbols-outlined text-sm">trending_up</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Puntos Summary -->
                    <div class="bg-white rounded-3xl border border-outline-variant p-8 shadow-sm h-full">
                        <h4 class="text-xl font-bold text-on-surface mb-6">¿Cómo ganar puntos?</h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-surface-container transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-all">
                                    <span class="material-symbols-outlined text-sm">groups</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold">Reunión General</p>
                                    <p class="text-[10px] text-outline">Participación presencial</p>
                                </div>
                                <span class="text-sm font-black text-secondary">+50</span>
                            </div>
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-surface-container transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:bg-tertiary group-hover:text-on-tertiary transition-all">
                                    <span class="material-symbols-outlined text-sm">school</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold">Talleres/Escuelas</p>
                                    <p class="text-[10px] text-outline">Formación para padres</p>
                                </div>
                                <span class="text-sm font-black text-secondary">+30</span>
                            </div>
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-surface-container transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-on-secondary transition-all">
                                    <span class="material-symbols-outlined text-sm">play_circle</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold">Videos Educativos</p>
                                    <p class="text-[10px] text-outline">Contenido interactivo</p>
                                </div>
                                <span class="text-sm font-black text-secondary">+10</span>
                            </div>
                        </div>
                        <div class="mt-10 p-6 bg-surface-container-high rounded-2xl text-center">
                            <p class="text-xs text-on-surface-variant italic leading-relaxed">"Su participación activa otorga puntos que desbloquean beneficios para su familia."</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php require APPROOT . '/views/inc/footer.php'; ?>
        </main>
    </div>
