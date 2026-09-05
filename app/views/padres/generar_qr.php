<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
</header>

<div class="flex">
    <!-- Sidebar reusable padres -->
    <?php require APPROOT . '/views/padres/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Generar QR de Asistencia</h2>
                    <p class="text-sm text-on-surface-variant">
                        <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['username'] ?? 'Familia'); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['nombre'] ?? $_SESSION['username'] ?? 'Familia'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Portal de Padres</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">logout</span>
                    </a>
                </div>
            </div>
        </header>
        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 max-w-3xl mx-auto w-full flex flex-col gap-6">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h2 class="text-headline-lg text-primary font-headline-lg">Generar Código QR</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Selecciona una actividad y tu QR familiar se generará automáticamente.</p>
                </div>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'no_activities'): ?>
            <div class="bg-error-container text-on-error-container px-4 py-3 rounded-xl shadow-sm text-sm font-semibold flex items-center gap-2 animate-fade-in mb-6">
                <span class="material-symbols-outlined">warning</span> No tienes actividades próximas disponibles para generar QR.
            </div>
            <?php endif; ?>

            <?php if (empty($data['actividades'])): ?>
            <div class="bg-surface border border-outline-variant rounded-xl p-12 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl block mb-3">event_busy</span>
                <p class="text-lg font-medium mb-2">No hay actividades próximas o en progreso</p>
                <p class="text-sm">Las actividades programadas aparecerán aquí cuando estén disponibles.</p>
            </div>
            <?php else: ?>

            <!-- Seleccionar Actividad -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Selecciona la actividad
                </h3>
                <select id="actividadSelect" class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    <option value="">-- Selecciona una actividad --</option>
                    <?php foreach ($data['actividades'] as $act): ?>
                        <option value="<?php echo $act->id_actividad; ?>"
                            data-nombre="<?php echo htmlspecialchars($act->nombre_actividad); ?>"
                            data-fecha="<?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?>"
                            data-hora="<?php echo date('H:i', strtotime($act->fecha_hora_inicio)); ?>"
                            data-sede="<?php echo htmlspecialchars($act->nombre_sede); ?>">
                            <?php echo htmlspecialchars($act->nombre_actividad); ?> — <?php echo date('d/m/Y H:i', strtotime($act->fecha_hora_inicio)); ?> (<?php echo htmlspecialchars($act->nombre_sede); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-on-surface-variant mt-2">Se muestra hasta 5 actividades próximas o en progreso</p>
            </div>

            <!-- Preview actividad + spinner (oculto por defecto) -->
            <div id="generandoSection" class="hidden bg-primary/10 border border-primary/30 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/20 rounded-xl flex items-center justify-center text-primary animate-pulse">
                        <span class="material-symbols-outlined text-2xl">qr_code</span>
                    </div>
                    <div>
                        <p class="font-bold text-on-surface" id="previewNombre">—</p>
                        <p class="text-sm text-on-surface-variant"><span id="previewFecha">—</span> · <span id="previewSede">—</span></p>
                        <p class="text-xs text-primary mt-1 font-semibold" id="previewStatus">Generando QR familiar...</p>
                    </div>
                </div>
            </div>

            <!-- Info box -->
            <div class="bg-tertiary/10 border border-tertiary/30 rounded-xl p-4">
                <h4 class="font-bold text-tertiary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span>
                    ¿Cómo funciona?
                </h4>
                <ol class="text-sm text-on-surface-variant space-y-1 list-decimal list-inside">
                    <li>Selecciona la <strong>actividad</strong> del menú de arriba.</li>
                    <li>Tu <strong>QR familiar</strong> se genera automáticamente.</li>
                    <li>El QR es <strong>único, de un solo uso</strong> y expira en <strong>24 horas</strong>.</li>
                    <li>Muéstralo al llegar; al escanearse se registra la asistencia de <strong>todos tus hijos</strong> automáticamente.</li>
                </ol>
            </div>
            <?php endif; ?>
        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const actividadSelect  = document.getElementById('actividadSelect');
    const generandoSection = document.getElementById('generandoSection');
    const previewNombre    = document.getElementById('previewNombre');
    const previewFecha     = document.getElementById('previewFecha');
    const previewSede      = document.getElementById('previewSede');
    const previewStatus    = document.getElementById('previewStatus');

    if (!actividadSelect) return;

    actividadSelect.addEventListener('change', async (e) => {
        const actividadId = e.target.value;

        if (!actividadId) {
            generandoSection.classList.add('hidden');
            return;
        }

        const opt = e.target.options[e.target.selectedIndex];
        previewNombre.textContent  = opt.dataset.nombre || opt.text;
        previewFecha.textContent   = (opt.dataset.fecha || '') + ' ' + (opt.dataset.hora || '');
        previewSede.textContent    = opt.dataset.sede || '';
        previewStatus.textContent  = 'Generando tu QR...';
        previewStatus.className    = 'text-xs text-primary mt-1 font-semibold animate-pulse';
        generandoSection.classList.remove('hidden');

        try {
            const res = await fetch('<?php echo URLROOT; ?>/padres/crear_qr', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ id_actividad: actividadId })
            });
            const data = await res.json();

            if (data.success) {
                previewStatus.textContent = '¡QR listo! Redirigiendo...';
                previewStatus.className   = 'text-xs text-green-600 mt-1 font-semibold';
                setTimeout(() => { window.location.href = data.qr_url; }, 600);
            } else {
                previewStatus.textContent = 'Error: ' + data.message;
                previewStatus.className   = 'text-xs text-red-600 mt-1 font-semibold';
            }
        } catch (err) {
            console.error(err);
            previewStatus.textContent = 'Error de conexión. Intenta de nuevo.';
            previewStatus.className   = 'text-xs text-red-600 mt-1 font-semibold';
        }
    });
});
</script>

</body>
</html>
