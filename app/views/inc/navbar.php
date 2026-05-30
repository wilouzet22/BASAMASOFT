    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md border-b border-outline-variant fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="<?php echo URLROOT; ?>" class="flex-shrink-0 flex items-center gap-3">
                        <img class="h-12 w-12 rounded-full border border-outline-variant shadow-sm" src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="EduSaft Logo">
                        <div class="flex flex-col">
                            <span class="font-headline-md text-headline-md text-primary leading-none">EduSaft</span>
                            <span class="text-[10px] text-on-surface-variant uppercase tracking-[0.2em] font-bold">Portal Escolar</span>
                        </div>
                    </a>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="<?php echo URLROOT; ?>" class="text-on-surface inline-flex items-center px-1 pt-1 border-b-2 border-primary font-label-md text-label-md">Inicio</a>
                        <a href="#" class="text-on-surface-variant hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-primary-fixed transition-all font-label-md text-label-md">Carrera</a>
                        <a href="#" class="text-on-surface-variant hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-primary-fixed transition-all font-label-md text-label-md">Contenidos</a>
                        <a href="#" class="text-on-surface-variant hover:text-primary inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-primary-fixed transition-all font-label-md text-label-md">Eventos</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="flex items-center gap-2 px-4 py-2 bg-surface-container rounded-full border border-outline-variant">
                            <span class="material-symbols-outlined text-primary text-sm">person</span>
                            <span class="font-label-sm text-on-surface"><?php echo $_SESSION['username']; ?></span>
                        </div>
                        <a href="<?php echo URLROOT; ?>/auth/logout" class="text-error hover:bg-error-container/10 px-4 py-2 rounded-full font-label-md text-label-md transition-colors">Salir</a>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="bg-primary text-on-primary px-6 py-2.5 rounded-full font-label-md text-label-md shadow-sm hover:opacity-90 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">login</span>
                            Ingresar
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center md:hidden">
                    <button type="button" id="mobile-menu-button" class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden bg-white border-t border-outline-variant" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="<?php echo URLROOT; ?>" class="bg-primary-container text-on-primary-container block pl-3 pr-4 py-3 border-l-4 border-primary font-label-md text-label-md">Inicio</a>
                <a href="#" class="text-on-surface-variant hover:bg-surface-container block pl-3 pr-4 py-3 border-l-4 border-transparent font-label-md text-label-md">Carrera</a>
                <a href="#" class="text-on-surface-variant hover:bg-surface-container block pl-3 pr-4 py-3 border-l-4 border-transparent font-label-md text-label-md">Contenidos</a>
                <a href="#" class="text-on-surface-variant hover:bg-surface-container block pl-3 pr-4 py-3 border-l-4 border-transparent font-label-md text-label-md">Eventos</a>
            </div>
            <div class="pt-4 pb-4 border-t border-outline-variant">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="space-y-1">
                        <a href="<?php echo URLROOT; ?>/padres/dashboard" class="block px-4 py-3 font-label-md text-label-md text-on-surface-variant hover:bg-surface-container">Mi Perfil</a>
                        <a href="<?php echo URLROOT; ?>/auth/logout" class="block px-4 py-3 font-label-md text-label-md text-error hover:bg-error-container/10">Cerrar Sesión</a>
                    </div>
                <?php else: ?>
                    <div class="px-4">
                        <a href="<?php echo URLROOT; ?>/auth/login" class="w-full bg-primary text-on-primary px-4 py-3 rounded-xl font-label-md text-label-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">login</span>
                            Iniciar Sesión
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
