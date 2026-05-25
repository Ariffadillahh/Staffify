@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Pendaftar Staff</h2>
                <p class="text-slate-500 font-medium mt-1">Pantau semua mahasiswa yang telah mengirimkan formulir
                    pendaftaran.</p>
            </div>

            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                <i class="ph-fill ph-users text-blue-500 text-xl"></i>
                <span class="text-sm font-bold text-slate-700">Total: {{ count($kandidats) }} Pendaftar</span>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 text-slate-600">
                <i class="ph-fill ph-funnel text-xl"></i>
                <span class="font-bold text-sm">Filter Berdasarkan Divisi:</span>
            </div>

            <form action="{{ route('po.kandidat') }}" method="GET" class="w-full md:w-1/3">
                <select name="divisi_id" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-300 rounded-xl p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition cursor-pointer outline-none shadow-sm">
                    <option value="">-- Tampilkan Semua Divisi --</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}" {{ $selected_divisi_id == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                            <th class="p-5 w-12 text-center">No</th>
                            <th class="p-5">Profil Kandidat</th>
                            <th class="p-5">Kontak</th>
                            <th class="p-5">Pilihan Divisi</th>
                            <th class="p-5 text-center">Nilai Akhir PM</th>
                            <th class="p-5 text-center">Status Tahapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $counterKuotaDivisi = [];
                        @endphp

                        @forelse($kandidats as $index => $kandidat)
                            @php
                                $divisiId = $kandidat->divisi_id;

                                $kuotaDivisi = $kandidat->divisi?->kuota_staff ?? 0;

                                if (!isset($counterKuotaDivisi[$divisiId])) {
                                    $counterKuotaDivisi[$divisiId] = 0;
                                }

                                $masukKuota = false;

                                if ($kandidat->status === 'diterima' && $counterKuotaDivisi[$divisiId] < $kuotaDivisi) {
                                    $masukKuota = true;
                                    $counterKuotaDivisi[$divisiId]++;
                                }
                            @endphp

                            <tr
                                class="hover:bg-slate-50/80 transition-colors {{ $masukKuota ? 'bg-blue-50/80 font-medium text-slate-900' : '' }}">
                                <td class="p-5 text-center font-bold text-slate-400">
                                    @if ($masukKuota)
                                        <span class="text-blue-600 font-black"
                                            title="Sah Masuk Kuota Formasi Divisi">{{ $index + 1 }}</span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="p-5">
                                    <a href="{{ route('pm.detail', $kandidat->id) }}" class="flex items-center gap-3 group">
                                        <img src="{{ asset('storage/' . $kandidat->foto) }}"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($kandidat->nama_lengkap) }}&background=eff6ff&color=1d4ed8';"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm group-hover:border-blue-400 transition">
                                        <div>
                                            <p
                                                class="font-bold text-slate-800 flex items-center gap-1.5 group-hover:text-blue-600 transition">
                                                {{ $kandidat->nama_lengkap }}
                                                @if ($masukKuota)
                                                    <span
                                                        class="text-blue-600 bg-blue-100 text-[9px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-wider scale-90">Lolos
                                                        Formasi</span>
                                                @endif
                                            </p>
                                            <p
                                                class="text-xs font-semibold text-slate-500 group-hover:text-blue-400/80 transition">
                                                {{ $kandidat->nim }} <span
                                                    class="text-[10px] font-normal underline opacity-0 group-hover:opacity-100 transition-all ml-1">Detail
                                                    Nilai →</span>
                                            </p>
                                        </div>
                                    </a>
                                </td>
                                <td class="p-5">
                                    <p class="text-xs text-slate-600 mb-1 flex items-center gap-1.5 font-medium"><i
                                            class="ph-fill ph-envelope-simple text-slate-400"></i> {{ $kandidat->email }}
                                    </p>
                                    <p class="text-xs text-slate-600 flex items-center gap-1.5 font-medium"><i
                                            class="ph-fill ph-whatsapp-logo text-green-500"></i>
                                        {{ $kandidat->no_whatsapp }}</p>
                                </td>
                                <td class="p-5">
                                    <p class="text-sm font-bold text-slate-700">{{ $kandidat->divisi->nama_divisi ?? '-' }}
                                    </p>
                                </td>

                                <td class="p-5 text-center font-black text-blue-600 text-sm">
                                    {{ $kandidat->total_pm > 0 ? number_format($kandidat->total_pm, 2) : '0.00' }}
                                </td>

                                <td class="p-5 text-center">
                                    @switch($kandidat->status)
                                        @case('diterima')
                                            <span
                                                class="bg-emerald-500 text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wide shadow-sm">
                                                Diterima
                                            </span>
                                        @break

                                        @case('ditolak')
                                            <span
                                                class="bg-rose-100 text-rose-700 border border-rose-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Ditolak
                                            </span>
                                        @break

                                        @case('dinilai')
                                            <span
                                                class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Sudah Dinilai
                                            </span>
                                        @break

                                        @default
                                            <span
                                                class="bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wide">
                                                Pendaftar Baru
                                            </span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-16 text-center text-slate-400">
                                        <i class="ph-fill ph-users text-5xl mb-3 block text-slate-300"></i>
                                        <p class="font-bold text-slate-500">Belum ada pendaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                </div>
            </div>
        </div>
    @endsection
