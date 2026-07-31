<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-gray-50 flex flex-col md:flex-row min-h-screen font-sans">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen w-full overflow-x-hidden" style="margin-left:16rem">
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto flex flex-col gap-6">

                <!-- Header -->
                <div class="flex flex-wrap justify-between items-center gap-3">
                    <h1 class="text-[#0d141b] tracking-tight text-3xl font-bold leading-tight">Gestión de Asistencias</h1>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-xl border border-[#cfdbe7] p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600">how_to_reg</span>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[#0d141b]"><?php echo $data['presentes'] + $data['ausentes']; ?></p>
                            <p class="text-xs text-[#4c739a] font-medium uppercase tracking-wide">Total Registros</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#cfdbe7] p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-green-600"><?php echo $data['presentes']; ?></p>
                            <p class="text-xs text-[#4c739a] font-medium uppercase tracking-wide">Presentes</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-[#cfdbe7] p-5 flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-600">cancel</span>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-red-600"><?php echo $data['ausentes']; ?></p>
                            <p class="text-xs text-[#4c739a] font-medium uppercase tracking-wide">Ausentes</p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-lg shadow-sm border border-[#cfdbe7] overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-[#cfdbe7]">
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Fecha Registro</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Estudiante</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Grupo / Grado</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Actividad</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Tipo</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Sede</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Profesor</th>
                                <th class="px-4 py-3 text-[#4c739a] font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['asistencias'])): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center text-[#4c739a]">
                                        <span class="material-symbols-outlined text-4xl block mb-2">event_busy</span>
                                        No hay registros de asistencia aún.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['asistencias'] as $asi): ?>
                                <tr class="border-b border-[#cfdbe7] hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-[#0d141b]">
                                        <?php echo date('d/m/Y H:i', strtotime($asi->fecha_registro)); ?>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-[#0d141b]">
                                        <?php echo htmlspecialchars($asi->estudiante_nombre); ?>
                                    </td>
                                    <td class="px-4 py-3 text-[#4c739a] text-xs">
                                        <?php echo htmlspecialchars($asi->nombre_grupo); ?><br>
                                        <span class="text-[10px]"><?php echo htmlspecialchars($asi->nombre_grado); ?></span>
                                    </td>
                                    <td class="px-4 py-3 text-[#0d141b]">
                                        <?php echo htmlspecialchars($asi->nombre_actividad); ?><br>
                                        <span class="text-[10px] text-[#4c739a]">
                                            <?php echo date('d/m/Y H:i', strtotime($asi->fecha_hora_inicio)); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-medium">
                                            <?php echo htmlspecialchars($asi->nombre_tipo); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-[#4c739a] text-xs">
                                        <?php echo htmlspecialchars($asi->nombre_sede); ?>
                                    </td>
                                    <td class="px-4 py-3 text-[#4c739a] text-xs">
                                        <?php echo htmlspecialchars($asi->profesor_nombre); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if ($asi->presente): ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="material-symbols-outlined text-xs" style="font-size:14px">check_circle</span>
                                                Presente
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="material-symbols-outlined text-xs" style="font-size:14px">cancel</span>
                                                Ausente
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
