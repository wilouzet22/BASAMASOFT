<?php
/**
 * Seeder: genera 30 actividades distribuidas desde la semana 1
 * (05 ene 2026) hasta la semana que contiene el 28 de agosto de 2026.
 *
 * Ejecución: /opt/lampp/bin/php seeder_30_actividades.php
 *
 * NO trunca tablas existentes — agrega a los datos actuales.
 */
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ── 1. Leer catálogos ───────────────────────────────────────────────────
    $tipos      = $pdo->query('SELECT id_tipo_actividad FROM tipos_actividad')->fetchAll(PDO::FETCH_COLUMN);
    $sedes      = $pdo->query('SELECT id_sede FROM sedes')->fetchAll(PDO::FETCH_COLUMN);
    $grupos     = $pdo->query('SELECT id_grupo FROM grupos')->fetchAll(PDO::FETCH_COLUMN);
    $profesores = $pdo->query('SELECT id_profesor FROM profesores')->fetchAll(PDO::FETCH_COLUMN);

    // Pares (estudiante → familia)
    $estFam = $pdo->query(
        'SELECT e.id_estudiante, fe.id_familia_fk
         FROM estudiantes e
         INNER JOIN familia_estudiante fe ON e.id_estudiante = fe.id_estudiante_fk'
    )->fetchAll(PDO::FETCH_OBJ);

    if (empty($tipos))      die("❌  Sin tipos de actividad en BD.\n");
    if (empty($sedes))      die("❌  Sin sedes en BD.\n");
    if (empty($profesores)) die("❌  Sin profesores en BD.\n");
    if (empty($grupos))     die("❌  Sin grupos en BD.\n");

    // ── 2. Calcular rango de semanas ────────────────────────────────────────
    // Semana 1 del año ISO 2026: lunes 05 de enero de 2026
    $primerLunes = new DateTime('2026-01-05');
    // Semana que contiene el 28 de agosto de 2026 (viernes 28-ago es semana 35 ISO)
    $fin28ago    = new DateTime('2026-08-28');
    $semanaFin   = (int)$fin28ago->format('W'); // 35
    $totalSemanas = $semanaFin;                 // semanas 1..35

    // ── 3. Distribuir 30 actividades en 35 semanas ─────────────────────────
    // Creamos un array de 35 semanas y repartimos aleatoriamente 30 slots
    $slotsTotal = 30;
    $semanas    = range(0, $totalSemanas - 1); // índices 0-34

    // Array con cuántas actividades va cada semana (inicialmente 0)
    $actPorSemana = array_fill(0, $totalSemanas, 0);

    // Primero garantizamos 1 por semana en semanas aleatorias (30 semanas de 35)
    shuffle($semanas);
    $semanasConActividad = array_slice($semanas, 0, $slotsTotal);
    foreach ($semanasConActividad as $idx) {
        $actPorSemana[$idx]++;
    }

    // ── 4. Preparar statements ──────────────────────────────────────────────
    $nombresPorTipo = [
        'Clase Regular'             => ['Clase de Matemáticas','Clase de Ciencias','Clase de Español','Clase de Historia','Clase de Arte','Clase de Tecnología'],
        'Reunión de Padres'         => ['Reunión de Padres','Encuentro Familiar','Asamblea de Padres','Sesión Informativa','Jornada de Puertas Abiertas'],
        'Evento Cultural/Deportivo' => ['Festival Cultural','Jornada Deportiva','Presentación Artística','Olimpiadas Internas','Muestra Cultural'],
    ];
    $nombresDefault = ['Taller Pedagógico','Actividad Formativa','Sesión Especial','Jornada Escolar','Encuentro Educativo','Capacitación Docente'];

    $stmtAct = $pdo->prepare(
        'INSERT INTO actividades
            (nombre_actividad, descripcion, fecha_hora_inicio, fecha_hora_fin,
             id_tipo_actividad_fk, id_sede_fk, creada_por_profesor_fk)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmtAG = $pdo->prepare(
        'INSERT INTO actividad_grupo (id_actividad_fk, id_grupo_fk) VALUES (?, ?)'
    );
    $stmtAsi = $pdo->prepare(
        'INSERT INTO asistencia
            (id_actividad_fk, id_familia_fk, id_estudiante_fk,
             presente, registrada_por_profesor_fk, fecha_registro)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    $hoy           = new DateTime();
    $actividadesCreadas = 0;

    echo "📅  Generando 30 actividades desde Sem 1 (05-ene-2026) hasta Sem {$semanaFin} (28-ago-2026)...\n\n";

    for ($semIdx = 0; $semIdx < $totalSemanas; $semIdx++) {
        $cantidad = $actPorSemana[$semIdx];
        if ($cantidad === 0) continue;

        for ($i = 0; $i < $cantidad; $i++) {
            // Día aleatorio Lunes–Viernes dentro de esta semana
            $diaSemana = rand(0, 4);
            $hora      = rand(7, 16);
            $minuto    = [0, 15, 30, 45][rand(0, 3)];

            $fecha = clone $primerLunes;
            $fecha->modify("+{$semIdx} weeks +{$diaSemana} days");
            $fecha->setTime($hora, $minuto, 0);

            $fin = clone $fecha;
            $fin->modify('+1 hour 30 minutes');

            // No crear actividades con fecha futura al 28-ago-2026
            $limite = new DateTime('2026-08-28 23:59:59');
            if ($fecha > $limite) continue;

            // Tipo y nombre
            $tipoId     = $tipos[array_rand($tipos)];
            $sedeId     = $sedes[array_rand($sedes)];
            $profId     = $profesores[array_rand($profesores)];
            $nombreTipo = $pdo->query("SELECT nombre_tipo FROM tipos_actividad WHERE id_tipo_actividad = $tipoId")->fetchColumn();
            $pool       = $nombresPorTipo[$nombreTipo] ?? $nombresDefault;
            $numSem     = $semIdx + 1;
            $nombre     = $pool[array_rand($pool)] . " (Sem {$numSem})";

            $stmtAct->execute([
                $nombre,
                "Actividad generada automáticamente – semana {$numSem} del camino",
                $fecha->format('Y-m-d H:i:s'),
                $fin->format('Y-m-d H:i:s'),
                $tipoId,
                $sedeId,
                $profId,
            ]);
            $idActividad = (int)$pdo->lastInsertId();

            // Asignar a todos los grupos
            foreach ($grupos as $gId) {
                $stmtAG->execute([$idActividad, $gId]);
            }

            // Si la actividad ya ocurrió → registrar asistencia aleatoria
            if ($fecha < $hoy) {
                foreach ($estFam as $ef) {
                    $presente = (rand(1, 100) <= 78) ? 1 : 0; // 78 % asistencia
                    $stmtAsi->execute([
                        $idActividad,
                        $ef->id_familia_fk,
                        $ef->id_estudiante,
                        $presente,
                        $profesores[array_rand($profesores)],
                        $fecha->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            $actividadesCreadas++;
            echo "  ✔  [{$fecha->format('d M Y H:i')}] {$nombre}\n";
        }
    }

    // ── 5. Resumen ──────────────────────────────────────────────────────────
    echo "\n✅  ¡Listo!\n";
    echo "   Actividades creadas en esta ejecución : {$actividadesCreadas}\n";
    $totalActs = $pdo->query('SELECT COUNT(*) FROM actividades')->fetchColumn();
    $totalAsi  = $pdo->query('SELECT COUNT(*) FROM asistencia')->fetchColumn();
    $totalPres = $pdo->query('SELECT SUM(presente) FROM asistencia')->fetchColumn();
    $pct       = $totalAsi > 0 ? round($totalPres / $totalAsi * 100) : 0;
    echo "   Total actividades en BD              : {$totalActs}\n";
    echo "   Total registros asistencia           : {$totalAsi}  (≈{$pct}% asistieron)\n";

} catch (PDOException $e) {
    echo "❌  Error de BD: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌  Error: " . $e->getMessage() . "\n";
}
