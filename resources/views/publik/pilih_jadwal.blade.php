@extends('layouts.public')

@section('content')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
            /* Perbaikan penulisan CSS rounded */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .sticky {
            position: sticky;
        }

        .pattern-diagonal-lines {
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0, 0, 0, 0.03) 10px, rgba(0, 0, 0, 0.03) 20px);
        }
    </style>

    <div class="bg-slate-50 font-sans antialiased">
        <div class="max-w-6xl mx-auto py-12 px-4">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Pilih Jadwal Wawancara</h1>
                <p class="text-slate-500 font-medium">Hai, {{ explode(' ', $pendaftaran->nama_lengkap)[0] }}! Tentukan waktu
                    wawancara untuk Divisi {{ $pendaftaran->divisi->nama_divisi }}.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 bg-red-100 text-red-700 p-4 rounded-xl border border-red-200 text-center font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6 text-xs font-bold uppercase tracking-wider justify-center">
                    <div class="flex items-center gap-2"><span
                            class="w-4 h-4 block bg-white border border-slate-300 rounded"></span> Tersedia</div>
                    <div class="flex items-center gap-2"><span
                            class="w-4 h-4 block bg-red-500 border border-red-300 rounded"></span> Penuh / Tidak Bisa</div>
                </div>

                <form action="{{ route('daftar.simpan_jadwal', $pendaftaran->id) }}" method="POST">
                    @csrf

                    <div class="w-full overflow-x-auto custom-scrollbar border border-slate-300 rounded-xl mb-8">
                        <table class="w-full border-collapse bg-slate-50 whitespace-nowrap" style="min-width: 1000px;">
                            <thead>
                                <tr class="bg-slate-800 text-white">
                                    <th class="border border-slate-700 p-4 w-32 sticky left-0 bg-slate-900 z-20">Waktu</th>
                                    @foreach ($tanggals as $tgl)
                                        <th class="border border-slate-700 p-4 min-w-[200px]">
                                            {{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d M') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($waktu_slots as $waktu)
                                    <tr>
                                        <td
                                            class="border border-slate-300 bg-slate-200 text-center font-bold p-3 text-sm sticky left-0 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)] text-slate-800">
                                            {{ \Carbon\Carbon::parse($waktu->waktu_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($waktu->waktu_selesai)->format('H:i') }}
                                        </td>
                                        @foreach ($tanggals as $tgl)
                                            @php $slot = $jadwals[$tgl]->where('waktu_mulai', $waktu->waktu_mulai)->first(); @endphp

                                            <td class="border border-slate-300 p-0 text-center relative">
                                                @if ($slot && $slot->status == 'tersedia')
                                                    <label class="block w-full h-full min-h-[60px] cursor-pointer">
                                                        <input type="radio" name="jadwal_id" value="{{ $slot->id }}"
                                                            required class="peer hidden">
                                                        <div
                                                            class="w-full h-full min-h-[60px] bg-white hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white transition-colors flex items-center justify-center">
                                                            <span
                                                                class="text-xs font-bold peer-checked:block hidden">TERPILIH</span>
                                                        </div>
                                                    </label>
                                                @else
                                                    <div
                                                        class="w-full h-full min-h-[60px] bg-red-500 cursor-not-allowed pattern-diagonal-lines">
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-12 rounded-2xl shadow-lg shadow-blue-200 transition-all duration-300 uppercase tracking-widest text-sm flex items-center gap-2">
                            Konfirmasi & Selesai <i class="ph ph-check-circle text-xl"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
