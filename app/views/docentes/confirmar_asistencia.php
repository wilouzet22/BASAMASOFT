<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
$rol = 'docentes'; // used for API routes
?>

<!-- Mobile Header -->
<header class="md:hidden flex justify-between items-center p-4 bg-white border-b border-outline-variant sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleDocentesMobileSidebar()" class="p-1 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <img src="<?php echo URLROOT; ?>/assets/img/logo.png" class="h-9 w-9 rounded-full" alt="Logo">
        <span class="font-bold text-primary text-lg">EduSaft</span>
    </div>
    <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
</header>

<div class="flex">
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <main id="mainContent" class="flex-1 min-h-screen bg-surface-container-lowest flex flex-col">
        <!-- Top Bar -->
        <header class="hidden md:flex items-center justify-between px-10 py-6 sticky top-0 bg-white/80 backdrop-blur-md z-30 border-b border-outline-variant/30">
            <div class="flex items-center gap-4">
                <button id="desktop-menu-toggle" class="material-symbols-outlined text-primary hover:bg-surface-container-low transition-colors p-2 rounded-full active:scale-95">menu</button>
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Confirmar Asistencia</h2>
                    <p class="text-sm text-on-surface-variant">
                        Prof. <span class="text-primary font-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php require APPROOT . '/views/inc/theme_toggle.php'; ?>
                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant">
                    <div class="text-right">
                        <p class="text-sm font-bold text-on-surface"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Docente'); ?></p>
                        <p class="text-[10px] text-outline uppercase font-bold tracking-tighter">Portal Docente</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/auth/logout" onclick="event.preventDefault(); openLogoutModal();" class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center overflow-hidden hover:bg-primary/20 transition-all cursor-pointer shadow-sm" title="Cerrar sesión">
                        <span class="material-symbols-outlined text-primary">school</span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 p-6 md:p-10 max-w-4xl mx-auto w-full flex flex-col gap-6">

            <div class="flex flex-wrap justify-between items-center gap-4">
                <div>
                    <h2 class="text-headline-lg text-primary font-headline-lg">Control de Asistencia QR</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Selecciona la actividad para generar el código QR. Los padres lo escanean desde su celular.</p>
                </div>
            </div>

            <!-- Activity Selector -->
            <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Seleccionar Actividad
                </h3>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select id="actividadSelect" class="flex-1 px-4 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        <option value="">-- Selecciona una actividad --</option>
                        <?php foreach ($data['actividades'] as $act): ?>
                            <option value="<?php echo $act->id_actividad; ?>">
                                <?php echo htmlspecialchars($act->nombre_actividad); ?> — <?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?>
                                (<?php echo htmlspecialchars($act->nombre_sede ?? ''); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button id="generarQrBtn" onclick="generarQr()" disabled
                        class="px-6 py-3 rounded-xl bg-primary text-on-primary font-bold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined">qr_code_2</span> Generar QR
                    </button>
                </div>
                <p class="text-xs text-on-surface-variant mt-2">El QR se renueva automáticamente cada 5 minutos para evitar capturas compartidas.</p>
            </div>

            <!-- QR Display Panel (hidden until generated) -->
            <div id="qrPanel" class="hidden bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <div class="flex flex-col md:flex-row gap-8 items-start">

                    <!-- QR Code -->
                    <div class="flex flex-col items-center gap-4 w-full md:w-auto">
                        <div id="qrWrapper" class="p-4 bg-white rounded-2xl border-2 border-primary/20 shadow-inner inline-block">
                            <div id="qrcode"></div>
                        </div>

                        <!-- Countdown to renewal -->
                        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl text-sm">
                            <span class="material-symbols-outlined text-amber-600 text-base">schedule</span>
                            <span class="text-amber-800 font-medium">Se renueva en: <strong id="countdown">5:00</strong></span>
                        </div>

                        <button onclick="forzarRenovacion()" class="text-xs text-primary underline hover:no-underline">
                            Renovar ahora
                        </button>
                    </div>

                    <!-- Info Panel -->
                    <div class="flex-1">
                        <h3 class="font-bold text-on-surface text-lg mb-1" id="qrActividadNombre">—</h3>
                        <p class="text-xs text-on-surface-variant mb-5" id="qrExpiraEn">—</p>

                        <!-- Live counter -->
                        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 mb-5">
                            <p class="text-xs text-on-surface-variant mb-1 uppercase font-bold tracking-wide">Familias confirmadas</p>
                            <p class="text-5xl font-extrabold text-primary" id="familiaCounter">0</p>
                            <p class="text-xs text-on-surface-variant mt-1">Se actualiza cada 5 segundos</p>
                        </div>

                        <div class="bg-surface-container rounded-xl p-4 text-sm space-y-2 text-on-surface-variant">
                            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-base text-primary">smartphone</span> Los padres escanean este QR con la cámara de su celular.</p>
                            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-base text-tertiary">lock_clock</span> El QR cambia cada 5 min para mayor seguridad.</p>
                            <p class="flex items-center gap-2"><span class="material-symbols-outlined text-base text-secondary">groups</span> Registra automáticamente a todos los hijos de cada familia.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scan History (confirmed families) -->
            <div id="historialPanel" class="hidden bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">group_add</span>
                    Familias Confirmadas
                </h3>
                <div id="historialLista">
                    <p class="text-center text-on-surface-variant py-4 text-sm">Ninguna familia ha confirmado aún.</p>
                </div>
            </div>

        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>
</div>

<!-- QRCode.js -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
const URLROOT = '<?php echo URLROOT; ?>';
let currentActividadId = null;
let currentToken       = null;
let qrInstance         = null;
let countdownSeconds   = 300; // 5 min
let countdownTimer     = null;
let pollingTimer       = null;

// Enable button when activity selected
document.getElementById('actividadSelect').addEventListener('change', function() {
    document.getElementById('generarQrBtn').disabled = !this.value;
});

async function generarQr(forzar = false) {
    const select = document.getElementById('actividadSelect');
    const id = select.value;
    if (!id) return;

    currentActividadId = id;

    const btn = document.getElementById('generarQrBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Generando...';

    try {
        const res = await fetch(`${URLROOT}/docentes/generar_qr_actividad/${id}`);
        const data = await res.json();

        if (!data.success) {
            alert('Error: ' + data.message);
            return;
        }

        currentToken = data.token;
        renderQR(data.url, data);

        if (data.renovado || forzar) {
            // reset countdown to 5 min
            countdownSeconds = 300;
        }

        startCountdown();
        startPolling();

        document.getElementById('qrPanel').classList.remove('hidden');
        document.getElementById('historialPanel').classList.remove('hidden');

    } catch (e) {
        alert('Error de conexión al generar QR');
        console.error(e);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined">qr_code_2</span> Generar QR';
    }
}

function renderQR(url, data) {
    const container = document.getElementById('qrcode');
    container.innerHTML = '';

    if (qrInstance) {
        qrInstance = null;
    }

    qrInstance = new QRCode(container, {
        text: url,
        width: 320,
        height: 320,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.Q
    });

    document.getElementById('qrActividadNombre').textContent =
        document.getElementById('actividadSelect').options[document.getElementById('actividadSelect').selectedIndex].text;
    document.getElementById('qrExpiraEn').textContent =
        'Expira: ' + new Date(data.expira_en).toLocaleTimeString('es-ES', {hour:'2-digit', minute:'2-digit'});
}

function startCountdown() {
    if (countdownTimer) clearInterval(countdownTimer);

    countdownTimer = setInterval(() => {
        countdownSeconds--;
        const m = Math.floor(countdownSeconds / 60);
        const s = countdownSeconds % 60;
        document.getElementById('countdown').textContent = `${m}:${s.toString().padStart(2,'0')}`;

        if (countdownSeconds <= 0) {
            clearInterval(countdownTimer);
            // Auto-renew
            generarQr(true);
        }
    }, 1000);
}

function forzarRenovacion() {
    if (countdownTimer) clearInterval(countdownTimer);
    countdownSeconds = 0;
    generarQr(true);
}

async function startPolling() {
    if (pollingTimer) clearInterval(pollingTimer);

    await fetchCounter(); // immediate first fetch
    pollingTimer = setInterval(fetchCounter, 5000);
}

async function fetchCounter() {
    if (!currentActividadId) return;
    try {
        const res = await fetch(`${URLROOT}/docentes/qr_actividad_status/${currentActividadId}`);
        const data = await res.json();
        document.getElementById('familiaCounter').textContent = data.count ?? 0;
    } catch(e) {}
}
</script>

<?php require APPROOT . '/views/inc/footer_scripts.php' ?>
</body>
</html>