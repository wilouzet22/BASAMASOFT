<footer class="bg-white border-t mt-12 transition-colors duration-200">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
        <?php
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $isAdminOrDocente = (strpos($uri, '/admin') !== false || strpos($uri, '/docentes') !== false);
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
        <div class="mt-8 md:mt-0 md:order-1">
            <p class="text-center text-base text-gray-400">&copy; <?php echo date('Y'); ?> EduSaft. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<!-- Selector Flotante de Temas (Esquina Inferior Derecha) -->
<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2.5">
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
</body>
</html>
