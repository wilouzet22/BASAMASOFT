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
                    <h2 class="text-xl font-bold text-on-surface">Tu Código QR</h2>
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
        <main class="flex-1 p-4 md:p-8 max-w-xl mx-auto w-full flex flex-col gap-6 items-center">
            <?php if (!empty($data['error'])): ?>
            <div class="w-full bg-error-container text-on-error-container px-6 py-4 rounded-2xl shadow-sm text-center text-sm font-semibold flex items-center justify-center gap-2 animate-fade-in">
                <span class="material-symbols-outlined">error</span> <?php echo htmlspecialchars($data['error']); ?>
            </div>
            <div class="w-full text-center mt-4">
                <a href="<?php echo URLROOT; ?>/padres/generar_qr" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-xl font-semibold hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span> Volver a generar QR
                </a>
            </div>
            <?php else: ?>
            
            <div class="w-full bg-surface border border-outline-variant rounded-2xl p-8 shadow-sm text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-bold mb-4">
                        <span class="material-symbols-outlined">qr_code</span>
                        Código de Asistencia
                    </div>
                    <h3 class="text-2xl font-bold text-on-surface mb-2"><?php echo htmlspecialchars($data['qr']->nombre_actividad); ?></h3>
                    <p class="text-on-surface-variant"><?php echo htmlspecialchars($data['qr']->nombre_sede); ?> · <?php echo date('d/m/Y H:i', strtotime($data['qr']->fecha_hora_inicio)); ?></p>
                </div>

                <div class="mb-6 p-4 bg-white rounded-xl border border-outline-variant inline-block">
                    <div id="qrcode"></div>
                </div>

                <div class="mb-6 text-sm text-on-surface-variant space-y-1">
                    <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($data['qr']->est_nombres . ' ' . $data['qr']->est_apellidos); ?></p>
                    <p><strong>Expira:</strong> <?php echo date('d/m/Y H:i', strtotime($data['qr']->expira_en)); ?></p>
                    <p class="text-tertiary"><strong>Válido por 24 horas · Un solo uso</strong></p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button onclick="window.print()" class="flex-1 px-4 py-3 rounded-xl bg-secondary/10 text-secondary font-semibold hover:bg-secondary/20 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">print</span>
                        <span>Imprimir QR</span>
                    </button>
                    <a href="<?php echo URLROOT; ?>/padres/generar_qr" class="flex-1 px-4 py-3 rounded-xl bg-primary text-on-primary font-semibold hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">qr_code</span>
                        <span>Generar otro QR</span>
                    </a>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="w-full bg-tertiary/10 border border-tertiary/30 rounded-xl p-4">
                <h4 class="font-bold text-tertiary mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">info</span>
                    Instrucciones
                </h4>
                <ol class="text-sm text-on-surface-variant space-y-2 list-decimal list-inside">
                    <li>Guarda una <strong>captura de pantalla</strong> o <strong>imprime</strong> este código.</li>
                    <li>Al llegar a la actividad, muestra este QR al profesor o administrador.</li>
                    <li>Ellos lo escanearán con el lector QR del sistema.</li>
                    <li>Tu asistencia se registrará <strong>automáticamente</strong> al escanear.</li>
                    <li>Este QR es <strong>de un solo uso</strong> y expira en 24 horas.</li>
                </ol>
            </div>
            <?php endif; ?>
        </main>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>
</div>

<script>
// Generar QR code usando la librería QRCode.js (incluida via CDN)
document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById('qrcode');
    if (!qrContainer) return;

    const url = '<?php echo $data["url_escanear"] ?? ""; ?>';
    if (!url) return;

    // Cargar QRCode.js dinámicamente si no existe
    if (typeof QRCode === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js';
        script.onload = () => generateQR();
        document.head.appendChild(script);
    } else {
        generateQR();
    }

    function generateQR() {
        new QRCode(qrContainer, {
            text: url,
            width: 256,
            height: 256,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>