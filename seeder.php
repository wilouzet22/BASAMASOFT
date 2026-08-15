<?php
/**
 * Seeder: genera 82 actividades (2 por semana, 41 semanas) esparcidas
 * a lo largo del año y asistencias aleatorias para todos los padres.
 *
 * Ejecución: /opt/lampp/bin/php seeder.php
 */
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ── 1. Limpiar datos anteriores ──────────────────────────────────────────
    echo "🗑  Limpiando datos anteriores...\n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('TRUNCATE TABLE asistencia');
    $pdo->exec('TRUNCATE TABLE actividad_grupo');
    $pdo->exec('TRUNCATE TABLE actividades');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    // ── 2. Leer catálogos ────────────────────────────────────────────────────
    $tipos      = $pdo->query('SELECT id_tipo_actividad FROM tipos_actividad')->fetchAll(PDO::FETCH_COLUMN);
    $sedes      = $pdo->query('SELECT id_sede FROM sedes')->fetchAll(PDO::FETCH_COLUMN);
    $grupos     = $pdo->query('SELECT id_grupo FROM grupos')->fetchAll(PDO::FETCH_COLUMN);
    $profesores = $pdo->query('SELECT id_profesor FROM profesores')->fetchAll(PDO::FETCH_COLUMN);
    $familias   = $pdo->query('SELECT id_familia FROM familias')->fetchAll(PDO::FETCH_COLUMN);

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

    // Nombres creativos por tipo
    $nombresPorTipo = [
        'Clase Regular'           => ['Clase de Matemáticas','Clase de Ciencias','Clase de Español','Clase de Historia','Clase de Arte'],
        'Reunión de Padres'       => ['Reunión de Padres','Encuentro Familiar','Asamblea de Padres','Sesión Informativa'],
        'Evento Cultural/Deportivo' => ['Festival Cultural','Jornada Deportiva','Presentación Artística','Olimpiadas Internas'],
    ];
    $nombresDefault = ['Taller Pedagógico','Actividad Formativa','Sesión Especial','Jornada Escolar','Encuentro Educativo'];

    // ── 3. Generar actividades ────────────────────────────────────────────────
    // Punto de inicio: hace 28 semanas (lunes)
    $inicioSemana = new DateTime('monday this week');
    $inicioSemana->modify('-28 weeks');

    $hoy = new DateTime();

    echo "📅  Generando 82 actividades (41 semanas × 2)...\n";

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

    for ($semana = 0; $semana < 41; $semana++) {
        for ($idx = 0; $idx < 2; $idx++) {

            // Día aleatorio dentro de la semana (Lun–Vie)
            $diaSemana  = rand(0, 4);
            $hora       = rand(7, 16);
            $minuto     = [0, 15, 30, 45][rand(0, 3)];

            $fecha = clone $inicioSemana;
            $fecha->modify("+{$semana} weeks +{$diaSemana} days");
            $fecha->setTime($hora, $minuto, 0);

            $fin = clone $fecha;
            $fin->modify('+1 hour 30 minutes');

            // Tipo y nombre
            $tipoId     = $tipos[array_rand($tipos)];
            $sedeId     = $sedes[array_rand($sedes)];
            $profId     = $profesores[array_rand($profesores)];
            $nombreTipo = $pdo->query("SELECT nombre_tipo FROM tipos_actividad WHERE id_tipo_actividad = $tipoId")->fetchColumn();
            $pool       = $nombresPorTipo[$nombreTipo] ?? $nombresDefault;
            $nombre     = $pool[array_rand($pool)] . ' (Sem ' . ($semana + 1) . ')';

            $stmtAct->execute([
                $nombre,
                'Actividad generada automáticamente – semana ' . ($semana + 1),
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
                    $presente = (rand(1, 100) <= 75) ? 1 : 0; // 75 % de asistencia
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
        }
    }

    // ── 4. Resumen ────────────────────────────────────────────────────────────
    $totalActs  = $pdo->query('SELECT COUNT(*) FROM actividades')->fetchColumn();
    $totalAsi   = $pdo->query('SELECT COUNT(*) FROM asistencia')->fetchColumn();
    $totalPres  = $pdo->query('SELECT SUM(presente) FROM asistencia')->fetchColumn();
    $pct        = $totalAsi > 0 ? round($totalPres / $totalAsi * 100) : 0;

    echo "✅  ¡Seeder completado!\n";
    echo "   Actividades creadas : $totalActs\n";
    echo "   Registros asistencia: $totalAsi  (≈{$pct}% asistieron)\n";

} catch (PDOException $e) {
    echo "❌  Error de BD: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌  Error: " . $e->getMessage() . "\n";
}
