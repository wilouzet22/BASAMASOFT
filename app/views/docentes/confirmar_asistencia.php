<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';
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
    <!-- Sidebar reusable docentes -->
    <?php require APPROOT . '/views/docentes/sidebar.php'; ?>

    <!-- Main Content Area -->
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
                    <h2 class="text-headline-lg text-primary font-headline-lg">Escáner de Código QR</h2>
                    <p class="text-on-surface-variant text-sm mt-1">Escanea el código QR del estudiante para confirmar su asistencia</p>
                </div>
            </div>

            <!-- Activity Selector -->
            <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event</span>
                    Seleccionar Actividad
                </h3>
                <select id="actividadSelect" class="w-full md:w-1/2 px-4 py-3 rounded-xl border border-outline-variant bg-surface text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    <option value="">-- Selecciona una actividad --</option>
                    <?php foreach ($data['actividades'] as $act): ?>
                        <option value="<?php echo $act->id_actividad; ?>" data-requiere-hijo="<?php echo $act->requiere_asistencia_por_hijo ?? 1; ?>">
                            <?php echo htmlspecialchars($act->nombre_actividad); ?> - <?php echo date('d/m/Y', strtotime($act->fecha_hora_inicio)); ?> (<?php echo htmlspecialchars($act->nombre_sede); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-on-surface-variant mt-2">Selecciona una actividad para habilitar el escáner</p>
            </div>

            <!-- Scanner Area -->
            <div id="scannerContainer" class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm hidden">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <!-- Camera View -->
                    <div class="flex-1 min-w-0">
                        <div class="aspect-video bg-slate-900 rounded-xl overflow-hidden relative border-2 border-primary/20">
                            <div id="qr-reader" style="width: 100%; height: 100%;"></div>
                            <!-- Overlay frame -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-3/4 h-3/4 border-2 border-primary/50 rounded-lg relative">
                                    <div class="absolute -top-3 -left-3 w-6 h-6 border-t-4 border-l-4 border-primary rounded-tl-lg"></div>
                                    <div class="absolute -top-3 -right-3 w-6 h-6 border-t-4 border-r-4 border-primary rounded-tr-lg"></div>
                                    <div class="absolute -bottom-3 -left-3 w-6 h-6 border-b-4 border-l-4 border-primary rounded-bl-lg"></div>
                                    <div class="absolute -bottom-3 -right-3 w-6 h-6 border-b-4 border-r-4 border-primary rounded-br-lg"></div>
                                </div>
                            </div>
                            <div id="scannerStatus" class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/70 text-white px-4 py-2 rounded-lg text-sm hidden">
                                Apuntando a código QR...
                            </div>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-3 text-center">Apunta la cámara al código QR del estudiante</p>
                    </div>

                    <!-- Info Panel -->
                    <div class="w-full md:w-80 flex-shrink-0">
                        <div class="bg-surface-container/30 border border-outline-variant rounded-xl p-4">
                            <h4 class="font-bold text-on-surface mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">info</span>
                                Información del Escaneo
                            </h4>
                            <div class="space-y-3" id="scanInfo">
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-on-surface-variant">Actividad Seleccionada</label>
                                    <span id="scanActividad" class="font-medium text-on-surface">--</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-on-surface-variant">Último Escaneo</label>
                                    <span id="scanTime" class="font-medium text-on-surface">--</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-on-surface-variant">Estudiante Detectado</label>
                                    <span id="scanEstudiante" class="font-medium text-primary">--</span>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-xs text-on-surface-variant">Estado</label>
                                    <span id="scanStatus" class="font-medium text-on-surface-variant">Selecciona una actividad</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 space-y-2">
                            <button id="startCameraBtn" class="w-full px-4 py-2.5 rounded-xl bg-primary text-on-primary font-semibold hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">videocam</span>
                                <span>Activar Cámara</span>
                            </button>
                            <button id="toggleTorchBtn" class="w-full px-4 py-2.5 rounded-xl bg-tertiary/10 text-tertiary font-semibold hover:bg-tertiary/20 transition-colors flex items-center justify-center gap-2" disabled>
                                <span class="material-symbols-outlined">flash_on</span>
                                <span>Activar Linterna</span>
                            </button>
                            <button id="switchCameraBtn" class="w-full px-4 py-2.5 rounded-xl bg-secondary/10 text-secondary font-semibold hover:bg-secondary/20 transition-colors flex items-center justify-center gap-2" disabled>
                                <span class="material-symbols-outlined">flip_camera_android</span>
                                <span>Cambiar Cámara</span>
                            </button>
                            <button id="stopCameraBtn" class="w-full px-4 py-2.5 rounded-xl bg-error/10 text-error font-semibold hover:bg-error/20 transition-colors flex items-center justify-center gap-2 hidden">
                                <span class="material-symbols-outlined">videocam_off</span>
                                <span>Detener Cámara</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scan History -->
            <div class="bg-white rounded-3xl border border-outline-variant p-6 shadow-sm">
                <h3 class="font-bold text-on-surface text-lg mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Historial de Escaneos
                </h3>
                <div id="scanHistory" class="max-h-64 overflow-y-auto space-y-2">
                    <p class="text-center text-on-surface-variant py-8">No hay escaneos aún. Selecciona una actividad y escanea un QR.</p>
                </div>
            </div>
        </div>
        <?php require APPROOT . '/views/inc/footer.php'; ?>
    </div>
</div>

<script>
// Camera Controller Module - Native getUserMedia
const CameraController = (function() {
    let stream = null;
    let videoElement = null;
    let currentFacingMode = 'environment';
    let torchEnabled = false;

    function isSupported() {
        return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }

    function isSecureContext() {
        return window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    }

    async function start(videoEl, options = {}) {
        if (!isSupported()) {
            throw new Error('API de cámara no soportada en este navegador');
        }
        if (!isSecureContext()) {
            throw new Error('Requiere HTTPS o localhost para acceder a la cámara');
        }

        stop();

        videoElement = videoEl;
        currentFacingMode = options.facingMode || 'environment';

        const constraints = {
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 1280, max: 1920 },
                height: { ideal: 720, max: 1080 }
            },
            audio: false
        };

        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
            videoElement.srcObject = stream;
            videoElement.muted = true;
            videoElement.playsInline = true;
            await videoElement.play();
            return stream;
        } catch (err) {
            let msg = 'Error al acceder a la cámara: ';
            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                msg = 'Permiso de cámara denegado. Permita el acceso en la configuración del navegador.';
            } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                msg = 'No se encontró ninguna cámara.';
            } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                msg = 'La cámara está en uso por otra aplicación.';
            } else if (err.name === 'OverconstrainedError') {
                msg = 'La cámara no soporta la resolución solicitada.';
            } else {
                msg += err.message;
            }
            throw new Error(msg);
        }
    }

    async function stop() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        if (videoElement) {
            videoElement.srcObject = null;
        }
        torchEnabled = false;
    }

    async function switchCamera() {
        if (!stream || !videoElement) return false;
        currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
        try {
            await start(videoElement, { facingMode: currentFacingMode });
            return true;
        } catch (err) {
            currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
            throw err;
        }
    }

    async function toggleTorch() {
        if (!stream) return false;
        const track = stream.getVideoTracks()[0];
        if (!track || !track.getCapabilities().torch) return false;
        try {
            torchEnabled = !torchEnabled;
            await track.applyConstraints({ advanced: [{ torch: torchEnabled }] });
            return torchEnabled;
        } catch (err) {
            torchEnabled = false;
            throw err;
        }
    }

    function getStream() {
        return stream;
    }

    function isActive() {
        return stream !== null && stream.active;
    }

    function getFacingMode() {
        return currentFacingMode;
    }

    function isTorchEnabled() {
        return torchEnabled;
    }

    return {
        isSupported,
        isSecureContext,
        start,
        stop,
        switchCamera,
        toggleTorch,
        getStream,
        isActive,
        getFacingMode,
        isTorchEnabled
    };
})();

// Camera Permission Modal
function showCameraPermissionModal() {
    return new Promise((resolve) => {
        const existing = document.getElementById('cameraPermissionModal');
        if (existing) existing.remove();

        const modal = document.createElement('div');
        modal.id = 'cameraPermissionModal';
        modal.className = 'fixed inset-0 z-[200] flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="hideCameraPermissionModal(false)"></div>
            <div class="relative bg-white rounded-3xl shadow-2xl border border-outline-variant/50 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="cameraModalContent">
                <div class="flex flex-col items-center text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-primary text-4xl">videocam</span>
                    </div>
                    <h3 class="text-xl font-extrabold text-on-surface mb-2">Permiso de Cámara Requerido</h3>
                    <p class="text-sm text-on-surface-variant mb-6 leading-relaxed px-4">
                        Para escanear códigos QR y confirmar asistencia, necesitamos acceso a tu cámara.
                        <br><br>
                        Al hacer clic en "Permitir", el navegador te pedirá autorización.
                    </p>
                    <div class="flex gap-3 w-full max-w-xs">
                        <button onclick="hideCameraPermissionModal(false)" 
                                class="flex-1 px-4 py-3 rounded-xl font-bold text-sm text-on-surface hover:bg-surface-container transition-colors border border-outline-variant">
                            Cancelar
                        </button>
                        <button onclick="hideCameraPermissionModal(true)" 
                                class="flex-1 px-4 py-3 rounded-xl font-bold text-sm bg-primary text-on-primary hover:bg-primary/90 transition-all shadow-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">videocam</span> Permitir
                        </button>
                    </div>
                    <p class="text-xs text-on-surface-variant/70 mt-4 px-4">
                        Puedes revocar este permiso en cualquier momento desde la configuración del navegador.
                    </p>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        requestAnimationFrame(() => {
            modal.querySelector('.absolute.inset-0').classList.add('opacity-100');
            const content = document.getElementById('cameraModalContent');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });

        window._cameraPermissionResolve = resolve;
    });
}

window.hideCameraPermissionModal = function(granted) {
    const modal = document.getElementById('cameraPermissionModal');
    if (!modal) return;

    const backdrop = modal.querySelector('.absolute.inset-0');
    const content = document.getElementById('cameraModalContent');

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.remove();
        if (window._cameraPermissionResolve) {
            window._cameraPermissionResolve(granted);
            window._cameraPermissionResolve = null;
        }
    }, 300);
};

// ZXing QR Scanner variables
let codeReader = null;
let videoInputDevices = [];
let currentDeviceId = null;
let lastScanTime = 0;
let isUsingNativeCamera = false;
let scanHistory = [];

function renderScanHistory() {
    const container = document.getElementById('scanHistory');
    if (scanHistory.length === 0) {
        container.innerHTML = '<p class="text-center text-on-surface-variant py-8">No hay escaneos aún. Selecciona una actividad y escanea un QR.</p>';
        return;
    }
    
    container.innerHTML = scanHistory.map((scan, index) => `
        <div class="flex items-center justify-between p-3 bg-surface-container/30 border border-outline-variant rounded-lg hover:bg-surface-container/50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                    <span class="material-symbols-outlined">qr_code</span>
                </div>
                <div>
                    <p class="font-medium text-on-surface text-sm">${scan.estudiante}</p>
                    <p class="text-xs text-on-surface-variant">${scan.actividad} · ${scan.fecha}</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold ${scan.exito ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${scan.exito ? 'Confirmado' : 'Error'}
            </span>
        </div>
    `).join('');
}

function addToHistory(estudiante, actividad, exito) {
    scanHistory.unshift({
        estudiante,
        actividad,
        fecha: new Date().toLocaleString('es-ES'),
        exito
    });
    saveScanHistory();
    renderScanHistory();
}

function saveScanHistory() {
    localStorage.setItem('qrScanHistory', JSON.stringify(scanHistory.slice(0, 50)));
}

function loadScanHistory() {
    const saved = localStorage.getItem('qrScanHistory');
    if (saved) {
        try {
            scanHistory = JSON.parse(saved);
            renderScanHistory();
        } catch (e) {
            scanHistory = [];
        }
    }
}

// Initialize ZXing scanner with native camera stream
async function initScanner() {
    const isSecureContext = window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    if (!isSecureContext) {
        showSecureContextError();
        return;
    }

    if (codeReader) {
        try {
            await codeReader.reset();
        } catch (e) {}
        codeReader = null;
    }

    if (CameraController.isActive()) {
        CameraController.stop();
        isUsingNativeCamera = false;
    }

    codeReader = new ZXing.BrowserQRCodeReader();

    try {
        videoInputDevices = await codeReader.listVideoInputDevices();
        if (videoInputDevices.length > 0) {
            currentDeviceId = videoInputDevices[0].deviceId;
            document.getElementById('switchCameraBtn').disabled = false;
            document.getElementById('toggleTorchBtn').disabled = false;
            document.getElementById('startCameraBtn').disabled = false;
        } else {
            throw new Error('No se encontraron cámaras');
        }

        const readerElement = document.getElementById('qr-reader');
        readerElement.innerHTML = '';
        
        const video = document.createElement('video');
        video.id = 'qr-video';
        video.style.width = '100%';
        video.style.height = '100%';
        video.style.objectFit = 'cover';
        video.muted = true;
        video.playsInline = true;
        readerElement.appendChild(video);

        document.getElementById('scannerStatus').classList.remove('hidden');
        const currentDevice = videoInputDevices.find(d => d.deviceId === currentDeviceId);
        document.getElementById('scanStatus').textContent = `Cámara lista - ${currentDevice?.label || 'Cámara ' + (videoInputDevices.indexOf(videoInputDevices.find(d => d.deviceId === currentDeviceId)) + 1)}`;
        document.getElementById('scanStatus').className = 'font-medium text-blue-600';
        
        document.getElementById('startCameraBtn').classList.remove('hidden');
        document.getElementById('stopCameraBtn').classList.add('hidden');
        
    } catch (err) {
        console.error('Error initializing scanner:', err);
        showScannerError(err);
    }
}

function showSecureContextError() {
    const reader = document.getElementById('qr-reader');
    reader.innerHTML = `
        <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center text-white">
            <span class="material-symbols-outlined text-6xl mb-3">security</span>
            <p class="text-lg font-medium mb-2">Acceso a cámara no disponible</p>
            <p class="text-sm opacity-75 mb-4">El escáner QR requiere HTTPS o localhost para funcionar.</p>
            <p class="text-xs opacity-50">URL actual: ${location.protocol}//${location.host}</p>
        </div>
    `;
    document.getElementById('scanStatus').textContent = 'Requiere HTTPS o localhost';
    document.getElementById('scanStatus').className = 'font-medium text-red-600';
}

function showScannerError(err) {
    let errorMsg = 'Error al iniciar cámara: ';
    if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
        errorMsg = 'Permiso de cámara denegado. Permita el acceso en la configuración del navegador.';
    } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError' || err.message.includes('No se encontraron')) {
        errorMsg = 'No se encontró ninguna cámara. Conecte una cámara e intente de nuevo.';
    } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
        errorMsg = 'La cámara está en uso por otra aplicación.';
    } else if (err.name === 'OverconstrainedError') {
        errorMsg = 'La cámara no soporta la resolución solicitada.';
    } else {
        errorMsg += err.message;
    }
    document.getElementById('scanStatus').textContent = errorMsg;
    document.getElementById('scanStatus').className = 'font-medium text-red-600';
    
    const reader = document.getElementById('qr-reader');
    reader.innerHTML = `
        <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center text-white">
            <span class="material-symbols-outlined text-6xl mb-3">videocam_off</span>
            <p class="text-lg font-medium mb-2">${errorMsg}</p>
            <p class="text-sm opacity-75">Requiere HTTPS o localhost para acceder a la cámara</p>
            <button onclick="initScanner()" class="mt-4 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                Reintentar
            </button>
        </div>
    `;
}

async function startCameraAndScan() {
    const startBtn = document.getElementById('startCameraBtn');
    const stopBtn = document.getElementById('stopCameraBtn');
    const torchBtn = document.getElementById('toggleTorchBtn');
    const switchBtn = document.getElementById('switchCameraBtn');
    
    if (!CameraController.isSupported()) {
        alert('Tu navegador no soporta acceso a la cámara (getUserMedia).');
        return;
    }

    if (!CameraController.isSecureContext()) {
        alert('Requiere HTTPS o localhost para acceder a la cámara.');
        return;
    }

    const granted = await showCameraPermissionModal();
    if (!granted) {
        document.getElementById('scanStatus').textContent = 'Permiso de cámara denegado';
        document.getElementById('scanStatus').className = 'font-medium text-red-600';
        return;
    }

    try {
        startBtn.disabled = true;
        startBtn.innerHTML = `<span class="material-symbols-outlined animate-spin">sync</span><span>Iniciando...</span>`;

        const readerElement = document.getElementById('qr-reader');
        let video = document.getElementById('qr-video');
        
        if (!video) {
            readerElement.innerHTML = '';
            video = document.createElement('video');
            video.id = 'qr-video';
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            video.muted = true;
            video.playsInline = true;
            readerElement.appendChild(video);
        }
        
        if (!codeReader) {
            codeReader = new ZXing.BrowserQRCodeReader();
        }

        await CameraController.start(video, { facingMode: 'environment' });
        isUsingNativeCamera = true;

        await codeReader.decodeFromVideoDevice(currentDeviceId, video, (result, err) => {
            if (result) {
                onScanSuccess(result.getText());
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.warn('Scan error:', err);
            }
        });

        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');
        torchBtn.disabled = false;
        switchBtn.disabled = false;
        document.getElementById('scanStatus').textContent = 'Escáner activo - Apunta al código QR';
        document.getElementById('scanStatus').className = 'font-medium text-green-600';

    } catch (err) {
        console.error('Error starting camera:', err);
        startBtn.disabled = false;
        startBtn.innerHTML = `<span class="material-symbols-outlined">videocam</span><span>Activar Cámara</span>`;
        showScannerError(err);
    }
}

async function stopCameraAndScan() {
    const startBtn = document.getElementById('startCameraBtn');
    const stopBtn = document.getElementById('stopCameraBtn');
    const torchBtn = document.getElementById('toggleTorchBtn');
    const switchBtn = document.getElementById('switchCameraBtn');
    
    try {
        if (codeReader) {
            try {
                await codeReader.reset();
            } catch (e) {}
        }

        CameraController.stop();
        isUsingNativeCamera = false;

        startBtn.disabled = false;
        startBtn.innerHTML = `<span class="material-symbols-outlined">videocam</span><span>Activar Cámara</span>`;
        startBtn.classList.remove('hidden');
        stopBtn.classList.add('hidden');
        torchBtn.disabled = true;
        switchBtn.disabled = true;
        document.getElementById('scanStatus').textContent = 'Cámara detenida';
        document.getElementById('scanStatus').className = 'font-medium text-on-surface-variant';

    } catch (err) {
        console.error('Error stopping camera:', err);
    }
}

async function switchCamera() {
    const switchBtn = document.getElementById('switchCameraBtn');
    const video = document.getElementById('qr-video');
    
    if (!CameraController.isActive()) return;
    
    try {
        switchBtn.disabled = true;
        switchBtn.innerHTML = `<span class="material-symbols-outlined animate-spin">sync</span><span>Cambiando...</span>`;
        
        await CameraController.switchCamera();
        
        await codeReader.reset();
        await codeReader.decodeFromVideoDevice(currentDeviceId, video, (result, err) => {
            if (result) {
                onScanSuccess(result.getText());
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.warn('Scan error:', err);
            }
        });
        
        const currentDevice = videoInputDevices.find(d => d.deviceId === currentDeviceId);
        document.getElementById('scanStatus').textContent = `Escáner activo - ${currentDevice?.label || 'Cámara'}`;
        
    } catch (err) {
        console.error('Error switching camera:', err);
        showScannerError(err);
    } finally {
        switchBtn.disabled = false;
        switchBtn.innerHTML = `<span class="material-symbols-outlined">flip_camera_android</span><span>Cambiar Cámara</span>`;
    }
}

async function toggleTorch() {
    const torchBtn = document.getElementById('toggleTorchBtn');
    
    try {
        const enabled = await CameraController.toggleTorch();
        torchBtn.innerHTML = `<span class="material-symbols-outlined">${enabled ? 'flash_off' : 'flash_on'}</span><span>${enabled ? 'Apagar Linterna' : 'Activar Linterna'}</span>`;
    } catch (err) {
        console.error('Torch error:', err);
    }
}

function onScanSuccess(decodedText) {
    if (Date.now() - lastScanTime < 2000) return;
    lastScanTime = Date.now();

    let estudianteId = decodedText;
    let scanActividadId = currentActividadId;
    
    if (decodedText.includes('|')) {
        const parts = decodedText.split('|');
        estudianteId = parts[0];
        scanActividadId = parts[1] || currentActividadId;
    }

    document.getElementById('scanEstudiante').textContent = `ID: ${estudianteId}`;
    document.getElementById('scanTime').textContent = new Date().toLocaleTimeString('es-ES');
    document.getElementById('scanStatus').textContent = 'Procesando...';
    document.getElementById('scanStatus').className = 'font-medium text-yellow-600';

    fetch('<?php echo URLROOT; ?>/docentes/procesar_qr_asistencia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            estudiante_id: estudianteId,
            actividad_id: scanActividadId,
            requiere_hijo: currentRequiereHijo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('scanEstudiante').textContent = data.estudiante_nombre || `ID: ${estudianteId}`;
            document.getElementById('scanStatus').textContent = '✓ Asistencia confirmada';
            document.getElementById('scanStatus').className = 'font-medium text-green-600';
            addToHistory(data.estudiante_nombre || `ID: ${estudianteId}`, data.actividad_nombre, true);
            
            const reader = document.getElementById('qr-reader');
            reader.style.borderColor = '#22c55e';
            setTimeout(() => reader.style.borderColor = '', 1000);
        } else {
            document.getElementById('scanStatus').textContent = '✗ ' + (data.message || 'Error al confirmar');
            document.getElementById('scanStatus').className = 'font-medium text-red-600';
            addToHistory(`ID: ${estudianteId}`, 'Desconocida', false);
            
            const reader = document.getElementById('qr-reader');
            reader.style.borderColor = '#ef4444';
            setTimeout(() => reader.style.borderColor = '', 1000);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        document.getElementById('scanStatus').textContent = '✗ Error de conexión';
        document.getElementById('scanStatus').className = 'font-medium text-red-600';
        addToHistory(`ID: ${estudianteId}`, 'Desconocida', false);
    });
}

function onScanFailure(error) {
    // Silently ignore scan failures
}

document.addEventListener('DOMContentLoaded', () => {
    loadScanHistory();
    
    const actividadSelect = document.getElementById('actividadSelect');
    const scannerContainer = document.getElementById('scannerContainer');
    
    actividadSelect.addEventListener('change', async (e) => {
        currentActividadId = e.target.value;
        
        if (!currentActividadId) {
            scannerContainer.classList.add('hidden');
            document.getElementById('scanActividad').textContent = '--';
            document.getElementById('scanEstudiante').textContent = '--';
            document.getElementById('scanTime').textContent = '--';
            document.getElementById('scanStatus').textContent = 'Selecciona una actividad';
            document.getElementById('scanStatus').className = 'font-medium text-on-surface-variant';
            return;
        }
        
        const selectedOption = e.target.options[e.target.selectedIndex];
        currentRequiereHijo = parseInt(selectedOption.dataset.requiereHijo) || 1;
        document.getElementById('scanActividad').textContent = selectedOption.text;
        
        scannerContainer.classList.remove('hidden');
        await initScanner();
    });

    document.getElementById('startCameraBtn').addEventListener('click', startCameraAndScan);
    document.getElementById('stopCameraBtn').addEventListener('click', stopCameraAndScan);
    document.getElementById('switchCameraBtn').addEventListener('click', switchCamera);
    document.getElementById('toggleTorchBtn').addEventListener('click', toggleTorch);

    window.addEventListener('beforeunload', async () => {
        if (codeReader) {
            try { await codeReader.reset(); } catch (e) {}
        }
        CameraController.stop();
    });
});
</script>

</body>
</html>