@extends('layouts.public')

@section('content')
    <div class="bg-slate-50 font-sans antialiased flex items-center justify-center min-h-screen">

        <div class="max-w-xl mx-auto px-4 py-12 w-full">
            <div
                class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden text-center p-8 md:p-12">

                <div
                    class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="ph-fill ph-check-circle text-6xl text-emerald-500"></i>
                </div>

                <h1 class="text-3xl font-black text-slate-900 tracking-tight mb-4">Pendaftaran Berhasil!</h1>

                @if (session('nama'))
                    <p class="text-slate-600 text-lg font-medium mb-3">Terima kasih, <span
                            class="text-blue-600 font-black">{{ session('nama') }}</span>.</p>
                @endif

                <p class="text-slate-500 mb-8 leading-relaxed text-sm md:text-base">
                    Data pendaftaran dan jadwal wawancara kamu telah tersimpan dengan aman di sistem Staffify. Kepala Divisi
                    terkait telah mendapatkan notifikasi mengenai jadwalmu.
                </p>

                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-8 text-left">
                    <h3 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-info text-xl"></i> Langkah Selanjutnya
                    </h3>
                    <ul class="text-sm text-blue-900 space-y-3 list-disc list-inside font-medium opacity-90">
                        <li>Persiapkan dirimu dengan baik untuk sesi wawancara.</li>
                        <li>Gunakan pakaian formal (Almamater PNJ) saat wawancara berlangsung.</li>

                        @if (session('link_wawancara'))
                            <li class="list-none bg-white p-3 rounded-lg border border-blue-200 shadow-sm mt-2">
                                <span class="block text-xs font-black uppercase text-blue-500 mb-1">Tautan Wawancara (Google
                                    Meet)</span>
                                <a href="{{ session('link_wawancara') }}" target="_blank"
                                    class="text-blue-700 font-bold hover:underline break-all">
                                    {{ session('link_wawancara') }}
                                </a>
                                <p class="text-xs text-slate-500 mt-1 italic">*Silakan simpan / bookmark tautan di atas
                                    untuk digunakan pada hari H.</p>
                            </li>
                        @else
                            <li>Tautan (link) GMeet/Zoom wawancara akan diinformasikan oleh Kepala Divisi menjelang jadwal
                                yang kamu pilih.</li>
                        @endif
                    </ul>
                </div>

                <a href="/"
                    class="inline-block w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 md:py-5 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                    <i class="ph ph-house text-lg"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
@endsection
