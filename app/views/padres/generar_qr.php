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
                    <p class="text-on-surface-variant text-sm mt-1">Crea un código QR único para confirmar la asistencia de tu hijo en una actividad próxima.</p>
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
                <p class="text-lg font-medium mb-2">No hay actividades próximas</p>
                <p class="text-sm">Las actividades programadas para los grupos de tus hijos aparecerán aquí cuando estén disponibles.</p>
            </div>
            <?php else: ?>
            
            <!-- Paso 1: Seleccionar Actividad -->
            <div class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Paso 1: Selecciona la actividad
                </h3>
                <select id="actividadSelect" class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    <option value="">-- Selecciona una actividad --</option>
                    <?php foreach ($data['actividades'] as $act): ?>
                        <option value="<?php echo $act->id_actividad; ?>" 
                            data-nombre="<?php echo htmlspecialchars($act->nombre_actividad); ?>"
                            data-fecha="<?php echo date('d/m/Y H:i', strtotime($act->fecha_hora_inicio)); ?>"
                            data-sede="<?php echo htmlspecialchars($act->nombre_sede); ?>"
                            data-grupos="<?php echo htmlspecialchars($act->grupos ?? ''); ?>">
                            <?php echo htmlspecialchars($act->nombre_actividad); ?> - <?php echo date('d/m/Y H:i', strtotime($act->fecha_hora_inicio)); ?> (<?php echo htmlspecialchars($act->nombre_sede); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-on-surface-variant mt-2">Elige la actividad para la que quieres generar el QR</p>
            </div>

            <!-- Paso 2: Seleccionar Estudiante (se muestra tras elegir actividad) -->
            <div id="estudianteSection" class="bg-surface border border-outline-variant rounded-xl p-6 shadow-sm hidden">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span>
                    Paso 2: Selecciona a tu hijo
                </h3>
                <select id="estudianteSelect" class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    <option value="">-- Selecciona a tu hijo --</option>
                    <?php foreach ($data['estudiantes'] as $est): ?>
                        <option value="<?php echo $est->id_estudiante; ?>"><?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-on-surface-variant mt-2">Elige cuál de tus hijos asistirá a la actividad</p>
            </div>

            <!-- Paso 3: Generar QR (se muestra tras elegir estudiante) -->
            <div id="generarSection" class="bg-primary/10 border border-primary/30 rounded-xl p-6 shadow-sm hidden">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">qr_code</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-on-surface text-lg">Paso 3: Genera tu QR</h3>
                            <p class="text-sm text-on-surface-variant">Actividad: <span id="qrActividadNombre" class="font-medium"></span></p>
                            <p class="text-sm text-on-surface-variant">Hijo: <span id="qrEstudianteNombre" class="font-medium"></span></p>
                        </div>
                    </div>
                    <button id="btnGenerarQR" class="px-6 py-3 rounded-lg bg-primary text-on-primary font-semibold hover:bg-primary/90 transition-colors flex items-center justify-center gap-2 md:w-auto md:ml-auto">
                        <span class="material-symbols-outlined">qr_code</span>
                        <span>Generar QR</span>
                    </button>
                </div>
                <p class="text-xs text-primary mt-3">El QR será único, de un solo uso y expirará en 24 horas.</p>
            </div>

            <!-- Info box -->
            <div class="bg-tertiary/10 border border-tertiary/30 rounded-xl p-4">
                <h4 class="font-bold text-tertiary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span>
                    ¿Cómo funciona?
                </h4>
                <ol class="text-sm text-on-surface-variant space-y-1 list-decimal list-inside">
                    <li>Selecciona la <strong>actividad</strong> (solo futuras/pendientes).</li>
                    <li>Selecciona a tu <strong>hijo</strong> que asistirá.</li>
                    <li>Presiona <strong>"Generar QR"</strong> - se crea un código único.</li>
                    <li>El QR es <strong>único, de un solo uso</strong> y expira en <strong>24 horas</strong>.</li>
                    <li>Muestra el QR al llegar a la actividad; al escanearse se registra tu asistencia <strong>automáticamente</strong>.</li>
                </ol>
            </div>
            <?php endif; ?>
        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const actividadSelect = document.getElementById('actividadSelect');
    const estudianteSection = document.getElementById('estudianteSection');
    const estudianteSelect = document.getElementById('estudianteSelect');
    const generarSection = document.getElementById('generarSection');
    const btnGenerarQR = document.getElementById('btnGenerarQR');
    const qrActividadNombre = document.getElementById('qrActividadNombre');
    const qrEstudianteNombre = document.getElementById('qrEstudianteNombre');

    // Paso 1: Actividad seleccionada
    actividadSelect.addEventListener('change', (e) => {
        const actividadId = e.target.value;
        
        if (actividadId) {
            // Mostrar sección de estudiante
            estudianteSection.classList.remove('hidden');
            generarSection.classList.add('hidden');
            
            // Actualizar nombre en resumen
            const selectedOption = e.target.options[e.target.selectedIndex];
            qrActividadNombre.textContent = selectedOption.dataset.nombre || selectedOption.text;
            qrEstudianteNombre.textContent = '—';
            
            // Reset estudiante
            estudianteSelect.value = '';
        } else {
            estudianteSection.classList.add('hidden');
            generarSection.classList.add('hidden');
        }
    });

    // Paso 2: Estudiante seleccionado
    estudianteSelect.addEventListener('change', (e) => {
        const estudianteId = e.target.value;
        
        if (estudianteId) {
            generarSection.classList.remove('hidden');
            const selectedOption = e.target.options[e.target.selectedIndex];
            qrEstudianteNombre.textContent = selectedOption.text;
        } else {
            generarSection.classList.add('hidden');
        }
    });

    // Paso 3: Generar QR
    btnGenerarQR.addEventListener('click', async () => {
        const actividadId = actividadSelect.value;
        const id_estudiante = estudianteSelect.value;
        
        if (!actividadId || !id_estudiante) return;

        btnGenerarQR.disabled = true;
        btnGenerarQR.innerHTML = `<span class="material-symbols-outlined animate-spin">sync</span><span>Generando...</span>`;

        try {
            const res = await fetch('<?php echo URLROOT; ?>/padres/crear_qr', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    id_actividad: actividadId,
                    id_estudiante: id_estudiante
                })
            });
            const data = await res.json();

            if (data.success) {
                // Redirigir a la vista del QR
                window.location.href = data.qr_url;
            } else {
                alert('Error: ' + data.message);
                btnGenerarQR.disabled = false;
                btnGenerarQR.innerHTML = `<span class="material-symbols-outlined">qr_code</span><span>Generar QR</span>`;
            }
        } catch (err) {
            console.error(err);
            alert('Error de conexión');
            btnGenerarQR.disabled = false;
            btnGenerarQR.innerHTML = `<span class="material-symbols-outlined">qr_code</span><span>Generar QR</span>`;
        }
    });
</script>

</body>
</html>