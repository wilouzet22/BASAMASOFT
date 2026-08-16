<!-- Theme Selector Component (Claro, Oscuro, Súper Oscuro) -->
<div class="relative inline-block text-left" id="theme-selector-wrapper">
    <button type="button" 
            onclick="toggleThemeMenu(event)" 
            id="theme-toggle-btn"
            class="w-10 h-10 rounded-full bg-surface-container-high/60 hover:bg-surface-container-high border border-outline-variant/60 flex items-center justify-center text-on-surface-variant hover:text-primary transition-all shadow-sm active:scale-95 cursor-pointer" 
            title="Cambiar tema visual (3 estilos)"
            aria-label="Selector de tema">
        <span id="theme-icon-indicator" class="material-symbols-outlined text-[20px]">palette</span>
    </button>

    <!-- Menú Desplegable con los 3 estilos -->
    <div id="theme-menu-dropdown" 
         class="hidden absolute right-0 mt-2 w-52 bg-surface/95 backdrop-blur-md rounded-2xl shadow-2xl border border-outline-variant/80 p-1.5 z-[100] flex-col gap-1 transition-all duration-200 opacity-0 transform scale-95 origin-top-right">
        
        <div class="px-3 py-1.5 border-b border-outline-variant/40 mb-1">
            <p class="text-[10px] uppercase font-extrabold tracking-wider text-outline">Tema Visual</p>
        </div>

        <!-- 1. Modo Claro -->
        <button type="button" 
                onclick="setTheme('light')" 
                class="theme-select-opt flex items-center gap-3 w-full px-3 py-2 rounded-xl text-xs font-semibold hover:bg-surface-container text-on-surface transition-all text-left cursor-pointer" 
                data-theme-value="light">
            <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-xs">
                <span class="material-symbols-outlined text-base">light_mode</span>
            </span>
            <div class="flex flex-col flex-1 leading-tight">
                <span class="font-bold">Modo Claro</span>
                <span class="text-[10px] text-on-surface-variant font-normal">Luminoso</span>
            </div>
            <span class="material-symbols-outlined text-base text-primary hidden check-mark">check</span>
        </button>

        <!-- 2. Modo Oscuro (Slate) -->
        <button type="button" 
                onclick="setTheme('dark')" 
                class="theme-select-opt flex items-center gap-3 w-full px-3 py-2 rounded-xl text-xs font-semibold hover:bg-surface-container text-on-surface transition-all text-left cursor-pointer" 
                data-theme-value="dark">
            <span class="w-7 h-7 rounded-lg bg-slate-800 text-sky-400 border border-slate-700 flex items-center justify-center flex-shrink-0 shadow-xs">
                <span class="material-symbols-outlined text-base">dark_mode</span>
            </span>
            <div class="flex flex-col flex-1 leading-tight">
                <span class="font-bold">Modo Oscuro</span>
                <span class="text-[10px] text-on-surface-variant font-normal">Azul pizarra</span>
            </div>
            <span class="material-symbols-outlined text-base text-primary hidden check-mark">check</span>
        </button>

        <!-- 3. Modo Súper Oscuro (OLED Black) -->
        <button type="button" 
                onclick="setTheme('superdark')" 
                class="theme-select-opt flex items-center gap-3 w-full px-3 py-2 rounded-xl text-xs font-semibold hover:bg-surface-container text-on-surface transition-all text-left cursor-pointer" 
                data-theme-value="superdark">
            <span class="w-7 h-7 rounded-lg bg-black text-neutral-200 border border-neutral-700 flex items-center justify-center flex-shrink-0 shadow-xs">
                <span class="material-symbols-outlined text-base">contrast</span>
            </span>
            <div class="flex flex-col flex-1 leading-tight">
                <span class="font-bold">Súper Oscuro</span>
                <span class="text-[10px] text-on-surface-variant font-normal">Negro profundo</span>
            </div>
            <span class="material-symbols-outlined text-base text-primary hidden check-mark">check</span>
        </button>
    </div>
</div>
