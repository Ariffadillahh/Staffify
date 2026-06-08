<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staffify - Open Recruitment BEM PNJ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <i class="ph ph-users-three text-3xl text-blue-600"></i>
                        <div>
                            <span class="text-xl font-black tracking-wider text-slate-800 block">STAFFIFY</span>
                            <span
                                class="text-[9px] text-slate-400 font-bold uppercase tracking-widest block leading-none">BEM
                                PNJ</span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="/"
                        class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors hidden sm:block">Beranda</a>

                    @php
                        $isSetup = \App\Models\User::where('role', 'po')->exists();
                        $adaDivisiOpen = \App\Models\Divisi::where('is_open', true)->exists();
                        $totalDivisi = \App\Models\Divisi::count();
                        $pengumumanDibuka = !$adaDivisiOpen && $totalDivisi > 0;

                    @endphp

                    @if ($isSetup)
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold border-2 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-xl transition-all duration-300">
                            Login Panitia
                        </a>

                        @if ($pengumumanDibuka)
                            <a href="{{ route('pengumuman.index') }}"
                                class="text-sm font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-xl transition-all">
                                Pengumuman
                            </a>
                        @endif
                    @else
                        <a href="{{ route('setup.create') }}"
                            class="text-sm font-bold bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                            <i class="ph-fill ph-rocket-launch text-lg"></i> Mulai Setup Sistem
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white border-t border-slate-800 mt-auto">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div>
                <p class="font-bold text-sm tracking-wide">BEM POLITEKNIK NEGERI JAKARTA</p>
                <p class="text-xs text-slate-400 mt-1">Kabinet Simpul Perubahan 2026</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://wa.me/6289527171722" target="_blank"
                    class="w-9 h-9 bg-slate-800/40 hover:bg-emerald-600/20 hover:text-emerald-400 text-slate-400 rounded-xl flex items-center justify-center transition-all duration-200 border border-slate-700/30"
                    title="WhatsApp">
                    <i class="ph-bold ph-whatsapp-logo text-base"></i>
                </a>

                <a href="https://instagram.com/pena.pnj" target="_blank"
                    class="w-9 h-9 bg-slate-800/40 hover:bg-pink-600/20 hover:text-pink-400 text-slate-400 rounded-xl flex items-center justify-center transition-all duration-200 border border-slate-700/30"
                    title="Instagram">
                    <i class="ph-bold ph-instagram-logo text-base"></i>
                </a>

                <a href="https://tiktok.com/@pena.pnj" target="_blank"
                    class="w-9 h-9 bg-slate-800/40 hover:bg-slate-950 hover:text-white text-slate-400 rounded-xl flex items-center justify-center transition-all duration-200 border border-slate-700/30"
                    title="TikTok">
                    <i class="ph-bold ph-tiktok-logo text-base"></i>
                </a>

                <a href="mailto:pena.pnj@gmail.com"
                    class="w-9 h-9 bg-slate-800/40 hover:bg-blue-600/20 hover:text-blue-400 text-slate-400 rounded-xl flex items-center justify-center transition-all duration-200 border border-slate-700/30"
                    title="Email">
                    <i class="ph-bold ph-envelope text-base"></i>
                </a>
            </div>

            <div class="text-center md:text-right text-[11px] text-slate-500">
                &copy; 2026 Staffify. Powered by <span class="text-blue-500/80 font-semibold">Laravel</span> & <span
                    class="text-cyan-500/80 font-semibold">Tailwind</span>.
            </div>

        </div>
    </footer>

</body>

</html>
