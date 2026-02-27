<!DOCTYPE html>
<html lang="es"><head>
<meta charset="utf-8"/>
<link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
<link as="style" href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<title>Stitch Design</title>
<link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<style type="text/tailwindcss">
      :root {
        --primary-color: #13a4ec;
        --secondary-color: #f0f3f4;
        --text-primary: #111618;
        --text-secondary: #617c89;
      }
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
    </style>
</head>
<body class="bg-gray-50" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
<div class="relative flex h-auto min-h-screen w-full flex-col bg-white group/design-root overflow-x-hidden">
<div class="flex h-full grow">
<aside class="flex flex-col w-64 bg-gray-50 border-r border-gray-200 p-4">
<div class="flex flex-col mb-8">
<h1 class="text-xl font-bold text-[var(--text-primary)]">EduConnect</h1>
<p class="text-sm text-[var(--text-secondary)]">Administrador</p>
</div>
<nav class="flex flex-col gap-2">
<a class="flex items-center gap-3 px-3 py-2 rounded-md text-[var(--text-primary)] hover:bg-gray-200" href="inicio.html">
<span class="material-symbols-outlined">home</span>
<span class="text-sm font-medium">Inicio</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-md bg-[var(--primary-color)] text-white" href="usuarios.html">
<span class="material-symbols-outlined">group</span>
<span class="text-sm font-medium">Usuarios</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-md text-[var(--text-primary)] hover:bg-gray-200" href="actividades.html">
<span class="material-symbols-outlined">calendar_today</span>
<span class="text-sm font-medium">Actividades</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-md text-[var(--text-primary)] hover:bg-gray-200" href="informes">
<span class="material-symbols-outlined">bar_chart</span>
<span class="text-sm font-medium">Informes</span>
</a>
<a class="flex items-center gap-3 px-3 py-2 rounded-md text-[var(--text-primary)] hover:bg-gray-200" href="configuracion.html">
<span class="material-symbols-outlined">settings</span>
<span class="text-sm font-medium">Configuración</span>
</a>
</nav>
      </aside>
      <main class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#0d141b] tracking-light text-[32px] font-bold leading-tight min-w-72">Manage Users</p>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal"
            >
              <span class="truncate">Add User</span>
            </button>
          </div>
            <div class="px-4 py-3">
              <label class="flex flex-col min-w-40 h-12 w-full">
                <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                  <div
                    class="text-[#4c739a] flex border-none bg-[#e7edf3] items-center justify-center pl-4 rounded-l-lg border-r-0"
                    data-icon="MagnifyingGlass"
                    data-size="24px"
                    data-weight="regular"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                      <path
                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"
                      ></path>
                    </svg>
                  </div>
                  <input
                    placeholder="Search users"
                    class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#0d141b] focus:outline-0 focus:ring-0 border-none bg-[#e7edf3] focus:border-none h-full placeholder:text-[#4c739a] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                    value=""
                  />
                </div>
              </label>
            </div>
            <div class="flex gap-3 p-3 flex-wrap pr-4">
              <button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-[#e7edf3] pl-4 pr-2">
                <p class="text-[#0d141b] text-sm font-medium leading-normal">Role</p>
                <div class="text-[#0d141b]" data-icon="CaretDown" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                  </svg>
                </div>
              </button>
              <button class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-[#e7edf3] pl-4 pr-2">
                <p class="text-[#0d141b] text-sm font-medium leading-normal">Status</p>
                <div class="text-[#0d141b]" data-icon="CaretDown" data-size="20px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                    <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                  </svg>
                </div>
              </button>
            </div>
            <div class="px-4 py-3 @container">
              <div class="flex overflow-hidden rounded-lg border border-[#cfdbe7] bg-slate-50">
                <table class="flex-1">
                  <thead>
                    <tr class="bg-slate-50">
                      <th class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 px-4 py-3 text-left text-[#0d141b] w-[400px] text-sm font-medium leading-normal">Name</th>
                      <th class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 px-4 py-3 text-left text-[#0d141b] w-[400px] text-sm font-medium leading-normal">Email</th>
                      <th class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 px-4 py-3 text-left text-[#0d141b] w-60 text-sm font-medium leading-normal">Role</th>
                      <th class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 px-4 py-3 text-left text-[#0d141b] w-60 text-sm font-medium leading-normal">Status</th>
                      <th class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 px-4 py-3 text-left text-[#0d141b] w-60 text-[#4c739a] text-sm font-medium leading-normal">
                        Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Sophia Clark
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        sophia.clark@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Admin</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Ethan Bennett
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        ethan.bennett@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Teacher</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Olivia Carter
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        olivia.carter@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Student</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Inactive</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Liam Davis
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        liam.davis@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Teacher</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Ava Evans
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        ava.evans@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Student</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Noah Foster
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        noah.foster@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Admin</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Isabella Green
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        isabella.green@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Teacher</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Inactive</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Jackson Hayes
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        jackson.hayes@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Student</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Mia Ingram
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        mia.ingram@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Teacher</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                    <tr class="border-t border-t-[#cfdbe7]">
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120 h-[72px] px-4 py-2 w-[400px] text-[#0d141b] text-sm font-normal leading-normal">
                        Lucas Jenkins
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240 h-[72px] px-4 py-2 w-[400px] text-[#4c739a] text-sm font-normal leading-normal">
                        lucas.jenkins@example.com
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Student</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                        <button
                          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 bg-[#e7edf3] text-[#0d141b] text-sm font-medium leading-normal w-full"
                        >
                          <span class="truncate">Active</span>
                        </button>
                      </td>
                      <td class="table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600 h-[72px] px-4 py-2 w-60 text-[#4c739a] text-sm font-bold leading-normal tracking-[0.015em]">
                        Edit | Delete
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <style>
                          @container(max-width:120px){.table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-120{display: none;}}
                @container(max-width:240px){.table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-240{display: none;}}
                @container(max-width:360px){.table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-360{display: none;}}
                @container(max-width:480px){.table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-480{display: none;}}
                @container(max-width:600px){.table-9ae8da83-510b-4130-ae1d-a1ed577deb85-column-600{display: none;}}
              </style>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
