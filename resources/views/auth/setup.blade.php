<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Setup Awal - Staffify</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col lg:flex-row font-sans antialiased">

    <!-- LEFT SIDE -->
    <div
        class="lg:w-5/12 bg-blue-600 text-white flex flex-col justify-between relative overflow-hidden p-6 sm:p-8 lg:p-14">

        <!-- Blur Decoration -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-blue-500 opacity-50 blur-3xl">
        </div>

        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-80 h-80 rounded-full bg-blue-700 opacity-50 blur-3xl">
        </div>

        <!-- Logo -->
        <div class="relative z-10 flex items-center gap-3 mb-12">
            <i class="ph-fill ph-users-three text-4xl text-blue-200"></i>

            <div>
                <h1 class="text-2xl font-black tracking-widest leading-none">
                    STAFFIFY
                </h1>

                <p class="text-[10px] text-blue-200 font-bold uppercase tracking-widest mt-1">
                    BEM Politeknik Negeri Jakarta
                </p>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 flex-1 flex flex-col justify-center py-6 lg:py-0">

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black mb-6 leading-tight">
                Bangun Tim <br />
                <span class="text-blue-200">Terbaikmu.</span>
            </h2>

            <p class="text-blue-100 text-sm sm:text-base lg:text-lg mb-8 leading-relaxed max-w-md">
                Sistem Pendukung Keputusan berbasis web untuk mempermudah proses Open Recruitment Staff menggunakan
                metode Profile Matching yang akurat dan transparan.
            </p>

            <!-- Info Card -->
            <div
                class="bg-blue-700/40 backdrop-blur-sm p-5 sm:p-6 rounded-2xl border border-blue-500/50 shadow-lg inline-block">

                <div class="flex items-center gap-3 mb-2">
                    <i class="ph-fill ph-info text-2xl text-blue-300"></i>

                    <h3 class="font-bold text-lg">
                        Informasi Setup
                    </h3>
                </div>

                <p class="text-blue-100 text-sm leading-relaxed">
                    Halaman ini hanya muncul satu kali saat instalasi awal.
                    Akun yang didaftarkan di sini akan otomatis menjadi
                    <strong class="text-white">
                        Project Officer (PO)
                    </strong>
                    yang memiliki akses penuh ke seluruh fitur sistem.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-12 text-blue-300 text-sm font-medium">
            &copy; {{ date('Y') }} Staffify System.
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div
        class="lg:w-7/12 bg-slate-50 flex items-start lg:items-center justify-center min-h-screen overflow-y-auto custom-scrollbar p-5 sm:p-6 lg:p-12">

        <!-- CARD -->
        <div
            class="w-full max-w-2xl bg-white p-6 sm:p-8 md:p-12 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 my-6 lg:my-10">

            <!-- Header -->
            <div class="mb-10 mt-2 lg:mt-0">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                    Setup Sistem Awal
                </h3>

                <p class="text-slate-500 mt-2 font-medium text-sm sm:text-base leading-relaxed">
                    Lengkapi data Project Officer dan detail program kerja di bawah ini untuk memulai.
                </p>
            </div>

            <!-- FORM -->
            <form action="{{ route('setup.store') }}" method="POST" class="space-y-10">
                @csrf

                <!-- SECTION A -->
                <div>
                    <h4
                        class="text-sm font-black text-blue-600 uppercase tracking-widest mb-5 flex items-center gap-2 border-b border-slate-100 pb-3">

                        <i class="ph-fill ph-user-circle text-xl"></i>

                        A. Akun Project Officer
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- Nama -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Nama Lengkap
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="name" required placeholder="Contoh: Arif Fadillah"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Email Utama
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="email" name="email" required placeholder="po@mahasiswa.pnj.ac.id"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                        </div>

                        <!-- Password -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Password Login
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="password" name="password" required minlength="6"
                                placeholder="Minimal 6 karakter"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                        </div>
                    </div>
                </div>

                <!-- SECTION B -->
                <div>
                    <h4
                        class="text-sm font-black text-blue-600 uppercase tracking-widest mb-5 flex items-center gap-2 border-b border-slate-100 pb-3">

                        <i class="ph-fill ph-kanban text-xl"></i>

                        B. Data Program Kerja
                    </h4>

                    <div class="space-y-5">

                        <!-- Nama Proker -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Nama Program Kerja
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="text" name="nama_proker" required
                                placeholder="Contoh: Pekan Edukasi dan Penalaran 2026"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                Deskripsi Singkat Proker (Opsional)
                            </label>

                            <textarea name="deskripsi" rows="3" placeholder="Deskripsikan secara singkat tujuan dan tema proker..."
                                class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium leading-relaxed"></textarea>
                        </div>

                        <!-- Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Tanggal Mulai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" name="tanggal_mulai" required
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Tanggal Selesai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="date" name="tanggal_selesai" required
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition outline-none font-medium">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 uppercase tracking-widest text-sm flex items-center justify-center gap-2 group">

                        Selesaikan Setup & Mulai

                        <i
                            class="ph ph-rocket-launch text-lg group-hover:-translate-y-1 transition-transform duration-300"></i>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

</body>

</html>
