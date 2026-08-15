<?php
    $uri_footer = $_SERVER['REQUEST_URI'] ?? '';
    $isCaminoPage = (strpos($uri_footer, '/padres/camino') !== false);
?>
<?php if (!$isCaminoPage): ?>
<footer class="bg-white border-t mt-12 transition-colors duration-200">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 flex flex-col items-center gap-6 lg:px-8">
        <?php
            $isAdminOrDocente = (strpos($uri_footer, '/admin') !== false || strpos($uri_footer, '/docentes') !== false);
        ?>
        <?php if (!$isAdminOrDocente): ?>
        <div class="flex justify-center space-x-6 md:order-2">
            <a href="#" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Facebook</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Instagram</span>
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.48 2h.08zm-1.6 1.6c-2.474 0-2.767.01-3.732.054-.965.044-1.488.206-1.838.342-.457.178-.783.39-1.127.734-.345.344-.556.67-.734 1.127-.136.35-.298.873-.342 1.838-.044.965-.054 1.258-.054 3.732v.08c0 2.474.01 2.767.054 3.732.044.965.206 1.488.342 1.838.178.457.39.783.734 1.127.344.345.67.556 1.127.734.35.136.873.298 1.838.342.965.044 1.258.054 3.732.054h.08c2.474 0 2.767-.01 3.732-.054.965-.044 1.488-.206 1.838-.342.457-.178.783-.39 1.127-.734.345-.344.556-.67.734-1.127.136-.35.298-.873.342-1.838.044-.965.054-1.258.054-3.732v-.08c0-2.474-.01-2.767-.054-3.732-.044-.965-.206-1.488-.342-1.838-.178-.457-.39-.783-.734-1.127-.344-.345-.67-.556-1.127-.734.35-.136.873-.298-1.838-.342.965-.044 1.258-.054 3.732-.054h.08zM12.337 6.4h.065c3.096 0 5.6 2.504 5.6 5.6 0 3.096-2.504 5.6-5.6 5.6-3.096 0-5.6-2.504-5.6-5.6 0-3.096 2.504-5.6 5.6-5.6zm0 1.6c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <?php endif; ?>
        <div class="mt-8 md:mt-0 md:order-1 w-full flex flex-col items-center text-center">
            <div class="text-sm text-gray-500 space-y-1 mb-4">
                <p>Dirección: Calle 63 No 108BB - 160 Barrio: Robledo Santa Margarita</p>
                <p>Contacto: Bachillerato: 3004176050 - Sec.Escuela Santa Margarita: 3004184485 - Sec.Escuela Pedro Nel Ospina: 3004186418</p>
                <p>Correo electrónico: contactenos@iebarriosantamargarita.edu.co | Medellín - Antioquia - Colombia</p>
            </div>
            <p class="text-base text-gray-400">&copy; <?php echo date('Y'); ?> EduSaft. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Selector Flotante de Temas (Esquina Inferior Derecha) -->
<div id="global-theme-selector" class="fixed bottom-[85px] md:bottom-6 right-4 md:right-6 z-50 flex flex-col items-end gap-2.5">
    <!-- Menú Desplegable de Temas -->
    <div id="theme-options-menu" class="hidden flex-col items-end gap-2 transition-all duration-300 opacity-0 transform translate-y-3 pointer-events-none mb-1">
        <!-- Opción: Modo Súper Oscuro -->
        <button type="button" onclick="setTheme('superdark')"
                class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-black text-white text-xs font-bold shadow-2xl border border-neutral-700 hover:scale-105 active:scale-95 transition-all cursor-pointer group">
            <span class="w-3.5 h-3.5 rounded-full bg-black border border-white group-hover:scale-110 transition-transform"></span>
            <span class="material-symbols-outlined text-base">contrast</span>
            <span>Súper Oscuro</span>
        </button>

        <!-- Opción: Modo Oscuro (Slate/Azul) -->
        <button type="button" onclick="setTheme('dark')"
                class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-slate-800 text-white text-xs font-bold shadow-2xl border border-slate-600 hover:scale-105 active:scale-95 transition-all cursor-pointer group">
            <span class="w-3.5 h-3.5 rounded-full bg-slate-900 border border-sky-400 group-hover:scale-110 transition-transform"></span>
            <span class="material-symbols-outlined text-base">dark_mode</span>
            <span>Modo Oscuro</span>
        </button>

        <!-- Opción: Modo Claro -->
        <button type="button" onclick="setTheme('light')"
                class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-white text-gray-900 text-xs font-bold shadow-2xl border border-gray-300 hover:scale-105 active:scale-95 transition-all cursor-pointer group">
            <span class="w-3.5 h-3.5 rounded-full bg-amber-400 border border-amber-600 group-hover:scale-110 transition-transform"></span>
            <span class="material-symbols-outlined text-base text-amber-500">light_mode</span>
            <span>Modo Claro</span>
        </button>
    </div>

    <!-- Botón Principal Globo -->
    <button id="theme-main-toggle" type="button" title="Cambiar Tema" aria-expanded="false"
            class="p-3.5 bg-primary hover:bg-primary/90 text-white rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 border-2 border-white/30 dark:bg-amber-400 dark:text-gray-900 dark:border-gray-800 flex items-center justify-center cursor-pointer">
        <span class="material-symbols-outlined text-2xl">palette</span>
    </button>
</div>

<script>
    (function() {
        function initThemeSelector() {
            const menu = document.getElementById('theme-options-menu');
            const mainBtn = document.getElementById('theme-main-toggle');

            function toggleMenu() {
                if (!menu) return;
                const isHidden = menu.classList.contains('hidden');
                if (isHidden) {
                    menu.classList.remove('hidden');
                    setTimeout(function() {
                        menu.classList.remove('opacity-0', 'translate-y-3', 'pointer-events-none');
                        menu.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                    }, 10);
                    mainBtn.setAttribute('aria-expanded', 'true');
                } else {
                    menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                    menu.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
                    setTimeout(function() {
                        menu.classList.add('hidden');
                    }, 300);
                    mainBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (mainBtn) {
                mainBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu();
                });
            }

            document.addEventListener('click', function(e) {
                if (menu && !menu.contains(e.target) && mainBtn && !mainBtn.contains(e.target)) {
                    if (!menu.classList.contains('hidden')) {
                        menu.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                        menu.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
                        setTimeout(function() {
                            menu.classList.add('hidden');
                        }, 300);
                        mainBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            window.setTheme = function(theme) {
                if (theme === 'superdark') {
                    document.documentElement.classList.add('dark', 'superdark');
                    localStorage.setItem('theme', 'superdark');
                } else if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    document.documentElement.classList.remove('superdark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark', 'superdark');
                    localStorage.setItem('theme', 'light');
                }
                toggleMenu();
            };
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initThemeSelector);
        } else {
            initThemeSelector();
        }
    })();
</script>

<script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>

<?php
    $uri_nav = $_SERVER['REQUEST_URI'] ?? '';
    $isAdmin    = strpos($uri_nav, '/admin') !== false;
    $isDocente  = strpos($uri_nav, '/docentes') !== false;
    $isFamilia  = strpos($uri_nav, '/padres') !== false;
?>

<!-- Bottom Navigation Bar (solo móvil) -->
<nav id="mobile-bottom-nav" aria-label="Navegación móvil">
    <?php if ($isAdmin): ?>
        <a href="<?php echo URLROOT; ?>/admin/dashboard"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/admin/dashboard') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl" style="<?php echo strpos($uri_nav, '/admin/dashboard') !== false ? 'font-variation-settings:\'FILL\' 1' : ''; ?>">dashboard</span>
            <span>Panel</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/estudiantes"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/admin/estudiantes') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl" style="<?php echo strpos($uri_nav, '/admin/estudiantes') !== false ? 'font-variation-settings:\'FILL\' 1' : ''; ?>">groups</span>
            <span>Estudiantes</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/asistencias"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/admin/asistencias') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl" style="<?php echo strpos($uri_nav, '/admin/asistencias') !== false ? 'font-variation-settings:\'FILL\' 1' : ''; ?>">how_to_reg</span>
            <span>Asistencias</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/actividades"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/admin/actividades') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl" style="<?php echo strpos($uri_nav, '/admin/actividades') !== false ? 'font-variation-settings:\'FILL\' 1' : ''; ?>">event</span>
            <span>Actividades</span>
        </a>
        <button type="button" onclick="openModal('adminMoreModal')"
                class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 text-gray-500">
            <span class="material-symbols-outlined text-2xl">more_horiz</span>
            <span>Más</span>
        </button>
    <?php elseif ($isDocente): ?>
        <a href="<?php echo URLROOT; ?>/docentes/dashboard"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/docentes/dashboard') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">dashboard</span>
            <span>Panel</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/actividades"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/docentes/actividades') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">assignment</span>
            <span>Actividades</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/asistencia"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/docentes/asistencia') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">event_available</span>
            <span>Asistencia</span>
        </a>
        <button type="button" onclick="openModal('docentesMoreModal')"
                class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 text-gray-500">
            <span class="material-symbols-outlined text-2xl">more_horiz</span>
            <span>Más</span>
        </button>
    <?php elseif ($isFamilia): ?>
        <a href="<?php echo URLROOT; ?>/padres/dashboard"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/padres/dashboard') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">home</span>
            <span>Inicio</span>
        </a>
        <a href="<?php echo URLROOT; ?>/padres/camino"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/padres/camino') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">route</span>
            <span>Camino</span>
        </a>
        <a href="<?php echo URLROOT; ?>/padres/puntos"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/padres/puntos') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">stars</span>
            <span>Puntos</span>
        </a>
        <a href="<?php echo URLROOT; ?>/padres/mensajes"
           class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 <?php echo strpos($uri_nav, '/padres/mensajes') !== false ? 'text-primary' : 'text-gray-500'; ?>">
            <span class="material-symbols-outlined text-2xl">mail</span>
            <span>Mensajes</span>
        </a>
        <button type="button" id="mobileMoreBtn"
                onclick="openModal('mobileMoreModal')"
                class="flex flex-col items-center flex-1 py-1 text-[10px] gap-0.5 text-gray-500">
            <span class="material-symbols-outlined text-2xl">more_horiz</span>
            <span>Más</span>
        </button>
    <?php endif; ?>
</nav>

<!-- ===== MODALS DE NAVEGACIÓN (MÁS OPCIONES) ===== -->

<!-- 1. ADMIN MODAL -->
<?php if ($isAdmin): ?>
<div id="adminMoreModal" class="fixed inset-0 z-[200] hidden items-center justify-center pointer-events-none">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-auto" 
         onclick="closeModal('adminMoreModal')"></div>
    <div class="relative bg-surface rounded-3xl p-6 max-w-sm w-[85%] max-h-[85vh] overflow-y-auto mx-auto shadow-2xl border border-outline-variant/50 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto flex flex-col gap-2">
        <h3 class="text-xl font-extrabold text-on-surface mb-2 text-center">Menú Principal</h3>
        
        <a href="<?php echo URLROOT; ?>/admin/profesores" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">school</span>
            <span class="font-bold text-sm">Profesores</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/familias" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">family_restroom</span>
            <span class="font-bold text-sm">Familias</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/sedes" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">domain</span>
            <span class="font-bold text-sm">Sedes</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/grupos" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">category</span>
            <span class="font-bold text-sm">Grupos</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/mensajes" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">mail</span>
            <span class="font-bold text-sm">Mensajes</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/auditoria" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">history</span>
            <span class="font-bold text-sm">Auditoría</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/configuracion" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">settings</span>
            <span class="font-bold text-sm">Configuración</span>
        </a>
        <a href="<?php echo URLROOT; ?>/auth/logout" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-error-container/20 hover:bg-error-container/50 transition-colors text-error mt-2 border border-error/20">
            <span class="material-symbols-outlined text-2xl text-error">logout</span>
            <span class="font-bold text-sm">Cerrar sesión</span>
        </a>
        
        <div class="mt-2 w-full flex justify-center">
            <button type="button" onclick="closeModal('adminMoreModal')" class="text-xs text-on-surface-variant font-bold uppercase tracking-wider hover:text-on-surface p-2">CERRAR MENÚ</button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 2. DOCENTES MODAL -->
<?php if ($isDocente): ?>
<div id="docentesMoreModal" class="fixed inset-0 z-[200] hidden items-center justify-center pointer-events-none">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-auto" 
         onclick="closeModal('docentesMoreModal')"></div>
    <div class="relative bg-surface rounded-3xl p-6 max-w-sm w-[85%] max-h-[85vh] overflow-y-auto mx-auto shadow-2xl border border-outline-variant/50 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto flex flex-col gap-2">
        <h3 class="text-xl font-extrabold text-on-surface mb-2 text-center">Menú Principal</h3>
        
        <a href="<?php echo URLROOT; ?>/docentes/mensajes" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">mail</span>
            <span class="font-bold text-sm">Mensajes</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/notificaciones" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">notifications</span>
            <span class="font-bold text-sm">Notificaciones</span>
        </a>
        <a href="<?php echo URLROOT; ?>/docentes/configuracion" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface">
            <span class="material-symbols-outlined text-2xl text-primary">settings</span>
            <span class="font-bold text-sm">Configuración</span>
        </a>
        <a href="<?php echo URLROOT; ?>/auth/logout" class="flex items-center gap-4 px-4 py-3 rounded-xl bg-error-container/20 hover:bg-error-container/50 transition-colors text-error mt-2 border border-error/20">
            <span class="material-symbols-outlined text-2xl text-error">logout</span>
            <span class="font-bold text-sm">Cerrar sesión</span>
        </a>
        
        <div class="mt-2 w-full flex justify-center">
            <button type="button" onclick="closeModal('docentesMoreModal')" class="text-xs text-on-surface-variant font-bold uppercase tracking-wider hover:text-on-surface p-2">CERRAR MENÚ</button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 3. PADRES MODAL -->
<?php if ($isFamilia): ?>
<div id="mobileMoreModal" class="fixed inset-0 z-[200] hidden items-center justify-center pointer-events-none">
    <!-- Backdrop con blur -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-auto" 
         onclick="closeModal('mobileMoreModal')"></div>
    
    <!-- Contenido del modal -->
    <div class="relative bg-surface rounded-3xl p-6 max-w-sm w-[85%] mx-auto shadow-2xl border border-outline-variant/50 transform scale-95 opacity-0 transition-all duration-300 pointer-events-auto flex flex-col gap-2">
        <h3 class="text-xl font-extrabold text-on-surface mb-4 text-center">Menú de Opciones</h3>
        
        <a href="<?php echo URLROOT; ?>/padres/mensajes"
           class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors <?php echo strpos($uri_nav, '/padres/mensajes') !== false ? 'text-primary' : 'text-on-surface'; ?>">
            <span class="material-symbols-outlined text-2xl <?php echo strpos($uri_nav, '/padres/mensajes') !== false ? 'text-primary' : 'text-primary'; ?>">mail</span>
            <span class="font-bold text-sm">Mis Mensajes</span>
        </a>

        <button type="button" onclick="closeModal('mobileMoreModal'); setTimeout(()=>openModal('contactosModal'), 300)"
                class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface w-full text-left">
            <span class="material-symbols-outlined text-2xl text-tertiary">group</span>
            <span class="font-bold text-sm">Contáctanos</span>
        </button>

        <button type="button" onclick="closeModal('mobileMoreModal'); setTimeout(()=>openModal('opinionModal'), 300)"
                class="flex items-center gap-4 px-4 py-3 rounded-xl bg-surface-container-low hover:bg-primary/10 transition-colors text-on-surface w-full text-left">
            <span class="material-symbols-outlined text-2xl text-secondary">chat_bubble</span>
            <span class="font-bold text-sm">Opinión</span>
        </button>

        <button type="button" onclick="closeModal('mobileMoreModal'); setTimeout(()=>openLogoutModal(), 300)"
                class="flex items-center gap-4 px-4 py-3 rounded-xl bg-error-container/20 hover:bg-error-container/50 transition-colors text-error w-full text-left mt-2 border border-error/20">
            <span class="material-symbols-outlined text-2xl text-error">logout</span>
            <span class="font-bold text-sm">Cerrar sesión</span>
        </button>
        
        <div class="mt-4 w-full flex justify-center">
            <button type="button" onclick="closeModal('mobileMoreModal')" class="text-xs text-on-surface-variant font-bold uppercase tracking-wider hover:text-on-surface p-2 transition-colors">CERRAR MENÚ</button>
        </div>
    </div>
</div>
<?php endif; ?>
</body>
</html>
