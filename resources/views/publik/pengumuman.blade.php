@extends('layouts.public')

@section('content')
    <div class="bg-slate-50 font-sans antialiased min-h-[80vh] flex flex-col items-center justify-center py-12 px-4">

        <div class="w-full max-w-2xl text-center mb-8">
            <div
                class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-blue-200">
                <i class="ph ph-magnifying-glass text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Cek Hasil Kelulusan</h1>
            <p class="text-slate-500 font-medium mt-3">Masukkan Nomor Induk Mahasiswa (NIM) Anda untuk melihat hasil akhir
                seleksi wawancara Staff PENA 2026.</p>
        </div>

        <div
            class="w-full max-w-xl bg-white p-4 rounded-full shadow-lg shadow-slate-200/50 border border-slate-100 mb-8 relative z-10">
            <form action="{{ route('pengumuman.index') }}" method="GET" class="flex items-center">
                <div class="pl-4 pr-2 text-slate-400">
                    <i class="ph ph-identification-card text-2xl"></i>
                </div>
                <input type="number" name="nim" value="{{ request('nim') }}" required
                    placeholder="Ketikkan NIM Anda di sini..."
                    class="w-full bg-transparent border-none focus:ring-0 text-slate-700 font-bold p-2 outline-none">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-md transition-colors flex items-center gap-2 whitespace-nowrap">
                    Cari <i class="ph ph-arrow-right"></i>
                </button>
            </form>
        </div>

        @if ($error)
            <div
                class="w-full max-w-xl bg-red-50 border border-red-200 text-red-700 p-5 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in">
                <i class="ph-fill ph-warning-circle text-2xl mt-0.5 text-red-500"></i>
                <div>
                    <h3 class="font-bold">Pencarian Gagal</h3>
                    <p class="text-sm mt-1 opacity-90">{{ $error }}</p>
                </div>
            </div>
        @endif

        @if ($hasil)
            <div
                class="w-full max-w-xl bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden transform transition-all animate-fade-in mt-4">

                @if ($hasil->status == 'diterima')
                    <div class="bg-emerald-500 p-8 text-center text-white relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 opacity-20">
                            <i class="ph-fill ph-confetti text-9xl"></i>
                        </div>
                        <div
                            class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg relative z-10">
                            <i class="ph-fill ph-check-circle text-5xl text-emerald-500"></i>
                        </div>
                        <h2 class="text-2xl font-black relative z-10">SELAMAT!</h2>
                        <p class="font-medium opacity-90 relative z-10">Anda dinyatakan LULUS seleksi.</p>
                    </div>

                    <div class="p-8 text-center">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">NIM: {{ $hasil->nim }}
                        </p>
                        <h3 class="text-2xl font-black text-slate-800 mb-6">{{ $hasil->nama_lengkap }}</h3>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-8">
                            <p class="text-sm text-slate-500 mb-2">Ditempatkan pada:</p>
                            <p
                                class="text-lg font-black text-blue-700 uppercase tracking-wide flex items-center justify-center gap-2">
                                <i class="ph-fill ph-users-three"></i> DIVISI {{ $hasil->divisi->nama_divisi }}
                            </p>
                        </div>

                        @if ($hasil->divisi->grup_link)
                            <a href="{{ $hasil->divisi->grup_link }}" target="_blank"
                                class="block w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-4 rounded-xl shadow-lg shadow-emerald-200 transition-colors uppercase tracking-wider text-sm flex items-center justify-center gap-2">
                                <i class="ph-fill ph-whatsapp-logo text-xl"></i> Gabung Grup Divisi
                            </a>
                            <p class="text-xs text-slate-400 mt-3 font-medium">Segera bergabung untuk informasi *onboarding*
                                selanjutnya!</p>
                        @else
                            <div
                                class="p-4 bg-amber-50 text-amber-700 rounded-xl border border-amber-200 text-sm font-bold flex items-center justify-center gap-2">
                                <i class="ph-fill ph-warning"></i> Link grup divisi belum tersedia.
                            </div>
                        @endif
                    </div>
                @elseif ($hasil->status == 'ditolak')
                    <div class="bg-red-500 p-8 text-center border-b border-red-200">
                        <div
                            class="w-16 h-16 bg-red-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-red-200">
                            <i class="ph-fill ph-x-circle text-4xl text-white"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-800">Tetap Semangat!</h2>
                    </div>

                    <div class="p-8 text-center">
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">NIM: {{ $hasil->nim }}
                        </p>
                        <h3 class="text-xl font-black text-slate-800 mb-4">{{ $hasil->nama_lengkap }}</h3>

                        <p class="text-slate-600 leading-relaxed text-sm mb-6">
                            Mohon maaf, berdasarkan hasil penilaian wawancara, kami belum bisa menempatkan Anda di struktur
                            kepanitiaan/pengurus saat ini. Jangan berkecil hati dan terus kembangkan potensi diri Anda di
                            kesempatan berikutnya!
                        </p>
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div
                            class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-500">
                            <i class="ph-fill ph-hourglass-high text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2">{{ $hasil->nama_lengkap }}</h3>
                        <p class="text-slate-600 leading-relaxed text-sm">Status Anda saat ini masih dalam proses penentuan
                            akhir oleh Project Officer. Silakan cek kembali nanti secara berkala.</p>
                    </div>
                @endif
            </div>
        @endif

    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
