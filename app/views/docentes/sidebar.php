<nav class="hidden md:flex flex-col fixed left-0 top-0 h-full py-4 docked w-64 border-r border-outline-variant bg-white z-40">
    <div class="px-6 pb-6">
        <h1 class="text-primary font-headline-md text-headline-md">Edusaft Portal</h1>
        <p class="text-on-surface-variant font-body-md text-body-md">Gestión Docente</p>
    </div>
    
    <div class="flex-grow flex flex-col gap-1">
        <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/docentes/dashboard">
            <span class="material-symbols-outlined">dashboard</span>
            Panel Principal
        </a>
        <a class="bg-primary-container text-on-primary-container font-semibold rounded-lg mx-2 px-4 py-3 flex items-center gap-3" href="<?php echo URLROOT; ?>/docentes/actividades">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">assignment</span>
            Actividades
        </a>
        <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/docentes/asistencia">
            <span class="material-symbols-outlined">event_available</span>
            Asistencia
        </a>
        <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="#">
            <span class="material-symbols-outlined">group</span>
            Estudiantes
        </a>
    </div>

    <div class="mt-auto border-t border-outline-variant pt-4 flex flex-col gap-1">
        <a class="text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/docentes/configuracion">
            <span class="material-symbols-outlined">settings</span>
            Configuración
        </a>
        <a class="text-error px-4 py-3 mx-2 hover:bg-error-container rounded-lg transition-colors flex items-center gap-3" href="<?php echo URLROOT; ?>/auth/logout">
            <span class="material-symbols-outlined">logout</span>
            Cerrar Sesión
        </a>
    </div>
</nav>
