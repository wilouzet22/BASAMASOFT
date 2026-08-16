<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleMobileSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>

</header>

<div class="flex">
    <!-- Sidebar reusable admin -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Gestión de Familias</h2>
                    <p class="text-sm text-on-surface-variant">
                        Administración · <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Administrador del Sistema</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">admin_panel_settings</span>
                    </a>
                </div>
            </div>
        </header>

        <div class="p-6 md:p-10 space-y-6 max-w-7xl mx-auto w-full flex-1">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-3xl md:text-4xl">family_restroom</span>
                        Familias / Acudientes <span class="text-lg font-medium text-on-surface-variant">(<?php echo count($data['familias']); ?>)</span>
                    </h1>
                    <p class="text-sm text-on-surface-variant mt-1">Listado de acudientes y familias vinculadas a la comunidad educativa.</p>
                </div>
            </div>

            <!-- Buscador de Estudiantes -->
            <div class="bg-white rounded-2xl border border-outline-variant/60 p-4 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                        <input
                            type="text"
                            id="buscador-estudiante"
                            placeholder="Buscar por nombre del estudiante, acudiente, usuario o documento..."
                            oninput="filtrarFamilias(this.value)"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant bg-surface-container/30 text-sm font-medium text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary focus:bg-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all"
                        >
                        <button type="button" onclick="limpiarBusqueda()" id="btn-limpiar" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-on-surface-variant bg-surface-container/50 rounded-xl px-3 py-2 border border-outline-variant/40 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[16px] text-primary">filter_list</span>
                        <span id="contador-resultados" class="font-bold text-on-surface"><?php echo count($data['familias']); ?></span>
                        <span>de <?php echo count($data['familias']); ?> familias</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de Familias -->
            <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <?php if (empty($data['familias'])): ?>
                    <div class="bg-surface-container-low p-8 rounded-2xl border border-outline-variant/30 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-60">family_restroom</span>
                        <p class="text-sm font-medium">No hay familias registradas.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left" id="tabla-familias">
                            <thead>
                                <tr class="border-b border-outline-variant/40 text-on-surface-variant text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Acudiente Principal</th>
                                    <th class="py-3 px-4">Documento</th>
                                    <th class="py-3 px-4">Correo</th>
                                    <th class="py-3 px-4">Teléfono</th>
                                    <th class="py-3 px-4">Usuario</th>
                                    <th class="py-3 px-4">Estudiantes Vinculados</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/20" id="tbody-familias">
                                <?php foreach ($data['familias'] as $fam): ?>
                                    <tr class="familia-row hover:bg-surface-container-low/50 transition-colors"
                                        data-search="<?php echo strtolower(htmlspecialchars(
                                            $fam->nombre_principal_acudiente . ' ' .
                                            $fam->apellidos_principal_acudiente . ' ' .
                                            $fam->documento_principal_acudiente . ' ' .
                                            $fam->username . ' ' .
                                            ($fam->email_contacto ?? '') . ' ' .
                                            ($fam->estudiantes ?? '')
                                        )); ?>">
                                        <td class="py-3.5 px-4 font-bold text-on-surface">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-secondary/10 border border-secondary/20 flex items-center justify-center flex-shrink-0">
                                                    <span class="material-symbols-outlined text-secondary text-[16px]">person</span>
                                                </div>
                                                <?php echo htmlspecialchars($fam->nombre_principal_acudiente . ' ' . $fam->apellidos_principal_acudiente); ?>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs text-on-surface-variant"><?php echo htmlspecialchars($fam->documento_principal_acudiente); ?></td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($fam->email_contacto); ?></td>
                                        <td class="py-3.5 px-4 text-xs text-on-surface-variant font-medium"><?php echo htmlspecialchars($fam->telefono_contacto ?? '—'); ?></td>
                                        <td class="py-3.5 px-4">
                                            <span class="bg-secondary/10 text-secondary font-bold px-2.5 py-1 rounded-full text-xs">
                                                <?php echo htmlspecialchars($fam->username); ?>
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <?php if (!empty($fam->estudiantes)): ?>
                                                <div class="flex flex-wrap gap-1">
                                                    <?php foreach (explode(', ', $fam->estudiantes) as $est): ?>
                                                        <span class="inline-flex items-center gap-1 bg-primary/8 text-primary border border-primary/15 px-2 py-0.5 rounded-full text-[11px] font-semibold">
                                                            <span class="material-symbols-outlined text-[11px]">school</span>
                                                            <?php echo htmlspecialchars(trim($est)); ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-xs text-on-surface-variant/60 italic">Sin estudiantes</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Estado vacío cuando no hay resultados del buscador -->
                        <div id="sin-resultados" class="hidden py-12 text-center">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 block mb-3">search_off</span>
                            <p class="text-sm font-bold text-on-surface-variant">No se encontraron familias</p>
                            <p class="text-xs text-on-surface-variant/60 mt-1">Intenta con otro nombre de estudiante o acudiente</p>
                            <button onclick="limpiarBusqueda()" class="mt-3 text-xs text-primary hover:underline font-semibold">Limpiar búsqueda</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </main>
</div>

<script>
const totalFamilias = <?php echo count($data['familias']); ?>;

function filtrarFamilias(query) {
    const q = query.trim().toLowerCase();
    const rows = document.querySelectorAll('.familia-row');
    const btnLimpiar = document.getElementById('btn-limpiar');
    const sinResultados = document.getElementById('sin-resultados');
    const contador = document.getElementById('contador-resultados');

    btnLimpiar.classList.toggle('hidden', q === '');

    let visibles = 0;
    rows.forEach(row => {
        const texto = row.getAttribute('data-search') || '';
        const coincide = texto.includes(q);
        row.classList.toggle('hidden', !coincide);
        if (coincide) visibles++;
    });

    contador.textContent = visibles;

    if (sinResultados) {
        sinResultados.classList.toggle('hidden', visibles > 0 || q === '');
    }
}

function limpiarBusqueda() {
    const input = document.getElementById('buscador-estudiante');
    input.value = '';
    filtrarFamilias('');
    input.focus();
}
</script>

</body>
</html>

