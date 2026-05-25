@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Monitoring Jadwal Wawancara</h2>
                <p class="text-blue-600 font-bold uppercase text-sm mt-1">PANTAU PROGRES WAWANCARA KADIV</p>
            </div>
        </div>

        <div
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 flex flex-col lg:flex-row gap-6 justify-between items-center">
            <form action="{{ route('po.monitoring.jadwal') }}" method="GET" class="w-full lg:w-1/3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Divisi yang
                    Dipantau</label>
                <select name="divisi_id" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-gray-300 rounded-lg p-3 font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition cursor-pointer outline-none">
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ $selected_divisi_id == $divisi->id ? 'selected' : '' }}>
                            Divisi {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if ($divisi_terpilih)
                <div
                    class="w-full lg:w-auto bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-center gap-4 flex-1 lg:flex-none">
                    <div
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 shrink-0">
                        <i class="ph-fill ph-video-camera text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-blue-800 uppercase mb-1">Ruang Wawancara Virtual</p>
                        @if ($divisi_terpilih->link_wawancara)
                            <a href="{{ $divisi_terpilih->link_wawancara }}" target="_blank"
                                class="text-blue-600 font-bold hover:underline break-all text-sm">
                                {{ $divisi_terpilih->link_wawancara }}
                            </a>
                        @else
                            <p class="text-slate-500 text-sm font-medium italic">Kadiv belum mengatur Link GMeet.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 bg-slate-800 text-white md:flex md:justify-between md:items-center">
                <h3 class="font-bold">Daftar Kandidat - Divisi {{ $divisi_terpilih->nama_divisi ?? '-' }}</h3>
                <span class="bg-slate-700 px-3 py-1 rounded-full text-xs font-bold mt-3 md:mt-0">{{ count($kandidats) }}
                    Terjadwal</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4">Calon Staff</th>
                            <th class="p-4">Hari & Tanggal</th>
                            <th class="p-4 text-center">Waktu (WIB)</th>
                            <th class="p-4 text-center">Status Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kandidats as $index => $kandidat)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                                        {{ $kandidat->nama_lengkap }}
                                    </div>
                                    <div class="text-[10px] text-gray-500">{{ $kandidat->nim }} •
                                        {{ $kandidat->no_whatsapp }}</div>
                                </td>
                                <td class="p-4 font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($kandidat->jadwalWawancara->tanggal)->translatedFormat('l, d F Y') }}
                                </td>
                                <td class="p-4 text-center">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 border border-indigo-100 px-3 py-1 rounded font-black text-xs inline-block shadow-sm">
                                        {{ \Carbon\Carbon::parse($kandidat->jadwalWawancara->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($kandidat->jadwalWawancara->waktu_selesai)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @switch($kandidat->status)
                                        @case('diterima')
                                            <span
                                                class="bg-emerald-100 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Diterima
                                            </span>
                                        @break

                                        @case('ditolak')
                                            <span
                                                class="bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Ditolak
                                            </span>
                                        @break

                                        @case('dinilai')
                                            <span
                                                class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Selesai Dinilai
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Belum Diwawancarai
                                            </span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-400">
                                        <i class="ph ph-users-three text-5xl mb-3 block opacity-20"></i>
                                        <p class="font-medium">Belum ada kandidat yang mem-booking jadwal di divisi ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
