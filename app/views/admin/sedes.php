<?php $data = $data ?? []; require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-gray-50 flex flex-col md:flex-row min-h-screen font-sans">
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <div class="flex-1 md:ml-64 flex flex-col min-h-screen w-full overflow-x-hidden">
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto flex flex-col gap-6">

                <div class="flex flex-wrap justify-between items-center gap-3">
                    <h1 class="text-[#0d141b] tracking-tight text-3xl font-bold leading-tight">Sedes Institucionales</h1>
                </div>

                <?php if (empty($data['sedes'])): ?>
                    <div class="bg-white rounded-xl border border-[#cfdbe7] p-12 text-center text-[#4c739a]">
                        <span class="material-symbols-outlined text-5xl block mb-3">apartment</span>
                        No hay sedes registradas en el sistema.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['sedes'] as $sede): ?>
                        <div class="bg-white rounded-xl border border-[#cfdbe7] p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 bg-tertiary/10 rounded-lg flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-tertiary">apartment</span>
                            </div>
                            <h3 class="text-lg font-bold text-[#0d141b] mb-3">
                                <?php echo htmlspecialchars($sede->nombre_sede); ?>
                            </h3>
                            <?php if (!empty($sede->direccion_sede)): ?>
                            <div class="flex items-start gap-2 text-sm text-[#4c739a] mb-2">
                                <span class="material-symbols-outlined text-base mt-0.5">location_on</span>
                                <?php echo htmlspecialchars($sede->direccion_sede); ?>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($sede->telefono_sede)): ?>
                            <div class="flex items-center gap-2 text-sm text-[#4c739a]">
                                <span class="material-symbols-outlined text-base">phone</span>
                                <?php echo htmlspecialchars($sede->telefono_sede); ?>
                            </div>
                            <?php endif; ?>
                            <div class="mt-4 pt-4 border-t border-[#cfdbe7]">
                                <span class="text-xs text-[#4c739a]">ID Sede: <strong><?php echo $sede->id_sede; ?></strong></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
</body>
</html>
