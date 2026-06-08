@extends('layouts.public')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="text-center py-12 md:py-16">
            <div
                class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-6 shadow-sm">
                <i class="ph-fill ph-rocket-launch text-sm"></i> Open Staff Recruitment
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight mb-4">
                {{ $proker->nama_proker ?? 'Pekan Edukasi dan Penalaran 2026' }}
            </h1>

            @if ($proker)
                <p class="text-sm font-bold text-slate-500 flex items-center justify-center gap-1.5">
                    <i class="ph-fill ph-calendar-blank text-blue-500"></i> Timeline Kegiatan:
                    {{ \Carbon\Carbon::parse($proker->tanggal_mulai)->translatedFormat('d M Y') }} s/d
                    {{ \Carbon\Carbon::parse($proker->tanggal_selesai)->translatedFormat('d M Y') }}
                </p>
            @endif
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 md:p-10 mb-8">
            <h2 class="text-xl font-black text-slate-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="ph-fill ph-info text-blue-500"></i> Tentang Program Kerja
            </h2>

            <div class="text-slate-600 text-sm md:text-base leading-relaxed space-y-4">
                @if ($proker && $proker->deskripsi)
                    <p class="whitespace-pre-line">{{ $proker->deskripsi }}</p>
                @else
                    <p class="italic text-gray-400 text-center py-4">Belum ada deskripsi resmi yang ditambahkan untuk
                        program kerja ini.</p>
                @endif
            </div>
        </div>

        <div class="my-16">
            <h2 class="text-2xl font-black text-center text-slate-800 mb-12 tracking-tight">
                Alur Pendaftaran
            </h2>

            <div
                class="relative flex flex-col md:flex-row justify-between items-center md:items-start gap-8 md:gap-4 max-w-5xl mx-auto px-4">
                <div class="hidden md:block absolute top-7 left-[10%] right-[10%] h-[2px] bg-gray-200 z-0"></div>

                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-1/5">
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-md mb-4 ring-4 ring-white">
                        <i class="ph-fill ph-browser text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 mb-1">Pendaftaran Online</h3>
                    <span
                        class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">11
                        - 20 Mei 2026</span>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-1/5">
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-md mb-4 ring-4 ring-white">
                        <i class="ph-fill ph-users text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 mb-1">Wawancara</h3>
                    <span
                        class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">12
                        - 20 Mei 2026</span>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-1/5">
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-md mb-4 ring-4 ring-white">
                        <i class="ph-fill ph-megaphone text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 mb-1">Pengumuman</h3>
                    <span
                        class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">23
                        Mei 2026</span>
                </div>

                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-1/5">
                    <div
                        class="w-14 h-14 bg-blue-600 text-white rounded-full flex items-center justify-center shadow-md mb-4 ring-4 ring-white">
                        <i class="ph-fill ph-confetti text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 mb-1">First Gathering</h3>
                    <span
                        class="text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100">26
                        Mei 2026</span>
                </div>
            </div>
        </div>

        @if ($proker && $proker->divisi->where('is_open', true)->count() > 0)
            <div class="text-center mt-10">
                <a href="/daftar"
                    class="inline-flex items-center gap-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-base px-8 py-4 rounded-2xl shadow-lg transition-all hover:-translate-y-0.5 duration-200">
                    <i class="ph-fill ph-user-plus text-xl"></i> Daftarkan Diri Kamu Sekarang
                </a>
                <p class="text-xs text-slate-400 font-semibold mt-3 uppercase tracking-wider">Pastikan berkas CV &
                    Portofolio sudah siap</p>
            </div>
        @else
            <div class="text-center mt-10">
                <div
                    class="inline-flex items-center gap-2 bg-rose-50 text-rose-700 border border-rose-100 px-6 py-3 rounded-2xl text-sm font-bold shadow-sm">
                    <i class="ph-fill ph-lock-keyhole text-base"></i> Pendaftaran Belum Dibuka / Sudah Ditutup Resmi
                </div>
            </div>
        @endif
    </div>

    <div class="my-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Divisi Kami</h2>
            <p class="text-sm text-slate-500">Kenali tugas dan peran masing-masing divisi sebelum memilih.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto px-4 items-stretch">
            @if ($proker && $proker->divisi->count() > 0)
                @foreach ($proker->divisi as $div)
                    @php
                        $parts = explode('//', $div->deskripsi);
                        $inisial = isset($parts[0]) ? trim($parts[0]) : '';
                        $kadiv = isset($parts[1]) ? trim($parts[1]) : '';
                        $deskripsi_asli = count($parts) >= 3 ? trim($parts[2]) : trim($div->deskripsi);

                        $nama_lower = strtolower($div->nama_divisi);
                        $icon = 'ph-users-three'; 

                        if (str_contains($nama_lower, 'acara')) {
                            $icon = 'ph-calendar-star';
                        } elseif (str_contains($nama_lower, 'humas') || str_contains($nama_lower, 'pr')) {
                            $icon = 'ph-megaphone-simple';
                        } elseif (
                            str_contains($nama_lower, 'pubdok') ||
                            str_contains($nama_lower, 'pdd') ||
                            str_contains($nama_lower, 'media')
                        ) {
                            $icon = 'ph-camera';
                        } elseif (str_contains($nama_lower, 'kreatif') || str_contains($nama_lower, 'design')) {
                            $icon = 'ph-palette';
                        }
                        elseif (str_contains($nama_lower, 'perkap') || str_contains($nama_lower, 'logistik')) {
                            $icon = 'ph-toolbox';
                        } elseif (str_contains($nama_lower, 'konsumsi')) {
                            $icon = 'ph-hamburger';
                        } elseif (
                            str_contains($nama_lower, 'danspon') ||
                            str_contains($nama_lower, 'danus') ||
                            str_contains($nama_lower, 'sponsor')
                        ) {
                            $icon = 'ph-handshake';
                        }
                        elseif (str_contains($nama_lower, 'kestari') || str_contains($nama_lower, 'sekretaris')) {
                            $icon = 'ph-folders';
                        } elseif (str_contains($nama_lower, 'bendahara') || str_contains($nama_lower, 'keuangan')) {
                            $icon = 'ph-wallet';
                        }
                        elseif (str_contains($nama_lower, 'kompetisi') || str_contains($nama_lower, 'lomba')) {
                            $icon = 'ph-trophy';
                        }
                        elseif (
                            str_contains($nama_lower, 'k3l') ||
                            str_contains($nama_lower, 'keamanan') ||
                            str_contains($nama_lower, 'kesehatan')
                        ) {
                            $icon = 'ph-shield-plus';
                        } 
                    @endphp

                    <div x-data="{ open: false }"
                        class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm flex flex-col items-center text-center justify-between transition-all hover:shadow-md hover:-translate-y-1 duration-200 h-full group">

                        <div class="flex flex-col items-center w-full">
                            <div
                                class="w-16 h-16 bg-blue-50 group-hover:bg-blue-600 transition-colors duration-300 rounded-2xl flex items-center justify-center mb-4 overflow-hidden shadow-sm border border-blue-100">
                                @if (!empty($div->foto))
                                    <img src="data:image/jpeg;base64,{{ base64_encode($div->foto) }}"
                                        alt="Logo {{ $div->nama_divisi }}" class="w-full h-full object-cover">
                                @else
                                    <i
                                        class="ph-fill {{ $icon }} text-3xl text-blue-600 group-hover:text-white transition-colors duration-300"></i>
                                @endif
                            </div>

                            <h3 class="font-black text-slate-800 text-base mb-1">{{ $div->nama_divisi }}</h3>

                            @if ($kadiv)
                                <p class="text-[11px] font-bold text-blue-500 mb-3 uppercase tracking-wide min-h-[16px]">
                                    Kadiv: {{ $kadiv }}</p>
                            @endif
                        </div>

                        <div class="relative w-full flex-grow flex items-center justify-center min-h-[72px] my-2">
                            <p class="text-slate-500 text-xs leading-relaxed text-center transition-all duration-300"
                                :class="open ? '' : 'line-clamp-3'">
                                {{ $deskripsi_asli ?: 'Deskripsi tugas untuk divisi ini belum ditambahkan oleh panitia.' }}
                            </p>
                        </div>

                        @if ($deskripsi_asli)
                            <div class="w-full pt-3 flex justify-center border-t border-gray-50 mt-2">
                                <button @click="open = !open"
                                    class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-400 hover:text-blue-500 uppercase tracking-wider transition-colors focus:outline-none">
                                    <span x-text="open ? 'Tutup Detail' : 'Baca Detail'"></span>
                                    <i class="ph-bold transition-transform duration-200"
                                        :class="open ? 'ph-caret-up' : 'ph-caret-down'"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="my-20 max-w-4xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-2">FAQ</h2>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pertanyaan yang Sering Diajukan</p>
        </div>

        <div
            class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-wrap items-center justify-center gap-6 mb-4 text-slate-700 text-sm font-bold">
            <div class="flex items-center gap-2 text-emerald-600">
                <i class="ph-bold ph-check-square-offset text-lg"></i>
                <span class="text-slate-700">Mahasiswa Semester 2 dan 4 Aktif PNJ</span>
            </div>
            <div class="flex items-center gap-2 text-emerald-600">
                <i class="ph-bold ph-check-square-offset text-lg"></i>
                <span class="text-slate-700">Mempersiapkan Berkas (CV, Portofolio, dll.)</span>
            </div>
        </div>

        <div class="space-y-3">
            <details
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 group">
                <summary
                    class="w-full flex items-center justify-between p-5 text-left font-black text-slate-700 hover:text-blue-600 text-sm sm:text-base focus:outline-none transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span>Apakah boleh mendaftar lebih dari satu divisi?</span>
                    <i
                        class="ph-bold ph-caret-down text-slate-400 text-base transition-transform duration-300 group-open:rotate-180 group-open:text-blue-600"></i>
                </summary>
                <div class="px-5 pb-5 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-gray-50 pt-3">
                    Boleh banget! Kamu bisa memilih hingga maksimal <strong class="text-slate-700">2 pilihan divisi</strong>
                    saat mengisi formulir pendaftaran. Namun, pastikan kamu siap memprioritaskan pilihan pertama saat tahap
                    wawancara nanti, ya!
                </div>
            </details>

            <details
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 group">
                <summary
                    class="w-full flex items-center justify-between p-5 text-left font-black text-slate-700 hover:text-blue-600 text-sm sm:text-base focus:outline-none transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span>Kapan wawancara dilaksanakan?</span>
                    <i
                        class="ph-bold ph-caret-down text-slate-400 text-base transition-transform duration-300 group-open:rotate-180 group-open:text-blue-600"></i>
                </summary>
                <div class="px-5 pb-5 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-gray-50 pt-3">
                    Jadwal wawancara akan dilaksanakan setelah masa pendaftaran online ditutup. Detail teknis, pembagian
                    jam, serta tautan ruangan virtual (Google Meet/Zoom) akan diinformasikan langsung oleh perwakilan
                    panitia melalui WhatsApp.
                </div>
            </details>

            <details
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 group">
                <summary
                    class="w-full flex items-center justify-between p-5 text-left font-black text-slate-700 hover:text-blue-600 text-sm sm:text-base focus:outline-none transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span>Bagaimana jika belum memiliki pengalaman organisasi?</span>
                    <i
                        class="ph-bold ph-caret-down text-slate-400 text-base transition-transform duration-300 group-open:rotate-180 group-open:text-blue-600"></i>
                </summary>
                <div class="px-5 pb-5 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-gray-50 pt-3">
                    Tidak masalah! Open Recruitment ini dirancang sebagai wadah belajar bersama. Yang paling penting adalah
                    <strong class="text-slate-700">komitmen, kemauan keras untuk belajar, serta antusiasme</strong> kamu
                    terhadap tugas-tugas divisi yang kamu pilih.
                </div>
            </details>

            <details
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-200 group">
                <summary
                    class="w-full flex items-center justify-between p-5 text-left font-black text-slate-700 hover:text-blue-600 text-sm sm:text-base focus:outline-none transition-colors cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <span>Apakah seluruh rangkaian pendaftaran dipungut biaya?</span>
                    <i
                        class="ph-bold ph-caret-down text-slate-400 text-base transition-transform duration-300 group-open:rotate-180 group-open:text-blue-600"></i>
                </summary>
                <div class="px-5 pb-5 text-xs sm:text-sm text-slate-500 leading-relaxed border-t border-gray-50 pt-3">
                    Seluruh proses rekrutmen staf kepanitiaan ini <strong class="text-slate-700">100% GRATIS</strong> tanpa
                    dipungut biaya apa pun. Hati-hati terhadap segala bentuk penipuan yang mengatasnamakan panitia.
                </div>
            </details>
        </div>

        <div class="text-center mt-16 pt-8 border-t border-gray-100">
            <h3 class="text-xl font-black text-slate-800 mb-2">Pertanyaan Lebih Lanjut?</h3>
            <p class="text-slate-500 text-xs sm:text-sm mb-6">Hubungi kontak info resmi di bawah ini jika kamu menemui
                kendala atau butuh informasi tambahan.</p>

            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 max-w-md mx-auto">
                <a href="https://wa.me/6281293772795" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 font-bold text-xs px-6 py-3 rounded-xl shadow-sm transition-all hover:-translate-y-0.5 duration-200">
                    <i class="ph-fill ph-whatsapp-logo text-lg"></i> Arif
                </a>

                <a href="https://wa.me/6285710906646" target="_blank"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 font-bold text-xs px-6 py-3 rounded-xl shadow-sm transition-all hover:-translate-y-0.5 duration-200">
                    <i class="ph-fill ph-whatsapp-logo text-lg"></i> Cahya
                </a>
            </div>
        </div>
    </div>
@endsection
s
