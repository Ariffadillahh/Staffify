<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staffify Admin - BEM PNJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden relative">

    <div id="sidebarOverlay"
        class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"
        onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="w-64 flex-shrink-0 bg-slate-900 text-white flex flex-col h-full shadow-2xl md:shadow-lg transition-transform duration-300 absolute md:relative z-50 -translate-x-full md:translate-x-0">

        <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
            <div class="flex items-center">
                <i class="ph ph-users-three text-3xl text-blue-500 mr-3"></i>
                <div>
                    <h1 class="text-xl font-bold tracking-wider">STAFFIFY</h1>
                </div>
            </div>
            <button class="md:hidden text-slate-400 hover:text-white" onclick="toggleSidebar()">
                <i class="ph ph-x text-2xl font-bold"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1.5 custom-scrollbar">
            <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 mt-2">Menu Utama</p>

            @if (auth()->user()->role === 'po' || auth()->user()->role === 'vpo')
                <a href="{{ route('proker.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('proker.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-kanban text-xl mr-3 {{ request()->routeIs('proker.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Dashboard Proker</span>
                </a>

                <a href="{{ route('kadiv.generate_page') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('kadiv.generate_page') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-user-plus text-xl mr-3 {{ request()->routeIs('kadiv.generate_page') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Kelola Divisi</span>
                </a>

                <a href="{{ route('po.monitoring.jadwal') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('po.monitoring.jadwal') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-calendar text-xl mr-3 {{ request()->routeIs('po.monitoring.jadwal') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Jadwal Wawancara</span>
                </a>

                <a href="{{ route('po.kandidat') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('po.kandidat') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-users text-xl mr-3 {{ request()->routeIs('po.kandidat') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Daftar Kandidat</span>
                </a>
            @endif

            @if (auth()->user()->role === 'kadiv')
                <a href="{{ route('kadiv.dashboard') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('kadiv.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-squares-four text-xl mr-3 {{ request()->routeIs('kadiv.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Dashboard Divisi</span>
                </a>

                <a href="{{ route('jadwal.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('jadwal.*', 'penilaian.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-calendar-check text-xl mr-3 {{ request()->routeIs('jadwal.*', 'penilaian.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Manajemen Jadwal</span>
                </a>

                <a href="{{ route('kriteria.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('kriteria.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-list-numbers text-xl mr-3 {{ request()->routeIs('kriteria.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Kriteria Penilaian</span>
                </a>

                <a href="{{ route('pm.index') }}"
                    class="flex items-center px-3 py-2.5 rounded-lg group transition-all duration-200 
                    {{ request()->routeIs('pm.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i
                        class="ph ph-chart-bar text-xl mr-3 {{ request()->routeIs('pm.index') ? 'text-white' : 'text-slate-400 group-hover:text-white' }} transition-colors"></i>
                    <span class="font-medium text-sm">Hasil Profile Matching</span>
                </a>
            @endif
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950 flex-shrink-0">
            <div class="flex items-center text-sm">
                <div
                    class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center font-black text-white mr-3 shadow-md border border-blue-500">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="font-bold text-white truncate text-sm leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                        {{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full min-w-0">

        <header
            class="h-16 bg-white shadow-sm flex items-center justify-between px-4 md:px-6 z-10 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()"
                    class="md:hidden text-slate-500 hover:text-blue-600 focus:outline-none transition-colors">
                    <i class="ph ph-list text-3xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-2 sm:gap-5">
                <span class="text-sm font-bold text-slate-500 hidden sm:block">
                    <i class="ph-fill ph-calendar-blank mr-1"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>

                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                <button onclick="openPasswordModal()"
                    class="flex items-center text-sm font-bold text-slate-600 hover:text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="ph ph-key text-xl mr-1.5"></i> <span class="hidden sm:inline">Ubah Password</span>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit"
                        class="flex items-center text-sm font-bold text-rose-500 hover:text-rose-700 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">
                        <i class="ph ph-sign-out text-xl mr-1.5"></i> <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 bg-slate-50">
            @if ($errors->has('current_password') || $errors->has('password'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm flex items-start">
                    <i class="ph ph-warning-circle text-red-500 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <p class="text-red-800 font-bold mb-1">Gagal Mengubah Password!</p>
                        <ul class="text-red-700 text-sm list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <div id="modalPassword"
        class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-lock-key text-blue-600"></i> Ubah Password
                </h3>
                <button onclick="closePasswordModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="ph ph-x text-xl font-bold"></i>
                </button>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Password Saat Ini <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-lock text-slate-400 absolute left-3 top-3"></i>
                            <input type="password" name="current_password" required
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Password Baru <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-key text-slate-400 absolute left-3 top-3"></i>
                            <input type="password" name="password" required minlength="6"
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 font-medium">Minimal 6 karakter.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi Password Baru <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="ph ph-check-circle text-slate-400 absolute left-3 top-3"></i>
                            <input type="password" name="password_confirmation" required minlength="6"
                                class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closePasswordModal()"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 font-bold text-sm rounded-xl transition hover:bg-slate-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white font-bold text-sm rounded-xl shadow-md transition hover:bg-blue-700 flex items-center gap-2">
                        <i class="ph ph-floppy-disk text-lg"></i> Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function openPasswordModal() {
            document.getElementById('modalPassword').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('modalPassword').classList.add('hidden');
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</body>

</html>
