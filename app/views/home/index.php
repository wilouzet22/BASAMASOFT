<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="graph-paper-bg min-h-screen pt-16">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-headline-xl text-primary font-headline-xl mb-6 tracking-tight">Bienvenidos a EduSaft</h1>
            <p class="text-body-lg text-on-surface-variant mb-8 max-w-2xl">
                Conectando familia y escuela para un mejor futuro. Participa activamente en el proceso educativo de tus hijos a través de nuestra plataforma interactiva.
            </p>
            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                <a href="<?php echo URLROOT; ?>/auth/login" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-md text-label-md shadow-sm hover:opacity-90 transition-opacity">
                    Comenzar Ahora
                </a>
                <a href="<?php echo URLROOT; ?>/home/terminos" class="border-2 border-primary text-primary px-8 py-3 rounded-lg font-label-md text-label-md hover:bg-surface-container transition-colors">
                    Ver Términos
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="bg-surface-container-highest rounded-full w-64 h-64 md:w-96 md:h-96 absolute -top-12 -left-12 opacity-50 blur-3xl"></div>
            <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="EduSaft Logo" class="relative z-10 w-full max-w-md mx-auto drop-shadow-2xl animate-pulse">
        </div>
    </div>

    <!-- Content Sections -->
    <div class="bg-white/80 backdrop-blur-sm py-20 border-t border-outline-variant">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-headline-lg text-on-surface font-headline-lg mb-4">Nuestras Secciones</h2>
                <p class="text-body-md text-on-surface-variant">Explora las herramientas que EduSaft tiene para ti.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card Carrera -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-primary-fixed rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl">flag</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Carrera</h3>
                    <p class="text-body-md text-on-surface-variant mb-6">Sigue el progreso de tu familia a través de un camino interactivo. ¡Asiste a eventos y desbloquea logros!</p>
                    <a href="#" class="text-primary font-semibold hover:underline flex items-center gap-2">
                        Ver más <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <!-- Card Contenidos -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-tertiary-fixed rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-tertiary text-3xl">library_books</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Contenidos</h3>
                    <p class="text-body-md text-on-surface-variant mb-6">Accede a recursos educativos, videos y guías para apoyar el aprendizaje desde casa.</p>
                    <a href="#" class="text-tertiary font-semibold hover:underline flex items-center gap-2">
                        Explorar <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>

                <!-- Card Eventos -->
                <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-secondary-fixed rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-secondary text-3xl">event</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface font-headline-md mb-2">Eventos</h3>
                    <p class="text-body-md text-on-surface-variant mb-6">Mantente al día con las reuniones, talleres y actividades escolares programadas.</p>
                    <a href="#" class="text-secondary font-semibold hover:underline flex items-center gap-2">
                        Ver calendario <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
