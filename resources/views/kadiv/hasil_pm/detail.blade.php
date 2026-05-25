@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
                class="text-slate-500 hover:text-blue-600 font-bold text-sm flex items-center gap-2">
                <i class="ph ph-arrow-left"></i> Kembali ke Halaman Sebelumnya
            </a>
        </div>

        <div
            class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <img src="{{ Storage::url($pendaftaran->foto) }}"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($pendaftaran->nama_lengkap) }}&background=random';"
                    class="w-16 h-16 rounded-full object-cover border">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $pendaftaran->nama_lengkap }}</h2>
                    <p class="text-gray-500 text-sm font-bold">{{ $pendaftaran->nim }}</p>
                    <p class="text-sm font-bold text-slate-700">
                        {{ $kandidat->divisi?->nama_divisi ?? '' }}
                    </p>
                </div>
            </div>
            <div class="text-center md:text-right bg-blue-50 border border-blue-100 px-6 py-3 rounded-xl">
                <p class="text-[10px] font-black text-blue-500 uppercase tracking-wider">Nilai Akhir PM</p>
                <p class="text-3xl font-black text-blue-700 mt-0.5">{{ $ringkasan['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="p-5 bg-slate-50 border-b border-gray-100">
                <h3 class="font-bold text-slate-800 text-base">Tahap 1: Pemetaan Nilai Gap & Konversi Bobot</h3>
                <p class="text-xs text-slate-500 mt-0.5">Rumus: Gap = Nilai Aktual - Nilai Target</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse whitespace-nowrap text-left text-sm">
                    <thead>
                        <tr
                            class="bg-slate-100/70 border-b border-gray-200 text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-4">Kriteria</th>
                            <th class="p-4 text-center">Jenis</th>
                            <th class="p-4 text-center">Target Ideal</th>
                            <th class="p-4 text-center">Nilai Aktual</th>
                            <th class="p-4 text-center">Nilai Gap</th>
                            <th class="p-4 text-center bg-blue-50/50 text-blue-700">Bobot Hasil</th>
                            <th class="p-4">Keterangan Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($detail_kriteria as $dk)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 font-bold text-gray-800">{{ $dk['nama'] }}</td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $dk['jenis'] == 'core' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $dk['jenis'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-center font-medium text-gray-500">{{ $dk['target'] }}</td>
                                <td class="p-4 text-center font-bold text-gray-700">{{ $dk['aktual'] }}</td>
                                <td
                                    class="p-4 text-center font-black {{ $dk['gap'] < 0 ? 'text-red-500' : ($dk['gap'] > 0 ? 'text-blue-500' : 'text-emerald-500') }}">
                                    {{ $dk['gap'] > 0 ? '+' . $dk['gap'] : $dk['gap'] }}
                                </td>
                                <td
                                    class="p-4 text-center font-black bg-blue-50/30 text-blue-600 text-base border-x border-blue-50">
                                    {{ $dk['bobot'] }}
                                </td>
                                <td class="p-4 text-xs text-gray-500 font-medium">{{ $dk['keterangan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-3">
            <h3 class="font-bold text-slate-800 text-base">Tahap 2: Pengelompokan Faktor & Nilai Total Akhir</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm text-center flex flex-col justify-between">
                <div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-black uppercase">Core
                        Factor (60%)</span>
                    <p class="text-3xl font-black text-slate-800 mt-4">{{ $ringkasan['avg_cf'] }}</p>
                </div>
                <p
                    class="text-[11px] bg-slate-50 text-slate-600 rounded-lg p-2 mt-3 font-bold border border-slate-100 tracking-wide">
                    Rumus: ({{ $ringkasan['teks_cf'] }}) / {{ $ringkasan['count_cf'] }}
                </p>
            </div>

            <div
                class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm text-center flex flex-col justify-between">
                <div>
                    <span
                        class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-[10px] font-black uppercase">Secondary
                        (40%)</span>
                    <p class="text-3xl font-black text-slate-800 mt-4">{{ $ringkasan['avg_sf'] }}</p>
                </div>
                <p
                    class="text-[11px] bg-slate-50 text-slate-600 rounded-lg p-2 mt-3 font-bold border border-slate-100 tracking-wide">
                    Rumus: ({{ $ringkasan['teks_sf'] }}) / {{ $ringkasan['count_sf'] }}
                </p>
            </div>

            <div
                class="bg-slate-800 p-5 rounded-2xl border border-slate-700 shadow-sm text-center text-white flex flex-col justify-center items-center">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kombinasi Nilai</p>
                <p class="text-xs text-slate-400 mt-1">(60% × NCF) + (40% × NSF)</p>
                <p class="text-3xl font-black text-amber-400 mt-3">{{ $ringkasan['total'] }}</p>
            </div>
        </div>

        <div
            class="bg-blue-50/60 border border-blue-100 p-6 rounded-2xl text-slate-700 text-xs md:text-sm leading-relaxed space-y-3 shadow-sm">
            <h4 class="font-bold text-blue-800 flex items-center gap-1.5 text-sm uppercase tracking-wide">
                <i class="ph-fill ph-info text-lg"></i> Keterangan Cara Perhitungan
            </h4>
            <p>
                Nilai Akhir dihitung otomatis oleh sistem menggunakan pembobotan standar <span class="font-bold">Profile
                    Matching</span>:
            </p>
            <ul class="list-disc list-inside space-y-2 pl-2 font-medium opacity-90">
                <li>
                    <strong>Core Factor (NCF):</strong> Total nilai bobot dari seluruh kriteria berjenis <span
                        class="text-blue-600 font-bold">CORE</span> dibagi dengan jumlah kriterianya. Nilai saat ini
                    adalah <span
                        class="font-mono text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded font-bold text-xs bg-amber-50 ">
                        ({{ $ringkasan['teks_cf'] }}) / {{ $ringkasan['count_cf'] }} = {{ $ringkasan['avg_cf'] }}
                    </span>
                </li>
                <li>
                    <strong>Secondary Factor (NSF):</strong> Total nilai bobot dari seluruh kriteria berjenis <span
                        class="text-slate-600 font-bold">SECONDARY</span> dibagi dengan jumlah kriterianya. Nilai saat ini
                    adalah <span class="font-mono text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded font-bold text-xs">
                        ({{ $ringkasan['teks_sf'] }}) / {{ $ringkasan['count_sf'] }} = {{ $ringkasan['avg_sf'] }}
                    </span>
                </li>
                <li>
                    <strong>Total Nilai Akhir:</strong> Hasil penggabungan persentase baku dari kedua faktor penentu di atas
                    dengan kalkulasi:
                    <span
                        class="bg-slate-800 text-amber-300 px-2 py-1 rounded font-black text-xs inline-block tracking-wide">(60%
                        × {{ $ringkasan['avg_cf'] }}) + (40% × {{ $ringkasan['avg_sf'] }}) =
                        {{ $ringkasan['total'] }}</span>.
                </li>
            </ul>
        </div>
    </div>
    </div>
@endsection
