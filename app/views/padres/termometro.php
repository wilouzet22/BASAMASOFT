<?php
// Cálculo del termómetro de asistencia
// Requiere que la variable $etapasTermometro esté definida con las etapas
$actividadesPasadas  = 0;
$actividadesAsistidas = 0;
$etapasTermometro = $etapasTermometro ?? [];
foreach ($etapasTermometro as $e) {
    if (!isset($e['dias'])) continue;
    foreach ($e['dias'] as $dia) {
        if (isset($dia['estado'])) {
            if ($dia['estado'] === 'completado') {
                $actividadesPasadas++;
                $actividadesAsistidas++;
            } elseif ($dia['estado'] === 'inasistencia') {
                $actividadesPasadas++;
            }
        }
    }
}
$actividadesProgramadas = $actividadesPasadas; // para compatibilidad
$porcentajeTermometro = $actividadesPasadas > 0 ? round(($actividadesAsistidas / $actividadesPasadas) * 100) : 0;
?>
<style>
/* ── Thermometer: CSS custom-property theming ───────────────────────────── */
:root {
    --therm-bg          : rgba(255, 255, 255, 0.92);
    --therm-border      : rgba(203, 213, 225, 0.80);   /* slate-300/80 */
    --therm-shadow      : 0 8px 32px rgba(0,0,0,0.18);
    --therm-tube-bg     : rgba(203, 213, 225, 0.70);   /* slate-200/70 */
    --therm-tube-border : #cbd5e1;                      /* slate-300 */
    --therm-label-color : #6366f1;                      /* indigo / primary light */
    --therm-mini-bg     : rgba(255, 255, 255, 0.92);
    --therm-tooltip-bg  : rgba(15, 23, 42, 0.95);
    --therm-tooltip-fg  : #f8fafc;
    --therm-bulb-border : rgba(255, 255, 255, 0.90);   /* blanco en claro */
    --therm-bulb-shine  : rgba(255, 255, 255, 0.40);   /* brillo interior */
}

/* ── Modo oscuro ─────────────────────────────────────────────────────────── */
.dark {
    --therm-bg          : rgba(30, 41, 59, 0.92);      /* slate-800/92 */
    --therm-border      : rgba(71, 85, 105, 0.70);     /* slate-600/70 */
    --therm-shadow      : 0 8px 32px rgba(0,0,0,0.50);
    --therm-tube-bg     : rgba(51, 65, 85, 0.80);      /* slate-700/80 */
    --therm-tube-border : #475569;                      /* slate-600 */
    --therm-label-color : #93c5fd;                      /* blue-300 */
    --therm-mini-bg     : rgba(30, 41, 59, 0.92);
    --therm-tooltip-bg  : rgba(2, 6, 23, 0.96);
    --therm-tooltip-fg  : #e2e8f0;
    --therm-bulb-border : rgba(71, 85, 105, 0.90);    /* slate-600 oscuro */
    --therm-bulb-shine  : rgba(148, 163, 184, 0.25);  /* brillo tenue */
}

/* ── Super oscuro ────────────────────────────────────────────────────────── */
.superdark {
    --therm-bg          : rgba(10, 10, 10, 0.95);
    --therm-border      : rgba(38, 38, 38, 0.90);
    --therm-shadow      : 0 8px 32px rgba(0,0,0,0.80);
    --therm-tube-bg     : rgba(23, 23, 23, 0.90);
    --therm-tube-border : #262626;
    --therm-label-color : #a5b4fc;                     /* indigo-300 */
    --therm-mini-bg     : rgba(10, 10, 10, 0.95);
    --therm-tooltip-bg  : rgba(0, 0, 0, 0.98);
    --therm-tooltip-fg  : #d1d5db;
    --therm-bulb-border : rgba(38, 38, 38, 0.95);     /* casi negro */
    --therm-bulb-shine  : rgba(80, 80, 80, 0.20);     /* brillo muy tenue */
}

/* Bulbo del termómetro */
#thermometerWidget .therm-bulb {
    border-color: var(--therm-bulb-border) !important;
    transition  : border-color 0.3s ease;
}
#thermometerWidget .therm-bulb-shine {
    background: var(--therm-bulb-shine) !important;
    transition: background 0.3s ease;
}

/* ── Aplicar variables al widget ─────────────────────────────────────────── */
#thermometerWidget {
    position       : fixed;
    right          : 0.75rem;
    bottom         : 1rem;
    top            : auto;
    transform      : none;
    z-index        : 9999;
    background     : var(--therm-bg);
    border         : 1px solid var(--therm-border);
    box-shadow     : var(--therm-shadow);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition     : background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}
@media (min-width: 768px) {
    #thermometerWidget {
        top      : 50%;
        bottom   : auto;
        transform: translateY(-50%);
        right    : 1rem;
    }
}
@media (max-width: 479px) {
    #thermometerWidget { display: none !important; }
    #thermometerMini   { display: flex !important; }
}

/* Tubo interno */
#thermometerWidget .therm-tube {
    background  : var(--therm-tube-bg);
    border-color: var(--therm-tube-border);
    transition  : background 0.3s ease, border-color 0.3s ease;
}

/* Etiqueta del % */
#thermometerWidget .therm-label {
    color     : var(--therm-label-color);
    transition: color 0.3s ease;
}

/* Mini pill */
#thermometerMini {
    background     : var(--therm-mini-bg);
    border         : 1px solid var(--therm-border);
    box-shadow     : var(--therm-shadow);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition     : background 0.3s ease, border-color 0.3s ease;
}

/* Tooltip */
#thermTooltip {
    position   : absolute;
    right      : calc(100% + 14px);
    top        : 50%;
    transform  : translateY(-50%) translateX(8px);
    background : var(--therm-tooltip-bg);
    backdrop-filter: blur(8px);
    color      : var(--therm-tooltip-fg);
    padding    : 10px 14px;
    border-radius: 14px;
    white-space  : nowrap;
    font-size    : 13px;
    font-weight  : 700;
    pointer-events: none;
    opacity      : 0;
    transition   : opacity 0.22s ease, transform 0.22s ease,
                   background 0.3s ease, color 0.3s ease;
    z-index    : 100;
    box-shadow : 0 8px 28px rgba(0,0,0,0.40);
    border     : 1px solid rgba(255,255,255,0.08);
}
#thermTooltip::after {
    content    : "";
    position   : absolute;
    right      : -7px;
    top        : 50%;
    transform  : translateY(-50%);
    border     : 7px solid transparent;
    border-left-color: var(--therm-tooltip-bg);
    border-right: none;
}
#thermometerWidget:hover #thermTooltip,
#thermometerWidget.tooltip-open #thermTooltip {
    opacity  : 1;
    transform: translateY(-50%) translateX(0);
}
</style>

<!-- Mini pill shown only on xs (< 480px) -->
<div id="thermometerMini" class="fixed bottom-4 right-4 z-[9999] hidden items-center gap-2 px-3 py-2 rounded-full shadow-xl">
    <span class="material-symbols-outlined text-base" style="color:<?php
                                                                    if ($porcentajeTermometro >= 75) echo '#22c55e';
                                                                    elseif ($porcentajeTermometro >= 50) echo '#eab308';
                                                                    elseif ($porcentajeTermometro >= 25) echo '#f97316';
                                                                    else echo '#ef4444';
                                                                    ?>;"><?php
                                                                            if ($porcentajeTermometro >= 75) echo 'sentiment_very_satisfied';
                                                                            elseif ($porcentajeTermometro >= 50) echo 'sentiment_neutral';
                                                                            elseif ($porcentajeTermometro >= 25) echo 'sentiment_dissatisfied';
                                                                            else echo 'sentiment_very_dissatisfied';
                                                                            ?></span>
    <span class="text-xs font-black text-primary"><?= $porcentajeTermometro ?>%</span>
</div>

<!-- Full thermometer for sm+ -->
<div id="thermometerWidget" class="flex flex-col items-center p-3 rounded-[48px] gap-2 cursor-pointer z-[9999]">
    <!-- Tooltip de asistencia -->
    <div id="thermTooltip">
        <?php
        $tooltipColor = $porcentajeTermometro >= 75 ? '#22c55e' : ($porcentajeTermometro >= 50 ? '#eab308' : ($porcentajeTermometro >= 25 ? '#f97316' : '#ef4444'));
        ?>
        <div style="font-size:22px;font-weight:900;color:<?= $tooltipColor ?>;line-height:1;margin-bottom:4px;"><?= $porcentajeTermometro ?>%</div>
        <div style="font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:6px;">de asistencia</div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;">
            <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;flex-shrink:0;"></span>
            <span style="color:#d1fae5;"><?= $actividadesAsistidas ?> asistidas</span>
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;margin-top:3px;">
            <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block;flex-shrink:0;"></span>
            <span style="color:#fee2e2;"><?= ($actividadesPasadas - $actividadesAsistidas) ?> inasistencias</span>
        </div>
        <?php if ($actividadesPasadas > 0): ?>
        <div style="margin-top:8px;padding-top:8px;border-top:1px solid rgba(255,255,255,0.1);font-size:11px;color:#64748b;"><?= $actividadesPasadas ?> actividades evaluadas</div>
        <?php else: ?>
        <div style="margin-top:8px;padding-top:8px;border-top:1px solid rgba(255,255,255,0.1);font-size:11px;color:#64748b;">Sin actividades registradas aún</div>
        <?php endif; ?>
    </div>

    <!-- Tube + Faces inside -->
    <div class="relative flex flex-col items-center" style="height:180px; width:36px;">
        <!-- Tube background -->
        <div class="therm-tube absolute inset-x-2 top-0 bottom-0 rounded-t-full border shadow-inner overflow-hidden">
            <!-- Liquid fill -->
            <div id="thermLiquid" class="absolute bottom-0 left-0 right-0 rounded-t-full transition-all duration-1000 ease-in-out"
                style="height:<?= $porcentajeTermometro ?>%; background:linear-gradient(to top,#ef4444,#f97316,#eab308,#22c55e);"></div>
        </div>
        <!-- Faces overlaid inside the tube at 25%, 50%, 75%, 100% positions -->
        <?php
        $faces = [
            ['icon' => 'sentiment_very_dissatisfied', 'pct' => 0,  'color' => '#ef4444'],
            ['icon' => 'sentiment_dissatisfied',      'pct' => 33, 'color' => '#f97316'],
            ['icon' => 'sentiment_neutral',           'pct' => 58, 'color' => '#eab308'],
            ['icon' => 'sentiment_very_satisfied',    'pct' => 83, 'color' => '#22c55e'],
        ];
        foreach ($faces as $face):
            $topPct = 100 - $face['pct'] - 12;
            $covered = $porcentajeTermometro >= ($face['pct'] + 10);
        ?>
            <div class="absolute left-0 right-0 flex justify-center" style="top:<?= $topPct ?>%;">
                <span class="material-symbols-outlined transition-all duration-700"
                    style="font-size:16px; color:<?= $covered ? '#ffffff' : $face['color'] ?>;">
                    <?= $face['icon'] ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Bulb -->
    <div class="therm-bulb w-9 h-9 rounded-full -mt-1 z-10 flex items-center justify-center border-2 shadow-md"
        style="background:<?= $porcentajeTermometro > 0 ? '#ef4444' : '#94a3b8' ?>;">
        <div class="therm-bulb-shine w-3 h-3 rounded-full"></div>
    </div>
    <!-- % label -->
    <span class="therm-label text-[11px] font-bold mt-1"><?= $porcentajeTermometro ?>%</span>
</div>

<script>
// Toggle tooltip on mobile tap
(function() {
    const widget = document.getElementById('thermometerWidget');
    if (!widget) return;
    if (widget.dataset.initialized) return;
    widget.dataset.initialized = "true";
    
    widget.addEventListener('click', function(e) {
        if (window.matchMedia('(hover: none)').matches) {
            widget.classList.toggle('tooltip-open');
            if (widget.classList.contains('tooltip-open')) {
                function closeOnOutside(ev) {
                    if (!widget.contains(ev.target)) {
                        widget.classList.remove('tooltip-open');
                        document.removeEventListener('click', closeOnOutside);
                    }
                }
                setTimeout(() => document.addEventListener('click', closeOnOutside), 10);
            }
        }
    });
})();
</script>
