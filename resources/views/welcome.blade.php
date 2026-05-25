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

        <div class="text-center mt-10">
            <a href="/daftar"
                class="inline-flex items-center gap-2.5 bg-blue-600 hover:bg-blue-700 text-white font-black text-base px-8 py-4 rounded-2xl shadow-lg transition-all hover:-translate-y-0.5 duration-200">
                <i class="ph-fill ph-user-plus text-xl"></i> Daftarkan Diri Kamu Sekarang
            </a>
            <p class="text-xs text-slate-400 font-semibold mt-3 uppercase tracking-wider">Pastikan berkas CV & Portofolio
                sudah siap</p>
        </div>
    </div>
@endsection
