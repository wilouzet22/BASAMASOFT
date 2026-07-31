<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-gray-50 flex flex-col md:flex-row min-h-screen font-sans">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div id="main-content-wrap" class="flex-1 flex flex-col min-h-screen w-full overflow-x-hidden" style="margin-left:16rem">
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto flex flex-col gap-6">

                <!-- Header -->
                <div class="flex flex-wrap justify-between gap-3">
                    <h1 class="text-[#0d141b] tracking-tight text-3xl font-bold leading-tight">Gestión de Usuarios</h1>
                </div>

                <!-- ===== PROFESORES ===== -->
                <section id="profesores" class="scroll-mt-6">
                    <h2 class="text-xl font-bold text-primary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined">school</span>
                        Profesores (<?php echo count($data['profesores']); ?>)
                    </h2>
                    <div class="overflow-hidden rounded-lg border border-[#cfdbe7] bg-slate-50">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-100 border-b border-[#cfdbe7]">
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Nombre</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Documento</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Correo</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Teléfono</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Username</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Grupos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['profesores'])): ?>
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-[#4c739a]">No hay profesores registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($data['profesores'] as $prof): ?>
                                    <tr class="border-b border-[#cfdbe7] hover:bg-white transition-colors">
                                        <td class="px-4 py-3 font-medium text-[#0d141b]">
                                            <?php echo htmlspecialchars($prof->nombres . ' ' . $prof->apellidos); ?>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] font-mono text-xs"><?php echo htmlspecialchars($prof->documento_identidad); ?></td>
                                        <td class="px-4 py-3 text-[#4c739a]"><?php echo htmlspecialchars($prof->email); ?></td>
                                        <td class="px-4 py-3 text-[#4c739a]"><?php echo htmlspecialchars($prof->telefono ?? '—'); ?></td>
                                        <td class="px-4 py-3">
                                            <code class="bg-primary/10 text-primary px-2 py-0.5 rounded text-xs"><?php echo htmlspecialchars($prof->username); ?></code>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] text-xs"><?php echo htmlspecialchars($prof->grupos ?? '—'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ===== FAMILIAS ===== -->
                <section id="familias" class="scroll-mt-6">
                    <h2 class="text-xl font-bold text-secondary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined">family_restroom</span>
                        Familias / Acudientes (<?php echo count($data['familias']); ?>)
                    </h2>
                    <div class="overflow-hidden rounded-lg border border-[#cfdbe7] bg-slate-50">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-100 border-b border-[#cfdbe7]">
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Acudiente Principal</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Documento</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Correo</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Teléfono</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Username</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Estudiantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['familias'])): ?>
                                    <tr><td colspan="6" class="px-4 py-6 text-center text-[#4c739a]">No hay familias registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($data['familias'] as $fam): ?>
                                    <tr class="border-b border-[#cfdbe7] hover:bg-white transition-colors">
                                        <td class="px-4 py-3 font-medium text-[#0d141b]">
                                            <?php echo htmlspecialchars($fam->nombre_principal_acudiente . ' ' . $fam->apellidos_principal_acudiente); ?>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] font-mono text-xs"><?php echo htmlspecialchars($fam->documento_principal_acudiente); ?></td>
                                        <td class="px-4 py-3 text-[#4c739a]"><?php echo htmlspecialchars($fam->email_contacto); ?></td>
                                        <td class="px-4 py-3 text-[#4c739a]"><?php echo htmlspecialchars($fam->telefono_contacto ?? '—'); ?></td>
                                        <td class="px-4 py-3">
                                            <code class="bg-secondary/10 text-secondary px-2 py-0.5 rounded text-xs"><?php echo htmlspecialchars($fam->username); ?></code>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] text-xs"><?php echo htmlspecialchars($fam->estudiantes ?? '—'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ===== ESTUDIANTES ===== -->
                <section id="estudiantes" class="scroll-mt-6">
                    <h2 class="text-xl font-bold text-tertiary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined">groups</span>
                        Estudiantes (<?php echo count($data['estudiantes']); ?>)
                    </h2>
                    <div class="overflow-hidden rounded-lg border border-[#cfdbe7] bg-slate-50">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-100 border-b border-[#cfdbe7]">
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Nombre</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Documento</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Fecha Nacimiento</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Grupo</th>
                                    <th class="px-4 py-3 text-[#0d141b] font-semibold">Grado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['estudiantes'])): ?>
                                    <tr><td colspan="5" class="px-4 py-6 text-center text-[#4c739a]">No hay estudiantes registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($data['estudiantes'] as $est): ?>
                                    <tr class="border-b border-[#cfdbe7] hover:bg-white transition-colors">
                                        <td class="px-4 py-3 font-medium text-[#0d141b]">
                                            <?php echo htmlspecialchars($est->nombres . ' ' . $est->apellidos); ?>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] font-mono text-xs"><?php echo htmlspecialchars($est->documento_identidad ?? '—'); ?></td>
                                        <td class="px-4 py-3 text-[#4c739a]">
                                            <?php echo $est->fecha_nacimiento ? date('d/m/Y', strtotime($est->fecha_nacimiento)) : '—'; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="bg-tertiary/10 text-tertiary px-2 py-0.5 rounded text-xs font-medium">
                                                <?php echo htmlspecialchars($est->nombre_grupo); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-[#4c739a] text-xs"><?php echo htmlspecialchars($est->nombre_grado); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
