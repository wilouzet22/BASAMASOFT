<?php require APPROOT . '/views/inc/header.php'; ?>

<body class="bg-gray-50 flex flex-col md:flex-row min-h-screen font-sans">
    <!-- SideNavBar -->
    <?php require APPROOT . '/views/admin/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen w-full group/design-root overflow-x-hidden">
        
        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap justify-between gap-3 p-4">
                    <p class="text-[#0d141b] tracking-light text-[32px] font-bold leading-tight min-w-72">Gestión de Asistencias</p>
                    <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#13a4ec] text-white text-sm font-medium leading-normal hover:bg-blue-600 transition-colors">
                        <span class="material-symbols-outlined mr-2" style="font-size: 20px;">add</span>
                        <span class="truncate">Registrar Asistencia</span>
                    </button>
                </div>
                
                <!-- Filters -->
                <div class="flex gap-3 p-3 flex-wrap pr-4 bg-white rounded-lg shadow-sm mb-4 border border-[#cfdbe7]">
                    <!-- Date Filter -->
                    <div class="flex flex-col gap-1 w-full md:w-auto">
                        <label class="text-sm font-medium text-[#4c739a]">Fecha</label>
                        <input type="date" class="form-input flex w-full md:w-48 resize-none overflow-hidden rounded-lg text-[#0d141b] focus:outline-0 focus:ring-0 border border-[#cfdbe7] bg-white h-10 px-4 text-sm" value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <!-- Student Filter -->
                    <div class="flex flex-col gap-1 w-full md:w-auto flex-1 md:min-w-[200px]">
                        <label class="text-sm font-medium text-[#4c739a]">Alumno</label>
                        <div class="flex w-full items-stretch rounded-lg h-10 border border-[#cfdbe7] bg-white overflow-hidden">
                            <div class="text-[#4c739a] flex items-center justify-center pl-3">
                                <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                            </div>
                            <input placeholder="Buscar por nombre..." class="form-input flex w-full min-w-0 resize-none overflow-hidden text-[#0d141b] focus:outline-none border-none bg-transparent px-3 text-sm h-full">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex flex-col gap-1 w-full md:w-auto">
                        <label class="text-sm font-medium text-[#4c739a]">Estado</label>
                        <select class="form-select flex w-full md:w-48 rounded-lg text-[#0d141b] focus:outline-0 focus:ring-0 border border-[#cfdbe7] bg-white h-10 px-4 text-sm">
                            <option value="">Todos</option>
                            <option value="presente">Presente</option>
                            <option value="ausente">Ausente</option>
                            <option value="retardo">Retardo</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="px-4 py-3 @container bg-white rounded-lg shadow-sm border border-[#cfdbe7]">
                    <div class="flex overflow-hidden rounded-lg">
                        <table class="flex-1 w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-[#cfdbe7]">
                                    <th class="px-4 py-3 text-[#4c739a] text-sm font-semibold leading-normal">Fecha</th>
                                    <th class="px-4 py-3 text-[#4c739a] text-sm font-semibold leading-normal">Alumno</th>
                                    <th class="px-4 py-3 text-[#4c739a] text-sm font-semibold leading-normal">Curso/Grado</th>
                                    <th class="px-4 py-3 text-[#4c739a] text-sm font-semibold leading-normal">Estado</th>
                                    <th class="px-4 py-3 text-[#4c739a] text-sm font-semibold leading-normal text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Mock Record 1 -->
                                <tr class="border-b border-[#cfdbe7] hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-normal"><?php echo date('d/m/Y'); ?></td>
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-medium">Juan Pérez</td>
                                    <td class="px-4 py-4 text-[#4c739a] text-sm font-normal">5to Básico</td>
                                    <td class="px-4 py-4 text-sm font-normal">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Presente
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-right flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md" title="Editar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md" title="Eliminar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Mock Record 2 -->
                                <tr class="border-b border-[#cfdbe7] hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-normal"><?php echo date('d/m/Y'); ?></td>
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-medium">María González</td>
                                    <td class="px-4 py-4 text-[#4c739a] text-sm font-normal">5to Básico</td>
                                    <td class="px-4 py-4 text-sm font-normal">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ausente
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-right flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md" title="Editar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md" title="Eliminar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Mock Record 3 -->
                                <tr class="border-b border-[#cfdbe7] hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-normal"><?php echo date('d/m/Y'); ?></td>
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-medium">Carlos Ruiz</td>
                                    <td class="px-4 py-4 text-[#4c739a] text-sm font-normal">8vo Básico</td>
                                    <td class="px-4 py-4 text-sm font-normal">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Retardo
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-right flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md" title="Editar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md" title="Eliminar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </td>
                                </tr>
                                 <!-- Mock Record 4 -->
                                 <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-normal"><?php echo date('d/m/Y', strtotime('-1 days')); ?></td>
                                    <td class="px-4 py-4 text-[#0d141b] text-sm font-medium">Ana Martínez</td>
                                    <td class="px-4 py-4 text-[#4c739a] text-sm font-normal">1ro Medio</td>
                                    <td class="px-4 py-4 text-sm font-normal">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Presente
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-right flex justify-end gap-2">
                                        <button class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded-md" title="Editar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded-md" title="Eliminar">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php require APPROOT . '/views/inc/footer.php'; ?>
