@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Form Penilaian Wawancara</h2>
        <p class="text-gray-500 text-sm mt-1">Silakan beri nilai berdasarkan performa dan berkas kandidat.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="text-center mb-6">
                    <img src="{{ Storage::url($pendaftaran->foto) }}"
                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($pendaftaran->nama_lengkap) }}&background=eff6ff&color=1d4ed8&size=128';"
                        alt="Foto {{ $pendaftaran->nama_lengkap }}"
                        class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-blue-100 shadow-sm">

                    <h3 class="text-xl font-bold text-gray-800 mt-4">{{ $pendaftaran->nama_lengkap }}</h3>
                    <p class="text-gray-500 font-medium">{{ $pendaftaran->nim }}</p>

                    <div
                        class="mt-3 inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-xs px-3 py-1.5 rounded-full font-bold border border-blue-100">
                        <i class="ph ph-clock text-sm"></i>
                        {{ \Carbon\Carbon::parse($pendaftaran->jadwalWawancara->waktu_mulai)->format('H:i') }} WIB
                    </div>
                </div>

                <hr class="border-gray-100 mb-4">

                <div class="text-sm space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kontak Kandidat</p>
                        <p class="flex items-center gap-2 text-gray-700 mb-1"><i
                                class="ph ph-envelope-simple text-gray-400"></i> {{ $pendaftaran->email }}</p>
                        <p class="flex items-center gap-2 text-gray-700"><i class="ph ph-whatsapp-logo text-green-500"></i>
                            {{ $pendaftaran->no_whatsapp }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Divisi Pilihan</p>
                        <p class="font-bold text-blue-700">{{ $pendaftaran->divisi->nama_divisi }}</p>
                        <p
                            class="text-gray-600 italic mt-1 bg-gray-50 p-2 rounded border border-gray-100 text-xs leading-relaxed">
                            "{{ $pendaftaran->alasan }}"
                        </p>

                        @if ($pendaftaran->bersedia_pindah_divisi)
                            <div
                                class="mt-2 inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-md border border-emerald-200 text-[10px] font-bold uppercase tracking-wider">
                                <i class="ph-fill ph-check-circle text-emerald-500 text-sm"></i> Bersedia Dipindah Divisi
                            </div>
                        @else
                            <div
                                class="mt-2 inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-2.5 py-1 rounded-md border border-rose-200 text-[10px] font-bold uppercase tracking-wider">
                                <i class="ph-fill ph-x-circle text-rose-500 text-sm"></i> Tidak Mau Dipindah
                            </div>
                        @endif
                    </div>

                    @if ($pendaftaran->alasan_mengikuti_proker)
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Motivasi Ikut Proker
                            </p>
                            <p
                                class="text-gray-600 italic bg-gray-50 p-2 rounded border border-gray-100 text-xs leading-relaxed">
                                "{{ $pendaftaran->alasan_mengikuti_proker }}"
                            </p>
                        </div>
                    @endif

                    <div class="w-full overflow-hidden">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
                            Pengalaman
                        </p>

                        <div
                            class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-sm text-gray-700 leading-relaxed overflow-hidden break-all">

                            {{ $pendaftaran->pengalaman ?? 'Tidak mencantumkan pengalaman.' }}

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-1 lg:col-span-2">
            <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-200">

                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="ph ph-clipboard-text text-blue-600 text-2xl"></i> Aspek Penilaian
                    </h3>
                </div>

                @if ($sudahDinilai)
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-500 text-2xl mt-0.5"></i>
                        <div>
                            <h4 class="text-emerald-800 font-bold">Kandidat ini sudah dinilai!</h4>
                            <p class="text-emerald-600 text-sm mt-0.5">Berikut adalah hasil evaluasi wawancara yang telah
                                Anda simpan sebelumnya. Data ini sudah dikunci.</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('penilaian.store', $pendaftaran->id) }}" method="POST">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse mb-8">
                            <thead>
                                <tr class="bg-slate-50 text-gray-600 text-xs uppercase tracking-wider">
                                    <th class="p-4 rounded-tl-lg font-bold border-b border-gray-200">Kriteria Penilaian</th>
                                    <th class="p-4 font-bold border-b border-gray-200 text-center">Jenis Factor</th>
                                    <th class="p-4 rounded-tr-lg font-bold border-b border-gray-200 text-center w-48">Nilai
                                        (1-5)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($kriterias as $kriteria)
                                    @php
                                        // Mencari nilai yang sudah diinput sebelumnya (jika ada)
                                        $nilaiTersimpan = $sudahDinilai
                                            ? $pendaftaran->penilaians->where('kriteria_id', $kriteria->id)->first()
                                                    ->nilai ?? null
                                            : null;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="p-4">
                                            <p class="font-bold text-gray-800 text-sm mb-1">{{ $kriteria->nama_kriteria }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-medium">Nilai Target Ideal: <span
                                                    class="font-bold text-blue-600">{{ $kriteria->nilai_target }}</span>
                                            </p>
                                        </td>
                                        <td class="p-4 text-center">
                                            @if ($kriteria->jenis_factor == 'core')
                                                <span
                                                    class="bg-blue-100 text-blue-700 border border-blue-200 text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-wide">Core</span>
                                            @else
                                                <span
                                                    class="bg-slate-100 text-slate-700 border border-slate-200 text-[10px] px-2.5 py-1 rounded-full font-black uppercase tracking-wide">Secondary</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <select name="nilai[{{ $kriteria->id }}]"
                                                {{ $sudahDinilai ? 'disabled' : 'required' }}
                                                class="w-full border border-gray-300 font-bold rounded-lg p-2.5 outline-none shadow-sm 
                                                {{ $sudahDinilai ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer' }}">
                                                <option value="" disabled {{ !$nilaiTersimpan ? 'selected' : '' }}>
                                                    Pilih...</option>
                                                <option value="1" {{ $nilaiTersimpan == 1 ? 'selected' : '' }}>1 -
                                                    Sangat Kurang</option>
                                                <option value="2" {{ $nilaiTersimpan == 2 ? 'selected' : '' }}>2 -
                                                    Kurang</option>
                                                <option value="3" {{ $nilaiTersimpan == 3 ? 'selected' : '' }}>3 -
                                                    Cukup</option>
                                                <option value="4" {{ $nilaiTersimpan == 4 ? 'selected' : '' }}>4 -
                                                    Baik</option>
                                                <option value="5" {{ $nilaiTersimpan == 5 ? 'selected' : '' }}>5 -
                                                    Sangat Baik</option>
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-8 text-center text-gray-400">
                                            <i class="ph ph-warning-circle text-4xl mb-2 block"></i>
                                            Belum ada kriteria penilaian untuk divisi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (!$sudahDinilai)
                        <div class="flex justify-end pt-4 border-t border-gray-100">
                            <button type="submit" @if ($kriterias->isEmpty()) disabled @endif
                                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3 px-8 rounded-lg shadow-md transition-all flex items-center gap-2">
                                <i class="ph ph-floppy-disk text-lg"></i> Simpan Hasil Wawancara
                            </button>
                        </div>
                    @endif

                </form>
            </div>
        </div>
    </div>
@endsection
