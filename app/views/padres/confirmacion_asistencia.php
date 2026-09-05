<?php
$data = $data ?? [];
$bodyClass = 'bg-surface-container-lowest text-on-background font-lexend min-h-screen';
require APPROOT . '/views/inc/header.php';

$error     = $data['error']     ?? null;
$actividad = $data['actividad'] ?? '';
$sede      = $data['sede']      ?? '';
$fecha     = $data['fecha']     ?? '';
$yaEstaba  = $data['ya_estaba'] ?? false;
$registros = $data['registrados'] ?? 0;
$mensaje   = $data['mensaje']   ?? null;
?>

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl border border-outline-variant overflow-hidden">

        <?php if ($error): ?>
        <!-- ERROR STATE -->
        <div class="p-8 text-center">
            <div class="w-20 h-20 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-error text-5xl">error</span>
            </div>
            <h1 class="text-xl font-extrabold text-on-surface mb-2"><?php echo htmlspecialchars($data['title']); ?></h1>
            <p class="text-sm text-on-surface-variant mb-6"><?php echo htmlspecialchars($error); ?></p>
            <a href="<?php echo URLROOT; ?>/padres" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">home</span> Ir al inicio
            </a>
        </div>

        <?php elseif ($yaEstaba): ?>
        <!-- YA REGISTRADO -->
        <div class="bg-blue-50 px-6 py-5 text-center border-b border-blue-100">
            <span class="material-symbols-outlined text-blue-500 text-5xl">info</span>
        </div>
        <div class="p-8 text-center">
            <h1 class="text-xl font-extrabold text-on-surface mb-1">Ya registrado</h1>
            <p class="text-sm text-on-surface-variant mb-4"><?php echo htmlspecialchars($mensaje ?? 'Tu familia ya tiene asistencia registrada.'); ?></p>
            <div class="bg-surface-container rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                <p><span class="font-bold">Actividad:</span> <?php echo htmlspecialchars($actividad); ?></p>
                <?php if ($sede): ?><p><span class="font-bold">Sede:</span> <?php echo htmlspecialchars($sede); ?></p><?php endif; ?>
                <?php if ($fecha): ?><p><span class="font-bold">Fecha:</span> <?php echo date('d/m/Y H:i', strtotime($fecha)); ?></p><?php endif; ?>
            </div>
            <a href="<?php echo URLROOT; ?>/padres" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">home</span> Ir al inicio
            </a>
        </div>

        <?php else: ?>
        <!-- ÉXITO -->
        <div class="bg-gradient-to-br from-green-400 to-emerald-500 px-6 py-8 text-center">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-3 animate-bounce">
                <span class="material-symbols-outlined text-white text-5xl">check_circle</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white">¡Asistencia Confirmada!</h1>
        </div>
        <div class="p-8 text-center">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-left text-sm space-y-2 mb-6">
                <p><span class="font-bold text-green-800">Actividad:</span> <span class="text-green-700"><?php echo htmlspecialchars($actividad); ?></span></p>
                <?php if ($sede): ?><p><span class="font-bold text-green-800">Sede:</span> <span class="text-green-700"><?php echo htmlspecialchars($sede); ?></span></p><?php endif; ?>
                <?php if ($fecha): ?><p><span class="font-bold text-green-800">Fecha:</span> <span class="text-green-700"><?php echo date('d/m/Y H:i', strtotime($fecha)); ?></span></p><?php endif; ?>
                <p><span class="font-bold text-green-800">Hijos registrados:</span> <span class="text-green-700"><?php echo (int)$registros; ?></span></p>
            </div>
            <p class="text-xs text-on-surface-variant mb-5">Tu asistencia ha sido registrada exitosamente. ¡Gracias por participar!</p>
            <a href="<?php echo URLROOT; ?>/padres" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">home</span> Ir al inicio
            </a>
        </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
