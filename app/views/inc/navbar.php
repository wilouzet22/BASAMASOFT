    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo URLROOT; ?>" class="flex-shrink-0 flex items-center">
                        <img class="h-10 w-auto rounded-full" src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="EduSaft Logo">
                        <span class="ml-2 font-bold text-xl text-gray-800">EduSaft</span>
                    </a>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="<?php echo URLROOT; ?>" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium">Inicio</a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Carrera</a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Contenidos</a>
                        <a href="#" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Eventos</a>
                    </div>
                </div>
                <div class="flex items-center md:hidden">
                    <!-- Mobile menu button -->
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Abrir menú principal</span>
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
                <div class="hidden md:flex items-center">
                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="ml-3 relative flex space-x-4">
                            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Notificaciones</a>
                            <a href="#" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Configuración</a>
                            <a href="<?php echo URLROOT; ?>/padres/dashboard" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Mi Perfil</a>
                            <a href="<?php echo URLROOT; ?>/auth/logout" class="text-red-500 hover:text-red-700 text-sm font-medium">Salir</a>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo URLROOT; ?>/auth/login" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                            <span class="material-symbols-outlined mr-1">account_circle</span>
                            Ingresar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="hidden md:hidden bg-white border-t" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="<?php echo URLROOT; ?>" class="bg-blue-50 border-blue-500 text-blue-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Inicio</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Carrera</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Contenidos</a>
                <a href="#" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Eventos</a>
            </div>
            <div class="pt-4 pb-4 border-t border-gray-200">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="space-y-1">
                        <a href="<?php echo URLROOT; ?>/padres/dashboard" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Mi Perfil</a>
                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Configuración</a>
                        <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Notificaciones</a>
                        <a href="<?php echo URLROOT; ?>/auth/logout" class="block px-4 py-2 text-base font-medium text-red-500 hover:text-red-700 hover:bg-gray-100">Cerrar Sesión</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-1">
                        <a href="<?php echo URLROOT; ?>/auth/login" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 flex items-center">
                            <span class="material-symbols-outlined mr-2">login</span>
                            Iniciar Sesión
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
