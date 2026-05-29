@extends('layouts.admin')

@section('content')
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Manajemen Jadwal Wawancara</h2>
                <p class="text-blue-600 font-semibold uppercase text-sm mt-1">
                    DIVISI {{ auth()->user()->divisi->nama_divisi ?? 'BELUM DISET' }}
                </p>
            </div>

            <div class="flex flex-wrap gap-4 text-xs font-bold uppercase tracking-wider">
                <div class="flex items-center gap-2"><span class="w-4 h-4 block bg-red-500 border border-black"></span> Kadiv
                    Tidak Bisa</div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 block bg-cyan-400 border border-black"></span>
                    Dibooking</div>
                <div class="flex items-center gap-2"><span class="w-4 h-4 block bg-white border border-black"></span>
                    Tersedia</div>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 border border-red-200 font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div
            class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200 flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-center">
            <div>
                <h3 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                    <i class="ph ph-calendar-plus text-xl"></i> Langkah 1: Generate Slot
                </h3>
                <form action="{{ route('jadwal.generate') }}" method="POST" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-blue-700 uppercase mb-1">Mulai</label>
                        <input type="date" name="tanggal_mulai" required
                            class="border-gray-300 rounded-lg p-2 text-sm shadow-sm ">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-blue-700 uppercase mb-1">Selesai</label>
                        <input type="date" name="tanggal_selesai" required
                            class="border-gray-300 rounded-lg p-2 text-sm shadow-sm ">
                    </div>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow-md transition">
                        Generate
                    </button>
                </form>
            </div>

            @if ($jadwals->isNotEmpty())
                <div class="border-l-0 xl:border-l-2 border-blue-200 xl:pl-6">
                    <h3 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                        <i class="ph ph-pencil-simple-line text-xl"></i> Langkah 2: Atur Ketersediaan
                    </h3>
                    <button onclick="document.getElementById('modalJadwal').classList.remove('hidden')"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition shadow-md w-full xl:w-auto flex items-center justify-center gap-2">
                        <i class="ph ph-calendar-x"></i> Kosongkan Jadwal
                    </button>
                </div>
            @endif
        </div>

        <div class="w-full overflow-x-auto custom-scrollbar border border-gray-800 rounded-lg shadow-inner bg-gray-100">
            <table class="w-full border-collapse bg-white whitespace-nowrap" style="min-width: 1000px;">
                <thead>
                    <tr class="bg-slate-700 text-white">
                        <th class="border border-gray-800 p-4 w-32 sticky left-0 bg-slate-800 z-20">Waktu</th>
                        @foreach ($tanggals as $tgl)
                            <th class="border border-gray-800 p-4 min-w-[180px] text-sm">
                                {{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d M') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($waktu_slots as $waktu)
                        <tr>
                            <td
                                class="border border-gray-800 bg-slate-200 text-center font-bold p-3 text-sm sticky left-0 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.1)]">
                                {{ \Carbon\Carbon::parse($waktu->waktu_mulai)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($waktu->waktu_selesai)->format('H:i') }}
                            </td>

                            @foreach ($tanggals as $tgl)
                                @php
                                    $slot = $jadwalsGrouped
                                        ->get($tgl, collect())
                                        ->where('waktu_mulai', $waktu->waktu_mulai)
                                        ->first();

                                    $isDinilai =
                                        $slot &&
                                        $slot->status == 'dibooking' &&
                                        $slot->pendaftaran &&
                                        in_array($slot->pendaftaran->status, [
                                            'dinilai',
                                            'diterima',
                                            'pindah',
                                            'ditolak',
                                        ]);

                                    $isPending = $slot && $slot->status == 'dibooking' && !$isDinilai;
                                @endphp

                                <td
                                    class="border border-gray-800 p-0 text-center transition-colors duration-200
                                    {{ !$slot || $slot->status == 'tidak_tersedia' ? 'bg-red-500' : '' }}
                                    {{ $isPending ? 'bg-cyan-400' : '' }}
                                    {{ $isDinilai ? 'bg-emerald-400' : '' }}
                                    {{ $slot && $slot->status == 'tersedia' ? 'bg-white' : '' }}">

                                    @if ($slot && $slot->status == 'dibooking')
                                        <a href="{{ $slot->pendaftaran ? route('penilaian.create', $slot->pendaftaran->id) : '#' }}"
                                            class="block w-full h-full min-h-[50px] p-2 transition group flex flex-col justify-center items-center
                                            {{ $isDinilai ? 'hover:bg-emerald-500' : 'hover:bg-cyan-500' }}"
                                            title="{{ $isDinilai ? 'Kandidat ini sudah dinilai' : 'Klik untuk memberi nilai wawancara' }}">

                                            <div
                                                class="text-[10px] text-center font-black text-gray-900 uppercase leading-tight group-hover:text-white">
                                                {{ $slot->pendaftaran->nama_lengkap ?? 'Data Dihapus' }}
                                            </div>

                                            @if ($isDinilai)
                                                <div
                                                    class="text-[9px] mt-1 text-emerald-900 font-bold group-hover:text-emerald-100 flex items-center gap-1">
                                                    <i class="ph-fill ph-check-circle text-sm"></i> Selesai Dinilai
                                                </div>
                                            @else
                                                <div
                                                    class="text-[9px] mt-1 text-cyan-900 font-bold group-hover:text-cyan-100 flex items-center gap-1 border-b border-cyan-700/50 pb-0.5">
                                                    <i
                                                        class="ph ph-note-pencil text-sm text-cyan-800 group-hover:text-white"></i>
                                                    Beri Nilai
                                                </div>
                                            @endif
                                        </a>
                                    @else
                                        <div class="min-h-[50px]"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modalJadwal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-[95vw] h-[90vh] flex flex-col overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Ubah Ketersediaan Jadwal</h2>
                    <p class="text-sm text-gray-500">Klik kotak putih untuk menandai jadwal sebagai <span
                            class="font-bold text-red-500">Tidak Bisa (Merah)</span>.</p>
                </div>
                <button onclick="document.getElementById('modalJadwal').classList.add('hidden')"
                    class="text-gray-400 hover:text-red-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-hidden flex flex-col p-6">
                <form action="{{ route('jadwal.bulk_update') }}" method="POST" class="h-full flex flex-col">
                    @csrf
                    <div
                        class="flex-1 w-full overflow-x-auto overflow-y-auto custom-scrollbar border-2 border-gray-800 rounded-lg shadow-inner bg-gray-100 mb-4">
                        <table class="w-full border-collapse bg-white select-none whitespace-nowrap"
                            style="min-width: 1200px;">
                            <thead>
                                <tr class="bg-slate-700 text-white">
                                    <th class="border border-gray-800 p-4 w-32 sticky top-0 left-0 bg-slate-800 z-30">Waktu
                                    </th>
                                    @foreach ($tanggals as $tgl)
                                        <th class="border border-gray-800 p-4 min-w-[200px] sticky top-0 bg-slate-700 z-20">
                                            {{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d M Y') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($waktu_slots as $waktu)
                                    <tr>
                                        <td
                                            class="border border-gray-800 bg-slate-200 text-center font-bold p-4 text-sm sticky left-0 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.1)]">
                                            {{ \Carbon\Carbon::parse($waktu->waktu_mulai)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($waktu->waktu_selesai)->format('H:i') }}
                                        </td>

                                        @foreach ($tanggals as $tgl)
                                            @php $slot = $jadwalsGrouped->get($tgl, collect())->where('waktu_mulai', $waktu->waktu_mulai)->first(); @endphp
                                            <td class="border border-gray-800 p-0 text-center relative">
                                                @if ($slot)
                                                    @if ($slot->status == 'dibooking')
                                                        <div
                                                            class="w-full h-full min-h-[60px] bg-cyan-400 p-3 flex items-center justify-center">
                                                            <span
                                                                class="text-xs font-black text-gray-900 uppercase whitespace-normal">{{ $slot->pendaftaran->nama_lengkap ?? 'Data Dihapus' }}</span>
                                                        </div>
                                                    @else
                                                        <label
                                                            class="block w-full h-full min-h-[60px] cursor-pointer relative">
                                                            <input type="checkbox" name="jadwal_merah[]"
                                                                value="{{ $slot->id }}" class="peer hidden"
                                                                {{ $slot->status == 'tidak_tersedia' ? 'checked' : '' }}>
                                                            <div
                                                                class="absolute inset-0 transition-colors border-4 border-transparent peer-hover:border-gray-300 peer-checked:bg-red-500 peer-checked:hover:bg-red-600 bg-white hover:bg-gray-100 flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-white hidden peer-checked:block"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @else
                                                    <div class="min-h-[60px] bg-gray-300"></div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="md:flex md:justify-end md:gap-3 mt-auto pt-4 border-t">
                        <button type="button" onclick="document.getElementById('modalJadwal').classList.add('hidden')"
                            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition w-full md:w-auto">Batal</button>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg transition text-sm md:text-lg flex items-center gap-2 w-full md:w-auto justify-center mt-3 md:mt-0">
                            <i class="ph ph-floppy-disk"></i> Simpan Perubahan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 12px;
            width: 12px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
            border: 3px solid #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }

        .sticky {
            position: sticky;
        }
    </style>
@endsection
